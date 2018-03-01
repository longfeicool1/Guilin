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
        // echo '<pre>';print_r($this->userinfo);die;
        $condition = ['a.isShow' => 1];
        if (!empty($data['dataLevel'])) {
            $condition['dataLevel'] = $data['dataLevel'];
        }
        if (!empty($data['customLevel'])) {
            $condition['customLevel'] = $data['customLevel'];
        }
        if (!empty($data['t']) && $data['t'] == 1) {
            $condition['meetTime >='] = date('Y-m-d');
            $condition['meetTime <='] = date('Y-m-d',strtotime('+1 day'));
        }
        if (!empty($data['t']) && $data['t'] == 2) {
            $condition['a.give_time >='] = date('Y-m-d');
            $condition['a.give_time <='] = date('Y-m-d',strtotime('+1 day'));
        }
        if (!empty($data['t']) && $data['t'] == 3) {
            $condition['callType >'] = 1;
        }
        if (!empty($data['bt'])) {
            $condition['meetTime >='] = $data['bt'];
        }
        if (!empty($data['et'])) {
            $condition['meetTime <='] = $data['et'] . ' 23:59:59';
        }
        if (!empty($data['spt'])) {
            $condition['a.created >='] = $data['spt'];
        }
        if (!empty($data['ept'])) {
            $condition['a.created <='] = $data['ept'] . ' 23:59:59';
        }
        if (!empty($data['content'])) {
            $condition['CONCAT(a.name,a.mobile,a.city,a.source) like'] = "%{$data['content']}%";
        }
        $page  = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
        $size  = !empty($data['pageSize']) ? $data['pageSize'] : 30;
        $list  = $this->MemberModel->getMemberList($page,$size,$condition);
        $count = $this->MemberModel->getMemberCount($condition);
        $users = $this->MemberModel->getUser();
        // print_r($list);die;
        $this->ci_smarty->assign('users',$users);
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
        if ($data['callType'] == 1) {
            $this->CommonModel->ajaxReturn(300,'请选择通话记录的状态！','',false);
        }
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
        if (!empty($data['bt'])) {
            $condition['a.update >='] = $data['bt'];
        }
        if (!empty($data['et'])) {
            $condition['a.update <='] = $data['et'] . ' 23:59:59';
        }
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
        $ids    = $this->input->post_get('delids');
        $result = $this->MemberModel->toBackData($ids);
        if ($result['errcode'] == 200) {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'rubbish',false);
        } else {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'',false);
        }
    }

    public function dataUpload()
    {
        $files = scandir('./static/upload_his');
        unset($files[0],$files[1]);
        $list = [];
        foreach ($files as $k => $v) {
            $filesize = filesize('./static/upload_his/'.$v);
            $list[] = [
                'xuhao'    => $k -1,
                'filename' => $v,
                'size'     => round($filesize/1024) .'K',
                'url'      => '/static/upload_his/'.$v,
            ];
        }
        $this->ci_smarty->assign('list',$list);
        // echo '<pre>';print_r($list);die;
        $this->ci_smarty->display('member/dataUpload.tpl');
    }

    public function delFile()
    {
        $pathfile = './static/upload_his/'.$this->input->get('filename');
        if (@unlink($pathfile)) {
            $this->CommonModel->ajaxReturn(200,'删除成功','dataUpload',false);
        }
        $this->CommonModel->ajaxReturn(300,'删除失败','',false);
    }

    public function startUpload()
    {
        $pathfile = $this->CommonModel->upload(true,date('Ymd_His'));
        if (!empty($tmpfile['errcode'])) {
            $this->CommonModel->ajaxReturn($tmpfile['errcode'],$tmpfile['errmsg']);
        }
        $result = $this->CommonModel->readExecl($pathfile);
        if (empty($result)) {
            $this->CommonModel->ajaxReturn(300,'获取到数据0条');
        }
        // D($result);
        $payType = array_flip($this->MemberModel->getValue('payType'));
        $insert  = [];
        $mobile  = [];
        foreach ($result as $v) {
            if (!empty($v[1]) && in_array($v[1],$mobile)) {
                continue;
            }
            $mobile[] = $v[1];
            $insert[] = [
                'name'           => $v[0],
                'mobile'         => $v[1],
                'city'           => $v[2],
                'sex'            => $v[3] == '男' ? 1 : 2,
                'age'            => $v[4],
                'daiMoney'       => $v[5],
                'haveCredit'     => $v[6] == '有' ? 2 : 1,
                'hourseDai'      => $v[7] == '有' ? 2 : 1,
                'carDai'         => $v[8] == '有' ? 2 : 1,
                'occapation'     => $v[9],
                'payType'        => !empty($payType[$v[10]]) ? $payType[$v[10]] : 1,
                'income'         => $v[11],
                'reservedFunds'  => $v[12] == '有' ? 2 : 1,
                'socialSecurity' => $v[13] == '有' ? 2 : 1,
                'daiTime'        => $v[14],
                'dataLevel'      => $v[15],
                'source'         => $v[16],
                // 'firstOwer'      => '',
            ];
        }
        //
        // $result      = $this->db->select('mobile,firstOwer')->where_in('mobile',$mobile)->group_by('mobile')->get_where('md_custom_list',['isShow' => 1])->result_array();
        // $existMobile = [];
        // foreach ($result as $v) {
        //     $existMobile[$v['mobile']] = $v['firstOwer'];
        // }
        // D($insert);
        $this->db->insert_batch('md_custom_list',$insert);
        // $this->db->where_in('mobile',$mobile)->update('md_custom_list',['isRepeat' => 2]);
        // print_r($insert);die;
        $this->CommonModel->ajaxReturn(200,'上传成功'.count($insert).'条','dataUpload',false);
    }

    public function createCheckOrder()
    {
        $id = $this->input->get('id');
        if ($id) {
            $info = $this->MemberModel->getMemberInfo($id);
            $this->ci_smarty->assign('data', $info);
        }
        $this->ci_smarty->assign('id', $id);
        $this->ci_smarty->display('member/createCheckOrder.tpl');
    }

    public function toCreateOrder()
    {
        $data   = $this->input->post();
        $result = $this->MemberModel->toCreateOrder($data);
        if ($result['errcode'] == 200) {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'');
        } else {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'',false);
        }
    }

    public function orderList()
    {
        $data = $this->input->post();
        if ($data) {
            $this->session->set_userdata('orderList', $data);
        } else {
            $data = $this->session->userdata('orderList');
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
        $page  = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
        $size  = !empty($data['pageSize']) ? $data['pageSize'] : 30;
        $list  = $this->MemberModel->getOrderList($page,$size,$condition);
        $count = $this->MemberModel->getOrderCount($condition);
        $users = $this->MemberModel->getUser();
        $this->ci_smarty->assign('users',$users);
        // D($list);die;
        $this->ci_smarty->assign('list',$list);
        $this->ci_smarty->assign('count',$count);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->display('member/orderList.tpl');
    }

    public function orderInfo()
    {
        $id   = $this->input->get('id');
        $info = $this->MemberModel->getOrderInfo($id);
        // D($this->userinfo);
        $this->ci_smarty->assign('data', $info);
        $this->ci_smarty->display('member/orderInfo.tpl');
    }

    public function editOrder()
    {
        $id   = $this->input->get('id');
        $info = $this->MemberModel->getOrderInfo($id);
        // D($this->userinfo);
        $this->ci_smarty->assign('data', $info);
        $this->ci_smarty->display('member/editOrder.tpl');
    }

    public function checkOrder()
    {
        $id     = $this->input->get('id');
        $status = $this->input->post('status');
        $result = $this->MemberModel->updateOrder($id,$status);
        if ($result['errcode'] == 200) {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'orderList');
        } else {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'',false);
        }
    }

}