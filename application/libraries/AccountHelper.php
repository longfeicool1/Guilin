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
        get_instance()->load->model("manage/Account_model", "ManageAccount_model");
        $model = new Account_model;
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
            AccountHelper::$_accountData['login_num'] = AccountHelper::$_accountData['login_num'] + 1;
            $this->_writeLoginData(AccountHelper::$_accountData);
            $this->_updateMsg($username, AccountHelper::$_accountData['login_num']);
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
        $model = new Account_model;
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
    public static function checkPassword($value, $username)
    {
        $model = new Account_model;
        $data = $model->getRow(array('username' => $username),
            'id, username, password, enable, login_num, real_name, email, mobile,
            departments, update_password_num, last_update_time, last_login_time, remark, queue, exten,team');
        $ph = new PasswordHash;
        if (empty($data))
        {
            return '账号或密码不正确';
        }

        $ok = $ph->CheckPassword($value, $data['password']);
        if (!$ok)
        {
            return '账号或密码不正确';
        }

        get_instance()->load->model("manage/GroupUser_model", "ManageGroupUser_model");
        get_instance()->load->model("manage/Auth_model", "ManageAuth_model");
        $group = get_instance()->ManageGroupUser_model->getList(array('account_id' => $data['id']));
        $group = DataExecuter::KeyToArray($group, 'group_id');
        $data['group'] = $group;
        $data['allow_menu_id'] = (array) (empty($group) ? array() : DataExecuter::KeyToArray(get_instance()->ManageAuth_model->getList('group_id IN ('.join(',', $group).')'), 'menu_id'));
        AccountHelper::$_accountData = $data;
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
            return $code.'验证码不正确'.$code2;
        }

        if(time() - 300 > $codeCreated)
        {
            return '验证码过期';
        }
        get_instance()->session->unset_userdata(array('code', 'codeCreated'));

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