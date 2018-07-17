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
        $whereOr   = [];
        if (!empty($data['dataLevel'])) {
            $condition['dataLevel'] = $data['dataLevel'];
        }
        if (!empty($data['tag'])) {
            $data['firstOwer']      = $data['tag'];
        }
        if (!empty($data['firstOwer'])) {
            $condition['firstOwer'] = $data['firstOwer'];
        }
        if (!empty($data['customStatus'])) {
            $condition['customStatus'] = $data['customStatus'];
        }
        // D($data);
        if (!empty($data['source'])) {
            $condition['source'] = $data['source'];
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
            $whereOr['callType'] = 1;
            $whereOr['isAllot'] = 2;
            $condition['firstOwer != '] = 57;
            $condition['secOwer != '] = 57;
            $condition['firstOwer!='] = 109;
            $condition['secOwer!='] = 109;
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
            $condition['a.created <='] =  preg_replace("/[^\d-]/",'',$data['ept']) . ' 23:59:59';
        }
        if (!empty($data['sct'])) {
            $condition['a.updated >='] = $data['sct'];
        }
        if (!empty($data['ect'])) {
            $condition['a.updated <='] = $data['ect'] . ' 23:59:59';
        }
        if (!empty($data['content'])) {
            $condition['CONCAT(a.name,a.mobile,a.city,a.source) like'] = "%{$data['content']}%";
        }
        $page  = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
        $size  = !empty($data['pageSize']) ? $data['pageSize'] : 30;
        $list  = $this->MemberModel->getMemberList($page,$size,$condition,$whereOr);
        // print_r($this->db->last_query());die;
        $count = $this->MemberModel->getMemberCount($condition,$whereOr);
        $users = $this->MemberModel->getUser();
        $new   = $this->MemberModel->getMemberCount([
            'a.isShow'        => 1,
            'customLevel'     => 1,
            'a.give_time >='  => date('Y-m-d'),
            'a.give_time <= ' => date('Y-m-d',strtotime('+1 day')),],'');
        //$condition['firstOwer != '] = 57;
            $condition['secOwer != '] = 57;
            $condition['firstOwer!='] = 109;
            $condition['secOwer!='] = 109;
        $old   = $this->MemberModel->getMemberCount([
            'a.isShow'      => 1,
            'firstOwer != ' => 57,
            'secOwer != '   => 57,
            'firstOwer!='   => 109,
            'secOwer!='     => 109,
            ],['callType'   => 1, 'isAllot' => 2]);
        // print_r($list);die;
        $this->ci_smarty->assign('new',$new);
        $this->ci_smarty->assign('old',$old);
        $this->ci_smarty->assign('source',$this->MemberModel->getSource());
        $this->ci_smarty->assign('customStatus',$this->MemberModel->customStatus);
        $this->ci_smarty->assign('users',$users);
        $this->ci_smarty->assign('list',$list);
        $this->ci_smarty->assign('count',$count);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->display('member/memberList.tpl');
    }

    public function searchUser()
    {
        $word    = $this->input->get('term');
        $users = $this->MemberModel->autoGetUser(['name like' => "%{$word}%"]);
        $this->CommonModel->output($users);
    }

    public function searchMember()
    {
        $data = $this->input->post();
        if ($data) {
            $this->session->set_userdata('searchMember', $data);
        } else {
            $data = $this->session->userdata('searchMember');
        }
        // echo '<pre>';print_r($this->userinfo);die;
        $condition = [];
        if (!empty($data['content'])) {
            $condition['CONCAT(a.name,a.mobile) like'] = "%{$data['content']}%";
            $page  = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
            $size  = !empty($data['pageSize']) ? $data['pageSize'] : 30;
            $list  = $this->MemberModel->getSearchList($page,$size,$condition);
            $count = $this->MemberModel->getSearchCount($condition);
        } else {
            $count = 0;$list = [];
        }
        // print_r($list);die;
        $this->ci_smarty->assign('list',$list);
        $this->ci_smarty->assign('count',$count);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->display('member/searchMember.tpl');
    }

    public function memberInfo()
    {
        $id      = $this->input->get('id');
        $watch   = $this->input->get('watch');
        $data    = $this->MemberModel->getMemberInfo($id);
        $comment = $this->MemberModel->getCommentList($id);
        // $customStatus = $data['customStatus'] == 1 ? [] : $this->getCustomStatus($data['customLevel'],['customStatus' =>$data['customStatus']]);
        $customStatus = $data['customStatus'] == 1 ? [] : $this->getCustomStatus($data['customLevel']);
        // echo '<pre>';print_r($customStatus);die;
        $this->ci_smarty->assign('watch', $watch);
        $this->ci_smarty->assign('data', $data);
        $this->ci_smarty->assign('comment', $comment);
        $this->ci_smarty->assign('payType', $this->MemberModel->getValue('payType'));
        $this->ci_smarty->assign('customLevel', $this->MemberModel->getValue('customLevel'));
        $this->ci_smarty->assign('customStatus', $customStatus);
        $this->ci_smarty->assign('callType', $this->MemberModel->getValue('callType'));
        $this->ci_smarty->display('member/memberInfo.tpl');
    }

    public function updateInfo()
    {
        $id     = $this->input->get('id');
        $data   = $this->input->post();
        if ($data['callType'] == 1) {
            $this->CommonModel->ajaxReturn(300,'请选择通话记录！','',false);
        }
        if (empty($data['customStatus']) || $data['customStatus'] == 1) {
            $this->CommonModel->ajaxReturn(300,'请选择名单状态！','',false);
        }
        if ($data['customLevel'] == 1) {
            $this->CommonModel->ajaxReturn(300,'请选择名单等级！','',false);
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
        ini_set('memory_limit', '256M');
        $data      = $this->input->get();
        $condition = ['a.isShow' => 1];
        $whereIn   = [];
        if (!empty($data['dataLevel'])) {
            $condition['dataLevel'] = $data['dataLevel'];
        }
        if (!empty($data['firstOwer'])) {
            $condition['firstOwer'] = $data['firstOwer'];
        }
        if (!empty($data['source'])) {
            $condition['source'] = $data['source'];
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
        if (!empty($data['sct'])) {
            $condition['a.updated >='] = $data['sct'];
        }
        if (!empty($data['ect'])) {
            $condition['a.updated <='] = $data['ect'] . ' 23:59:59';
        }
        if (!empty($data['ids'])) {
            $whereIn = ['a.id' => explode(',',trim($data['ids'],','))];
        }
        $list = $this->MemberModel->getMemberList(1,5000,$condition,$whereIn);
        // D($listt);
        if (empty($list)) {
            echo '未查到符合条件的数据';return;
        }
        // foreach ($list as $k => $v) {
        //     $list[$k]['lastComment'] = preg_replace("/[\s]/",'',$v['lastComment']);
        // }
        $header = array(
            'created'        => '导入时间',
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
            'daiMoney'       => '贷款额度',
            'firstName'      => '业务员',
            'meetTime'       => '预约时间',
            'customStatus'   => '用户状态',
            'dataLevel'      => '数据类型',
            'customLevel'    => '名单星级',
            'callTypeName'   => '通话记录',
            'source'         => '来源',
            'lastComment'    => '最后备注内容',
        );
        // echo '<pre>';print_r($data);die;
        $filename = date('Y-m-d').'客户下载列表.xls';
        $this->CommonModel->export($header, $list, $filename);
    }

    public function rubbish()
    {
        $data = $this->input->post();
        if ($data) {
            $this->session->set_userdata('rubbishList', $data);
        } else {
            $data = $this->session->userdata('rubbishList');
        }
        // echo '<pre>';print_r($this->userinfo);die;
        $condition = ['a.isShow' => 2];
        $whereOr   = [];
        if (!empty($data['dataLevel'])) {
            $condition['dataLevel'] = $data['dataLevel'];
        }
        if (!empty($data['firstOwer'])) {
            $condition['firstOwer'] = $data['firstOwer'];
        }
        if (!empty($data['source'])) {
            $condition['source'] = $data['source'];
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
            $whereOr['callType'] = 1;
            // $condition['meetTime'] = '0000-00-00 00:00:00';
            $whereOr['isAllot'] = 2;
            // $condition['id >'] = '0 AND (callType = 1 OR isAllot = 2)';
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
        if (!empty($data['sct'])) {
            $condition['a.updated >='] = $data['sct'];
        }
        if (!empty($data['ect'])) {
            $condition['a.updated <='] = $data['ect'] . ' 23:59:59';
        }
        if (!empty($data['content'])) {
            $condition['CONCAT(a.name,a.mobile,a.city,a.source) like'] = "%{$data['content']}%";
        }
        $page  = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
        $size  = !empty($data['pageSize']) ? $data['pageSize'] : 30;
        $list  = $this->MemberModel->getMemberList($page,$size,$condition,$whereOr);
        $count = $this->MemberModel->getMemberCount($condition,$whereOr);
        $users = $this->MemberModel->getUser();
        // print_r($list);die;
        $this->ci_smarty->assign('source',$this->MemberModel->getSource());
        $this->ci_smarty->assign('users',$users);
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
                'city'           => !empty($v[2]) ? $v[2] : '无',
                'sex'            => !empty($v[3]) && $v[3] == '男' ? 1 : 2,
                'age'            => !empty($v[4]) ? $v[4] : 0,
                'daiMoney'       => !empty($v[5]) ? $v[5] : '',
                'haveCredit'     => !empty($v[6]) && $v[6] == '有' ? 2 : 1,
                'hourseDai'      => !empty($v[7]) && $v[7] == '有' ? 2 : 1,
                'carDai'         => !empty($v[8]) && $v[8] == '有' ? 2 : 1,
                'occapation'     => !empty($v[9]) ? $v[9] : '',
                'payType'        => !empty($payType[$v[10]]) ? $payType[$v[10]] : 1,
                'income'         => !empty($v[11]) ? $v[11] :'',
                'reservedFunds'  => !empty($v[12]) && $v[12] == '有' ? 2 : 1,
                'socialSecurity' => !empty($v[13]) && $v[13] == '有' ? 2 : 1,
                'daiTime'        => !empty($v[14]) ? $v[14] : '',
                'dataLevel'      => !empty($v[15]) ? $v[15] : 'C',
                'source'         => !empty($v[16]) ? $v[16] : '',
                'firstOwer'      => '',
            ];
        }
        //
        $result      = $this->db->select('mobile,firstOwer')->where_in('mobile',$mobile)->get_where('md_custom_list',['isShow' => 1,'firstOwer >' => 0])->result_array();
        $existMobile = [];
        foreach ($result as $v) {
            $existMobile[$v['mobile']] = $v['firstOwer'];
        }
        foreach ($insert as $k => $v) {
            if (!empty($existMobile[$v['mobile']])) {
                $insert[$k]['firstOwer'] = $existMobile[$v['mobile']];
            }
        }
        // D($insert);
        $this->db->insert_batch('md_custom_list',$insert);
        $sql = "update `md_custom_list` b
            join (select count(*) as tot,mobile,secOwer from `md_custom_list` where isShow = 1 group by mobile) as a
            on a.mobile = b.mobile
            set isRepeat = 2,b.secOwer = a.secOwer WHERE a.tot > 1";
        $this->db->query($sql);
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

    public function customInfo()
    {
        $mobile = $this->input->get('mobile');
        $data   = $this->MemberModel->getMemberInfoByMobile(trim($mobile));
        $this->CommonModel->output($data);
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
        // D($data);
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
        if (!empty($data['sendSart'])) {
            $condition['a.sendTime >='] = $data['sendSart'];
        }
        if (!empty($data['sendEnd'])) {
            $condition['a.sendTime <='] = $data['sendEnd'] . ' 23:59:59';
        }
        if (!empty($data['tag'])) {
            $data['uid']      = $data['tag'];
        }
        if (!empty($data['uid'])) {
            $condition['a.uid'] = $data['uid'];
        }
        if (!empty($data['content'])) {
            $condition['CONCAT(a.username,a.mobile,b.city) like'] = "%{$data['content']}%";
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
        $users = $this->MemberModel->getUser();
        $this->ci_smarty->assign('users',$users);
        $this->ci_smarty->assign('data', $info);
        $this->ci_smarty->display('member/editOrder.tpl');
    }

    public function checkOrder()
    {
        $id     = $this->input->get('id');
        $data   = $this->input->post();
        $result = $this->MemberModel->updateOrder($id,$data);

        if ($result['errcode'] == 200) {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'orderList');
        } else {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'',false);
        }
    }

    public function delOrder()
    {
        $ids    = $this->input->post_get('delids');
        $result = $this->MemberModel->toDelOrder($ids);
        if ($result['errcode'] == 200) {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'orderList',false);
        } else {
            $this->CommonModel->ajaxReturn($result['errcode'],$result['errmsg'],'',false);
        }
    }

    public function checkOrderListExport()
    {
        $data = $this->input->get();
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
        if (!empty($data['sendSart'])) {
            $condition['a.sendTime >='] = $data['sendSart'];
        }
        if (!empty($data['sendEnd'])) {
            $condition['a.sendTime <='] = $data['sendEnd'] . ' 23:59:59';
        }
        if (!empty($data['uid'])) {
            $condition['a.uid'] = $data['uid'] . ' 23:59:59';
        }
        if (!empty($data['content'])) {
            $condition['CONCAT(a.username,a.mobile,b.city) like'] = "%{$data['content']}%";
        }
        $list  = $this->MemberModel->getOrderList(1,5000,$condition);
        // echo '<pre>';print_r($list);die;
        if (empty($list)) {
            echo '未查到符合条件的数据';return;
        }
        $header = array(
            'username'    => '姓名',
            'mobile'      => '手机',
            'city'        => '城市',
            'source'      => '来源',
            'channel'     => '进件渠道',
            'product'     => '贷款产品',
            'money'       => '贷款额度',
            'rate'        => '费率',
            'deposit'     => '定金',
            'firstName'   => '业务员',
            'secondUid'   => '后勤对接员',
            'isBackMoney' => '退定金?',
            'sendMoney'   => '批款额度',
            'income'      => '创收',
            'sendTime'    => '收款时间',
            'orderStatus' => '审核状态',
            'created'     => '创建时间',
            'team'        => '团长',
            'area'        => '区长',
        );
        // echo '<pre>';print_r($data);die;
        $filename = date('Y-m-d').'审件下载列表.xls';
        $this->CommonModel->export($header, $list, $filename);
    }

    public function changeCustomStatus()
    {
        $customLevel = $this->input->get('customLevel');
        $this->CommonModel->output($this->getCustomStatus($customLevel));
    }

    protected function getCustomStatus($customLevel,array $extendid = [])
    {
        $ids         = [];
        switch ($customLevel) {
            case 1:
                $ids = [1];
                break;
            case 2:
                $ids = [2];
                break;
            case 3:
                $ids = [4];
                break;
            case 4:
            case 5:
            case 6:
                $ids = [6,3,5,7,8,9];
                break;
            default:
                $ids = [];
                break;
        }
        $ids = array_merge($ids,$extendid);
        $customStatus = $this->MemberModel->getValue('customStatus');
        $result       = [];
        foreach ($ids as $v) {
            if (!empty($customStatus[$v])) {
                $result[] = [
                    'value' => $v,
                    'label' => $customStatus[$v],
                ];
            }
        }
        // foreach ($customStatus as $k => $v) {
        //     if (in_array($k,$ids)) {
        //         $result[] = [
        //             'value' => $k,
        //             'label' => $v,
        //         ];
        //     }
        // }
        return $result;
    }

}
