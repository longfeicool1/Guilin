<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('AccountHelper', null, 'AccountHelper');
    }
    /**
     * 登录页
     * @return html
     */
    public function index()
    {
        $type = $this->input->get('type');
        if (!empty($_POST)) {
            if ($type == 1) {
                $res = $this->AccountHelper->loginIn($_POST['username'], $_POST['password'], $_POST['captcha']);
            } else {
                $res = $this->AccountHelper->codeLoginIn($_POST['username'], $_POST['phoneCaptcha']);
            }
            echo $this->bjuiRes($res[0], $res[1]);
        } else {
            if ($type == 1) {
                $this->ci_smarty->display('login/index2.tpl');
            } else {
                $this->ci_smarty->display('login/index.tpl');
            }
        }

    }

    public function phoneCode()
    {
        $mobile     = $this->input->get('mobile');
        $imgCaptcha = $this->input->get('imgCaptcha');
        $res = $this->AccountHelper->toSendPhoneCode($mobile, $imgCaptcha);
        echo $this->bjuiRes($res[0], $res[1]);
    }

    /**
     * 显示验证码图片
     * @return images
     */
    public function captcha()
    {
        $this->AccountHelper->createCaptcha();
    }

    /**
     * 注销
     * @return void
     */
    public function loginOut()
    {
        $this->AccountHelper->loginOut();
        redirect('/login');
    }
}