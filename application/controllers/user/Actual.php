<?php
class Actual extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // 引入model
        $this->load->model('Actual_model', 'actualModel');
        $this->load->model('Common_model', 'commonModel');
    }

    public function actualLists()
    {
        $data              = $this->input->post();
        $bindid            = $this->input->get('bindid');
        // $data['bindid'] = 30644;
        $condition         = ['bindid' => $bindid];
        if (!empty($data['bt'])) {
            $condition['created >= '] = $data['bt'];
        }
        if (!empty($data['et'])) {
            $condition['created <= '] = $data['et'];
        }
        if (empty($bindid)) {
            $list   = [];
            $count  = 0;
            $search = [];
            $bindid = '';
        } else {
            $page       = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
            $size       = !empty($data['pageSize']) ? $data['pageSize'] : 30;
            $list       = $this->actualModel->lists($page,$size,$condition);
            $count      = $this->actualModel->actualCount($condition);
        }
        $this->ci_smarty->assign('list', $list);
        $this->ci_smarty->assign('count', $count);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->assign('bindid', $bindid);
        $this->display('yg_user/actualLists.tpl');
    }

    public function actualInfo()
    {
        $trailid = $this->input->get('trailid');
        $data    = $this->actualModel->info($trailid);
        // $data['trip_id'] = 58;
        $brake   = $this->actualModel->brakes($data['trip_id'],$data['src']);
        $this->ci_smarty->assign('data', $data);
        $this->ci_smarty->assign('brake', $brake);
        $this->display('yg_user/actualInfo.tpl');
    }

}