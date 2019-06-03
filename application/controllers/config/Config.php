<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 配置设置
 * @author  Loonfiy
 * +2018-03-02
 */

class Config extends MY_Controller
{
    public function __CONSTRUCT()
    {
        parent::__CONSTRUCT();
        $this->load->model('config/ConfigModel');
        $this->load->model('CommonModel');
    }

    public function interfaceConf()
    {
        $data = $this->input->post();
        if ($data) {
            $this->session->set_userdata('interfaceConf', $data);
        } else {
            $data = $this->session->userdata('interfaceConf');
        }
        $condition = [];
        $page  = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
        $size  = !empty($data['pageSize']) ? $data['pageSize'] : 30;
        $list  = $this->ConfigModel->getConfList($page,$size,$condition);
        $count = $this->ConfigModel->getConfNum($condition);
        // print_r($list);die;
        $this->ci_smarty->assign('list',$list);
        $this->ci_smarty->assign('count',$count);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->display('config/interfaceConf.tpl');
    }

    public function addConfName()
    {
        $id   = $this->input->get('id');
        $data = [];
        if ($id) {
            $data = $this->ConfigModel->getConfName($id);
        }
        $this->ci_smarty->assign('id',$id);
        $this->ci_smarty->assign('data',$data);
        $this->ci_smarty->display('config/addConfName.tpl');
    }

    public function toadd()
    {
        $id     = $this->input->get('id');
        $data   = $this->input->post();
        $result = $this->ConfigModel->toadd($id,$data);
        if ($result['errcode'] == 200) {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'interfaceConf');
        } else {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'',false);
        }
    }

    public function createAppScrect()
    {
        $this->CommonModel->output(random_str(18));
    }
}