<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class OutsideModel extends MY_Model
{
    protected $env;
    public function insertCustom($insert)
    {
        if ($this->env == 1) {
            return $this->db->insert('test_base.md_custom_list',$insert);
        } else if($this->env == 2){
            return $this->db->insert('md_custom_list',$insert);
        } else {
            return ['errcode' => 10010,'errmsg' => '该APPID已失效'];
        }
    }

    public function checkAppid($appid)
    {
        $result = $this->db->get_where('md_config',['appid' => $appid])->row_array();
        if (!empty($result)) {
            $this->env = $result['status'];
        }
        return $result;
    }
}