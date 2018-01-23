<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 权限
 * @author  liuweilong
 * +2016-03-15
 */
class Auth extends MY_Controller
{

    /**
     * 构造
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model("manage/Menu_model", "ManageMenu_model");
        $this->load->model("manage/Auth_model", "ManageAuth_model");
        $this->load->model("manage/Group_model", "ManageGroup_model");
    }

    protected static $messageTemplate="路比提示：{code}（短信验证码），3分钟内有效。泄漏还有风险，如非您本人操作，请致电联系路比客服：0755-84357001";

    /**
     * 默认Action
     * @return html
     */
    public function index()
    {
        $groupId = (int) DataExecuter::get($_GET, 'groupId', 0);
        if (!empty($_POST))
        {
            $groupId = $_POST['group_id'];
            $this->ManageAuth_model->delete(array('group_id' => $groupId));
            $menus = explode(',', $_POST['menus']);
            $data = array();
            $time = date('Y-m-d H:i:s');
            if (!empty($menus))
            {
                foreach ($menus as $k => $v)
                {
                    $data[$k] = array();
                    $data[$k]['group_id'] = $groupId;
                    $data[$k]['auth'] = 1;
                    $data[$k]['created'] = $time;
                    $temp = explode('id:', $v);
                    $data[$k]['menu_id'] = $temp[1];
                }
            }

            if (!empty($data))
            {
                $this->ManageAuth_model->addBatch($data);
            }
            echo $this->bjuiRes(true,"操作成功");
        }
        else
        {
            $data = $this->ManageMenu_model->getList('', 'id, pid as pId, concat(title,\' id:\',id) as name', $orderBy = 'sorted ASC', $groupBy = '', 0, 1000);
            
            $group = $this->ManageGroup_model->getList('id != 1');
            $group = DataExecuter::keyToField($group, 'id', 'name');
            $groupId = $groupId <= 1 ? current(array_keys($group)) : $groupId;
            $menu = $this->ManageAuth_model->getList(array('group_id' => $groupId));
            $menu = DataExecuter::keyToField($menu, 'menu_id', 'auth');
            $menuValues = array();
            foreach ($data as $key => $val)
            {
                if (isset($menu[$val['id']]))
                {
                    $menuValues[] = $val['name'];
                    $data[$key]['checked'] = true;
                }
            }
            $str = json_encode($data);
            $menuValues = join(',', $menuValues);
            echo '<script type="text/javascript">
                     function ztree_returnjson() {
                                        return '.$str.';
                                    }
                    //选择事件
                    function S_NodeCheck(e, treeId, treeNode) {
                        var zTree = $.fn.zTree.getZTreeObj(treeId),
                            nodes = zTree.getCheckedNodes(true)
                        var ids = \'\', names = \'\'
                        
                        for (var i = 0; i < nodes.length; i++) {
                            ids   += \',\'+ nodes[i].id
                            names += \',\'+ nodes[i].name
                        }
                        if (ids.length > 0) {
                            ids = ids.substr(1), names = names.substr(1)
                        }
                        
                        var $from = $(\'#\'+ treeId).data(\'fromObj\')
                        
                        if ($from && $from.length) $from.val(names)
                    }
                    //单击事件
                    function S_NodeClick(event, treeId, treeNode) {
                        var zTree = $.fn.zTree.getZTreeObj(treeId)
                        zTree.checkNode(treeNode, !treeNode.checked, true, true)
                        event.preventDefault()
                    }
                    </script>
                    ';

            $fh = new FormHelper;

            list($formHeaderConfig, $formBodyConfig) = $this->_formConfigProvider($fh, '/manage/auth?groupId='.$groupId, $group, $menuValues, $groupId);
            $form = $fh->formCreater($formHeaderConfig, $formBodyConfig)->render('Table', 
                    array('title' => '权限配置')
            );
            echo $form;
        }
    }
    public function checkSendMsg()
    {
        $mobile = $this->input->post_get('mobile');

        if ($_POST)
        {
            $mobilecode =$this->session->userdata('mobilecode');
            if ($mobilecode != $_POST['code'])
            {
                echo $this->output_json('error');
            }
            var_dump($_POST);
            var_dump($mobilecode);
            die;
        }
        $this->ci_smarty->assign('mobile',$mobile);
        $this->ci_smarty->display('login/sendMsg.tpl');

    }

    public function sendMsg()
    {
        $mobile = $this->input->post_get('mobile');

        if (empty($mobile))
        {
            echo $this->output_json('error');
        }
        $code = rand(pow(10,(6-1)), pow(10,6)-1);

        $this->session->set_userdata('mobilecode',$code);
        $this->load->helper('sms');
        $m=(ENVIRONMENT=='development')?'13760061577':$mobile;
        log_message('info', "发送验证短信: file:account;method:sendMsg");
        sms_mobset($m, $this->getMsgContent($code));
        $this->session->set_userdata('isSendMsg',true);
        echo $this->output_json('ok');
        return;
    }
    /**
     * 短信内容
     * @param $code
     * @return mixed
     */
    public function getMsgContent($code)
    {
        return str_replace("{code}",$code,self::$messageTemplate );
    }


    /**
     * 表单配置供给
     * @return array
     */
    protected function _formConfigProvider(FormHelper $fh, $action = '/manage/auth', $group = array(), $menuValues = array(), $groupId = 0)
    {
        $formHeaderConfig = array('data-toggle' => 'validate', 'action' => $action, 'method' => 'post');
        $formBodyConfig = array(
            array('type' => 'select', 'key' => 'group_id', 'label' => '组', 'data' => $group ,'defaultValue' => $groupId, 'order' => 1, 'attr' => array('style'=>"")),
            array('type' => 'block', 'content' => '<tr>
                                        <td align="right">组选择：</td>
                                        <td>
                                            <input type="text" name="menus" 
                                            id="j_ztree_menus1" data-toggle="selectztree" value="'.$menuValues.'" size="18" data-tree="#j_select_tree1" readonly>
                                            <ul  data-options="{nodes:\'ztree_returnjson\', expandAll: false, simpleData: true}" id="j_select_tree1" class="ztree hide" data-toggle="ztree" data-expand-all="true" data-check-enable="true" data-on-check="S_NodeCheck" data-on-click="S_NodeClick">
                                            </ul>
                                        </td>
                                    </tr>', 'order' => 2),
            array('type' => 'submit', 'key' => 'submit', 'label' => '<i class="fa fa-save"></i>保存', 'defaultValue' => '', 'order' => 999, 'attr' => array('class'=>"btn btn-default")),
        );
        // if (isset($data) && !empty($data))
        // {
        //     $formBodyConfig = $fh->mergeData($formBodyConfig, $data);
        // }
        return array($formHeaderConfig, $formBodyConfig);
    }
}