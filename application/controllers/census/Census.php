<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 线下服务商后台
 */
class Census extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // 引入model
        $this->load->model('Census_model', 'censusModel');
        $this->load->model('Common_model', 'commonModel');
    }

    /**
     * [totalWork 安装统计]
     * @return [type] [description]
     */
    public function totalWork()
    {
        $data = $this->input->post();
        if ($data) {
            $this->session->set_userdata('totalWork', $data);
        } else {
            $data = $this->session->userdata('totalWork');
        }
        $condition = [];
        if (!empty($data['bt'])) {
            $condition['collect_date >= '] = $data['bt'];
        }
        if (!empty($data['et'])) {
            $condition['collect_date <= '] = $data['et'];
        }
        $page  = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
        $size  = !empty($data['pageSize']) ? $data['pageSize'] : 30;
        $list  = $this->censusModel->installList($page,$size,$condition);
        $count = $this->censusModel->installCount($condition);
        $num   = $this->censusModel->sumObd($condition);
        // print_r($num);die;
        $this->ci_smarty->assign('num', $num);
        $this->ci_smarty->assign('list', $list);
        $this->ci_smarty->assign('count', $count);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->display('census/totalWork.tpl');
    }


    /**
     * [totalWork 在线统计]
     * @return [type] [description]
     */
    public function totalOnline()
    {
        $data = $this->input->post();
        if ($data) {
            $this->session->set_userdata('totalOnline', $data);
        } else {
            $data = $this->session->userdata('totalOnline');
        }
        $condition = [];
        if (!empty($data['bt'])) {
            $condition['collect_date >= '] = $data['bt'];
        }
        if (!empty($data['et'])) {
            $condition['collect_date <= '] = $data['et'];
        }
        $page       = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
        $size       = !empty($data['pageSize']) ? $data['pageSize'] : 30;
        $list       = $this->censusModel->onlineList($page,$size,$condition);
        $count      = $this->censusModel->onlineCount($condition);
        $this->ci_smarty->assign('list', $list);
        $this->ci_smarty->assign('count', $count);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->display('census/totalOnline.tpl');
    }

    /**
     * [totalWork 在线统计]
     * @return [type] [description]
     */
    public function threeDayTotal()
    {
        $data = $this->input->post();
        if ($data) {
            $this->session->set_userdata('threeDayTotal', $data);
        } else {
            $data = $this->session->userdata('threeDayTotal');
        }
        $condition = [];
        if (!empty($data['bt'])) {
            $condition['collect_date >= '] = $data['bt'];
        }
        if (!empty($data['et'])) {
            $condition['collect_date <= '] = $data['et'];
        }
        $page       = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
        $size       = !empty($data['pageSize']) ? $data['pageSize'] : 30;
        $list       = $this->censusModel->threeOnlineList($page,$size,$condition);
        $count      = $this->censusModel->threeOnlineCount($condition);
        $this->ci_smarty->assign('list', $list);
        $this->ci_smarty->assign('count', $count);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->display('census/threeDayTotal.tpl');
    }

}