<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 菜单
 * @author  liuweilong
 * +2016-03-15
 */
class Menu extends MY_Controller
{   
    /**
     * 构造
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model("manage/Menu_model", "ManageMenu_model");
    }

    /**
     * 默认Action
     * @return html
     */
    public function index()
    {
        $data = $this->ManageMenu_model->getList('', 'id, pid as pId, concat(title, \' s:\', sorted, \' v:\', visable, \' t:\', type, \' id:\', id) as name', $orderBy = 'sorted ASC', $groupBy = '', 0, 1000);
        $str = json_encode($data);
        echo '
        <div class="bjui-pageContent">
            <script type="text/javascript">
                function ztree_returnjson() {
                    //return [{id:1,pid:0,name:\'表单元素\',children:[{id:10,pId:1,name:\'按钮\'},{id:11,pId:1,name:\'文本框\'}]}]
                    return '.$str.';
                }

                function addChild(id) {
                    $(this).bjuiajax(\'doLoad\', {target: $("#ttt"), url: "manage/menu/add?id=" + id});
                }

                function del(id) {
                    $(this).bjuiajax(\'doAjax\', {url: "manage/menu/del?id=" + id});
                }

                function edit(id) {
                    $(this).bjuiajax(\'doLoad\', {target: $("#ttt"), url: "manage/menu/edit?id=" + id});
                }

                function onClick(event, treeId, treeNode, clickFlag) {
                    //alert(treeNode.id);
                    edit(treeNode.id);
                }
                edit(1);
            </script>

            <div class="row">

                <div class="col-md-3">
                    <ul 
                    id="ztree-test-demo2" 
                    class="ztree"
                    data-toggle="ztree" data-options="{nodes:\'ztree_returnjson\', expandAll: true, simpleData: true, setting: {callback: {
                onClick: onClick
            }}}"></ul>
                </div>

                <div class="col-md-5" id="ttt" style="height:400px;">

                </div>
            </div>
        </div>
        ';
    }

    /**
     * [del description]
     * @return [type] [description]
     */
    public function del()
    {
        $id = (int) DataExecuter::get($_GET, 'id', 0);
        $success = false;
        $count = $this->ManageMenu_model->getCount(array('id' => $id));
        if ($count > 0)
        {
            $childCount = $this->ManageMenu_model->getCount(array('pid' => $id));
            if ($childCount == 0)
            {
                $this->ManageMenu_model->delete(array('id' => $id));
                $success = true;
            }
            else
            {
                $errors = '子节点不为空';
            }
        }
        else
        {
            $errors = '菜单不存在';
        }
        echo $this->bjuiRes($success, $success ? "操作成功" : $errors);
    }

