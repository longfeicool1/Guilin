<?php

/**
 * 消息列表
 * User: Administrator
 * Date: 2017/4/10
 * Time: 11:58
 */
class Notice extends MY_Controller
{
    # 消息类型
    const IMAGE = 2; # 图文
    const TXT = 3;   # 文本

    # 推送消息类型
    const TXT_TYPE = '1'; # 文本消息
    const MSG_TYPE = '2'; # 图文消息

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Notice_model', 'NoticeModel');
        $this->load->model('NoticeToUser_model', 'NoticeUserModel');
        $this->load->model('YgUser_model', 'YgUserModel');
        $this->_tabId  = 'id288';
    }

    /**
     * 消息列表
     */
    public function lists()
    {
        $data = $this->input->post();
        if (empty($data['type_status'])) {
            $data['type'] = '2,3';
        } else {
            $data['type'] = $data['type_status'];
        }

        $data['orderField'] = 'id';
        $data['orderDirection'] = 'desc';

        list($pageCurrent, $pageSize) = $this->getPageSizeAndCurrent($data);
        $offset = ($pageCurrent - 1 ) * $pageSize;
        $count = $this->NoticeModel->counts($data);
        $list = $this->NoticeModel->lists($data, $pageCurrent, $pageSize);


        foreach ($list as $k=>$v) {
            $list[$k]['row_id'] = $offset + $k + 1;
            $list[$k]['sub_txt'] = mb_substr($v['txt'], 0, 15);
        }

        $this->assign('list', $list);
        $this->assign('limit', $pageSize);
        $this->assign('count', $count);
        $this->assign('search', $data);
        $this->display('notice/list.tpl');
    }

    /**
     * 图片消息添加和修改
     */
    public function setImage()
    {
        $data = [];
        # 保存信息
        if ($_POST && $this->isPost()) {
            $name = $this->post('name');
            $image = $this->post('image');
            $toUser = $this->post('all_user');
            $userId = $this->post('user_id');
            $url = $this->post('url');
            $data = [
                'name' => $name,
                'url' => $url,
                'image' => $image,
                'type' => self::IMAGE,
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

            if (empty($toUser) && empty($userId)) {
                echo $this->bjuiRes(false, '请选择用户', '', false);
                return;
            }

            $sql = "SELECT a.pid,u.uniqueId,u.appkey,u.systemName,u.deviceName,u.version FROM baohe.pm_account_list AS a
                        LEFT JOIN baohe.pub_passport_info AS u ON u.pid = a.pid
                        WHERE a.account_type = 4 ";
            if ($toUser == 1) {
                $list = $this->db->query($sql)->result_array();
                $toNum = !empty($list) ? count($list) : 0;
            } else {
                $toNum = count($userId);
                $sql .= " AND a.pid in (" . implode(',', $userId) . ")";
                $list = $this->db->query($sql)->result_array();
            }
            $data['to_num'] = $toNum;

            $noticeId = $this->NoticeModel->insert($data);
            echo $this->bjuiRes(true, '添加成功', '', false);

            if(function_exists('fastcgi_finish_request')) {
                fastcgi_finish_request();
            }

            # 给用户发推送消息
            $i = 1;

            foreach ($list as $k => $v) {
                $v['title'] = $name;
                $v['msgType'] = self::MSG_TYPE; # 图文消息类型
                $v['msgUrl'] = $url;

                # 给用户发推送消息
                $this->push($v);

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

            $this->display('notice/setImage.tpl');
        }

    }

    /**
     * 文本添加和修改
     */
    public function setTxt()
    {
        $data = [];
        # 保存信息
        if ($_POST && $this->isPost()) {
            $name = $this->post('name');
            $toUser = $this->post('all_user');
            $userId = $this->post('user_id');
            $txt = strip_tags($this->post('txt'));
            $data = [
                'name' => $name,
                'type' => self::TXT, # 文本类型
                'txt' =>  $txt,
            ];

            # 名称
            if (empty($name)) {
                echo $this->bjuiRes(false, '名称不能为空', '', false);
                return;
            }

            if (empty($txt)) {
                echo $this->bjuiRes(false, '内容不能为空', '', false);
                return;
            }

            if (empty($toUser) && empty($userId)) {
                echo $this->bjuiRes(false, '请选择用户', '', false);
                return;
            }

            $sql = "SELECT a.pid,u.uniqueId,u.appkey,u.systemName,u.deviceName,u.version FROM baohe.pm_account_list AS a
                        LEFT JOIN baohe.pub_passport_info AS u ON u.pid = a.pid
                        WHERE a.account_type = 4 ";
            if ($toUser == 1) {
                $list = $this->db->query($sql)->result_array();
                $toNum = !empty($list) ? count($list) : 0;
            } else {
                $toNum = count($userId);
                $sql .= " AND a.pid in (" . implode(',', $userId) . ")";
                $list = $this->db->query($sql)->result_array();
            }
            $data['to_num'] = $toNum;

            $noticeId = $this->NoticeModel->insert($data);
            echo $this->bjuiRes(true, '添加成功', '', false);

            if(function_exists('fastcgi_finish_request')) {
                fastcgi_finish_request();
            }

            # 给用户发推送消息
            $i = 1;

            foreach ($list as $k => $v) {
                $v['title'] = $name;
                $v['msgType'] = self::TXT_TYPE; # 文本消息类型
                $v['msgUrl'] = '';

                if (! empty($v['uniqueId']) ) { # 上架并且用户有uniqueId 才有给用户发推送消息
                    $this->push($v);
                }
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

            $this->display('notice/setTxt.tpl');
        }

    }

    /**
     * 详细详情
     */
    public function info()
    {
        $id = $this->get('id');
        $where = ['id' => $id];
        $info = $this->NoticeModel->getRow($where);

        $this->assign('info', $info);
        $this->display('notice/info.tpl');
    }

}