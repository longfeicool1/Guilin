<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/5
 * Time: 14:10
 */
class SrcCityMonth_model extends MY_Model
{
    public $_table = 'pm_src_city_month';

    public function __construct()
    {
        parent::__construct();
    }

    public function installList($province, $city)
    {
        $date = date('Y');
        $sql = "SELECT province, city, `year`,`month`, install_num FROM {$this->_table}
                WHERE `year` = '{$date}' AND province = '{$province}' AND city = '{$city}' ORDER BY month asc";
        $list = $this->db->query($sql)->result_array();
        return $list;
    }


}