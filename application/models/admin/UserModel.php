<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 系统用户模型
 * @author  loonfiy
 * +2016-03-16
 */
class UserModel extends MY_Model
{
    public function getUserList($page,$size,$condition)
    {
        if($this->userinfo['uid'] != 1){
            $this->db->where(['add_id' => $this->userinfo['uid']]);
        }
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $offset = ($page - 1) * $size;
        $result = $this->db->limit($size,$offset)->get('md_user')->result_array();
        return $result;
    }

    public function getUserCount($condition)
    {
        if($this->userinfo['uid'] != 1){
            $this->db->where(['add_id' => $this->userinfo['uid']]);
        }
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        return $this->db->count_all_results('md_user');
    }

    public function getRoleList()
    {
        $result = $this->db->select("id,role_name")->get_where('md_auth_role',['isshow' => 1])->result_array();
        return $result;
    }

    public function toAddUser($infos)
    {
        $err  = [];
        foreach ($infos as $info) {
            $r1 = $this->addOneUser($info);
            if ($r1['errcode'] == 300) {
                $err[] = $r1['errmsg'];
            }
        }
        if (!empty($err)) {
            return ['errcode' => 300,'errmsg' => implode('|',$err)];
        }
        return ['errcode' => 200,'errmsg' => '操作成功'];
    }

    public function addOneUser($info)
    {
        if (empty($info['username']) || empty($info['password'])) {
            return ['errcode' => 300,'errmsg' => '用户名或密码不能为空'];
        }
        $sql = "SELECT * FROM md_user WHERE username = ?";
        $rs1 = $this->db->query($sql,[$info['username']])->row_array();
        $dd  = [
            'username' => $info['username'],
            'role_id'  => $info['role_id'],
            'name'     => $info['name'],
            'sex'      => $info['sex'],
        ];
        if ($info['password'] != '密码已加密隐藏') {
            $dd['password'] = md5($info['username'] . md5($info['username'] . $info['password']));
        }
        if (!empty($info['act'])) {
            if (!empty($rs1)) {
                return ['errcode' => 300,'errmsg' => $info['username'].'用户名已存在'];
            }
            $id = $this->db->insert('md_user',$dd);
        } else {
            if (!empty($rs1) && $rs1['uid'] != $info['uid']) {
                return ['errcode' => 300,'errmsg' => $info['username'].'用户名已存在'];
            }
            $id = $this->db->update('md_user',$dd,['uid' => $info['uid']]);
        }
        if ($id !== false) {
            return ['errcode' => 200,'errmsg' => '操作成功'];
        } else {
            return ['errcode' => 300,'errmsg' => $info['username'].'操作失败'];
        }
    }

    public function toDelUser($uid)
    {
        if ($this->db->update('md_user',['is_show' => 2],['uid' => $uid])) {
            return ['errcode' => 200,'errmsg' => '删除成功'];
        }
        return ['errcode' => 300,'errmsg' => '删除失败'];
    }

}
