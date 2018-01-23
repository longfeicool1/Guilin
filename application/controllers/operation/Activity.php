<?php

/**
 * banner管理
 * User: Administrator
 * Date: 2017/3/15
 * Time: 10:25
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Notice_model', 'NoticeModel');
        $this->load->model('NoticeToUser_model', 'NoticeUserModel');
        $this->load->model('YgUser_model', 'YgUserModel');
        $this->_tabId  = 'id284';
    }

    /**
     * 列表
     */
    public function lists()
    {
        $data = $this->input->post();
        $data['type'] = 1;
        list($pageCurrent, $pageSize) = $this->getPageSizeAndCurrent($data);

        $offset = ($pageCurrent - 1 ) * $pageSize;
        $count = $this->NoticeModel->counts($data);
        $list = $this->NoticeModel->lists($data, $pageCurrent, $pageSize);

        $nowTime = time();
        foreach ($list as $k=>$v) {
            $list[$k]['row_id'] = $offset + $k + 1;

            if (strtotime($v['start_time']) <=  $nowTime && $nowTime <= strtotime($v['end_time']) && $v['status'] == 1) {
                $list[$k]['show_status'] = 1;
            } else {
                $list[$k]['show_status'] = 2; #'未展示';
                if ( $v['status'] == 2) {
                    $list[$k]['show_txt_status'] = 2; #'已下架';
                } else if (strtotime($v['start_time']) >  $nowTime) {
                    $list[$k]['show_txt_status'] = 1; #'未开始';
                } else {
                    $list[$k]['show_txt_status'] = 3; #'已结束';
                }
            }
        }

        $this->assign('list', $list);
        $this->assign('limit', $pageSize);
        $this->assign('count', $count);
        $this->assign('search', $data);
        $this->display('activity/list.tpl');
    }

    /**
     * 添加或者修改
     */
    public function set()
    {
        $data = [];
        # 保存信息
        if ($_POST && $this->isPost()) {

            $status = $this->post('status');
            $name = $this->post('name');
            $image = $this->post('image');
            $startTime = $this->post('start_time');
            $endTime = $this->post('end_time');
            $toUser = $this->post('all_user');
            $userId = $this->post('user_id');
            $url = $this->post('url');
            $data = [
                'name' => $name,
                'url' => $url,
                'image' => $image,
                'type' => 1,
                'status' => $status,
                'start_time' => $startTime,
                'end_time' => $endTime,
            ];

            # 名称
            if (empty($name)) {
                echo $this->bjuiRes(false, '名称不能为空', '', false);
                return;
            }

            if (empty($url)) {
                echo $this->bjuiRes(false, '链接地址不能为空', '', false);
                return;
            }

            if (empty($image)) {
                echo $this->bjuiRes(false, '封面图片不能为空', '', false);
                return;
            }

            if (empty($startTime)) {
                echo $this->bjuiRes(false, '开始时间不能为空', '', false);
                return;
            }
            if (empty($endTime)) {
                echo $this->bjuiRes(false, '结束时间不能为空', '', false);
                return;
            }

            if (strtotime($startTime) > strtotime($endTime)) {
                echo $this->bjuiRes(false, '开始时间不能大于结束时间', '', false);
                return;
            }

            if (empty($toUser) && empty($userId)) {
                echo $this->bjuiRes(false, '请选择用户', '', false);
                return;
            }

            $sql = "SELECT a.pid,s.bindid,u.uniqueId,u.appkey,u.systemName,u.deviceName,u.version FROM baohe.pm_account_list AS a
                        LEFT JOIN baohe.pub_passport_info AS u ON u.pid = a.pid
                        LEFT JOIN baohe.app_obd_bind AS c ON c.USER_ID = u.pid
                        LEFT JOIN baohe.pub_src_info AS s ON s.bindid = c.BIND_ID
                        WHERE a.account_type = 4";
            if ($toUser == 1) {
                $sql .= " GROUP BY u.pid";
                $list = $this->db->query($sql)->result_array();
                $toNum = !empty($list) ? count($list) : 0;
            } else {
                $toNum = count($userId);
                $sql .= " AND a.pid in (" . implode(',', $userId) . ") GROUP BY u.pid";
                $list = $this->db->query($sql)->result_array();
            }
            $data['to_num'] = $toNum;
            $data['is_send'] = $status == 1 ? 1 : 2;

            $noticeId = $this->NoticeModel->insert($data);
            echo $this->bjuiRes(true, '添加成功', '', false);

            if(function_exists('fastcgi_finish_request')) {
                fastcgi_finish_request();
            }

            # 给用户发推送消息
            $i = 1;

            foreach ($list as $k => $v) {
                $v['title'] = $name;
                $v['msgType'] = '2'; # 图文消息类型 不能是整形的数据
                $v['msgUrl'] = $url;

                # 给用户发推送消息
                // $this->push($v);



                $table = $this->NoticeUserModel->getAllName($v['pid']);
                $noticeUserData[$table][] = [
                    'user_id' => $v['pid'],
                    'notice_id' => $noticeId,
                ];

                if ($i % 300 == 0) {
                    foreach ($noticeUserData as $t => $d) {
                        $this->db->insert_batch($t, $d);
                    }
                    $noticeUserData = [];
                }
                $i++;
            }

            if ($noticeUserData) {
                foreach ($noticeUserData as $t => $d) {
                    $this->db->insert_batch($t, $d);
                }
            }

        } else {
            $this->assign('token', $this->qiniuToken());
            $this->assign('data',$data);

            $this->display('activity/set.tpl');
        }
    }

    /**
     * 上下架
     */
    public function line()
    {
        $id = $this->input->get('id');
        $status = intval($this->get('status'));
        if (! in_array($status, [1, 2])) {
            echo $this->bjuiRes(true, '状态无效', $this->_tabId, false);
            return;
        }

        $row = $this->NoticeModel->getRow(['id' => $id]);
        if (strtotime($row['end_time']) < time() && $status == 1) {
            echo $this->bjuiRes(true, '活动已过期，请编辑活动时间', $this->_tabId, false);
            return;
        }


        $where = ['id' => $id];
        $data = ['status' => $status];
        $num = $this->NoticeModel->update($data, $where);
        if ($num) {
            echo $this->bjuiRes(true, '设置成功', $this->_tabId, false);
            # 未推送消息，再次上架从新推送消息
            if(function_exists('fastcgi_finish_request')) {
                fastcgi_finish_request();
            }
            if ($row['is_send'] == 2 && $status == 1) {
                $userId = $this->NoticeUserModel->getUserId($id);
                foreach ($userId as $k => $v) {

                    $userInfo = $this->YgUserModel->getUserDevice($v['user_id']);
                    # 有uniqueId才推送消息
                    if (! empty($userInfo['uniqueId'])) {
                        $userInfo['title'] = $row['name'];
                        $userInfo['msgType'] = 2; # 图文消息类型
                        $userInfo['msgUrl'] = $row['url'];
                        // $this->push($userInfo);
    //                    print_r($userInfo);
                    }
                }
            }
        } else {
            echo $this->bjuiRes(false, '系统异常', $this->_tabId, false);
        }
        return;
    }

    /**
     * 文件导入
     */
    public function upload()
    {
        $type = $this->get('type');
        if ($_FILES) {
            $json = ['status' => 200, 'msg' => 'ok', 'data' => []];

            $this->load->library('ci_phpexcel');
            $importFile = $_FILES['file'];

            if ($importFile['error'] == 4) {
                $json['status'] = 300;
                $json['msg'] = '请选择需要导入的文件';
                echo $this->output_json($json);
                exit;
            }

            $filename       = $importFile['name'];
            $temp           = explode('.', $filename);
            $fileExtension = $temp[1];
            if ($fileExtension == 'xls' || $fileExtension == 'xlsx') {
                # 拼接查询条件
                $data = $this->_loadFromExcel($importFile['tmp_name']);

                $where = [];
                foreach ($data as $k => $v) {
                    $where[] = $v[0]; # 属性值
                }
                $where = !empty($where) ? "'" . implode("','", $where) . "'" : '';

                $sql = "SELECT a.pid as user_id, a.account_login,s.src,c.CARCARD as car_card  FROM baohe.pm_account_list AS a
                        LEFT JOIN baohe.pub_passport_info AS u ON u.pid = a.pid
                        LEFT JOIN baohe.app_obd_bind AS c ON c.USER_ID = u.pid
                        LEFT JOIN baohe.pub_src_info AS s ON s.bindid = c.BIND_ID AND s. STATUS = 1
                        WHERE a.account_type = 4";
                if ($type == 'm' && ! empty($where)) { # 手机
                    $sql .= " AND a.account_login in ({$where})";
                } else if ($type == 's' && ! empty($where)) { # 设备ID
                    $sql .= " AND s.src in ({$where})";
                }
                $sql .= " GROUP BY a.pid ";
                $list = $this->db->query($sql)->result_array();

                $json['data'] = $list;
                echo $this->output_json($json);
                exit;
            }  else {
                $json['status'] = 300;
                $json['msg'] = '请选择正确的文档格式';
                echo $this->output_json($json);
                exit;
            }
        } else {
            $this->assign('type', $type);
            $this->display('activity/upload.tpl');
        }
    }
}