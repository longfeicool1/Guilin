<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/5
 * Time: 14:10
 */
class SrcCityDay_model extends MY_Model
{
    public $_table = 'pm_src_city_day';

    public function __construct()
    {
        parent::__construct();
    }

    public function cityList()
    {
        $date = date('Y-m-d', strtotime('-1 days'));
        $sql = "SELECT province,city,active_num,enter_num,install_num,valid_num,no_install_num FROM {$this->_table}
                WHERE day_date = '{$date}' group by province asc, city asc";
        $list = $this->db->query($sql)->result_array();
        return $list;
    }


}