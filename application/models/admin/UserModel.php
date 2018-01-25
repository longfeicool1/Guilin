<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 系统菜单模型
 * @author  liuweilong
 * +2016-03-16
 */
class UserModel extends MY_Model
{
    public function getUserList()
    {
        if($this->userinfo['uid'] != 1){
            $this->db->where(['add_id' => $this->userinfo['uid']]);
        }
        return $this->db->get('md_user')->result_array();
    }

    public function getRoleList()
    {

    }
}
