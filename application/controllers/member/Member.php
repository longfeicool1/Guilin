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
        $condition = [];
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

    public function memberDownload()
    {
        $data = $this->input->get();
        $condition = [];
        if (!empty($data['bt'])) {
            $condition['a.created >= '] = $data['bt'];
        }
        if (!empty($data['et'])) {
            $condition['a.created <= '] = $data['et'];
        }
        $list      = $this->MemberModel->getMemberList(1,999999,$condition);
        $header = array(
            'account_login'  => '账户',
            'amount'         => '提现金额(元)',
            'bankcode'       => '银行账户',
            'name'           => '银行名称',
            'location'       => '所在地',
            'deposit_bank'   => '开户行',
            'account_name'   => '账户名',
            'bankCardStatus' => '银行卡状态',
            'auditName'      => '转账状态',
            'created'        => '创建时间',
        );
        // echo '<pre>';print_r($data);die;
        $filename = date('Y-m-d').'阳光U驾宝提现审核列表.xls';
        $this->commonModel->export($header, $list, $filename);
    }
}