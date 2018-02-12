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
        //排除所有分类为0的业务员
        foreach ($rules as $k => $v) {
            if (array_sum($v) == 0) {
                unset($rules[$k]);
            }
        }
        if (empty($rules)) {
            return ['errcode' => 300,'errmsg' => '请选择要分配的业务员'];
        }
        //每个等级(ABC)要分的人
        $Auser = [];
        $Buser = [];
        $Cuser = [];
        foreach ($rules as $k => $v) {
            if ($v['A'] > 0) {
                $Auser[$k] = $v['A'];
            }
            if ($v['B'] > 0) {
                $Buser[$k] = $v['B'];
            }
            if ($v['C'] > 0) {
                $Cuser[$k] = $v['C'];
            }
        }
        // D($Auser);
        //从数据库里取出相应的数据
        $errmsg    = '';
        $allReturn = [];
        $peopleA   = $this->getPeople('A',array_sum($Auser));
        $returnA   = $this->givePeople('A',$Auser,$peopleA);
        if (!empty($returnA['errmsg'])) {
            $errmsg .= $returnA['errmsg'];
        } else {
            $allReturn = $this->mergeArray($returnA,$allReturn);
        }
        $peopleB = $this->getPeople('B',array_sum($Buser));
        $returnB = $this->givePeople('B',$Buser,$peopleB);
        if (!empty($returnB['errmsg'])) {
            $errmsg .= $returnB['errmsg'];
        } else {
            $allReturn = $this->mergeArray($returnB,$allReturn);
        }
        $peopleC = $this->getPeople('C',array_sum($Cuser));
        $returnC = $this->givePeople('C',$Cuser,$peopleC);
        if (!empty($returnC['errmsg'])) {
            $errmsg .= $returnC['errmsg'];
        } else {
            $allReturn = $this->mergeArray($returnC,$allReturn);
        }
        foreach ($allReturn as $uid => $customIds) {
            $this->db->where_in('id',$customIds)->update('md_custom_list',['firstOwer' => $uid,'give_time' => date('Y-m-d H:i:s')]);
        }
        return ['errcode' => 200,'errmsg'=> '分配成功'];
        // D($allReturn);
    }

    /**
     * [mergeArray 合并二维数组]
     * @param  [type] $array1 [description]
     * @param  [type] $array2 [description]
     * @return [type]         [description]
     */
    public function mergeArray($array1,$array2)
    {
        foreach ($array1 as $k => $v) {
            if (!empty($array2[$k])) {
                $array2[$k] = array_merge($v,$array2[$k]);
            } else {
                $array2[$k] = $v;
            }
        }
        return $array2;
    }

    /**
     * [givePeople 轮询分配到每个业务员名下]
     * @param  [type] $dataLevel [description]
     * @param  [type] $users     [description]
     * @param  array  $people    [description]
     * @return [type]            [description]
     */
    public function givePeople($dataLevel,$users,$people = [])
    {
        if (empty($people)) {
            return ['errmsg' => "{$dataLevel}等级用户不足,无法分配"];
        }
        $result = [];
        $i = 0;
        while (true) {
            if (empty($people) || empty($users)) {
                break;
            }
            foreach ($users as $uid => $num) {
                if (empty($people)) {
                    break;
                }
                //出栈操作得到一条记录
                $gift = array_pop($people);
                //分配给客户
                $result[$uid][] = $gift;
                //分配后数量减一,并且等于0则舍弃它
                if ($num - 1 == 0) {
                    unset($users[$uid]);
                } else {
                    $users[$uid] = $num - 1;
                }
            }
        }
        return $result;
    }

    protected function getPeople($dataLevel,$number)
    {
        if ($number <= 0) {
            return [];
        }
        $sql    = "SELECT id FROM md_custom_list WHERE dataLevel = ? AND isShow = 1 AND firstOwer = 0 ORDER BY id ASC LIMIT ?";
        $result = $this->db->query($sql,[$dataLevel,$number])->result_array();
        return !empty($result) ? array_column($result,'id') : [];
    }

    public function toReallot($data)
    {
        $ids    = explode(',',trim($data['ids'], ','));
        $update = ['firstOwer' => $data['firstOwer']];
        if ($data['meetTime']) {
            $update['meetTime'] = $data['meetTime'];
        }
        if ($this->db->where_in('id',$ids)->update('md_custom_list', $update) !== false) {
            return ['errcode' => 200,'errmsg'=> '重分配成功'];
        }
        return ['errcode' => 300,'errmsg'=> '重分配失败'];
    }
}