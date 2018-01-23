<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 用户管理控制器
 * @author  liuweilong
 * +2016-03-15
 */
class Account extends MY_Controller
{
    protected $_tabid = 'id5';

    public static $group;

    public static $groupUser;

    public static $departmentsList = array(-1 => '请选择',
        '技术部'                                     => '技术部', '硬件研发部' => '硬件研发部',
        '市场部'                                     => '市场部', '运营部'   => '运营部',
        '测试部'                                     => '测试部', '客服部'   => '客服部',
        '合作第三方'                                   => '合作第三方');
    /**
     * 构造
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model("manage/Account_model", "ManageAccount_model");
        $this->load->model("manage/Group_model", "ManageGroup_model");
        $this->load->model("manage/GroupUser_model", "ManageGroupUser_model");

        if ($this->uri->uri_string() == 'manage/account/editPassMe') {
            return;
        }

        if (!in_array($this->_account['id'], array(22, 75)) && $this->_account['username'] != "admin") {
            echo 'Permission denied!#3';
            exit;
        }
    }

    /**
     * 默认Action
     * @return html
     */
    public function index()
    {
        $fh       = new FormHelper;
        $dt       = new DataTable();
        $postData = isset($_POST) && !empty($_POST) ? $_POST : array();

        list($formHeaderConfig, $formBodyConfig) = $this->_formSearchConfigProvider($fh);
        list($pageCurrent, $pageSize)            = array((int) isset($postData['pageCurrent']) && !empty($postData['pageCurrent']) ? $postData['pageCurrent'] : 1,
            (int) isset($postData['pageSize']) && !empty($postData['pageSize']) ? $postData['pageSize'] : 30);

        $formSearch = $fh->formCreater($formHeaderConfig, $formBodyConfig)->render('FormSearch',
            array('topButton' => '<p><a data-width="900" data-height="500" href="/manage/account/add" data-toggle="dialog" class="btn btn-blue"><i class="fa fa-plus"></i>添加用户</a></p>')
        );

        $keys = DataExecuter::keyToArray($formBodyConfig, 'key');
        array_pop($keys);
        // $postData = DataExecuter::filter($postData, array('', -1), $keys);

        $data = $this->ManageAccount_model->lists($postData, $pageCurrent, $pageSize);

        $ids   = DataExecuter::keyToArray($data, 'id');
        $group = DataExecuter::keyToField($this->ManageGroup_model->getList(''), 'id', 'name');

        if (!empty($data)) {
            $groupUser = $this->ManageGroupUser_model->getList('account_id IN (' . join(',', $ids) . ')');
        } else {
            $groupUser = array();
        }

        $newGroupUser = array();
        foreach ($groupUser as $k => $v) {
            if (!isset($newGroupUser[$v['account_id']])) {
                $newGroupUser[$v['account_id']] = array();
            }
            $newGroupUser[$v['account_id']][] = $v['group_id'];
        }

        list(Account::$group, Account::$groupUser) = array($group, $newGroupUser);
        // var_dump($group, $newGroupUser, $groupUser);
        $total = $this->ManageAccount_model->counts($postData);
        $data  = $dt->setAttr(array('class' => "table table-striped table-bordered table-hover nowrap"))
            ->setHeader($this->_dataTableConfigProvider())
            ->setData($data)
            ->setTopContent($formSearch . '<div class="bjui-pageContent tableContent">')
            ->setBottomContent('</div>' . BjuiPager::get($pageCurrent, $total, $pageSize))
            ->setRowCounter($pageCurrent, $pageSize)
            ->render();
        echo $data;
    }

    /**
     * [checkUsername description]
     * @return [type] [description]
     */
    public static function checkUsername($value)
    {
        $model = get_instance()->ManageAccount_model;
        $data  = $model->getRow(array('username' => $value));
        if (!empty($data)) {
            return '用户已存在';
        }
    }

