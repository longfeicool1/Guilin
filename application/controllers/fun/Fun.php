<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fun extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('fun/FunModel');
        $this->load->model('admin/UserModel');
        $this->load->model('member/MemberModel');
        $this->load->model('CommonModel');
    }

    /**
     * [allot 数据分配]
     * @return [type] [description]
     */
    public function allot()
    {
        $data  = $this->FunModel->getCustomTotal();
        $man   = $this->FunModel->getSalesman();
        $limit = $this->FunModel->getMyLimit();
        // D($this->userinfo);
        $this->ci_smarty->assign('data', $data);
        $this->ci_smarty->assign('limit', $limit);
        $this->ci_smarty->assign('man', $man);
        $this->ci_smarty->display('fun/allot.tpl');
    }

    public function startAllot()
    {
        $data   = $this->input->post();
        // D($data);
        $result = $this->FunModel->toAllot($data);
        if ($result['errcode'] == 200) {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'allot',false,$result['result']);
        } else {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'',false);
        }
    }

    public function reallot()
    {
        $ids     = $this->input->get('ids');
        $saleman = $this->MemberModel->getUser();
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

    public function tuanSet()
    {
        $data = $this->input->post();
        if (!empty($data['type']) && $data['type'] == 'update') {
            $result = $this->FunModel->toSetLimit($data['list']);
            if ($result['errcode'] == 200) {
                $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'memberList');
            } else {
                $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'',false);
            }
            D($data);
        } else {
            $man  = $this->FunModel->getSalesman(2);
            $limitInfo = $this->FunModel->getLimitInfo();
            $limitInfo = array_column($limitInfo,null,'uid');
            foreach ($man as $k => $v) {
                if (!in_array($v['position'], [2,3, 4])) {
                    unset($man[$k]);
                    continue;
                }
                $limit = [
                    'limit_num_a' => 0,
                    'limit_num_b' => 0,
                    'limit_num_c' => 0,
                    'exist'       => 0,
                ];
                if (!empty($limitInfo[$v['uid']])) {
                    $limit          = array_merge($limit,$limitInfo[$v['uid']]);
                    $limit['exist'] = 1;
                }
                $man[$k] = array_merge($v,$limit);
            }
            // echo '<pre>';
            // print_r($man);die;
            $this->ci_smarty->assign('man', $man);
            $this->ci_smarty->display('fun/tuanSet.tpl');
        }
    }
}