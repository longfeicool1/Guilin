<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 模型生成器控制器
 * @author  liuweilong
 * +2016-03-04
 */
class Modelc extends MY_Controller
{

    /**
     * 数据列表
     * @return html
     */
    public function index()
    {
        $content = '';
        $this->load->library('ModelCreater', null, 'ModelCreater');
        if ($_POST)
        {
            if (!empty($_POST['dbs']))
            {
                $list = $this->ModelCreater->getDBList();
                foreach ($_POST['dbs'] as $v)
                {
                    $content .= $this->ModelCreater->run($list[$v]);
                }
            }
            
            echo $content;
            // echo $this->bjuiRes(!empty($content) ? true : false, '操作成功');
        }
        else
        {
            $fh = new FormHelper;
            list($formHeaderConfig, $formBodyConfig) = $this->_formConfigProvider();
            echo $fh->formCreater($formHeaderConfig, $formBodyConfig)->render('Table');
        }

    }

    /**
     * 表单配置供给
     * @return array
     */
    protected function _formConfigProvider()
    {
        $list = $this->ModelCreater->getDBList();
        $formHeaderConfig = array('id' => 'pagerForm','target' => '_blank', 'action' => '/developer/modelc', 'method' => 'post');
        $formBodyConfig = array(
            array('type' => 'muticheckbox', 'key' => 'dbs', 'label' => '生成库', 'data' => $list ,'defaultValue' => 0, 'order' => 11, 'valid' => array()),
            array('type' => 'submit', 'key' => 'submit', 'label' => '提交', 'order' => 999),
        );
        return array($formHeaderConfig, $formBodyConfig);
    }
   
}