    /**
     * 新增
     * @return html
     */
    public function add()
    {
        $ph = new PasswordHash;
        $v  = new DooValidator;
        $fh = new FormHelper;
        if (!empty($_POST)) {
            $_POST['password'] = 'kv123456';

            $success = true;
            $errors  = array();
            $rules   = array(
                'username' => array(
                    array('required', '用户必须填写'),
                    // array('username',4 ,16,'username invalid'),
                    array('custom', 'Account::checkUsername'),
                ),
                'password' => array(
                    array('required', '密码不允许为空'),
                    array('password', 6, 16),
                ),
            );

            if ($errors = $v->validate($_POST, $rules)) {
                $success = false;
            } else {
                $_POST['password'] = $ph->HashPassword($_POST['password']);
                $groups            = isset($_POST['groups']) ? $_POST['groups'] : array();

                unset($_POST['groups']);
                $this->ManageAccount_model->add($_POST);
                $id = $this->ManageAccount_model->db->insert_id();
                // exit;
                if (!empty($groups)) {
                    $this->ManageGroupUser_model->delete(array('account_id' => $id));
                    $data = array();
                    foreach ($groups as $k => $v) {
                        $data[$k]               = array();
                        $data[$k]['group_id']   = $v;
                        $data[$k]['account_id'] = $id;
                    }
                    $this->ManageGroupUser_model->addBatch($data);
                }
            }
            echo $this->bjuiRes($success, $success ? "操作成功" : $v->errorToString($errors), $this->_tabid);
        } else {
            list($formHeaderConfig, $formBodyConfig) = $this->_formConfigProvider($fh, '/manage/account/add', array(), 'add');
            $formSearch                              = $fh->formCreater($formHeaderConfig, $formBodyConfig)->render('TableMuti',
                array('size' => 2, 'title' => '添加用户')
            );
            echo $formSearch;
        }
    }

    /**
     * 编辑
     * @return html
     */
    public function edit()
    {
        $id = (int) DataExecuter::get($_GET, 'id', 0);

        $fh = new FormHelper;
        if (!empty($_POST)) {
            $_POST['groups'] = isset($_POST['groups']) ? $_POST['groups'] : array();
            $groups          = $_POST['groups'];
            unset($_POST['groups']);

            if (!empty($groups)) {
                $this->ManageGroupUser_model->delete(array('account_id' => $id));
                $data = array();
                foreach ($groups as $k => $v) {
                    $data[$k]               = array();
                    $data[$k]['group_id']   = $v;
                    $data[$k]['account_id'] = $id;
                }
                $this->ManageGroupUser_model->addBatch($data);
            }
            $this->ManageAccount_model->edit(array('id' => $id), $_POST);
            echo $this->bjuiRes(true, "操作成功", $this->_tabid);
        } else {
            $data                                    = $this->ManageAccount_model->getRow(array('id' => $id));
            $group                                   = $this->ManageGroupUser_model->getList(array('account_id' => $id));
            $data['groups']                          = DataExecuter::keyToArray($group, 'group_id');
            list($formHeaderConfig, $formBodyConfig) = $this->_formConfigProvider($fh, '/manage/account/edit?id=' . $id, $data);
            $formSearch                              = $fh->formCreater($formHeaderConfig, $formBodyConfig)->render('TableMuti',
                array('size' => 2, 'title' => '编辑')
            );
            echo $formSearch;
        }
    }

    /**
     * 修改自己的密码
     * @return html
     */
    public function editPassMe()
    {
        $_GET['id'] = 0;
        return $this->editPass(true);
    }

    /**
     * 编辑密码
     * @return html
     */
    public function editPass($me = false)
    {
        $id   = (int) DataExecuter::get($_GET, 'id', 0);
        $msg  = DataExecuter::get($_GET, 'msg', '');
        $msg  = !empty($msg) ? urldecode($msg) : '';
        $ph   = new PasswordHash;
        $fh   = new FormHelper;
        $v    = new DooValidator();
        $isMe = false;
        if ($id == 0 || $me) {
            $account = $this->AccountHelper->info();
            $id      = $account['id'];
            $isMe    = true;
        }

        if (!empty($_POST)) {
            if ($_POST['password'] != $_POST['confirmpwd']) {
                echo $this->bjuiRes(false, '两次密码输入不一样，请重新输入');
                return;
            }
            $success = true;
            $errors  = array();
            $rules   = array(
                'id'       => array('required', '用户不存在'),
                'password' => array(
                    array('required', '密码不允许为空'),
                    // array('password', 6, 16),
                ),
            );

            if ($errors = $v->validate($_POST, $rules)) {
                $success = false;
            } else {
                $d = $this->ManageAccount_model->getRow(array('id' => $_POST['id']));
                $this->ManageAccount_model->edit(array('id' => $_POST['id']),
                    array('password'      => $ph->HashPassword($_POST['password']),
                        'update_password_num' => $d['update_password_num'] + 1,
                        'last_update_time'    => time(),
                    )
                );
            }

            if ($success && $isMe) {
                $this->AccountHelper->loginOut();
            }
            echo $this->bjuiRes($success, $success ? "操作成功" . ($isMe ? ', 请刷新页面，重新登录!' : '') : $v->errorToString($errors));
        } else {

            $data                                    = $this->ManageAccount_model->getRow(array('id' => $id));
            list($formHeaderConfig, $formBodyConfig) = $this->_formPassConfigProvider($fh, $isMe ? '/manage/account/editPassMe' : '/manage/account/editPass?id=' . $id, array('id' => $id));
            $formSearch                              = $fh->formCreater($formHeaderConfig, $formBodyConfig)->render('TableMuti',
                array('size' => 1, 'title' => !$isMe ? '修改 ' . $data['real_name'] . ' 的密码' : '修改我的密码' . (!empty($msg) ? '<p><code>' . $msg . '</code></p>' : ''))
            );
            echo $formSearch;
        }
    }

