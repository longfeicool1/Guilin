<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fun extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('fun/FunModel');
        $this->load->model('admin/UserModel');
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
        // D($this->userinfo);
        $this->ci_smarty->assign('data', $data);
        $this->ci_smarty->assign('man', $man);
        $this->ci_smarty->display('fun/allot.tpl');
    }

    public function startAllot()
    {
        $data   = $this->input->post();
        // D($data);
        $result = $this->FunModel->toAllot($data);
        if ($result['errcode'] == 200) {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'allot',false);
        } else {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'',false);
        }
    }

    public function reallot()
    {
        $ids     = $this->input->get('ids');
        $saleman = $this->UserModel->getUserList(1,99,['position' => 5]);
        $this->ci_smarty->assign('saleman', $saleman);
        $this->ci_smarty->assign('ids', $ids);
        $this->ci_smarty->display('fun/reallot.tpl');
    }

    public function startReallot()
    {
        $data = $this->input->post();
        $result = $this->FunModel->toReallot($data);
        if ($result['errcode'] == 200) {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'memberList');
        } else {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'',false);
        }
    }
}