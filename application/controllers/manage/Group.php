<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 用户组管理控制器
 * @author  liuweilong
 * +2016-03-15
 */
class Group extends MY_Controller
{   
    protected $_tabid = 'id8';
    /**
     * 构造
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->Model("manage/group_model", "ManageGroup_model");
    }

    /**
     * 默认Action
     * @return html
     */
    public function index()
    {
        
        $dt = new DataTable();
        $postData =  isset($_POST) && !empty($_POST) ? $_POST : array();
        list($pageCurrent, $pageSize) = array((int) isset($postData['pageCurrent']) && !empty($postData['pageCurrent']) ? $postData['pageCurrent'] : 1, 
                                              (int) isset($postData['pageSize']) && !empty($postData['pageSize']) ? $postData['pageSize'] : 30);

        $data = $this->ManageGroup_model->lists($postData, $pageCurrent, $pageSize);
        $total = $this->ManageGroup_model->counts($postData);

        $data = $dt->setAttr(array('class' => "table table-striped table-bordered table-hover nowrap"))
           ->setHeader($this->_dataTableConfigProvider())
           ->setData($data)
           ->setTopContent(
            '<div class="bjui-pageContent tableContent">'.
            '<p><a href="/manage/group/add" data-toggle="dialog" class="btn btn-blue"><i class="fa fa-plus"></i>添加用户组</a></p>'
            )
           ->setBottomContent('</div>'.BjuiPager::get($pageCurrent, $total, $pageSize))
           ->setRowCounter($pageCurrent, $pageSize)
           ->render();
        echo $data;
    }

    /**
     * 新增
     * @return html
     */
    public function add()
    {
        $ph = new PasswordHash;
        $v = new DooValidator;
        $fh = new FormHelper;
        if (!empty($_POST))
        {
            $success = true;
            $errors = array();
            $rules = array(
                'name' => array(
                                array('required', '用户必须填写'),
                             ),
            );
            
            if($errors = $v->validate($_POST, $rules))
            {
                $success = false;
            }
            else
            {
                $this->ManageGroup_model->add($_POST);
            }
            echo $this->bjuiRes($success, $success ? "操作成功" : $v->errorToString($errors), $this->_tabid);
        }
        else
        {
            list($formHeaderConfig, $formBodyConfig) = $this->_formConfigProvider($fh, '/manage/group/add' , array(), 'add');
             $formSearch = $fh->formCreater($formHeaderConfig, $formBodyConfig)->render('TableMuti', 
                array('size' => 3, 'title' => '添加用户组')
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
        if (!empty($_POST))
        {
            $this->ManageGroup_model->edit(array('id' => $id), $_POST);
            echo $this->bjuiRes(true, "操作成功");
        }
        else
        {
            $data = $this->ManageGroup_model->getRow(array('id' => $id));
            list($formHeaderConfig, $formBodyConfig) = $this->_formConfigProvider($fh, '/manage/group/edit?id='.$id , $data);
            $formSearch = $fh->formCreater($formHeaderConfig, $formBodyConfig)->render('TableMuti', 
                array('size' => 3, 'title' => '编辑')
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
                //'rowCounter' => array(),
                // 'checkboxs' => array('field' => 'id'),
                'id'  => array('name'=>'ID'),
                'name'  => array('name'=>'名称'),
                'is_mobile'  => array('name'=>'是否手机验证','callback'=>function($row, $rowData, $field){
                    return $rowData[$field]==2 ? '是' : '否';
                }),
                'btn'  => array('name'=>'操作', 'callback'=>function($row, $rowData, $field)
                {
                    // '<a data-toggle="navtab" href="/manage/group/del?id='.$rowData['id'].'" class="btn btn-red"><i class="fa fa-remove"></i>删除</a>';
                    return '<a data-toggle="dialog" href="/manage/group/edit?id='.$rowData['id'].'" class="btn btn-default"><i class="fa fa-edit"></i>编辑</a> '.
                    '<a data-toggle="navtab" data-id="id17" data-pid="2" href="/manage/auth?groupId='.$rowData['id'].'" class="btn btn-default"><i class="fa fa-edit"></i>权限配置</a>';
                }),

        );
    }

    /**
     * 表单新增编辑配置供给
     * @return array
     */
    protected function _formConfigProvider(FormHelper $fh, $action = '/manage/account/add', $data = array(), $type = 'edit')
    {
        $formHeaderConfig = array('data-toggle' => 'validate', 'action' => $action, 'method' => 'post');
        $formBodyConfig = array(
            array('type' => 'text', 'key' => 'name', 'label' => '名称', 'data' => null ,'defaultValue' => '', 'order' => 1, 'attr' => array('data-rule' => 'require;')),
            array('type' => 'radio', 'key' => 'is_mobile', 'label' => '是否手机验证', 'data' => array(1=>'否', 2=>'是') ,'defaultValue' => 1, 'order' => 1, 'attr' => array('data-rule' => 'require;')),

            array('type' => 'hidden', 'key' => 'id', 'defaultValue' => -1, 'order' => 888)
            // array('type' => 'button', 'key' => 'submit', 'label' => '<i class="fa fa-search"></i>搜索', 'order' => 999, 'attr' => array('class'=> 'btn btn-default')),
        );
        if (isset($data) && !empty($data))
        {
            $formBodyConfig = $fh->mergeData($formBodyConfig, $data);
        }
        return array($formHeaderConfig, $formBodyConfig);
    }
}