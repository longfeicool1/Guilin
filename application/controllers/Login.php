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
        if (!empty($_POST)) {
            $res = $this->AccountHelper->loginIn($_POST['username'], $_POST['password'], $_POST['captcha']);
            echo $this->bjuiRes($res[0], $res[1]);
        } else {
            $this->ci_smarty->display('login/index.tpl');
        }

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