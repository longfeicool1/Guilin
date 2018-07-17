<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 数据统计模型
 * @author  loonfiy
 * +2018-03-16
 */
class DataModel extends MY_Model
{
    public $position;
    public function __construct()
    {
        parent::__construct();
        $this->rules();
        $this->sameLevel();
        $this->position = $this->userinfo['position'];
    }


    public function getSimpleData($page,$size,$condition)
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $offset = ($page - 1) * $size;
        if ($this->sameLevel) {
            $this->db->where_in('a.uid',$this->sameLevel);
        }
        $result = $this->db
            ->select('a.*,b.name')
            ->join('md_user b','a.uid = b.uid','left')
            ->order_by('monthInMoney DESC')
            ->limit($size,$offset)
            ->get_where('md_rank_personal a',['collectDate' => date('Y-m-d',strtotime('-1 day'))])
            ->result_array();
        // D($result);
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            $result[$k]['xuhao'] = $n;
        }
        return $result;
    }

    public function getSimpleDataCount($condition)
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        if ($this->sameLevel) {
            $this->db->where_in('uid',$this->sameLevel);
        }
        $this->db->where(['collectDate' => date('Y-m-d',strtotime('-1 day'))]);
        return $this->db->count_all_results('md_rank_personal');
    }


    public function getPersonalData()
    {
        $result = [];
        switch ($this->position) {
            case 2:
                $result = $this->db->order_by('collectDate DESC')->get_where('md_rank_city',['uid' => $this->userinfo['uid']])->row_array();
                break;
            case 3:
                $result = $this->db->order_by('collectDate DESC')->get_where('md_rank_area',['uid' => $this->userinfo['uid']])->row_array();
                break;
            case 4:
                $result = $this->db->order_by('collectDate DESC')->get_where('md_rank_team',['uid' => $this->userinfo['uid']])->row_array();
                break;
            case 5:
                $result = $this->db->order_by('collectDate DESC')->get_where('md_rank_personal',['uid' => $this->userinfo['uid']])->row_array();
                break;
            default:
                if ($this->userinfo['role_id'] == 1) {
                    $result = $this->db->order_by('collectDate DESC')->get('md_rank_total')->row_array();
                }
                break;
        }
        return $result;
    }

    public function getTeamData($condition)
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        if ($this->uids) {
            $this->db->where_in('a.uid',$this->uids);
        }
        $result = $this->db
            ->select('a.*,b.name')
            ->join('md_user b','a.uid = b.uid','left')
            ->order_by('monthInMoney DESC')
            ->get_where('md_rank_team a',['collectDate' => date('Y-m-d',strtotime('-1 day'))])
            ->result_array();
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            $result[$k]['xuhao'] = $n;
        }
        return $result;
    }

    public function getAreaData($condition)
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        if ($this->uids) {
            $this->db->where_in('a.uid',$this->uids);
        }
        $result = $this->db
            ->select('a.*,b.name')
            ->join('md_user b','a.uid = b.uid','left')
            ->order_by('monthInMoney DESC')
            ->get_where('md_rank_area a',['collectDate' => date('Y-m-d',strtotime('-1 day'))])
            ->result_array();
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            $result[$k]['xuhao'] = $n;
        }
        return $result;
    }

    public function getCityData($condition)
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        if ($this->uids) {
            $this->db->where_in('a.uid',$this->uids);
        }
        $result = $this->db
            ->select('a.*,b.name')
            ->join('md_user b','a.uid = b.uid','left')
            ->order_by('monthInMoney DESC')
            ->get_where('md_rank_city a',['collectDate' => date('Y-m-d',strtotime('-1 day'))])
            ->result_array();
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            $result[$k]['xuhao'] = $n;
        }
        return $result;
    }

    public function getTotalData($condition)
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $result = $this->db
            ->order_by('monthInMoney DESC')
            ->get_where('md_rank_total a',['collectDate' => date('Y-m-d',strtotime('-1 day'))])
            ->result_array();
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            $result[$k]['xuhao'] = $n;
        }
        return $result;
    }

    public function getCallData($page,$size,$condition)
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $offset = ($page - 1) * $size;
        if ($this->uids) {
            $this->db->where_in('a.uid',$this->uids);
        }
        $result = $this->db
            ->select('a.*,b.name')
            ->join('md_user b','a.uid = b.uid','left')
            ->order_by('monthInMoney DESC')
            ->limit($size,$offset)
            ->get('md_rank_personal a')
            ->result_array();
        // D($this->db->last_query());
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            $result[$k]['xuhao'] = $n;
        }
        return $result;
    }

    public function getCallDataCount($condition)
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        if ($this->uids) {
            $this->db->where_in('a.uid',$this->uids);
        }
        return $this->db
        ->join('md_user b','a.uid = b.uid','left')
        ->count_all_results('md_rank_personal a');
    }

    public function getLiveData($condition1,$condition2,$collectDate)
    {
        if ($condition1) {
            foreach ($condition1 as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }

        // $users = array_column($users,'uid');
        if (!empty($this->uids)) {
            $this->db->where_in('secOwer',$this->uids);
        }
        $rs1  = $this->db
            ->select('count(*) as dayAllotCustom,secOwer as uid')
            ->where(['isShow' => 1,'secOwer > '=>0])
            ->group_by('secOwer')
            ->get('md_custom_list')
            ->result_array();
        // D($this->db->last_query());
        $new1 = [];
        $new2 = [];
        $new3 = [];
        foreach ($rs1 as $v) {
            $new1[$v['uid']] =$v['dayAllotCustom'];
        }
        if ($condition2) {
            foreach ($condition2 as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        if (!empty($this->uids)) {
            $this->db->where_in('uid',$this->uids);
        }
        $rs2  = $this->db
            ->select('count(*) as dayPhone,uid')
            ->group_by('uid')
            ->get('md_comment')
            ->result_array();
        // D($this->db->last_query());
        foreach ($rs2 as $v) {
            $new2[$v['uid']] =$v['dayPhone'];
        }
        $startMonth = date('Y-m-01',strtotime($collectDate));
        $endMonth   = date('Y-m-31',strtotime($collectDate));
        if (!empty($condition1['secOwer'])) {
            $this->db->where(['secOwer' => $condition1['secOwer']]);
        }
        if (!empty($this->uids)) {
            $this->db->where_in('secOwer',$this->uids);
        }
        $rs3 = $this->db
            ->select('count(*) as monthAllotCustom,secOwer AS uid')
            ->where(['give_time >=' => $startMonth,'give_time <=' => $endMonth,'isShow' => 1])
            ->group_by('secOwer')
            ->get('md_custom_list')
            ->result_array();
        // D($this->db->last_query());
        foreach ($rs3 as $v) {
            $new3[$v['uid']] =$v['monthAllotCustom'];
        }
        if (!empty($this->uids)) {
            $this->db->where_in('uid',$this->uids);
        }
        $c = ['position >' => 3,'is_show' => 1];
        if (!empty($condition1['secOwer'])) {
            $c['uid'] = $condition1['secOwer'];
        }
        $users = $this->db->select('uid,name')->get_where('md_user',$c)->result_array();
        $list = [];
        foreach ($users as $k =>$v) {
            $users[$k]['collectDate']      = $collectDate;
            $users[$k]['dayAllotCustom']   = !empty($new1[$v['uid']]) ? $new1[$v['uid']] : 0;
            $users[$k]['dayPhone']         = !empty($new2[$v['uid']]) ? $new2[$v['uid']] : 0;
            $users[$k]['monthAllotCustom'] = !empty($new3[$v['uid']]) ? $new3[$v['uid']] : 0;
        }
        $sort = array_column($users,'monthAllotCustom');
        array_multisort($sort,SORT_DESC,SORT_NUMERIC,$users);
        // D($users);
        return $users;
    }

}