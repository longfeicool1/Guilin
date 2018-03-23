<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 前端推广页面
 * @author  Loonfiy
 * +2018-03-02
 */

class Spread extends CI_Controller
{
    public function __CONSTRUCT()
    {
        parent::__CONSTRUCT();
        $this->load->model('web/SpreadModel');
        $this->load->model('CommonModel');
    }

    public function index()
    {
        $channel = $this->input->get('channel') ? $this->input->get('channel') : 'WEB';
        $channel = $this->ci_smarty->assign('channel',$channel);
        $this->ci_smarty->display('web/spread.tpl');
    }

    public function submit()
    {
        $data    = $this->input->post();

        if (empty($data['name'])) {
            $this->CommonModel->output(['errcode' => 300,'errmsg' => '请填写您的姓名']);
        }
        if (empty($data['mobile'])) {
            $this->CommonModel->output(['errcode' => 300,'errmsg' => '请填写您的联系方式']);
        }
        if (empty($data['daiMoney'])) {
            $this->CommonModel->output(['errcode' => 300,'errmsg' => '请填写您的要贷款的金额']);
        }
        if (strlen($data['mobile']) != 11 || !preg_match('/^1[3|4|5|7|8][0-9]\d{4,8}$/', $data['mobile'])) {
            $this->CommonModel->output(['errcode' => 300,'errmsg' => '请填写正确的手机号']);
        }
        $rs = $this->db->get_where('md_custom_list',['isShow' => 1,'mobile' => $data['mobile']])->row_array();
        if (!empty($rs)) {
            $this->CommonModel->output(['errcode' => 300,'errmsg' => '已提交过一次啦,请等待客服代表联系您']);
        }
        $insert = [
            'name'      => $data['name'],
            'mobile'    => $data['mobile'],
            'daiMoney'  => $data['daiMoney'],
            'sex'       => $data['sex'],
            'city'      => $data['city'],
            'source'    => $data['channel'],
            'dataLevel' => 'A',
        ];
        if ($this->db->insert('md_custom_list',$insert)) {
            $this->CommonModel->output(['errcode' => 200,'errmsg' => '感谢您的支持,客服代表将会尽快跟您联系']);
        }
        $this->CommonModel->output(['errcode' => 300,'errmsg' => '提交失败']);
    }
}