<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 线下服务商后台
 */
class Report extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // 引入model
        $this->load->model('Report_model', 'reportModel');
        $this->load->model('Common_model', 'commonModel');
    }

    public function weekList()
    {
        $data = $this->input->post();
        if ($data) {
            $this->session->set_userdata('weekList', $data);
        } else {
            $data = $this->session->userdata('weekList');
        }
        $condition = [];
        if (!empty($data['get_income_status'])) {
            $condition['a.get_income_status'] = $data['get_income_status'];
        }
        if (!empty($data['bt'])) {
            $condition['created >= '] = $data['bt'];
        }
        if (!empty($data['et'])) {
            $condition['created <= '] = $data['et'];
        }
        if (!empty($data['content'])) {
            $condition['CONCAT(a.carcard,a.report_title) like'] = "%{$data['content']}%";
        }
        $page      = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
        $size      = !empty($data['pageSize']) ? $data['pageSize'] : 30;
        $list      = $this->reportModel->weekList($page,$size,$condition);
        $count     = $this->reportModel->weekCount($condition);
        $this->ci_smarty->assign('list', $list);
        $this->ci_smarty->assign('count', $count);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->display('report/week.tpl');
    }

    public function monthList()
    {
        $data = $this->input->post();
        if ($data) {
            $this->session->set_userdata('monthList', $data);
        } else {
            $data = $this->session->userdata('monthList');
        }
        $condition = [];
        // if ($data['car_status'] != '') {
        //     $condition['car_status'] = $data['car_status'];
        // }
        if (!empty($data['bt'])) {
            $condition['a.collect_date >= '] = $data['bt'];
        }
        if (!empty($data['et'])) {
            $condition['a.collect_date <= '] = $data['et'];
        }
        $page      = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
        $size      = !empty($data['pageSize']) ? $data['pageSize'] : 30;
        $list      = $this->reportModel->monthList($page,$size,$condition);
        $count     = $this->reportModel->monthCount($condition);
        $this->ci_smarty->assign('list', $list);
        $this->ci_smarty->assign('count', $count);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->display('report/month.tpl');
    }

    public function reportExport()
    {
        $list = $this->reportModel->getTotalList();
        $header = array(
            'account_login' => '账户',
            'tripCount'     => '总行程数',
            'totalMile'     => '总路程(KM)',
            'totalTime'     => '总行驶时间(H)',
        );
        // echo '<pre>';print_r($list);die;
        $filename = '6/14-'.date('m/d').'阳光用户里程统计.xls';
        $this->commonModel->exportExcel($header,$list,$filename);
        // echo "<pre>";print_r($list);die;
    }
}