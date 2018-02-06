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
        $condition = ['a.isShow' => 1];
        if (!empty($data['t']) && $data['t'] == 1) {
            $condition['meetTime >='] = date('Y-m-d');
            $condition['meetTime <='] = date('Y-m-d',strtotime('+1 day'));
        }
        if (!empty($data['t']) && $data['t'] == 2) {
            $condition['a.created >='] = date('Y-m-d');
            $condition['a.created <='] = date('Y-m-d',strtotime('+1 day'));
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
        if (!empty($data['content'])) {
            $condition['CONCAT(a.name,a.mobile) like'] = "%{$data['content']}%";
        }
        $page  = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
        $size  = !empty($data['pageSize']) ? $data['pageSize'] : 30;
        $list  = $this->MemberModel->getMemberList($page,$size,$condition);
        $count = $this->MemberModel->getMemberCount($condition);
        // print_r($list);die;
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
        $payType = array_flip($this->MemberModel->getValue('payType'));
        $insert  = [];
        foreach ($result as $v) {
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
            ];
        }
        $this->db->insert_batch('md_custom_list',$insert);
        // print_r($insert);die;
        $this->CommonModel->ajaxReturn(200,'上传成功'.count($insert).'条','dataUpload',false);
    }

}