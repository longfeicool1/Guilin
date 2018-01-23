<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 登录控制器
 * @author  liuweilong
 * +2016-03-03
 */
class Login extends MY_Controller
{
    /**
     * 登录页
     * @return html
     */
    public function index()
    {
        $ph = new PasswordHash;
        //echo $ph->HashPassword('It131415');
        //echo $ph->HashPassword('123456');  //$2a$08$lHt.wg/6RBdaI4jOkYKRZ.yqDygN1w81NCYuNpvrI2slof0lukRo2
        // var_dump( $ph->CheckPassword('It131415', '$2a$08$nWdPu4pve/t.nJyenaJYieBWROzJ4zVTLDNkjzrseJt3y/.KkwDrK') );
        // exit;
        if (!empty($_POST)) {
            $res = $this->AccountHelper->loginIn($_POST['username'], $_POST['password'], $_POST['captcha']);
            echo $this->bjuiRes($res[0], $res[1]);
            // print_r($res);
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