<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/5
 * Time: 14:10
 */
class SrcCityOnline_model extends MY_Model
{
    public $_table = 'pm_src_city_online';

    public function __construct()
    {
        parent::__construct();
    }

    public function onlineList()
    {
        $sql = "SELECT province, day_date, sum(online_num) as online_num,SUM(offline_num) as offline_num, sum(all_num) as all_num 
                FROM `pm_src_city_online` 
                GROUP BY province ASC,day_date DESC 
                ORDER BY province ASC limit 30;";
        $row = $this->db->query($sql)->result_array();
        return $row;
    }

    public function provinceList()
    {
        $sql = "SELECT province FROM `pm_src_city_online` 
                GROUP BY province ASC 
                ORDER BY province ASC";
        $row = $this->db->query($sql)->result_array();
        return $row;
    }

    public function provinceNum($province, $day)
    {
        $sql = "SELECT sum(online_num) as online_num, sum(all_num) as all_num FROM `pm_src_city_online` WHERE province = '{$province}' AND day_date = '{$day}'";
        $row = $this->db->query($sql)->row_array();
        return $row;
    }


}