    /**
     * 表格配置供给
     * @return array
     */
    protected function _dataTableConfigProvider()
    {
        return array(
            // 'rowCounter' => array(),
            // 'checkboxs' => array('field' => 'id'),
            'id'                  => array('name' => 'ID', 'order' => true),
            'username'            => array('name' => '账号'),
            'mobile'              => array('name' => '手机'),
            'email'               => array('name' => '邮箱'),
            'real_name'           => array('name' => '真实姓名'),
            'group_name'          => array('name' => '权限组', 'callback' => function ($row, $rowData, $field) {
                $name = array();
                //Account::$group, Account::$groupUser
                $id = $rowData['id'];
                if (isset(Account::$groupUser[$id])) {
                    foreach (Account::$groupUser[$id] as $v) {
                        $name[] = Account::$group[$v];
                    }
                }
                // return 'xxxx';
                return join(',', $name);
            }),
            'departments'         => array('name' => '所在部门'),
            'login_num'           => array('name' => '登录次数'),
            'update_password_num' => array('name' => '修改密码次数'),
            'last_update_time'    => array('name' => '最后修改密码时间', 'callback' => function ($row, $rowData, $field) {
                return date('Y-m-d H:i:s', $rowData[$field]);
            }),
            'last_login_time'     => array('name' => '最后登录时间'),
            'enable'              => array('name' => '状态', 'callback' => function ($row, $rowData, $field) {
                return $rowData[$field] == 1 ? '激活' : '<span style="color:red">禁用</span>';
            }),
            'remark'              => array('name' => '备注'),
            'exten'               => array('name' => '客服分机号'),
            'queue'               => array('name' => '客服分机队列'),
            'btn'                 => array('name' => '操作', 'callback' => function ($row, $rowData, $field) {
                $str = '';
                $str .= '<a data-id="id7" data-toggle="navtab" href="/manage/account/editPass?id=' . $rowData['id'] . '" class="btn btn-green"><i class="fa fa-lock"></i>修改密码</a>
                            <a data-toggle="dialog" data-width="800" data-height="400" href="/manage/account/edit?id=' . $rowData['id'] . '" class="btn btn-default"><i class="fa fa-edit"></i>编辑</a>';
                if ($rowData['exten']) {
                    $str .= '<a data-toggle="dialog" data-width="800" data-height="400" href="/manage/account/editServicer?id=' . $rowData['id'] . '" class="btn btn-default"><i class="fa fa-edit"></i>电销团队管理</a>';
                }
                return $str;
            }),

        );
    }

    /**
     * 表单查询配置供给
     * @return array
     */
    protected function _formSearchConfigProvider(FormHelper $fh)
    {
        $formHeaderConfig = array('id' => 'pagerForm', 'data-toggle' => 'ajaxsearch', 'action' => '/manage/account', 'method' => 'post');
        $formBodyConfig   = array(
            array('type' => 'text', 'key' => 'username', 'label' => '账号', 'data' => null, 'defaultValue' => '', 'order' => 1),
            array('type' => 'text', 'key' => 'mobile', 'label' => '手机号码', 'data' => null, 'defaultValue' => '', 'order' => 11),
            array('type' => 'text', 'key' => 'email', 'label' => '邮箱', 'data' => null, 'defaultValue' => '', 'order' => 31),
            array('type' => 'text', 'key' => 'real_name', 'label' => '真实姓名', 'data' => null, 'defaultValue' => '', 'order' => 41),
            array('type' => 'select', 'key' => 'enable', 'label' => '激活', 'data' => array(-1 => '请选择', 1 => '激活', 2 => '禁用'), 'defaultValue' => -1, 'order' => 41),
            array('type' => 'select', 'key' => 'departments', 'label' => '部门', 'data' => Account::$departmentsList, 'defaultValue' => -1, 'order' => 51),
            array('type' => 'button', 'key' => 'submit', 'label' => '<i class="fa fa-search"></i>搜索', 'order' => 999, 'attr' => array('class' => 'btn btn-default')),
            array('type' => 'block', 'content' => '<a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo"> 清空查询</a>', 'order' => 1000),
        );

        if (isset($_POST) && !empty($_POST)) {
            $formBodyConfig = $fh->mergeData($formBodyConfig, $_POST);
        }
        return array($formHeaderConfig, $formBodyConfig);
    }

