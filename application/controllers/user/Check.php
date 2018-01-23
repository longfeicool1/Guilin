<?php
class Check extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // 引入model
        $this->load->model('Check_model', 'checkModel');
        $this->load->model('Common_model', 'commonModel');
    }

    public function updateCarcard()
    {
        $bindid = $this->input->get('bindid');
        $this->ci_smarty->assign('data', $this->checkModel->carInfo($bindid));
        $this->ci_smarty->assign('bindid', $bindid);
        $this->display('yg_user/updateCarcard.tpl');
    }

    public function authentication()
    {
        $bindid = $this->input->get('bindid');
        $this->ci_smarty->assign('data', $this->checkModel->carInfo($bindid));
        $this->ci_smarty->assign('bindid', $bindid);
        $this->display('yg_user/authentication.tpl');
    }

    public function toUpdate()
    {
        $bindid     = $this->input->get('bindid');
        $data       = $this->input->post();
        // print_r($data);die;
        $update = [];
        if(!empty($data['carcard'])) {
            $carcard = strtoupper($data['carcard']);
            $zz = "/^[京津沪渝冀豫云辽黑湘皖鲁新苏浙赣鄂桂甘晋蒙陕吉闽贵粤青藏川宁琼使领]{1}[A-Z]{1}[警京津沪渝冀豫云辽黑湘皖鲁新苏浙赣鄂桂甘晋蒙陕吉闽贵粤青藏川宁琼]{0,1}[A-Z0-9]{4,5}[A-Z0-9挂学警港澳]{1}$/u";
            if (!preg_match($zz,$carcard)) {
                $this->commonModel->ajaxReturn(300,'车牌格式不正确');
            }
            $update['carcard'] = $carcard;
        }
        if (!empty($data['insure_code'])) {
            if (!preg_match('/^[0-9]{19}$/', $data['insure_code'])) {
                $this->commonModel->ajaxReturn(300,'保单格式不正确');
            }
            $update['insure_code'] = $data['insure_code'];
        }
        if (empty($update)) {
            $this->commonModel->ajaxReturn(300,'车牌和保单不能同时为空');
        }
        if (!empty($data['is_check']) && $data['is_check'] == 2 && empty($data['insure_code'])) {
            $this->commonModel->ajaxReturn(300,'更改为认证成功状态需填写正确的保单号');
        }
        if (!empty($data['is_check'])) {
            $update['is_check'] = $data['is_check'];
        }
        $result = $this->checkModel->updateCar($bindid,$update);
        if (!empty($result['errcode'])) {
            $this->commonModel->ajaxReturn(300,$result['errmsg']);
        }
        $this->commonModel->ajaxReturn(200,'修改成功','id273');
    }

    public function download()
    {
        $condition = [
            'a.is_check != ' => 2,
        ];
        $list   = $this->checkModel->noAuthList(1,999999,$condition);
        // echo '<pre>';print_r($this->db->last_query());die;
        $header = array(
            'bind_id'       => '车辆标识(勿动)',
            'cardtype'      => '临时牌/正常牌(二选一)',
            'account_login' => '账户',
            'carcard'       => '车牌',
            'src'           => '设备号',
            'insure_code'   => '商业保单号',
        );
        $filename = date('Y-m-d').'-未认证名单.csv';
        $this->commonModel->exportCsv($header, $list, $filename);
    }

    public function upload()
    {
        $filename = $this->commonModel->upload();
        $reuslt   = $this->commonModel->readCsv($filename);
        if (!empty($reuslt)) {
            foreach ($reuslt as $v) {
                if (trim($v['cardtype']) == '临时牌') {
                    $cardtype = 1;
                } else {
                    $cardtype = 2;
                }
                if (trim($v['is_check']) == '认证') {
                    $isCheck = 2;
                } else {
                    $isCheck = 3;
                }
                $update = [
                    'insure_code' => trim($v['insure_code']),
                    'cardtype'    => $cardtype,
                    'carcard'     => trim(strtoupper($v['carcard'])),
                    'is_check'    => $isCheck,
                ];
                // print_r($update);die;
                $this->checkModel->updateCar($v['bind_id'],$update);
            }
            $this->commonModel->ajaxReturn(200,'更新成功','id273');
        }
        $this->commonModel->ajaxReturn(300,'修改失败');
    }


    public function mulitCheck()
    {
        $this->display('yg_user/mulitCheck.tpl');
    }

    public function ygupload()
    {
        $filename = $this->commonModel->upload();
        $reuslt   = $this->commonModel->readExecl($filename);
        unset($reuslt[0]);
        // $this->commonModel->output($reuslt);
        $list     = [];
        foreach ($reuslt as $v) {
            $date = array_pop($v);
            $list[] = [
                'insure_end'  => !empty($date) ? date('Y-m-d',strtotime($date)) : '-',
                'insure_code' => array_pop($v),
                'src'         => array_pop($v),
                'carcard'     => array_pop($v),
            ];
        }
        // print_r($list);die;
        $this->commonModel->output($list);
        // $this->commonModel->ajaxReturn(300,'修改失败');
    }

    public function getNoAuth()
    {
        $condition = [
            'a.is_check != ' => 2,
        ];
        $list   = $this->checkModel->noAuthList(1,999999,$condition);
        // print_r($list);die;
        $this->commonModel->output($list);
    }

    public function meregWatch()
    {
        // print_r($this->input->post());die;
        $data  = $this->input->post();
        $soure = json_decode($data['soure'],true);
        $check = json_decode($data['check'],true);
        // echo '<pre>';print_r($soure);die;
        $rs    = [];
        $list  = [];
        foreach ($check as $v) {
            $src = preg_replace("/\D/",'',$v['src']);
            $rs[$src] = $v;
        }
        // echo '<pre>';print_r($rs);die;
        $i = 0;
        foreach ($soure as $v) {
            if (!empty($rs[$v['src']])) {
                $i++;
                $list[] = [
                    'xuhao'            => $i,
                    'bind_id'          => $v['bind_id'],
                    'cardtype'         => $v['cardtype2'],
                    'cardtypeName'     => $v['cardtype'],
                    // 'cardtypeName'  => $v['cardtype'] == '临时牌' ? '临时牌-><span class="changeRed">正常牌</span>' : '正常牌',
                    'account_login'    => $v['account_login'],
                    'carcard'          => $v['carcard'] == $rs[$v['src']]['carcard']? $v['carcard'] : $rs[$v['src']]['carcard'],
                    'carcardName'      => $v['carcard'] == $rs[$v['src']]['carcard']? $v['carcard'] : $v['carcard'] . '->' . "<span class=\"changeRed\">{$rs[$v['src']]['carcard']}</span>",
                    'src'              => $v['src'],
                    'insure_code_name' => "<span class=\"changeRed\">{$rs[$v['src']]['insure_code']}</span>",
                    'insure_code'      => $rs[$v['src']]['insure_code'],
                    'insure_end'       => $rs[$v['src']]['insure_end'],
                    'insure_end_name'  => "<span class=\"changeRed\">{$rs[$v['src']]['insure_end']}</span>",
                ];
            }
        }
        // echo '<pre>';print_r($list);die;
        $this->ci_smarty->assign('list', $list);
        $this->display('yg_user/meregWatch.tpl');
    }

    public function tomeregWatch()
    {
        $reuslt  = $this->input->post('list');
        // echo '<pre>';print_r($reuslt);
        if (!empty($reuslt)) {
            foreach ($reuslt as $v) {
                $update = [
                    'insure_code' => trim($v['insure_code']),
                    'cardtype'    => $v['cardtype'],
                    'carcard'     => trim(strtoupper($v['carcard'])),
                    'is_check'    => 2,
                ];
                if ($v['insure_end'] != '-') {
                    $update['insure_end'] = trim($v['insure_end']);
                }
                // print_r($update);die;
                $this->checkModel->updateCar($v['bind_id'],$update);
            }
            $this->commonModel->ajaxReturn(200,'更新成功','id273');
        }
        $this->commonModel->ajaxReturn(300,'修改失败');
    }

    public function updateLogin()
    {
        $pid = $this->input->get('pid');
        $act = $this->input->get('act');
        if ($act == 'edit') {
            $rs = $this->checkModel->updateUserInfo($pid,$this->input->post());
            if (!empty($rs['errcode'])) {
                $this->commonModel->ajaxReturn(300,$rs['errmsg']);
            } else {
                $this->commonModel->ajaxReturn(200,'更新成功','id273');
            }
            return;
        }
        $this->ci_smarty->assign('data', $this->checkModel->userInfo($pid));
        $this->ci_smarty->assign('pid', $pid);
        $this->display('yg_user/updateLogin.tpl');
    }
}