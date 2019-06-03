<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/AuthModel');
        $this->load->model('member/MemberModel');
        $this->load->model('CommonModel');
    }

    public function addAuth()
    {
        $nodes = $this->AuthModel->toGetAuth();
        foreach($nodes as $k=>$v){
            $n = substr_count($v['path'], ',');
            $m = !empty($nodes[$k+1]) ? substr_count($nodes[$k+1]['path'], ',') : 0;
            if($n > 0 && $m){
                if($n == $m){
                    $nodes[$k]['r'] = str_repeat("&nbsp;", ($n-1) * 3) . str_repeat('├ ',1);
                }else if($n < $m){
                    $nodes[$k]['r'] = str_repeat("&nbsp;", ($n-1) * 3) . str_repeat('├ ',1);
                }else{
                    $nodes[$k]['r'] = str_repeat("&nbsp;", ($n-1) * 3) . str_repeat('└ ',1);
                }
            }else if($n > 0 && !$m){
                $nodes[$k]['r'] = str_repeat("&nbsp;", ($n-1) * 3) . str_repeat('└ ',1);
            }
        }
        $this->ci_smarty->assign('rules',$nodes);
        $this->ci_smarty->display('admin/addAuth.tpl');
    }

    public function toAuth()
    {
        $data   = $this->input->post();
        $result = $this->AuthModel->toAddAuth($data);
        $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'',0);
    }

    public function role()
    {
        $role = $this->AuthModel->getRoleList();
        $rule = $this->AuthModel->toGetAuth();
        $city = $this->MemberModel->getCity();
        // D($city);
        $this->ci_smarty->assign('city', $city);
        $this->ci_smarty->assign('role', $role);
        $this->ci_smarty->assign('rule', $rule);
        $this->ci_smarty->display('admin/role.tpl');
    }

    public function addRole()
    {
        $roleId = $this->input->get('role_id','');
        $data   = $this->input->post();
        if (!empty($data['is_finance']) && $data['is_finance'] == 2) {
            $data['look_city'] = implode(',',$data['look_city']);
        } else {
            $data['look_city'] = '';
            // unset($data['look_city']);
        }
        $result = $this->AuthModel->toAddRole($data,$roleId);

        if ($result['errcode'] == 200) {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'role',0);
        } else {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'',0);
        }
    }

    public function editRole()
    {
        $roleId   = $this->input->get('id','');
        $findRole = $this->AuthModel->findRole($roleId);
        $rule     = $this->AuthModel->toGetAuth(1);
        $city     = $this->MemberModel->getCity();
        // D($city);
        $this->ci_smarty->assign('city', $city);
        $this->ci_smarty->assign('role',$findRole);
        $this->ci_smarty->assign('rid',explode(',', $findRole['rule_id']));
        $this->ci_smarty->assign('rule', $rule);
        $this->ci_smarty->display('admin/editRole.tpl');
    }

    public function delRole(){
        $id     = $this->input->get('id','');
        $result = $this->AuthModel->toDelRole($id);
        if ($result['errcode'] == 200) {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'role',0);
        } else {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'',0);
        }
    }

    public function changeShow()
    {
        $id = $this->input->get('id','');
        $result = $this->AuthModel->changeStatus($id);
        if ($result['errcode'] == 200) {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'role',0,$result['n']);
        } else {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'',0);
        }
    }

}