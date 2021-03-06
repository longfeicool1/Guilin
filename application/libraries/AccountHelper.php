<?php

/**
 * 用户
 * @author  liuweilong
 * +2016-03-14 created
 **/
class AccountHelper
{
    /**
     * [$_accountData description]
     * @var array
     */
    protected static $_accountData;


    // protected $_ci;

    public function __contruct()
    {
        // $this->_ci = get_instance();
    }

    /**
     * 是否登录
     * @return boolean
     */
    public function isLogin()
    {
        //return isset($_SESSION['account']) && !empty($_SESSION['account']) ? true : false;
        $account = get_instance()->session->userdata('account');
        return !empty($_SESSION['account']) ? true : false;
    }

    /**
     * 短信验证登入
     * @param  string $username
     * @param  string $password
     * @param  string $code
     * @return array
     */
    public function codeLoginIn($username, $captcha)
    {
        $success = true;
        $errors = array();
        $type = 2;
        get_instance()->load->model("admin/AccountModel", "ManageAccountModel");
        $model = new AccountModel;
        $rules = array(
            'username'=>array(
                array('required','账号不能为空'),
                array('custom', 'AccountHelper::checkUsername',[$type])
                ),
            'captcha'=>array(
                array('required', '验证码必须填写'),
                array('custom', 'AccountHelper::checkPhoneCaptcha',[$username])
                ),
        );

        $v = new DooValidator();
        // $v->checkMode = DooValidator::CHECK_SKIP;
        if($errors = $v->validate(array('username' => $username,'captcha'=> $captcha),$rules))
        {
            $success = false;
        }
        if($success){
            // AccountHelper::$_accountData['login_num'] = AccountHelper::$_accountData['login_num'] + 1;
            get_instance()->session->unset_userdata('code_'.$username);
            $this->_writeLoginData(AccountHelper::$_accountData);
        }

        AccountHelper::$_accountData = '';
        return array($success, $v->errorToString($errors));
    }

    /**
     * 登入
     * @param  string $username
     * @param  string $password
     * @param  string $code
     * @return array
     */
    public function loginIn($username, $password, $captcha)
    {
        $success = true;
        $errors = array();
        get_instance()->load->model("admin/AccountModel", "ManageAccountModel");
        $model = new AccountModel;
        $rules = array(
            'username'=>array(
                array('required','账号不能为空'),
                // array('username',4 ,16,'username invalid')
                ),
            'password'=>array(
                array('required','密码不能为空'),
                //array('password', 6, 16),
                array('custom', 'AccountHelper::checkPassword', array($username))
                ),
            'captcha'=>array(
                array('required', '验证码必须填写'),
                array('custom', 'AccountHelper::checkCaptcha')
                ),
        );

        $v = new DooValidator();
        // $v->checkMode = DooValidator::CHECK_SKIP;
        if($errors = $v->validate(array('username' => $username,
                                        'password' => $password,
                                        'captcha'=> $captcha),
                                        $rules))
        {
            $success = false;
        }

        if($success)
        {
            // AccountHelper::$_accountData['login_num'] = AccountHelper::$_accountData['login_num'] + 1;
            $this->_writeLoginData(AccountHelper::$_accountData);
            // $this->_updateMsg($username, AccountHelper::$_accountData['login_num']);
        }

        AccountHelper::$_accountData = '';
        return array($success, $v->errorToString($errors));
    }

    /**
     * 登出
     * @return void
     */
    public function loginOut()
    {
        get_instance()->session->unset_userdata('account');
        get_instance()->session->unset_userdata('org');
    }

    public function toSendPhoneCode($username,$captcha)
    {
        $success = true;
        $errors  = [];
        $rules   = array(
            'username'=>array(
                array('required','账号不能为空'),
                array('custom', 'AccountHelper::checkUsername')
                ),
            'captcha'=>array(
                array('required', '验证码必须填写'),
                array('custom', 'AccountHelper::checkCaptcha')
                ),
        );

        $v = new DooValidator();
        // $v->checkMode = DooValidator::CHECK_SKIP;
        $errors = $v->validate(['username' => $username,'captcha'=> $captcha],$rules);
        // D($errors);
        if(!empty($errors)){
            return array(false, $v->errorToString($errors));
        }
        // $code = get_instance()->cache->redis->get('code_'.$username);
        $code = get_instance()->session->userdata('code_'.$username);
        if (!empty($code)) {
            return array(false, '请不要频繁操作');
        }
        // get_instance()->cache->redis->save('code_'.$mobile, $code, $ttl);
        $code = rand(1000,9999);
        get_instance()->session->set_tempdata('code_'.$username, $code,300);
        sms($username,$code);
        return array($success, '发送成功,请注意查收');
    }

