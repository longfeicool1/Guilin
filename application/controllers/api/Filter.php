<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filter extends CI_Controller
{
    protected $screat = 'PPQFFa0D0NyGSGZfX3';

    public function __construct()
    {
        error_reporting(0);
        parent::__construct();
        $this->load->model('CommonModel');
    }

    public function result()
    {
        $data = file_get_contents('php://input');
        if (empty($data)) {
            $this->CommonModel->output(['code' => 10005,'msg' => '数据内容为空']);
        }
        file_put_contents('./error2.log', $data,FILE_APPEND);
        $data = json_decode($data, true);
        // print_r($data);die;
        if (!is_array($data)) {
            $this->CommonModel->output(['code' => 10006,'msg' => '数据格式错误2']);
        }
        if (empty($data['channel'])) {
            $this->CommonModel->output(['code' => 10007,'msg' => 'channel参数错误']);
        }
        if (empty($data['timestamp'])) {
            $this->CommonModel->output(['code' => 10008,'msg' => 'timestamp参数错误']);
        }
        if (empty($data['sign'])) {
            $this->CommonModel->output(['code' => 10009,'msg' => 'sign参数错误']);
        }
        if (empty($data['list'])) {
            $this->CommonModel->output(['code' => 10010,'msg' => '数据不能为空']);
        }
        $sign = md5($data['channel'].$data['timestamp'].$this->screat);
        // echo $sign;die;
        if($sign != $data['sign']) {
            $this->CommonModel->output(['code' => 10011,'msg' => '签名错误']);
        }
        if (count($data['list']) > 500) {
            $this->CommonModel->output(['code' => 10012,'msg' => '每次处理的数据不能超过500条']);
        }
        $mobiles = array_column($data['list'], 'mobile');
        if (empty($mobiles)) {
            $this->CommonModel->output(['code' => 10013,'msg' => '手机为空']);
        }
        $result  = $this->db->select('mobile')->where_in('mobile', $mobiles)->get('md_custom_list')->result_array();
        $this->CommonModel->output(['code' => 0,'msg' => '重复的项为','data' => $result]);
    }
}