<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 系统权限模型
 * @author  loonfiy
 * +2016-03-16
 */
class AuthModel extends MY_Model
{
    public function createMenu($menuid)
    {
        $rule1    = $this->db
            ->where_in('id',$menuid)
            ->order_by('order_num ASC')
            ->get_where('md_auth_rule',['item_type' => 1,'isshow' => 1])
            ->result_array();
        $rule2    = $this->db
            ->where_in('id',$menuid)
            ->order_by("concat(path,',',id) asc")
            ->get_where('md_auth_rule',['isshow' => 1,'item_type' => 2])
            ->result_array();
        foreach($rule1 as $k=>$v){                                                                      //对level 1的权限循环
            foreach($rule2 as $y){
                $path = explode(',', $y['path']);
                if(in_array($v['id'], $path)){
                    $rule1[$k]['child'][]       =   $y;
                }
            }
        }
        return $rule1;
    }

    public function toGetAuth($condition = '')
    {
        if ($condition) {
            $ruleid = implode(',',$this->userinfo['menu_id']);
            $sql = "SELECT *
                FROM `md_auth_rule`
                WHERE `id` != 17 AND id in({$ruleid})
                ORDER BY concat(path, ',', id) asc";
        } else {
            $sql = "SELECT *
                FROM `md_auth_rule`
                WHERE `id` != 17
                ORDER BY concat(path, ',', id) asc";
        }
        $nodes = $this->db->query($sql)->result_array();
        return $nodes;
    }

    public function toAddAuth($info)
    {
        if ($info['item_type'] == 1) {
            $info['parent_id'] = 0;
            $info['path']      = 0;
        } else {
            $info['parent_id'] = trim(strrchr($info['path'],','),',');
        }
        $this->db->insert('md_auth_rule',$info);
        $insertID = $this->db->insert_id();
        if($insertID){
            $sql = "UPDATE md_auth_role SET rule_id = concat(rule_id,',',{$insertID}) WHERE id = 1";
            $this->db->query($sql);
            return ['errcode' => 200,'errmsg' => '操作成功'];
        }
        return ['errcode' => 300,'errmsg' => '添加失败'];
    }

    public function getRoleList(){
        if($this->userinfo['uid'] != 1){
            $this->db->where(['uid' => $this->userinfo['uid']]);
        } else {
            $this->db->where(['uid >' => 0]);
        }
        $role = $this->db->get('md_auth_role')->result_array();
        return $role;
        // $rule   =   $this->rule->where("id in($this->auth) and id !=17")->order("concat(path,',',id) asc")->select();
    }

    public function toAddRole($data,$roleId)
    {
        $data['rule_id'] = implode(',', $data['rule_id']);
        $data['uid']     = $this->userinfo['uid'];
        if($roleId){            //修改操作（判断修改时和添加时是否同一账户）
            if($this->db->update('md_auth_role',$data,['id' => $roleId]) !== false){
                return ['errcode' => 200,'errmsg' => '操作成功'];
            }else{
                return ['errcode' => 300,'errmsg' => '操作失败'];
            }
        }else{                                                                                                  //添加操作
            if($this->db->insert('md_auth_role',$data)){
                return ['errcode' => 200,'errmsg' => '操作成功'];
            }else{
                return ['errcode' => 300,'errmsg' => '操作失败'];
            }
        }
    }

    public function findRole($id)
    {
        return $this->db->get_where('md_auth_role',['id' => $id])->row_array();
    }

    public function changeStatus($id)
    {
        $result =  $this->findRole($id);
        if($result['isshow'] == 1){
            $n   = 0;
        }
        if($result['isshow'] == 0){
            $n   = 1;
        }
        if(!empty($id) && $this->db->where(['id' => $id])->update('md_auth_role',['isshow' => $n])){
            return ['errcode' => 200,'errmsg' => '操作成功','n' => $n];
        }else{
            return ['errcode' => 300,'errmsg' => '操作失败'];
        }
    }

    public function toDelRole($id)
    {
        if (!empty($id) && $this->db->delete('md_auth_role',['id' => $id])) {
            return ['errcode' => 200,'errmsg' => '删除成功'];
        }else{
            return ['errcode' => 300,'errmsg' => '删除失败'];
        }
    }
}
