<?php

/**
 * banner管理
 * User: Administrator
 * Date: 2017/3/15
 * Time: 10:25
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner extends MY_Controller
{
    public $isLimit = [1 => '不限', 2 => '仅限'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Banner_model', 'BannerModel');
        $this->load->model('BannerAllow_model', 'BannerAllowModel');
        $this->_tabId = 'id281';
    }

    /**
     * 列表
     */
    public function lists()
    {
        $data = $this->input->post();
        list($pageCurrent, $pageSize) = $this->getPageSizeAndCurrent($data);

        $offset = ($pageCurrent - 1) * $pageSize;
        $count = $this->BannerModel->counts($data);
        $list = $this->BannerModel->lists($data, $pageCurrent, $pageSize);

        $nowTime = time();
        foreach ($list as $k => $v) {
            $list[$k]['row_id'] = $offset + $k + 1;
            if (strtotime($v['start_time']) <= $nowTime && $nowTime <= strtotime($v['end_time']) && $v['status'] == 1) {
                $list[$k]['show_status'] = 1;
            } else {
                $list[$k]['show_status'] = 2; #'未展示';
                if ($v['status'] == 2) {
                    $list[$k]['show_txt_status'] = 2; #'已下架';
                } else if (strtotime($v['start_time']) > $nowTime) {
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
        $this->assign('isLimit', $this->isLimit);
        $this->display('banner/list.tpl');
    }

    public function allow()
    {
        # bannerId
        $id = $this->post_get('id');
        $data = $this->input->post();
        $data['banner_id']  = $id;
        list($pageCurrent, $pageSize) = $this->getPageSizeAndCurrent($data);

        $offset = ($pageCurrent - 1) * $pageSize;
        $count = $this->BannerAllowModel->allowCounts($data);
        $list = $this->BannerAllowModel->allowLists($data, $pageCurrent, $pageSize);

        foreach($list as $k => $v) {
            $list[$k]['row_id'] = $offset + $k + 1;
        }

        $this->assign('list', $list);
        $this->assign('limit', $pageSize);
        $this->assign('count', $count);
        $this->assign('search', $data);
        $this->display('banner/allow_list.tpl');
    }

    /**
     * 添加或者修改
     */
    public function set()
    {
        $id = $this->input->post_get('id');
        if (!empty($_POST) && $this->isPost()) {
            $name = $this->input->post('name');
            $androidImg = $this->input->post('android_img');
            $iosImg = $this->input->post('ios_img');
            $url = $this->input->post('url');
            $start_time = $this->input->post('start_time');
            $end_time = $this->input->post('end_time');
            $status = $this->input->post('status');
            $userId = $this->post('user_id');
            $isLimit = $this->post('is_limit');

            # 检测开始时间和结束时间是否合理
            if (strtotime($start_time) > strtotime($end_time)) {
                echo $this->bjuiRes(false, '开始时间不能大于结束时间', $this->_tabId, false);
                return;
            }

            # 图片验证
            if (empty($androidImg)) {
                echo $this->bjuiRes(false, 'android图片不能为空', $this->_tabId, false);
                return;
            }
            if (empty($iosImg)) {
                echo $this->bjuiRes(false, 'ios图片不能为空', $this->_tabId, false);
                return;
            }

            # 状态验证
            if (!in_array($status, [1, 2])) {
                echo $this->bjuiRes(false, '上架状态不正确，请按正规流程操作');
                return;
            }

            if ($isLimit == 2 && empty($userId)) {
                echo $this->bjuiRes(false, '请选择可见用户');
                return;
            }

            $data = [
                'name' => $name,
                'url' => $url,
                'status' => $status,
                'start_time' => $start_time,
                'end_time' => $end_time,
                // 'image'       => $image,
                'android_img' => $androidImg,
                'ios_img' => $iosImg,
                'is_limit' => $isLimit,
            ];

            # 更新
            if ($id) {
                $where = ['id' => $id];
                $this->BannerModel->update($data, $where);
                echo $this->bjuiRes(true, '修改成功');
            } else { # 添加
                $id = $this->BannerModel->insert($data);

                if ($id) {
                    echo $this->bjuiRes(true, '添加成功');
                } else {
                    echo $this->bjuiRes(false, '系统异常');
                }
            }

            # 更新限制用户
            $this->BannerAllowModel->updateAllow($userId, $id);

            # 退出
            return;
        } else {
            $info = [];
            if ($id) {
                $where = ['id' => $id];
                $info = $this->BannerModel->getRow($where);
            }

            $this->assign('token', $this->qiniuToken());
            $this->assign('info', $info);
            $this->display('banner/set.tpl');
        }
    }

    /**
     * 上下架
     */
    public function line()
    {
        $id = $this->input->get('id');
        $status = intval($this->get('status'));
        if (!in_array($status, [1, 2])) {
            echo $this->bjuiRes(true, '状态无效', $this->_tabId, false);
            return;
        }

        $row = $this->BannerModel->getRow(['id' => $id]);
        if (strtotime($row['end_time']) < time() && $status == 1) {
            echo $this->bjuiRes(true, '活动已过期，请编辑活动时间', $this->_tabId, false);
            return;
        }


        $where = ['id' => $id];
        $data = ['status' => $status];
        $num = $this->BannerModel->update($data, $where);
        if ($num) {
            echo $this->bjuiRes(true, '设置成功', $this->_tabId, false);
        } else {
            echo $this->bjuiRes(false, '系统异常', $this->_tabId, false);
        }
        return;
    }
}