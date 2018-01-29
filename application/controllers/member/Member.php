<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('member/MemberModel');
        $this->load->model('CommonModel');
    }

    public function memberList()
    {
        $data = $this->input->post();
        if ($data) {
            $this->session->set_userdata('memberList', $data);
        } else {
            $data = $this->session->userdata('memberList');
        }
        $condition = [];
        if (!empty($data['content'])) {
            $condition['CONCAT(name,mobile) like'] = "%{$data['content']}%";
        }
        $page  = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
        $size  = !empty($data['pageSize']) ? $data['pageSize'] : 30;
        $list  = $this->UserModel->getUserList($page,$size,$condition);
        $count = $this->UserModel->getUserCount($condition);
        // print_r($roles);die;
        $this->ci_smarty->assign('list',$list);
        $this->ci_smarty->assign('count',$count);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->display('member/memberList.tpl');
    }
}