    /**
     * 表单新增编辑配置供给
     * @return array
     */
    protected function _formConfigProvider(FormHelper $fh, $action = '/manage/account/add', $data = array(), $type = 'edit')
    {
        $formHeaderConfig = array('data-toggle' => 'validate', 'action' => $action, 'method' => 'post');
        $formBodyConfig   = array(
            array('type' => 'text', 'key' => 'username', 'label' => '账号', 'data' => null, 'defaultValue' => '', 'order' => 1, 'attr' => array('data-rule' => 'require;')),
            array('type' => 'text', 'key' => 'mobile', 'label' => '手机号码', 'data' => null, 'defaultValue' => '', 'order' => 11, 'attr' => array('data-rule' => 'require;mobile')),
            array('type' => 'text', 'key' => 'email', 'label' => '邮箱', 'data' => null, 'defaultValue' => '', 'order' => 31, 'attr' => array('data-rule' => 'require;email')),
            array('type' => 'text', 'key' => 'real_name', 'label' => '真实姓名', 'data' => null, 'defaultValue' => '', 'order' => 41, 'attr' => array('style' => "width:95px", 'data-rule' => 'require;')),
            array('type' => 'select', 'key' => 'enable', 'label' => '激活', 'data' => array(-1 => '请选择', 1 => '激活', 2 => '禁用'), 'defaultValue' => 1, 'order' => 41),
            array('type' => 'select', 'key' => 'departments', 'label' => '部门', 'data' => Account::$departmentsList, 'defaultValue' => -1, 'order' => 51),
            array('type' => 'text', 'key' => 'exten', 'label' => '客服分机号', 'data' => null, 'defaultValue' => '', 'order' => 88, 'attr' => array('style' => "width:95px")),
            array('type' => 'text', 'key' => 'queue', 'label' => '客服分机队列', 'data' => null, 'defaultValue' => '', 'order' => 89, 'attr' => array('style' => "width:95px")),
            array('type' => 'hidden', 'key' => 'id', 'defaultValue' => -1, 'order' => 888),
            array('type' => 'muticheckbox', 'key' => 'groups', 'label' => '组', 'data' => DataExecuter::keyToField($this->ManageGroup_model->getList(''), 'id', 'name'), 'defaultValue' => -1, 'order' => 81),
            array('type' => 'select', 'key' => 'team', 'label' => '团队', 'data' => array(1 => '团队一', 2 => '团队二', 3 => '团队三'), 'defaultValue' => 1, 'order' => 90),
            // array('type' => 'button', 'key' => 'submit', 'label' => '<i class="fa fa-search"></i>搜索', 'order' => 999, 'attr' => array('class'=> 'btn btn-default')),
        );

        if ($type == 'edit') {
            $formBodyConfig[0]['type'] = 'show';
        }

        if (isset($data) && !empty($data)) {
            $formBodyConfig = $fh->mergeData($formBodyConfig, $data);
        }
        return array($formHeaderConfig, $formBodyConfig);
    }


    /**
     * 表单密码修改配置供给
     * @return array
     */
    protected function _formPassConfigProvider(FormHelper $fh, $action = '/manage/account/editPass', $data = array())
    {
        $formHeaderConfig = array('data-toggle' => 'validate', 'action' => $action, 'method' => 'post');
        $formBodyConfig   = array(
            array('type' => 'password', 'key' => 'password', 'label' => '新密码', 'data' => null, 'defaultValue' => '', 'order' => 1, 'attr' => array('data-rule' => 'require;')),
            array('type' => 'password', 'key' => 'confirmpwd', 'label' => '重复密码', 'data' => null, 'defaultValue' => '', 'order' => 1, 'attr' => array('data-rule' => 'require;')),

            array('type' => 'hidden', 'key' => 'id', 'defaultValue' => -1, 'order' => 888),
        );
        if (isset($data) && !empty($data)) {
            $formBodyConfig = $fh->mergeData($formBodyConfig, $data);
        }
        return array($formHeaderConfig, $formBodyConfig);
    }
}
