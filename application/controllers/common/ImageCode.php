<?php

/**
 * 图形验证码图片管理
 * User: Administrator
 * Date: 2017/3/15
 * Time: 10:25
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class ImageCode extends MY_Controller
{

    public $_insure_url = [
        '大地保险' => 'http://m.ubi001.com/h5/v1/insure',
        '中国大地保险' => 'http://m.ubi001.com/h5/v1/channel',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ImageCode_model', 'ImageCodeModel');
        $this->_tabId  = ENVIRONMENT == 'development' ? 'id278' : 'id278';
    }

    /**
     * 列表
     */
    public function lists()
    {
        $postData = $this->input->post();
        list($pageCurrent, $pageSize) = array((int) isset($postData['pageCurrent']) && !empty($postData['pageCurrent']) ? $postData['pageCurrent'] : 1,
            (int) isset($postData['pageSize']) && !empty($postData['pageSize']) ? $postData['pageSize'] : 30);

        $offset = ($pageCurrent - 1 ) * $pageSize;
        $count = $this->ImageCodeModel->getCount($postData);
        $list = $this->ImageCodeModel->getList($postData,'', '','', $offset, $pageSize);

        foreach ($list as $k=>$v) {
            $list[$k]['row_id'] = $offset + $k + 1;
        }

        $this->assign('list', $list);
        $this->assign('limit', $pageSize);
        $this->assign('count', $count);
        $this->assign('search', $postData);
        $this->display('image_code/list.tpl');
    }

    /**
     * 添加或者修改
     */
    public function set()
    {
        $id = $this->input->post_get('id');
        if(!empty($_POST) && $this->isPost()) {
            $url = $this->input->post('url');
            $name = $this->input->post('name');
            $data = [
                'url' => $url,
                'name' => $name,
            ];

            $id = !empty($id) ? $id : 0;
            $info = $this->ImageCodeModel->hasName($name, $id);
            if ($info) {
                echo $this->bjuiRes(false, '名字已存在，请换成别的名字', $this->_tabId, false);
                return ;
            }

            if ($id) {
                $where = ['id' => $id];
                $this->ImageCodeModel->update($data, $where);
                echo $this->bjuiRes(true, '修改成功', $this->_tabId);
            } else {
                $id = $this->ImageCodeModel->add($data);
                if ($id) {
                    echo $this->bjuiRes(true, '添加成功', $this->_tabId);
                } else{
                    echo $this->bjuiRes(false, '系统异常', $this->_tabId);
                }
            }

            # 退出
            return;
        } else {
            $info = [];
            if ($id) {
                $where = ['id' => $id];
                $info = $this->ImageCodeModel->getRow($where);
            }

            $this->assign('token', $this->qiniuToken());
            $this->assign('info', $info);
            $this->display('image_code/set.tpl');
        }
    }

    /**
     * 删除
     */
    public function delete()
    {
        $id = $this->input->get('id');
        $where = ['id' => $id];
        $num = $this->ImageCodeModel->delete($where);
        if ($num) {
            echo $this->bjuiRes(true, '删除成功', $this->_tabId, false);
        } else {
            echo $this->bjuiRes(false, '系统异常', $this->_tabId, false);
        }
        return;
    }
}