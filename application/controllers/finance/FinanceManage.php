<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 线下服务商后台
 */
class FinanceManage extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // 引入model
        $this->load->model('Finance_model', 'financeModel');
        $this->load->model('Common_model', 'commonModel');
    }

    /**
     * [cashRequest 提现审核]
     * @return [type] [description]
     */
    public function cashRequest()
    {
        $data = $this->input->post();
        if ($data) {
            $this->session->set_userdata('cashRequestSearch', $data);
        } else {
            $data = $this->session->userdata('cashRequestSearch');
        }
        $condition = ['a.audit' => 1, 'a.action' => 1, 'a.pay_type' => 'bank'];
        if (!empty($data['bt'])) {
            $condition['a.created >= '] = $data['bt'];
        }
        if (!empty($data['et'])) {
            $condition['a.created <= '] = $data['et'];
        }
        if (!empty($data['content'])) {
            $condition['CONCAT(c.account_login,f.carcard,b.account_name) like'] = "%{$data['content']}%";
        }
        $page       = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
        $size       = !empty($data['pageSize']) ? $data['pageSize'] : 30;
        $list       = $this->financeModel->requestList($page,$size,$condition);
        $count      = $this->financeModel->requestCount($condition);
        $totalMonsy = $this->financeModel->totalOutMoney();
        $todayMonsy = $this->financeModel->todayOutMoney();
        $this->ci_smarty->assign('list', $list);
        $this->ci_smarty->assign('count', $count);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->assign('totalMonsy', $totalMonsy);
        $this->ci_smarty->assign('todayMonsy', $todayMonsy);
        $this->ci_smarty->display('finance/cashRequest.tpl');
    }


    /**
     * [rewardList 奖励记录]
     * @return [type] [description]
     */
    public function rewardList()
    {
        $data = $this->input->post();
        if ($data) {
            $this->session->set_userdata('rewardList', $data);
        } else {
            $data = $this->session->userdata('rewardList');
        }
        $condition = ['a.get_income_status !=' => 4];
        if (!empty($data['get_income_status'])) {
            $condition['a.get_income_status'] = $data['get_income_status'];
        }
        if (!empty($data['bt'])) {
            $condition['a.collect_date >= '] = $data['bt'];
        }
        if (!empty($data['et'])) {
            $condition['a.collect_date <= '] = $data['et'];
        }
        if (!empty($data['content'])) {
            $condition['CONCAT(c.account_login,a.carcard) like'] = "%{$data['content']}%";
        }

        $data['get_income_status'] = isset($data['get_income_status']) ? $data['get_income_status'] : '';

        $page      = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
        $size      = !empty($data['pageSize']) ? $data['pageSize'] : 30;
        $list      = $this->financeModel->rewardList($page,$size,$condition);
        $count     = $this->financeModel->rewardCount($condition);
        $this->ci_smarty->assign('list', $list);
        $this->ci_smarty->assign('count', $count);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->display('finance/rewardList.tpl');
    }

    /**
     * [transferAccounts 转账记录]
     * @return [type] [description]
     */
    public function transferAccounts()
    {
        $data = $this->input->post();
        if ($data) {
            $this->session->set_userdata('transferAccounts', $data);
        } else {
            $data = $this->session->userdata('transferAccounts');
        }
        $condition = ['a.audit >= ' => 2, 'a.action' => 1, 'a.pay_type' => 'bank'];
        // if ($data['car_status'] != '') {
        //     $condition['car_status'] = $data['car_status'];
        // }
        if (!empty($data['bt'])) {
            $condition['a.created >= '] = $data['bt']. ' 00:00:00';
        }
        if (!empty($data['et'])) {
            $condition['a.created <= '] = $data['et']. ' 23:59:59';
        }
        if (!empty($data['content'])) {
            $condition['CONCAT(c.account_login,f.carcard) like'] = "%{$data['content']}%";
        }
        $page      = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
        $size      = !empty($data['pageSize']) ? $data['pageSize'] : 30;
        $list      = $this->financeModel->requestList($page,$size,$condition,2);
        $count     = $this->financeModel->requestCount($condition);
        $this->ci_smarty->assign('list', $list);
        $this->ci_smarty->assign('count', $count);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->display('finance/transferAccounts.tpl');
    }

    public function changeRequestStatus()
    {
        $id     = $this->input->get('id');
        $type   = $this->input->get('type');
        $bankId = $this->input->get('bankId');
        $result = $this->financeModel->toChangeRequestStatus($id,$type,$bankId);
        if ($result['errcode'] == 200) {
            $this->commonModel->ajaxReturn($result['errcode'],$result['errmsg'],'id285',false);
        } else {
            $this->commonModel->ajaxReturn($result['errcode'],$result['errmsg']);
        }
    }

    /**
     * [cashRequestExport 提现审核导出]
     * @return [type] [description]
     */
    public function cashRequestExport()
    {
        $data = $this->input->get();
        $condition = ['a.audit' => 1, 'a.action' => 1, 'a.pay_type' => 'bank'];
        if (!empty($data['bt'])) {
            $condition['a.created >= '] = $data['bt'];
        }
        if (!empty($data['et'])) {
            $condition['a.created <= '] = $data['et'];
        }
        $list      = $this->financeModel->requestList(1,999999,$condition);
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

    public function transferAccountsExport()
    {
        $data = $this->input->post();
        $condition = ['a.audit >= ' => 2, 'a.action' => 1, 'a.pay_type' => 'bank'];
        if (!empty($data['bt'])) {
            $condition['a.created >= '] = $data['bt'];
        }
        if (!empty($data['et'])) {
            $condition['a.created <= '] = $data['et'];
        }
        $list      = $this->financeModel->requestList(1,9999,$condition);
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
            'updated'        => '审核时间',
        );
        $css = [
            'bankcode'       => 'txt',
        ];
        // echo '<pre>';print_r($data);die;
        $filename = date('Y-m-d').'阳光U驾宝转账记录列表.xls';
        $this->commonModel->exportExcel($header, $list, $filename,$css);
    }

    public function rewardListExport()
    {
        $data = $this->input->get();
        $condition = ['a.get_income_status !=' => 4];
        if ($data['get_income_status'] != '') {
            $condition['a.get_income_status'] = $data['get_income_status'];
        }
        if (!empty($data['bt'])) {
            $condition['a.collect_date >= '] = $data['bt'];
        }
        if (!empty($data['et'])) {
            $condition['a.collect_date <= '] = $data['et'];
        }
        $list      = $this->financeModel->rewardList(1,9999,$condition);
        $header = array(
            'collect_date'    => '时间',
            'score'           => '周评分',
            'reawardName'     => '奖励类型',
            'week_income'     => '奖励金额(元)',
            'account_login'   => '账户',
            'carcard'         => '车牌',
            'getIncomeStatus' => '领取状态',
        );
        // echo '<pre>';print_r($data);die;
        $filename = date('Y-m-d').'阳光U驾宝周奖励记录.xls';
        $this->commonModel->export($header, $list, $filename);
    }
}