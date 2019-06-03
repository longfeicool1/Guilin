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
        if ($this->userinfo['role_id'] == 1 ) {
            $sql  = "SELECT COUNT(*) AS tot,dataLevel FROM md_custom_list
                WHERE firstOwer = 0 AND isShow = 1
                GROUP BY dataLevel";
        } else {
            $city = !empty($this->userinfo['city']) ? str_replace('市','',$this->userinfo['city']) : '';
            if (!$city) {
                return $default;
            }
            $sql  = "SELECT COUNT(*) AS tot,dataLevel FROM md_custom_list
                WHERE firstOwer = 0 AND isShow = 1 AND city LIKE '{$city}%'
                GROUP BY dataLevel";
        }
        $result = $this->db->query($sql)->result_array();
        // D($this->db->last_query());
        $mrs = [];
        foreach ($result as $v) {
            $mrs[$v['dataLevel']] = $v['tot'];
        }
        return array_merge($default,$mrs);
        // print_r(array_merge($default,$mrs));die;
    }

    public function getSalesman($position = 3)
    {
        if ($position == 3) {
            $sql = "SELECT
            a.uid,
            a.name,
            a.position
            FROM md_user a
            WHERE FIND_IN_SET(?,a.path) AND a.position >= {$position} AND a.is_show = 1
            ORDER BY concat(path, ',', uid)";
        } else {
            $sql = "SELECT
            a.uid,
            a.name,
            a.position
            FROM md_user a
            WHERE FIND_IN_SET(?,a.path) AND a.position >= {$position} AND a.is_show = 1
            ORDER BY position";
        }
        $result = $this->db->query($sql,[$this->userinfo['uid']])->result_array();
        // $return = [];
        foreach ($result as $k => $v) {
            if ($v['position'] == 3) {
                $result[$k]['positionName'] = '区';
            }
            if ($v['position'] == 4) {
                $result[$k]['positionName'] = '团';
            }
            if ($v['position'] == 2) {
                $result[$k]['positionName'] = '城';
            }
        }
        return $result;
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
        // D($rules);
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
        // 判断当前登录用户是否超出分配限制
        $res = $this->checkLimit(array_sum($Auser), array_sum($Buser), array_sum($Cuser));
        if (!empty($res['errcode'])) {
            return $res;
        }
        // D($Auser);
        //从数据库里取出相应的数据
        $errmsg    = '';
        $allReturn = [];
        $lastUsers = [];
        $peopleA   = $this->getPeople('A',array_sum($Auser));
        $returnA   = $this->givePeople('A',$Auser,$peopleA);
        $allReturn = $this->mergeArray($returnA[0],$allReturn);
        $lastUsers = $this->mergeArray($returnA[1],$lastUsers);

        $peopleB   = $this->getPeople('B',array_sum($Buser));
        $returnB   = $this->givePeople('B',$Buser,$peopleB);
        $allReturn = $this->mergeArray($returnB[0],$allReturn);
        $lastUsers = $this->mergeArray($returnB[1],$lastUsers);

        $peopleC   = $this->getPeople('C',array_sum($Cuser));
        $returnC   = $this->givePeople('C',$Cuser,$peopleC);
        $allReturn = $this->mergeArray($returnC[0],$allReturn);
        $lastUsers = $this->mergeArray($returnC[1],$lastUsers);
        // D($peopleC);
        $this->toUpdateLimit(count($peopleA), count($peopleB), count($peopleC));
        // die;
        foreach ($allReturn as $uid => $customIds) {
            $this->db->where_in('id',$customIds)->update('md_custom_list',['firstOwer' => $uid,'secOwer' => $uid,'give_time' => date('Y-m-d H:i:s')]);
        }

        //获取用户姓名
        $uids = array_keys($lastUsers);
        if (!$uids) {
            return ['errcode' => 201,'errmsg'=> '已足额分配成功'];
        }
        $user  = $this->db->select('name,uid')->where_in('uid',$uids)->get('md_user')->result_array();
        $nuser = [];
        foreach ($user as $v) {
            $nuser[$v['uid']] = $v['name'];
        }
        foreach ($lastUsers as $k => $v) {
            $lastUsers[$k]['name'] = $nuser[$k];
        }
        return ['errcode' => 200,'errmsg'=> '分配成功','result' => $lastUsers,'source' => $rules];
        // D($allReturn);
    }

    /**
     * [checkLimit 判断当前用户是否触发分配限制]
     * @param  [type] $sumA [description]
     * @param  [type] $sumB [description]
     * @param  [type] $sumC [description]
     * @return [type]       [description]
     */
    public function checkLimit($sumA = 0, $sumB = 0, $sumC = 0)
    {
        $limit = $this->getMyLimit();
        if ($limit['limit_num_a'] < ($sumA+$limit['send_num_a'])) {
            return ['errcode' => 300, 'errmsg' => '你分配的A级数据超出了分配限制'];
        }
        if ($limit['limit_num_b'] < ($sumB+$limit['send_num_b'])) {
            return ['errcode' => 300, 'errmsg' => '你分配的B级数据超出了分配限制'];
        }
        if ($limit['limit_num_c'] < ($sumC+$limit['send_num_c'])) {
            return ['errcode' => 300, 'errmsg' => '你分配的C级数据超出了分配限制'];
        }
        return true;
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
        // if (empty($people)) {
        //     return ['errmsg' => "{$dataLevel}等级用户不足,无法分配"];
        // }
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
        $lastUsers = [];
        foreach ($users as $k => $v) {
            $lastUsers[$k] = [$dataLevel => $v];
        }
        return [$result,$lastUsers];
    }

    protected function getPeople($dataLevel,$number)
    {
        if ($number <= 0) {
            return [];
        }
        if ($this->userinfo['role_id'] != 1) {
            if (!empty($this->userinfo['city'])) {
                $city = !empty($this->userinfo['city']) ? str_replace('市','',$this->userinfo['city']) : '';
                $sql  = "SELECT id FROM md_custom_list WHERE dataLevel = ? AND isShow = 1 AND firstOwer = 0 AND city LIKE '{$city}%' ORDER BY id ASC LIMIT ?";
            } else {
                return [];
            }
        } else {
            $sql    = "SELECT id FROM md_custom_list WHERE dataLevel = ? AND isShow = 1 AND firstOwer = 0 ORDER BY id ASC LIMIT ?";
        }
        $result = $this->db->query($sql,[$dataLevel,$number])->result_array();
        return !empty($result) ? array_column($result,'id') : [];
    }

    public function toReallot($data)
    {
        $idstr  = trim($data['ids'], ',');
        $ids    = explode(',',$idstr);
        // $sql = "UPDATE md_custom_list_copy2 SET secOwer =  firstOwer WHERE FIND_IN_SET(id,?) AND secOwer = 0";
        // $this->db->query($sql,$idstr);
        // D($this->db->last_query());
        $update = ['firstOwer' => $data['firstOwer'],'isAllot' => 2];
        if ($data['meetTime']) {
            $update['meetTime'] = $data['meetTime'];
        }

        if ($this->db->where_in('id',$ids)->update('md_custom_list', $update) !== false) {
            return ['errcode' => 200,'errmsg'=> '重分配成功'];
        }
        return ['errcode' => 300,'errmsg'=> '重分配失败'];
    }

    public function getLimitInfo()
    {
        return $this->db->get_where('md_tuan_config')->result_array();
    }

    public function toUpdateLimit($numa = 0, $numb = 0, $numc = 0)
    {
        $boolen = false;
        if ($numa > 0) {
            $this->db->set('send_num_a', 'send_num_a +'.$numa, FALSE);
            $boolen = true;
        }
        if ($numb > 0) {
            $this->db->set('send_num_b', 'send_num_b +'.$numb, FALSE);
            $boolen = true;
        }
        if ($numc > 0) {
            $this->db->set('send_num_c', 'send_num_c +'.$numc, FALSE);
            $boolen = true;
        }
        if ($boolen) {
            $this->db->where('uid', $this->userinfo['uid']);
            $this->db->update('md_tuan_config');
        }
        return true;
    }

    public function toSetLimit($data)
    {
        foreach ($data as $uid => $v) {
            if ($v['exist'] == 1) {
                $this->db->set('limit_num_a', $v['A']);
                $this->db->set('limit_num_b', $v['B']);
                $this->db->set('limit_num_c', $v['C']);
                $this->db->where('uid', $uid);
                $this->db->update('md_tuan_config');
            } else {
                unset($v['exist']);
                $this->db->insert('md_tuan_config', [
                    'uid'         => $uid,
                    'limit_num_a' => $v['A'],
                    'limit_num_b' => $v['B'],
                    'limit_num_c' => $v['C'],
                ]);
            }
        }
        return ['errcode' => 200,'errmsg'=> '重分配成功'];
    }

    public function getMyLimit()
    {
        $uid = $this->userinfo['uid'];
        return $this->db->get_where('md_tuan_config',['uid' => $uid])->row_array();
    }
}