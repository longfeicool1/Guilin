<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 配置模型
 * @author  loonfiy
 * +2016-03-16
 */
class ConfigModel extends MY_Model
{
    public $statusName = [
        1 => '调试',
        2 => '上线',
        3 => '终止',
    ];

    public function getConfList($page,$size,$condition,$whereOr = [])
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $offset = ($page - 1) * $size;
        $result = $this->db
            ->limit($size,$offset)
            ->order_by('updated DESC,created DESC')
            ->get_where('md_config')
            ->result_array();
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            $result[$k]['xuhao']      = $n;
            $result[$k]['statusName'] = !empty($this->statusName[$v['status']]) ? $this->statusName[$v['status']] : '错误';
        }
        return $result;
    }

    public function getConfNum($condition)
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        return $this->db->count_all_results('md_config');
    }

    public function getConfName($id)
    {
        return $this->db->get_where('md_config',['id' => $id])->row_array();
    }

    public function toadd($id,$data)
    {
        $data['appid'] = strtoupper($data['appid']);
        $result = $this->db->get_where('md_config',['appid' => $data['appid']])->row_array();
        // D($this->db->last_query());
        if (!empty($id)) {
            if (!empty($result) && $result['id'] != $id) {
                return ['errcode' => 300, 'errmsg' => '合作商标识已存在'];
            }
            if ($this->db->update('md_config',$data,['id' => $id]) !== false) {
                return ['errcode' => 200, 'errmsg' => '操作成功'];
            }
        } else {
            if ($result) {
                return ['errcode' => 300, 'errmsg' => '合作商标识已存在'];
            }
            if ($this->db->insert('md_config',$data)) {
                return ['errcode' => 200, 'errmsg' => '操作成功'];
            }
        }
        return ['errcode' => 300, 'errmsg' => '操作失败'];
    }
}