    /**
     * 新增
     * @return html
     */
    public function add()
    {
        $pid = (int) DataExecuter::get($_GET, 'id', 0);
        $ph = new PasswordHash;
        $v = new DooValidator;
        $fh = new FormHelper;

        $data = $this->ManageMenu_model->getRow(array('id' => $pid));
        if (!empty($_POST) && $pid > 0)
        {
            $success = true;
            $errors = array();
            $rules = array(
                'title' => array(
                                array('required', '菜单名必须填写'),
                             ),
            );
            
            if($errors = $v->validate($_POST, $rules))
            {
                $success = false;
            }
            else
            {
                $_POST['pid'] = $pid;
                $_POST['account_id'] = $this->_account['id'];
                $_POST['created'] = date('Y-m-d H:i:s');
                $this->ManageMenu_model->add($_POST);
            }
            echo $this->bjuiRes($success, $success ? "操作成功" : $v->errorToString($errors));
        }
        else
        {
            list($formHeaderConfig, $formBodyConfig) = $this->_formConfigProvider($fh, '/manage/menu/add?id='.$pid , array('pTitle' => $data['title']), 'add');
             $formSearch = $fh->formCreater($formHeaderConfig, $formBodyConfig)->render('Table', 
                array('title' => '添加子菜单')
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
        $id = (int) DataExecuter::get($_GET, 'id', 1);
        $fh = new FormHelper;
        if (!empty($_POST))
        {
            $_POST['account_id'] = $this->_account['id'];
            $res = $this->ManageMenu_model->edit(array('id' => $id), $_POST);
            echo $this->bjuiRes(true, "操作成功");
        }
        else
        {
            $data = $this->ManageMenu_model->getRow(array('id' => $id));
            list($formHeaderConfig, $formBodyConfig) = $this->_formConfigProvider($fh, '/manage/menu/edit?id='.$id , $data, 'edit', $id);
            $formSearch = $fh->formCreater($formHeaderConfig, $formBodyConfig)->render('table', 
                array('title' => '编辑')
            );
            echo $formSearch;
        }
    }
  
    /**
     * 表单新增编辑配置供给
     * @return array
     */
    protected function _formConfigProvider(FormHelper $fh, $action = '/manage/menu/edit', $data = array(), $type = 'edit', $id = 0)
    {
        $formHeaderConfig = array('data-toggle' => 'validate', 'action' => $action, 'method' => 'post');
        $formBodyConfig = array(
            array('type' => 'text', 'key' => 'title', 'label' => '菜单名称', 'data' => null ,'defaultValue' => '', 'order' => 1),
            array('type' => 'select', 'key' => 'type', 'label' => '类型', 'data' => array(1 => '目录', 2 => '页面') ,'defaultValue' => 1, 'order' => 2),
            array('type' => 'text', 'key' => 'icon', 'label' => '图标', 'data' => null ,'defaultValue' => '', 'order' => 11, 'attr' => array('style'=>"width:95px")),
            array('type' => 'text', 'key' => 'sorted', 'label' => '排序值', 'data' => null ,'defaultValue' => '', 'order' => 31, 'attr' => array('style'=>"width:95px")),
            array('type' => 'text', 'key' => 'url', 'label' => 'URL地址', 'data' => null ,'defaultValue' => '', 'order' => 41, 'attr' => array('style'=>"")),
            array('type' => 'radio', 'key' => 'visable', 'label' => '显示', 'data' => array(0=>'否', 1=> '是') ,'defaultValue' => 1, 'order' => 41, 'attr' => array('style'=>"")),
            //array('type' => 'hidden', 'key' => 'id', 'defaultValue' => -1, 'order' => 888),
            
        );
        
        if ($type == 'edit' && $id == 1)
        {
            $formBodyConfig[0]['type'] = 'show';
        }

        if ($type == 'edit')
        {
            $formBodyConfig[1]['attr'] = array('disabled' => 'disabled');
        }

        if (isset($data) && !empty($data))
        {
            $formBodyConfig = $fh->mergeData($formBodyConfig, $data);
        }

        if ($type == 'edit' && isset($data['type']) && $data['type'] == 1)        
        {
            $formBodyConfig[] = array('type' => 'block','order' => 999, 'content' => '<tr><td align="center" colspan="2"><div class="btn-group" role="group">
                        <button type="button" class="btn btn-blue"  onclick="addChild('.$id.')" data-icon="plus">添加子节点</button>
                        <button type="button" class="btn btn-red" onclick="del('.$id.')" data-icon="remove">删除</button>
                        <button type="submit" class="btn btn-default" data-icon="save">保存</button>
                    </div></td></tr>');
        }
        else if($type == 'edit')
        {
            $formBodyConfig[] = array('type' => 'block','order' => 999, 'content' => '<tr><td align="center" colspan="2"><div class="btn-group" role="group">
                        <button type="button" class="btn btn-red" onclick="del('.$id.')" data-icon="remove">删除</button>
                        <button type="submit" class="btn btn-default" data-icon="save">保存</button>
                    </div></td></tr>');
        }
        else
        {
            $formBodyConfig[] = array('type' => 'block','order' => 999, 'content' => '<tr><td align="center" colspan="2"><div class="btn-group" role="group">
                        <button type="submit" class="btn btn-default" data-icon="save">保存</button>
                    </div></td></tr>');
        }

        if ($type == 'add')
        {
            $formBodyConfig[] = array('type' => 'show', 'key' => 'title', 'label' => '父级名称', 'data' => null ,'defaultValue' => $data['pTitle'], 'order' => 0, 'attr' => array('style'=>"width:95px"));
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
        $formBodyConfig = array(
            array('type' => 'password', 'key' => 'password', 'label' => '新密码', 'data' => null ,'defaultValue' => '', 'order' => 1, 'attr' => array('style'=>"width:95px", 'data-rule' => 'require;')),
            array('type' => 'hidden', 'key' => 'id', 'defaultValue' => -1, 'order' => 888)
        );
        if (isset($data) && !empty($data))
        {
            $formBodyConfig = $fh->mergeData($formBodyConfig, $data);
        }
        return array($formHeaderConfig, $formBodyConfig);
    }
}