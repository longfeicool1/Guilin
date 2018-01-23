<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 示例控制器
 * @author  liuweilong
 * +2016-03-04
 */
class Demo extends MY_Controller
{

    /**
     * 数据列表
     * @return html
     */
    public function dataTable()
    {
    
    	log_message('debug', 'hello world');
        $this->load->library('FormHelper');
        $this->load->library('DataTable');
        $this->load->library('BjuiPager');

        list($formHeaderConfig, $formBodyConfig) = $this->_formSearchConfigProvider();

        $fh = new FormHelper;

        $formSearch = $fh->formCreater($formHeaderConfig, $formBodyConfig)->render('FormSearch');

        // function age($row,$rowData,$val) {
        //     return $val + 10;
        // }

        // function table_button($row,$rowData) {
        //     $a = '<a href="/upd?id='.$rowData['id'].'">修改</a>';
        //     $a .= '  <a href="/del?id='.$rowData['id'].'">删除</a>';
        //     return $a;
        // }

        $header = array(

                'id'  => 'ID',
                'name' => '姓名',
                'sex'  => array('name'=>'性别','defaultValue'=>'女'),
                'age'  => array('name'=>'年龄','callback'=>function($row,$rowData,$val) { return $val + 11; }),
                'age2' => array('name'=>'默认年龄','defaultValue'=>30),
                'phone'=> array('name'=>'手机'),
                'table_button_action' => array('name'=>'操作','callback'=>function($row,$rowData,$val){
                 $a = '<a href="/upd?id='.$rowData['id'].'">修改</a>';
                    $a .= '  <a href="/del?id='.$rowData['id'].'">删除</a>';
                    return $a;
                })
        );
        $data = array(
                0 => array('id'=>1,'name'=>'刘先生','sex'=>'男生','age'=>10,'phone'=>'134564546'),
                1 => array('id'=>2,'name'=>'杨先生','sex'=>'','age'=>12),
                2 => array('id'=>3,'name'=>'好生先','sex'=>'男生','age'=>16),
                3 => array('id'=>4,'name'=>'K要先','sex'=>'男生','age'=>44,'age2'=>60),
                4 => array('id'=>5,'name'=>'lala','sex'=>'男生','age2'=>90),
        );
        //echo "<pre>";print_r($header);
        $dt = new DataTable();
        $data = $dt->setAttr(array('class' => "table table-striped table-bordered table-hover nowrap"))
           ->setHeader($header)
           ->setData($data)
           ->setTopContent($formSearch)
           ->setBottomContent(BjuiPager::get($currPage = 1, $total = 120, $size = 30))
           ->render();
        echo $data;
    }


    /**
     * 增加数据
     * @return html
     */
    public function add()
    {
        if ($this->isPost())
        {

        }
        else
        {
            $this->load->library('FormHelper');
            $fh = new FormHelper;
            list($formHeaderConfig, $formBodyConfig) = $this->_formConfigProvider();
            echo $fh->formCreater($formHeaderConfig, $formBodyConfig)->render('Table');
        }
    }

    public function add2()
    {
        if ($this->isPost())
        {

        }
        else
        {
            $this->load->library('FormHelper');
            $fh = new FormHelper;
            list($formHeaderConfig, $formBodyConfig, $layout) = $this->_formConfigAddProvider();
            echo $fh->formCreater($formHeaderConfig, $formBodyConfig)->render('TableLayout', array('layout' => $layout));
        }
    }

    /**
     * 编辑数据
     * @return html
     */
    public function edit()
    {
        if ($this->isPost())
        {

        }
        else
        {
            $this->load->library('FormHelper');
            $fh = new FormHelper;
            $data = array('name' => '测试姓名填充');
            list($formHeaderConfig, $formBodyConfig) = $this->_formSearchConfigProvider();
            // 合并数据
            $formBodyConfig = $fh->mergeData($formBodyConfig, $data);
            echo $fh->formCreater($formHeaderConfig, $formBodyConfig)->render('Table');
        }
    }

    /**
     * 删除数据
     * @return html
     */
    public function del()
    {

    }

    /**
     * 表单查询配置供给
     * @return array
     */
    protected function _formSearchConfigProvider()
    {
        $formHeaderConfig = 'id="pagerForm" data-toggle="ajaxsearch" action="table1.html" method="post"';
        $formBodyConfig = array(
            array('type' => 'radio', 'key' => 'sex', 'label' => '性别', 'data' => array('男' => 0, '女' => 1) ,'defaultValue' => 1, 'order' => 11, 'valid' => array()),
            array('type' => 'text', 'key' => 'name', 'label' => '姓名', 'data' => null ,'defaultValue' => 1, 'order' => 21),
            array('type' => 'checkbox', 'key' => 'staffs', 'label' => '员工选择', 'data' => array('张三'=>10, '李四' => 20, '王五' => 30) ,'defaultValue' => 10, 'order' => 31),
            array('type' => 'select', 'key' => 'years', 'label' => '年份选择', 'data' => array('1990年' => 1990, '1991年' => 1991) ,'defaultValue' => 1990, 'order' => 41),
            array('type' => 'file', 'key' => 'file', 'label' => '文件上传', 'defaultValue' => 'xxx.png', 'order' => 51),
            array('type' => 'textarea', 'key' => 'content', 'label' => '内容填写', 'data' => null ,'defaultValue' => '哇哈哈', 'order' => 61),
            array('type' => 'hidden', 'key' => 'hidden', 'label' => null, 'data' => null ,'defaultValue' => '1000', 'order' => 71),
            array('type' => 'date', 'key' => 'date', 'label' => '日期', 'data' => null ,'defaultValue' => '2015/06/30', 'order' => 81),
            array('type' => 'button', 'key' => 'submit', 'label' => '提交', 'order' => 999),
        );
        return array($formHeaderConfig, $formBodyConfig);
    }

