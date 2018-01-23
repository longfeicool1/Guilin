<?php
/**
 * 权限
 * @author  liuweilong
 * +2016-03-17 created
 **/
class AuthHelper
{   
    /**
     * 构造
     */
    public function __construct()
    {
        get_instance()->load->model("manage/Menu_model", "ManageMenu_model");
    }

    /**
     * 取菜单ID
     * @param  [type] $url [description]
     * @return [type]      [description]
     */
    public function getMenuId($url)
    {
        $url = str_replace('//', '/', '/'.$url);
        $data = get_instance()->ManageMenu_model->getRow(array('url' => $url), 'id');
        return empty($data) ? 0 : $data['id'];
    }

    /**
     * 判断是否允许访问
     * @param  integer  $accountData [description]
     * @param  string  $url         [description]
     * @return boolean              -1 不允许
     */
    public function isAllow($account, $url)
    {
        $url = str_replace('//', '/', '/'.$url);
        $data = get_instance()->ManageMenu_model->getRow(array('url' => $url), 'id');
        if (empty($account))
        {
            return -1;
        }

        if (empty($data))
        {
            if (in_array(1, (array) $account['group']))
            {
                return 0;
            }
        }
        else
        {
            // echo '<pre>';
            // echo $data['id'], $url;
            // print_r($account);
            // ID=1为管理员组
            if (in_array(1, (array) $account['group']) || in_array($data['id'], (array) $account['allow_menu_id']))
            {
                return $data['id'];
            }
        }
        return -1;
    }

    /**
     * 生成菜单
     * @param  [type]  $accountData [description]
     * @param  integer $pid         [description]
     * @return [type]               [description]
     */
    public function createTree($account)
    {
        $html = array();
        $data = get_instance()->ManageMenu_model->getList('visable=1', 'id, pid, title, icon, type, url', 'sorted ASC');
        $html = array();
        $menu = array();
        
        // 过滤没有权限的菜单
        foreach ($data as $k => $v)
        {
            // ID=1为管理员组
            if (in_array(1, $account['group']) || in_array($v['id'], $account['allow_menu_id']))
            {
                $menu[] = $v;
            }
        }

        foreach ($this->_getData($menu, 1) as $k => $v)
        {
            $liCon = $this->_getli($menu, $v['id']);
            $icon = !empty($v['icon']) ? $v['icon'] : 'table';
            $html[] = '<li class="'.($k == 0 ? 'active' : '').'"><a href="javascript:;" data-toggle="slidebar"><i class="fa fa-'.$icon.'"></i>'.$v['title'].'</a>
                <div class="items hide" data-noinit="true">
                <ul id="bjui-hnav-tree-'.$v['id'].'" class="ztree ztree_main" data-toggle="ztree" data-on-click="MainMenuClick"  data-faicon="check-square-o">
                '.join("\n", $liCon).'
                </ul>
                </div>
            </li>';
        }

        return join("\n", $html);
    }

    /**
     * 生成li
     * @param  [type] $menus [description]
     * @param  [type] $pid   [description]
     * @return [type]        [description]
     */
    protected function _getli($menus, $pid)
    {
        $output = array();
        $data = $this->_getData($menus, $pid);
        if (empty($data))
        {
            return array();
        }

        foreach ($data as $k => $v)
        {
            $url = $v['type'] == 1 ? '' : 'data-url="'.$v['url'].'"';
            $icon = !empty($v['icon']) ? $v['icon'] : 'list';
            $output[] = '<li data-id="'.$v['id'].'" data-pid="'.$v['pid'].'" '.$url.' data-tabid="id'.$v['id'].'" data-faicon="'.$icon.'" title="'.$v['title'].'">'.$v['title'].'</li>';
            if ($v['type'] == 1)
            {
                $output = array_merge($output, $this->_getli($menus, $v['id']));
            }
        }
        return $output;
    }

    /**
     * 将数据按照缩进简单排列
     * @param  [type]  $tree   [description]
     * @param  integer $rootId [description]
     * @param  integer $level  [description]
     * @return [type]          [description]
     */
    public function data2arr($tree, $rootId = 0, $level = 0)
    {  
        $data = array();
        foreach($tree as $leaf)
        {  
            if($leaf['pid'] == $rootId)
            {  
                //echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) . $leaf['id'] . ' ' . $leaf['name'] . '<br/>';
                $data[] = $leaf;
                foreach($tree as $l)
                {  
                    if($l['pid'] == $leaf['id'])
                    {  
                        $data = array_merge($data, $this->data2arr($tree, $leaf['id'], $level + 1));
                        break;  
                    }  
                }  
            }  
        }
        return $data;
    }  

    /**
     * 取得OrgIds
     * @param  integer $account 
     * @return array
     */
    public function getOrgIds($accountId)
    {
        get_instance()->load->Model("default/PmOrgModel", "PmOrgModel");
        $ids = array();
        $data = get_instance()->session->userdata('org');
        if (empty($data))
        {
            $orgAccountdata = get_instance()->PmOrgAccountModel->getList(array('account_id' => $accountId));

            if (empty($orgAccountdata))
            {
                return array();
            }

            // 所有权限
            // if ($orgAccountdata['org_id'] == 1)
            // {
            //     return array(1);
            // }
            $orgIds = array();
            foreach ($orgAccountdata as $k => $v)
            {
                if ($v['org_id'] == 1)
                {
                    return array(1);
                }
                $orgIds[] = $v['org_id'];
            }

            $orgData = get_instance()->PmOrgModel->lists(array(), 1, 1000);
            $selfData = array();
            foreach ($orgData as $v)
            {
                if (in_array($v['id'], $orgIds))
                {
                    $selfData[$v['id']] = $v;
                    // break;
                }
            }

            $data = array();
            foreach ($orgIds as $k => $v) 
            {
                $data = array_merge($data, $this->data2arr($orgData, $v, 0));
                if (isset($selfData[$v]))
                {
                    $data = array_merge(array($selfData[$v]), $data);
                }
            }
            
            // $data = array_merge(array($selfData), $data);
            // echo '<pre>';
            // print_r($orgIds);
            // print_r($data);
            // exit;
            get_instance()->session->set_userdata('org', $data);
        }

        if (!empty($data))
        {
            foreach ($data as $k => $v)
            {
                $ids[] = $v['id'];
            }
        }

        return $ids;
    }

    /**
     * orgids
     * @var array
     */
    protected $_orgIds = array();

    /**
     * 判断是否有权力
     * @return boolean [description]
     */
    public function isAllowOrg($accountId, $targetOrgId)
    {
        if (empty($this->_orgIds))
        {
            $this->_orgIds = $this->getOrgIds($accountId);
        }

        if (in_array(1, $this->_orgIds) || in_array($targetOrgId, $this->_orgIds))
        {
            return true;
        }
        
        return false;
    }

    /**
     * 判断是否有权力(最高权力)
     * @return boolean [description]
     */
    public function isAllowOrgByManager($accountId, $targetOrgId)
    {
        if (empty($this->_orgIds))
        {
            $this->_orgIds = $this->getOrgIds($accountId);
        }

        if (in_array(1, $this->_orgIds))
        {
            return true;
        }
        
        return false;
    }
    /**
     * 取数据
     * @param  [type]  $menus [description]
     * @param  integer $pid   [description]
     * @return [type]         [description]
     */
    protected function _getData($menus, $pid = 1)
    {  
        $output = array();
        foreach ($menus as $k => $v)
        {
            if ($v['pid'] == $pid)
            {
                $output[] = $v;
            }
        }
        return $output;
    }
    
}