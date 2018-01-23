<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reward_model extends MY_Model
{
    public function rewardList($page,$size,$condition = [],$where_not_in)
    {
        if (!empty($where_not_in)) {
            $this->db->where_not_in('a.prize_id',$where_not_in);
        }
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $offset = ($page - 1) * $size;
        $result = $this->db
            ->select('a.created,c.loginname,d.money, d.is_random,a.random_money,al.name as activity_name,d.prize_level,d.prize_name,b.carcard,m.src,n.city,c.username')
            ->join('baohe.app_obd_bind b','a.bindid = b.bind_id','left')
            ->join('baohe.pub_passport_info c','a.uid = c.pid','left')
            ->join('pm_activity_prize d','a.prize_id = d.id','left')
            ->join('pm_activity_list al','al.id = d.activity_id','left')
            ->join('baohe.pub_src_info m','a.bindid = m.bindid and m.status = 1','left')
            ->join('pm_src_city n','n.src = m.src','left')
            ->limit($size,$offset)
            ->order_by('a.created desc')
            ->get('pm_activity_people a')
            ->result_array();
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            $result[$k]['xuhao'] = $offset + $n;
        }
        // echo '<pre>';print_r($this->db->last_query());die;
        // echo '<pre>';print_r($result);die;
        return $result;
    }

    public function rewardCount($condition = [],$where_not_in)
    {
        if (!empty($where_not_in)) {
            $this->db->where_not_in('a.prize_id',$where_not_in);
        }
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $count = $this->db
            ->join('baohe.app_obd_bind b','a.bindid = b.bind_id','left')
            ->join('baohe.pub_passport_info c','a.uid = c.pid','left')
            ->join('pm_activity_prize d','a.prize_id = d.id','left')
            ->count_all_results('pm_activity_people a');
        return $count;
    }

    /**
     * 获取活动随机虚拟金额
     * @param $condition
     * @return mixed
     */
    public function getSumMoney($condition)
    {
        if (!empty($where_not_in)) {
            $this->db->where_not_in('a.prize_id',$where_not_in);
        }
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }

        $row = $this->db
            ->select('(sum(if(is_random = 1, random_money, 0)) + sum(IF(is_random = 0, d.money, 0))) as sum_money')
            ->join('baohe.app_obd_bind b','a.bindid = b.bind_id','left')
            ->join('baohe.pub_passport_info c','a.uid = c.pid','left')
            ->join('pm_activity_prize d','a.prize_id = d.id','left')
            ->get('pm_activity_people a')->row_array();

        return !empty($row['sum_money']) ? $row['sum_money'] : 0;
    }
}