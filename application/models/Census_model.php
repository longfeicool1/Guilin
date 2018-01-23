<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Census_model extends MY_Model {
    public function __CONSTRUCT() {
        parent::__CONSTRUCT();
    }


    public function installList($page,$size,$condition = [])
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $offset = ($page - 1) * $size;
        $result = $this->db
            ->limit($size,$offset)
            ->order_by('collect_date desc')
            ->get('pm_install_census')
            ->result_array();
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            $result[$k]['xuhao']          = $n;
        }
        return $result;
    }

    public function installCount($condition = [])
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $count = $this->db
            ->count_all_results('pm_install_census');
        return $count;
    }

    public function sumObd($condition = [])
    {
        $default = [
            'sumWorkNum'   => 0,
            'sumActiveNum' => 0,
        ];
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $rs = $this->db
            ->select('SUM(work_num) as sumWorkNum, SUM(active_num) as sumActiveNum')
            ->get('pm_install_census')
            ->row_array();
        return !empty($rs) ? $rs : $default;
    }


    public function onlineList($page,$size,$condition = [])
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $offset = ($page - 1) * $size;
        $result = $this->db
            ->limit($size,$offset)
            ->order_by('total_time desc')
            ->get('pm_online_total_day')
            ->result_array();
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            $result[$k]['xuhao']          = $n;
        }
        return $result;
    }

    public function onlineCount($condition = [])
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $count = $this->db
            ->count_all_results('pm_online_total_day');
        return $count;
    }

    public function onlineWeekList($page,$size,$condition = [])
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $offset = ($page - 1) * $size;
        $result = $this->db
            ->limit($size,$offset)
            ->order_by('total_time desc')
            ->get('pm_online_total_week')
            ->result_array();
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            $result[$k]['xuhao']          = $n;
        }
        return $result;
    }

    public function onlineMonthList($page,$size,$condition = [])
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $offset = ($page - 1) * $size;
        $result = $this->db
            ->limit($size,$offset)
            ->order_by('total_time desc')
            ->get('pm_online_total_month')
            ->result_array();
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            $result[$k]['xuhao']          = $n;
        }
        return $result;
    }


    public function threeOnlineList($page,$size,$condition = [])
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $offset = ($page - 1) * $size;
        $result = $this->db
            ->limit($size,$offset)
            ->order_by('total_time desc')
            ->get('pm_online_total_threeday')
            ->result_array();
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            $result[$k]['xuhao']          = $n;
        }
        return $result;
    }

    public function threeOnlineCount($condition = [])
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $count = $this->db
            ->count_all_results('pm_online_total_threeday');
        return $count;
    }

}