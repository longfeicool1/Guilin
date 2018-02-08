<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 功能列表模型
 * @author  loonfiy
 * +2018-02-06
 */
class FunModel extends MY_Model
{
    public function getCustomTotal()
    {
        $default = [
            'A' => 0,
            'B' => 0,
            'C' => 0,
        ];
        $sql = "SELECT COUNT(*) AS tot,dataLevel FROM md_custom_list
            WHERE firstOwer = 0 AND isShow = 1
            GROUP BY dataLevel";
        $result = $this->db->query($sql)->result_array();
        $mrs = [];
        foreach ($result as $v) {
            $mrs[$v['dataLevel']] = $v['tot'];
        }
        return array_merge($default,$mrs);
        // print_r(array_merge($default,$mrs));die;
    }

    public function getSalesman()
    {
        $sql = "SELECT a.uid,a.name,b.name AS parentName FROM md_user a
            JOIN md_user b ON a.parent_id = b.uid
            WHERE FIND_IN_SET(?,a.path) AND a.position = 5 AND a.is_show = 1";
        $result = $this->db->query($sql,[$this->userinfo['uid']])->result_array();
        $return = [];
        foreach ($result as $v) {
            $return[$v['parentName']][] = ['uid' => $v['uid'],'name' => $v['name']];
        }
        return $return;
    }

    public function toAllot($rules)
    {
        $rules   = $rules['list'];
        $peopleA = array_sum(array_column($rules,'A'));
        $peopleB = array_sum(array_column($rules,'B'));
        $peopleC = array_sum(array_column($rules,'C'));
        $realA   = 0;
        $realB   = 0;
        $realC   = 0;
        $resultA = $this->getPeople('A',$peopleA);
        $resultB = $this->getPeople('B',$peopleB);
        $resultC = $this->getPeople('C',$peopleC);
        // print_r($peopleC);die;
        $sql     = "";
    }

    protected function getPeople($dataLevel,$number)
    {
        if ($number <= 0) {
            return [];
        }
        $sql    = "SELECT * FROM md_custom_list WHERE dataLevel = ? AND isShow = 1 AND firstOwer = 0 ORDER BY id ASC LIMIT ?";
        $result = $this->db->query($sql,[$dataLevel,$number])->result_array();
        return $result;
    }
}