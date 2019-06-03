<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Collect extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/OutsideModel');
        $this->load->model('CommonModel');
        // file_put_contents('./error.log', $post,FILE_APPEND);
    }

    public function info()
    {
        $channel = $this->input->get('channel');
        $this->ci_smarty->assign('channel',$channel);
        $this->ci_smarty->display('api/collectInfo.tpl');
    }

    public function tosave()
    {
        $data  = $this->input->post();
        $appid = $data['channel'];
        // D($data);
        if (empty($appid)) {
            $this->CommonModel->output(['errcode' => 10008,'errmsg' => 'appid不能为空']);
        }
        $cominfo = $this->OutsideModel->checkAppid($appid);
        if (empty($cominfo)) {
            $this->CommonModel->output(['errcode' => 10007,'errmsg' => '不存在的appid']);
        }
        if ($cominfo['status'] == 3) {
            $this->CommonModel->output(['errcode' => 10011,'errmsg' => '该APPID已失效']);
        }
        if(empty($data['phone'])) {
            $this->CommonModel->output(['errcode' => 10001,'errmsg' => '手机号码不能空']);
        }
        if(empty($data['name'])){
            $this->CommonModel->output(['errcode' => 10003,'errmsg' => '姓名不能为空']);
        }
        $base = 'md_custom_list';
        $rs = $this->db->get_where($base,['mobile' => $data['phone'],'created >' => date('Y-m-d',strtotime('-1800 days'))])->row_array();
        if (!empty($rs)) {
            $this->CommonModel->output(['errcode' => 10004,'errmsg' => '请不要重复提交此数据']);
        }
        // print_r($rs);die;
        $insert = [
            'mobile'    => $data['phone'],
            'name'      => $data['name'],
            'source'    => $appid,
            'from_type' => 'wap',
            'dataLevel' => !empty($cominfo['dataLevel']) ? $cominfo['dataLevel']:'WAP',
        ];
        if(!empty($data['city'])){
            $insert['city'] = $data['city'];
        }
        if(!empty($data['birthday'])){
            $insert['age'] = date('Y')-date('Y',strtotime($data['birthday']));
        }
        if(!empty($data['sex'])){
            $insert['sex'] = $data['sex'];
        }
        if(!empty($data['amount'])){
            $insert['daiMoney'] = $data['amount'].'万';
        }
        if(!empty($data['career'])){
            $insert['occapation'] = $data['career'];
        }
        if(!empty($data['is_atom']) && $data['is_atom'] == 1){
            $insert['weiMoney'] = '有用过';
        }
        if(!empty($data['is_atom']) && $data['is_atom'] == 2){
            $insert['weiMoney'] = '没用过';
        }
        if(!empty($data['credit_card'])){
            $insert['haveCredit'] = $data['credit_card'];
        }
        if(!empty($data['insureCode'])){
            $insert['insureCode'] = $data['insureCode'];
        }
        if(!empty($data['house'] && $data['house'] == 2)){
            $insert['haveHouse'] = 2;
            $insert['hourseDai'] = 2;
        }
        if(!empty($data['house']) && $data['house'] == 3){
            $insert['haveHouse'] = 2;
        }

        if(!empty($data['car']) && $data['car'] == 2){
            $insert['haveCar'] = 2;
            $insert['carDai']  = 2;
        }
        if(!empty($data['car']) && $data['car'] == 3){
            $insert['haveCar'] = 2;
        }
        if(!empty($data['occapation'])){
            $insert['occapation'] = $data['occapation'];
        }
        if(!empty($data['salary_modal'])){
            $insert['payType'] = $data['salary_modal'];
        }
        if(!empty($data['month_income'])){
            $insert['income'] = $data['month_income'];
        }
        if(!empty($data['policy'])){
            $insert['insureCode'] = $data['policy'];
        }
        if(!empty($data['accumulation_fund'])){
            $insert['reservedFunds'] = $data['accumulation_fund'];
        }
        if(!empty($data['social_security'])){
            $insert['socialSecurity'] = $data['social_security'];
        }
        // D($insert);
        $result = $this->OutsideModel->insertCustom($insert);
        if (!empty($result['errcode'])) {
            $this->CommonModel->output($result);
        }
        $this->CommonModel->output(['errcode' => 0,'errmsg' => '操作成功']);
        // print_r($data);
    }
}