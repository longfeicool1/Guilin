<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends MY_Controller
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
        $condition = ['a.isShow' => 1];
        if (!empty($data['content'])) {
            $condition['CONCAT(a.name,a.mobile) like'] = "%{$data['content']}%";
        }
        $page  = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
        $size  = !empty($data['pageSize']) ? $data['pageSize'] : 30;
        $list  = $this->MemberModel->getMemberList($page,$size,$condition);
        $count = $this->MemberModel->getMemberCount($condition);
        // print_r($roles);die;
        $this->ci_smarty->assign('list',$list);
        $this->ci_smarty->assign('count',$count);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->display('member/memberList.tpl');
    }

    public function memberInfo()
    {
        $id      = $this->input->get('id');
        $data    = $this->MemberModel->getMemberInfo($id);
        $comment = $this->MemberModel->getCommentList($id);
        $this->ci_smarty->assign('data', $data);
        $this->ci_smarty->assign('comment', $comment);
        $this->ci_smarty->assign('payType', $this->MemberModel->getValue('payType'));
        $this->ci_smarty->assign('customLevel', $this->MemberModel->getValue('customLevel'));
        $this->ci_smarty->assign('customStatus', $this->MemberModel->getValue('customStatus'));
        $this->ci_smarty->assign('callType', $this->MemberModel->getValue('callType'));
        $this->ci_smarty->display('member/memberInfo.tpl');
    }

    public function updateInfo()
    {
        $id     = $this->input->get('id');
        $data   = $this->input->post();
        $result = $this->MemberModel->toUpdateInfo($id,$data);
        if ($result['errcode'] == 200) {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'memberList');
        } else {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'',false);
        }
    }

    public function delMember()
    {
        $ids    = $this->input->post_get('delids');
        $result = $this->MemberModel->toDelMember($ids);
        if ($result['errcode'] == 200) {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'memberList',false);
        } else {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'',false);
        }
    }

    public function memberDownload()
    {
        $data = $this->input->get();
        $condition = [];
        if (!empty($data['content'])) {
            $condition['CONCAT(a.name,a.mobile) like'] = "%{$data['content']}%";
        }
        $list      = $this->MemberModel->getMemberList(1,1999,$condition);
        $header = array(
            'name'           => '姓名',
            'sex'            => '性别',
            'age'            => '年龄',
            'mobile'         => '手机',
            'city'           => '城市',
            'occapation'     => '职业',
            'payType'        => '发薪方式',
            'income'         => '收入',
            'socialSecurity' => '社保',
            'reservedFunds'  => '公积金',
            'haveHouse'      => '有房',
            'haveCar'        => '有车',
            'firstName'      => '业务员',
            'meetTime'       => '预约时间',
            'customStatus'   => '用户状态',
            'dataLevel'      => '数据类型',
            'customLevel'    => '名单星级',
        );
        // echo '<pre>';print_r($data);die;
        $filename = date('Y-m-d').'客户下载列表.xls';
        $this->commonModel->export($header, $list, $filename);
    }

    public function rubbish()
    {
        $data = $this->input->post();
        if ($data) {
            $this->session->set_userdata('memberList', $data);
        } else {
            $data = $this->session->userdata('memberList');
        }
        $condition = ['a.isShow' => 2];
        if (!empty($data['content'])) {
            $condition['CONCAT(a.name,a.mobile) like'] = "%{$data['content']}%";
        }
        $page  = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
        $size  = !empty($data['pageSize']) ? $data['pageSize'] : 30;
        $list  = $this->MemberModel->getMemberList($page,$size,$condition);
        $count = $this->MemberModel->getMemberCount($condition);
        // print_r($roles);die;
        $this->ci_smarty->assign('list',$list);
        $this->ci_smarty->assign('count',$count);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->display('member/rubbish.tpl');
    }

    public function back()
    {

    }

    public function dataUpload()
    {

    }
}