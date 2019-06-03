<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 系统用户模型
 * @author  loonfiy
 * +2016-03-11
 */
class AccountModel extends MY_Model
{
    public function userinfo($username)
    {
        $sql    = "SELECT *
            FROM md_user
            WHERE username = ? AND is_show = 1";
        $result = $this->db->query($sql,[$username])->row_array();
        return  $result;
    }

    public function getRules($roleid)
    {
        $sql    = "SELECT rule_id,is_finance,look_city FROM md_auth_role WHERE id = ?";
        $result = $this->db->query($sql,[$roleid])->row_array();
        $result['rule_id'] = !empty($result) ? explode(',',trim($result['rule_id'],',')) : [];
        return  $result;
    }

}
