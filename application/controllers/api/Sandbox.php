<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sandbox extends CI_Controller
{
    protected $data;
    protected $dataLevel;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/OutsideModel');
        $this->load->model('CommonModel');
    }

    /**
     * [checkSign 校验签名接口]
     * @return [type] [description]
     */
    public function checkSign()
    {
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
        $sign = $this->sign($cominfo['appSecrect'], $this->data);
        // print_r($sign);die;
        if ($this->data['sign'] != $sign) {
            print_r("检验结果::未通过".PHP_EOL);
        } else {
            print_r("检验结果::通过".PHP_EOL);
        }
    }

    function sign($secrect, $data, $signFields = [])
    {
        ksort($data);
        // 开始计算sign
        $newData = [];
        foreach ($data as $k => $v) {
            if ($k == 'sign') {
                continue;
            }

            if (empty($signFields) || in_array($k, $signFields)) {
                $newData[] = is_array($v) ? json_encode($v) : trim($v);
            }
        }
        $values = implode('', array_values($newData));
        print_r("values::{$values}".PHP_EOL);
        // print_r("secrect::{$secrect}".PHP_EOL);
        print_r("sign::".md5(md5($values) . $secrect).PHP_EOL);
    }

}