    /**
     * 表单配置供给
     * @return array
     */
    protected function _formConfigProvider()
    {
        $formHeaderConfig = 'id="pagerForm" data-toggle="ajaxsearch" action="table1.html" method="post"';
        $formBodyConfig = array(
            array('type' => 'radio', 'key' => 'sex', 'label' => '性别', 'data' => array('男' => 0, '女' => 1) ,'defaultValue' => 1, 'order' => 11, 'valid' => array()),
            array('type' => 'text', 'key' => 'name', 'label' => '姓名', 'data' => null ,'defaultValue' => 1, 'order' => 21),
            array('type' => 'checkbox', 'key' => 'staffs', 'label' => '员工选择', 'data' => array('张三'=>10, '李四' => 20, '王五' => 30) ,'defaultValue' => 10, 'order' => 31),
            array('type' => 'select', 'key' => 'years', 'label' => '年份选择', 'data' => array('1990年' => 1990, '1991年' => 1991) ,'defaultValue' => 1990, 'order' => 41),
            array('type' => 'file', 'key' => 'file', 'label' => '文件上传', 'defaultValue' => 'xxx.png', 'order' => 51),
            array('type' => 'textarea', 'key' => 'content', 'label' => '内容填写', 'data' => null ,'defaultValue' => '哇哈哈', 'order' => 61),
            array('type' => 'hidden', 'key' => 'hidden', 'label' => null, 'data' => null ,'defaultValue' => '1000', 'order' => 71),
            array('type' => 'date', 'key' => 'date', 'label' => '日期', 'data' => null ,'defaultValue' => '2015/06/30', 'order' => 81),
            array('type' => 'button', 'key' => 'submit', 'label' => '提交', 'order' => 999),
        );
        return array($formHeaderConfig, $formBodyConfig);
    }

    /**
     * 表单配置供给
     * @return array
     */
    protected function _formConfigAddProvider()
    {
        $formHeaderConfig = 'id="pagerForm" data-toggle="ajaxsearch" action="table1.html" method="post"';

        $formBodyConfig = array(
            array('type' => 'radio', 'key' => 'sex', 'label' => '性别', 'data' => array('男' => 0, '女' => 1) ,'defaultValue' => 1, 'order' => 11, 'valid' => array()),
            array('type' => 'text', 'key' => 'name', 'label' => '姓名', 'data' => null ,'defaultValue' => 1, 'order' => 21),
            array('type' => 'checkbox', 'key' => 'staffs', 'label' => '员工选择', 'data' => array('张三'=>10, '李四' => 20, '王五' => 30) ,'defaultValue' => 10, 'order' => 31),
            array('type' => 'select', 'key' => 'years', 'label' => '年份选择', 'data' => array('1990年' => 1990, '1991年' => 1991) ,'defaultValue' => 1990, 'order' => 41),
            array('type' => 'file', 'key' => 'file', 'label' => '文件上传', 'defaultValue' => 'xxx.png', 'order' => 51),
            array('type' => 'textarea', 'key' => 'content', 'label' => '内容填写', 'data' => null ,'defaultValue' => '哇哈哈', 'order' => 61),
            array('type' => 'hidden', 'key' => 'hidden', 'label' => null, 'data' => null ,'defaultValue' => '1000', 'order' => 71),
            array('type' => 'date', 'key' => 'date', 'label' => '日期', 'data' => null ,'defaultValue' => '2015/06/30', 'order' => 81),
            array('type' => 'date', 'key' => 'date', 'label' => '日期', 'data' => null ,'defaultValue' => '2015/06/30', 'order' => 81),
            array('type' => 'date', 'key' => 'date', 'label' => '日期', 'data' => null ,'defaultValue' => '2015/06/30', 'order' => 81),
            array('type' => 'button', 'key' => 'submit', 'label' => '提交', 'order' => 999),
        );

        $layout = [
            [[], [], ['size' => 2]],
            [[], [], [], []],
            [[], ['size' => 3]],
            [['size' => 4]]
        ];
        return array($formHeaderConfig, $formBodyConfig, $layout);
    }

    public function test()
    {
        $this->load->library('AccountHelper');
        $ah = new AccountHelper;
        $data = $ah->loginIn('admin', 'It131415', 'xxx2d');
        print_r($data);
    }
}