    /**
     * [checkUsername 检测登入名是否存在]
     * @param  [type] $username [description]
     * @return [type]           [description]
     */
    public static function checkUsername($username,$type = 1)
    {
        $valid = strlen($username) != 11 || !preg_match('/^1[3|4|5|6|7|8|9][0-9]\d{4,8}$/', $username) ? false : true;
        if (!$valid) {
            return '你输入正确的手机号';
        }
        get_instance()->load->model("admin/AccountModel", "ManageAccountModel");
        $model = new AccountModel;
        $info  = $model->userinfo($username);
        if (empty($info)){
            return '账号不正确';
        }
        if ($type == 2) {
            $rules                       = $model->getRules($info['role_id']);
            $info['menu_id']             = $rules['rule_id'];
            $info['is_finance']          = $rules['is_finance'];
            $info['look_city']           = explode(',', $rules['look_city']);
            AccountHelper::$_accountData = $info;
        }
    }

    /**
     * [_writeLoginData description]
     * @return [type] [description]
     */
    protected function _writeLoginData($data)
    {
        get_instance()->session->set_userdata('account', $data);
    }

    /**
     * 更新登录信息
     * @return [type] [description]
     */
    protected function _updateMsg($username, $loginNum)
    {
        $model = new AccountModel;
        $model->edit(
            array('username'=> $username),
            array('login_num' => $loginNum,
                  'last_login_time' => date('Y-m-d H:i:s')
                  )
        );
    }

    /**
     * 取得当前登录账号信息
     * @return array
     */
    public function info()
    {
        return  get_instance()->session->userdata('account');
    }

    /**
     * 检查帐号对应密码是否正确
     * @param  [type] $value    [description]
     * @param  [type] $username [description]
     * @return [type]           [description]
     */
    public static function checkPassword($password, $username)
    {
        $model = new AccountModel;
        $info  = $model->userinfo($username);
        if (empty($info)){
            return '账号或密码不正确';
        }
        $password = md5($username . md5($username . $password));
        if ($password != $info['password']){
            return '账号或密码不正确';
        }
        $rules                       = $model->getRules($info['role_id']);
        $info['menu_id']             = $rules['rule_id'];
        $info['is_finance']          = $rules['is_finance'];
        $info['look_city']           = explode(',', $rules['look_city']);
        AccountHelper::$_accountData = $info;
    }

    /**
     * 检查验证码是否正确
     * @param  [type] $value    [description]
     * @return [type]           [description]
     */
    public static function checkCaptcha($code)
    {
        $code2 = strtolower(get_instance()->session->userdata('code'));
        $codeCreated = get_instance()->session->userdata('codeCreated');

        if(empty($code2) || strtolower($code) != $code2)
        {
            return '验证码不正确';
        }

        if(time() - 300 > $codeCreated)
        {
            return '验证码过期';
        }
        get_instance()->session->unset_userdata(array('code', 'codeCreated'));

    }

    /**
     * 检查验证码是否正确
     * @param  [type] $value    [description]
     * @return [type]           [description]
     */
    public static function checkPhoneCaptcha($code,$mobile)
    {
        // $code2 = get_instance()->cache->redis->get('code_'.$mobile);
        $code2 = get_instance()->session->userdata('code_'.$mobile);
        if(empty($code2))
        {
            return '验证码过期';
        }

        if ($code != $code2) {
            return '验证码错误';
        }

    }

    /**
     * 生成验证码
     * @return bytes img
     */
    public static function createCaptcha()
    {
        get_instance()->load->library('captcha');
        $code = get_instance()->captcha->getCaptcha();
        get_instance()->session->set_userdata('code', $code);
        get_instance()->session->set_userdata('codeCreated', time());
        get_instance()->captcha->showImg();
    }
    /**
     * 检验登录是否发送验证码
     * @return bytes img
     */
    public function isSendMsg($groupIds)
    {
        if (empty($groupIds))
        {
            return false;
        }
        get_instance()->load->model("manage/Group_model", "ManageGroup_model");
        $data = get_instance()->ManageGroup_model->getIsMobile($groupIds);
        return $data;
    }
}