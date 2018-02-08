<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fun extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('fun/FunModel');
        $this->load->model('CommonModel');
    }

    /**
     * [allot 数据分配]
     * @return [type] [description]
     */
    public function allot()
    {
        $data = $this->FunModel->getCustomTotal();
        $man  = $this->FunModel->getSalesman();
        // D($man);
        $this->ci_smarty->assign('data', $data);
        $this->ci_smarty->assign('man', $man);
        $this->ci_smarty->display('fun/allot.tpl');
    }

    public function startAllot()
    {
        $data   = $this->input->post();
        $result = $this->FunModel->toAllot($data);
        echo '<pre>';print_r($data);die;
    }
}