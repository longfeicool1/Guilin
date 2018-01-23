<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends MY_Model {
    public $getIncomeStatus = [
        1 => '未领取',
        2 => '已领取',
        3 => '已过期',
        4 => '无法领取',
    ];

    public $ud = [
        1 => '升',
        2 => '降',
        3 => '=',
        4 => '/',
    ];
    // public $ud = [
    //     1 => '↑',
    //     2 => '↓',
    //     3 => '=',
    //     4 => '-',
    // ];

    public function __CONSTRUCT() {
        parent::__CONSTRUCT();
    }

    public function weekList($page,$size,$condition = [])
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $offset = ($page - 1) * $size;
        $result = $this->db
            // ->select('a.*,c.account_login')
            // ->join('baohe.app_obd_bind b','a.bindid = b.bind_id','left')
            // ->join('baohe.pm_account_list c','b.user_id = c.pid and c.is_show = 1','left')
            ->limit($size,$offset)
            ->order_by('collect_date desc')
            ->get('pm_week_report a')
            ->result_array();
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            $result[$k]['xuhao']           = $n;
            $result[$k]['getIncomeStatus'] = $this->getIncomeStatus[$v['get_income_status']];            $result[$k]['score_rate']   = $v['score_rate'] * 100 . '%';
            $result[$k]['week_acce_ud'] = $this->ud[$v['week_acce_ud']];
            $result[$k]['week_dece_ud'] = $this->ud[$v['week_dece_ud']];
            $result[$k]['week_coce_ud'] = $this->ud[$v['week_coce_ud']];
            $result[$k]['trip_mile_ud'] = $this->ud[$v['trip_mile_ud']];
            $result[$k]['trip_time_ud'] = $this->ud[$v['trip_time_ud']];
            $result[$k]['top_speed_ud'] = $this->ud[$v['top_speed_ud']];

        }
        // echo '<pre>';print_r($result);die;
        return $result;
    }

    public function weekCount($condition = [])
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $count = $this->db
            ->count_all_results('pm_week_report a');
        return $count;
    }

    public function monthList($page,$size,$condition = [])
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $offset = ($page - 1) * $size;
        $result = $this->db
            ->select('a.*')
            // ->join('baohe.app_obd_bind b','a.bindid = b.bind_id','left')
            // ->join('baohe.pm_account_list c','b.user_id = c.pid and c.is_show = 1','left')
            ->limit($size,$offset)
            ->order_by('collect_date desc')
            ->get('pm_month_report a')
            ->result_array();
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            $incomeDescList = json_decode($v['income_desc_list'],true);
            $result[$k]['xuhao'] = $n;
            $result[$k]['score_title_list'] = json_decode($v['score_title_list'],true);
            $result[$k]['income_desc_list'] = $incomeDescList;
            $result[$k]['week_acce_ud'] = $this->ud[$v['week_acce_ud']];
            $result[$k]['week_dece_ud'] = $this->ud[$v['week_dece_ud']];
            $result[$k]['week_coce_ud'] = $this->ud[$v['week_coce_ud']];
            $result[$k]['trip_mile_ud'] = $this->ud[$v['trip_mile_ud']];
            $result[$k]['trip_time_ud'] = $this->ud[$v['trip_time_ud']];
            $result[$k]['top_speed_ud'] = $this->ud[$v['top_speed_ud']];
            $result[$k]['weekNum'] = count($incomeDescList);
        }
        // echo '<pre>';print_r($result);die;
        return $result;
    }

    public function monthCount($condition = [])
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $count = $this->db
            ->count_all_results('pm_month_report a');
        return $count;
    }

    public function getTotalList()
    {
        $rs1 = $this->db
        ->select('a.uid,b.account_login,count(a.id) as tripCount,sum(tripmile) /1000 as totalMile,round(sum(ABS(etime - stime)) /3600) AS totalTime')
        ->join('baohe.pm_account_list b','a.uid = b.pid')
        ->group_by('uid')
        ->where('b.account_type = 4')
        ->get('pm_single_actual a')
        ->result_array();
        // echo "<pre>";print_r($this->db->last_query());die;
        return $rs1;
    }

}