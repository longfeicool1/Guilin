<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/UserModel');
        $this->load->model('CommonModel');
    }

    public function adminUserList()
    {
        $data = $this->input->post();
        if ($data) {
            $this->session->set_userdata('adminUserList', $data);
        } else {
            $data = $this->session->userdata('adminUserList');
        }
        $condition = ['is_show' => 1];
        if (!empty($data['content'])) {
            $condition['CONCAT(username,name) like'] = "%{$data['content']}%";
        }
        $page      = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
        $size      = !empty($data['pageSize']) ? $data['pageSize'] : 30;
        $users     = $this->UserModel->getUserList($page,$size,$condition);
        $count     = $this->UserModel->getUserCount($condition);
        $roles     = $this->UserModel->getRoleList();
        // print_r($roles);die;
        $this->ci_smarty->assign('users',$users);
        $this->ci_smarty->assign('roles',$roles);
        $this->ci_smarty->assign('count',$count);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->display('admin/adminUserList.tpl');
    }

    public function addUser()
    {
        $data   = $this->input->post();
        $result = $this->UserModel->toAddUser($data['userList']);
        $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'',0);
    }

    public function delUser()
    {
        $uid    = $this->input->get('uid');
        $result = $this->UserModel->toDelUser($uid);
        $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'',0);
    }

}