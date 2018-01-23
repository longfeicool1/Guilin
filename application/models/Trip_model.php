<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/3
 * Time: 16:51
 */
class Trip_model extends MY_Model
{
    public $_table = 'pm_tmp_user_trip_';

    /**
     * 列表
     * @param array $where
     * @param int $page
     * @param int $size
     * @param string $orderBy
     * @return mixed
     */
    public function lists($where, $page = 1, $size = 30, $orderBy = 'collect_date desc')
    {
        $start = ($page - 1) * $size;
        $field = "collect_date,score,ROUND(week_trip_mile / 1000,2) AS totalMile,ROUND(week_trip_time / 3600,1) AS driveTime,
            week_speed_top AS topSpeed,week_speed_avg AS avgSpeed,week_acce AS acce,week_dece AS dece";
        $sql = $this->getTripListSql($field, $where);
        $sql .= " ORDER BY {$orderBy} LIMIT {$start}, {$size}";
        $list = $this->db->query($sql)->result_array();
        $list = $this->setListId($list, $page, $size);
        return $list;
    }

    /**
     * 获取记录条数
     * @param array $where
     * @return int
     */
    public function counts($where)
    {
        $num = 0; # default
        $filed = "count(*) as num";
        $sql = $this->getTripListSql($filed, $where);
        $row = $this->db->query($sql)->row_array();
        if ($row) {
            $num = $row['num'];
        }
        return $num;
    }

    /**
     * 获取列表sql
     * @param string $field
     * @param string $where
     * @return string
     */
    private function getTripListSql($field = '*', $where = '')
    {

        $n = $where['bind_id'] % 256;
        $whereSql = 'bindid = ' . $where['bind_id'];
        if (isset($where['start_time']) && !empty($where['start_time'])) {
            $whereSql .= " AND collect_date >= '{$where['start_time']}'";
        }

        if (isset($where['end_time']) && !empty($where['end_time'])) {
            $whereSql .= " AND collect_date <= '{$where['end_time']}'";
        }

        $sql = "SELECT {$field} FROM trip.{$this->_table}{$n}";
        $sql .= " WHERE {$whereSql} ";

        return $sql;
    }

    /**
     * 设置列表的序号
     * @param $list
     * @param int $page
     * @param int $size
     * @return mixed
     */
    protected function setListId($list, $page = 1, $size = 30)
    {
        if (! empty($list)) {
            foreach ($list as $k => $v) {
                $list[$k]['row_id'] = ($page - 1) * $size + $k +1;
            }
        }
        return $list;
    }


}