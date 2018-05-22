<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Outside extends CI_Controller
{
    protected $data;
    protected $dataLevel;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/OutsideModel');
        $this->load->model('CommonModel');
        $post = file_get_contents('php://input');
        if (empty($post)) {
            $this->CommonModel->output(['errcode' => 10005,'errmsg' => '数据格式错误1']);
        }
        // log_message('Debug',print_r($post,true));
        $this->data   = json_decode($post,true);
        if (!is_array($this->data)) {
            $this->CommonModel->output(['errcode' => 10006,'errmsg' => '数据格式错误2']);
        }
        if (empty($this->data['appid'])) {
            $this->CommonModel->output(['errcode' => 10008,'errmsg' => 'appid不能为空']);
        }
        $cominfo = $this->OutsideModel->checkAppid($this->data['appid']);
        if (empty($cominfo)) {
            $this->CommonModel->output(['errcode' => 10007,'errmsg' => '不存在的appid']);
        }
        if ($cominfo['status'] == 3) {
            $this->CommonModel->output(['errcode' => 10011,'errmsg' => '该APPID已失效']);
        }
        // print_r($cominfo['appSecrect']);die;
        $sign = $this->CommonModel->sign($cominfo['appSecrect'], $this->data);
        // print_r($sign);die;
        // if ($this->data['sign'] != $sign) {
        //     $this->CommonModel->output(['errcode' => 10000,'errmsg' => '签名错误']);
        // }
        $this->dataLevel = $cominfo['dataLevel'];
    }

    public function customInfo()
    {
        $data   = $this->data;
        if(empty($data['mobile'])) {
            $this->CommonModel->output(['errcode' => 10001,'errmsg' => '手机号码不能空']);
        }
        if(empty($data['name'])){
            $this->CommonModel->output(['errcode' => 10003,'errmsg' => '姓名不能为空']);
        }
        $rs = $this->db->get_where('md_custom_list',['mobile' => $data['mobile'],'created >' => date('Y-m-d',strtotime('-180 days'))])->row_array();
        if (!empty($rs)) {
            $this->CommonModel->output(['errcode' => 10004,'errmsg' => '该数据已存在']);
        }
        // print_r($rs);die;
        $insert = [
            'mobile'    => $data['mobile'],
            'name'      => $data['name'],
        ];
        if(!empty($data['dataLevel'])){
            $insert['dataLevel'] = $data['dataLevel'];
        } else {
            $insert['dataLevel'] = !empty($this->dataLevel) ? $this->dataLevel : 'A';
        }
        if(!empty($data['city'])){
            $insert['city'] = $data['city'];
        }
        if(!empty($data['age'])){
            $insert['age'] = $data['age'];
        }
        if(!empty($data['sex'])){
            $insert['sex'] = $data['sex'];
        }
        if(!empty($data['daiMoney'])){
            $insert['daiMoney'] = $data['daiMoney'];
        }
        if(!empty($data['haveCredit'])){
            $insert['haveCredit'] = $data['haveCredit'];
        }
        if(!empty($data['insureCode'])){
            $insert['insureCode'] = $data['insureCode'];
        }
        if(!empty($data['hourseDai'])){
            $insert['haveHouse'] = $data['hourseDai'];
        }
        if(!empty($data['carDai'])){
            $insert['haveCar'] = $data['carDai'];
        }
        if(!empty($data['occapation'])){
            $insert['occapation'] = $data['occapation'];
        }
        if(!empty($data['payType'])){
            $insert['payType'] = $data['payType'];
        }
        if(!empty($data['income'])){
            $insert['income'] = $data['income'];
        }
        if(!empty($data['reservedFunds'])){
            $insert['reservedFunds'] = $data['reservedFunds'];
        }
        if(!empty($data['socialSecurity'])){
            $insert['socialSecurity'] = $data['socialSecurity'];
        }
        if(!empty($data['daiTime'])){
            $insert['daiTime'] = $data['daiTime'];
        }
        if(!empty($data['appid'])){
            $insert['source'] = $data['appid'];
        } else {
            $insert['source'] = 'JLX';
        }
        // D($insert);
        $result = $this->OutsideModel->insertCustom($insert);
        if (!empty($result['errcode'])) {
            $this->CommonModel->output($result);
        }
        $this->CommonModel->output(['errcode' => 0,'errmsg' => '操作成功']);
    }
}