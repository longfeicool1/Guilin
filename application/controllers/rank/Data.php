<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('rank/DataModel');
        $this->load->model('member/MemberModel');
        $this->load->model('CommonModel');
    }

    /**
     * [achievementPersonal 业务员个人业绩]
     * @return [type] [description]
     */
    public function achievementPersonal()
    {
        $data = $this->input->post();
        if ($data) {
            $this->session->set_userdata('achievementPersonal', $data);
        } else {
            $data = $this->session->userdata('achievementPersonal');
        }
        // echo '<pre>';print_r($this->userinfo);die;
        $condition = [];
        if (!empty($data['status'])) {
            $condition['a.status'] = $data['status'];
        }
        if (!empty($data['bt'])) {
            $condition['a.created >='] = $data['bt'];
        }
        if (!empty($data['et'])) {
            $condition['a.created <='] = $data['et'] . ' 23:59:59';
        }
        if (!empty($data['content'])) {
            $condition['CONCAT(a.username,a.mobile) like'] = "%{$data['content']}%";
        }
        $page     = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
        $size     = !empty($data['pageSize']) ? $data['pageSize'] : 30;
        $list     = $this->DataModel->getSimpleData($page,$size,$condition);
        $count    = $this->DataModel->getSimpleDataCount($condition);
        $info     = $this->DataModel->getPersonalData();
        // $users = $this->MemberModel->getUser();
        $this->ci_smarty->assign('info',$info);
        $this->ci_smarty->assign('list',$list);
        $this->ci_smarty->assign('count',$count);
        $this->ci_smarty->assign('position',$this->userinfo['position']);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->display('rank/achievementPersonal.tpl');
    }

    /**
     * [achievementPersonal 团队业绩]
     * @return [type] [description]
     */
    public function achievementTeam()
    {
        $data = $this->input->post();
        if ($data) {
            $this->session->set_userdata('orderList', $data);
        } else {
            $data = $this->session->userdata('orderList');
        }
        $condition = [];
        $list     = $this->DataModel->getTeamData($condition);
        $info     = $this->DataModel->getPersonalData();
        // $users = $this->MemberModel->getUser();
        $this->ci_smarty->assign('info',$info);
        $this->ci_smarty->assign('list',$list);
        $this->ci_smarty->assign('position',$this->userinfo['position']);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->display('rank/achievementTeam.tpl');
    }

    public function achievementArea()
    {
        $data = $this->input->post();
        if ($data) {
            $this->session->set_userdata('orderList', $data);
        } else {
            $data = $this->session->userdata('orderList');
        }
        $condition = [];
        $list     = $this->DataModel->getAreaData($condition);
        $info     = $this->DataModel->getPersonalData();
        // $users = $this->MemberModel->getUser();
        $this->ci_smarty->assign('info',$info);
        $this->ci_smarty->assign('list',$list);
        $this->ci_smarty->assign('position',$this->userinfo['position']);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->display('rank/achievementArea.tpl');
    }

    public function achievementCity()
    {
        $data = $this->input->post();
        if ($data) {
            $this->session->set_userdata('orderList', $data);
        } else {
            $data = $this->session->userdata('orderList');
        }
        $condition = [];
        $list     = $this->DataModel->getCityData($condition);
        $info     = $this->DataModel->getPersonalData();
        // $users = $this->MemberModel->getUser();
        $this->ci_smarty->assign('info',$info);
        $this->ci_smarty->assign('list',$list);
        $this->ci_smarty->assign('position',$this->userinfo['position']);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->display('rank/achievementCity.tpl');
    }

    public function achievementTotal()
    {
        $data = $this->input->post();
        if ($data) {
            $this->session->set_userdata('orderList', $data);
        } else {
            $data = $this->session->userdata('orderList');
        }
        $condition = [];
        $list     = $this->DataModel->getTotalData($condition);
        $info     = $this->DataModel->getPersonalData();
        // $users = $this->MemberModel->getUser();
        $this->ci_smarty->assign('info',$info);
        $this->ci_smarty->assign('list',$list);
        $this->ci_smarty->assign('position',$this->userinfo['position']);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->display('rank/achievementTotal.tpl');
    }

    public function callTotal()
    {
        $data = $this->input->post();
        if ($data) {
            $this->session->set_userdata('dataTotal', $data);
        } else {
            $data = $this->session->userdata('dataTotal');
        }
        // echo '<pre>';print_r($this->userinfo);die;
        $condition = [];
        if (!empty($data['uid'])) {
            $condition['secOwer'] = $data['uid'];
            $condition2['uid']    = $data['uid'];
        }
        $collectDate = date('Y-m-d');
        if (!empty($data['collectDate'])) {
            $condition['give_time >= '] = $data['collectDate'];
            $condition['give_time <= '] = $data['collectDate']. ' 23:59:59';
            $condition2['created >= '] = $data['collectDate'];
            $condition2['created <= '] = $data['collectDate']. ' 23:59:59';
            $collectDate = $data['collectDate'];
        } else {
            $condition['give_time >= '] = date('Y-m-d');
            $condition['give_time <= '] = date('Y-m-d',strtotime('+1 day'));
            $condition2['created  >= '] = date('Y-m-d');
            $condition2['created  <= '] = date('Y-m-d',strtotime('+1 day'));

        }
        $list    = $this->DataModel->getLiveData($condition,$condition2,$collectDate);
        // $info = $this->DataModel->getPersonalData();
        $users   = $this->MemberModel->getUser();
        $this->ci_smarty->assign('users',$users);
        $this->ci_smarty->assign('list',$list);
        $this->ci_smarty->assign('count',0);
        $this->ci_smarty->assign('position',$this->userinfo['position']);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->display('rank/callTotal.tpl');
    }

    public function downLoadCallData()
    {
        $data = $this->input->get();
        $condition = [];
        if (!empty($data['uid'])) {
            $condition['uid'] = $data['uid'];
        }
        $collectDate = date('Y-m-d');
        if (!empty($data['collectDate'])) {
            $condition['give_time >= '] = $data['collectDate'];
            $condition['give_time <= '] = $data['collectDate']. ' 23:59:59';
            $condition2['created >= '] = $data['collectDate'];
            $condition2['created <= '] = $data['collectDate']. ' 23:59:59';
            $collectDate = $data['collectDate'];
        } else {
            $condition['give_time >= '] = date('Y-m-d');
            $condition['give_time <= '] = date('Y-m-d',strtotime('+1 day'));
            $condition2['created  >= '] = date('Y-m-d');
            $condition2['created  <= '] = date('Y-m-d',strtotime('+1 day'));

        }
        $list = $this->DataModel->getLiveData($condition,$condition2,$collectDate);
        if (empty($list)) {
            echo '未查到符合条件的数据';return;
        }
        $header = array(
            'collectDate'      => '日期',
            'name'             => '姓名',
            'monthAllotCustom' => '总量',
            'dayAllotCustom'   => '当天下发量',
            'dayPhone'         => '当天拨打次数',
        );
        // echo '<pre>';print_r($data);die;
        $filename = date('Y-m-d').'业务员数据统计.xls';
        $this->CommonModel->export($header, $list, $filename);
    }

}