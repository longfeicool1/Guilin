<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/5
 * Time: 14:10
 */
class SrcCityYear_model extends MY_Model
{
    public $_table = 'pm_src_city_year';

    public function __construct()
    {
        parent::__construct();
    }

    public function cityNumber($year, $province, $city)
    {
        $sql = "SELECT `year`, install_num FROM {$this->_table}
                WHERE `year` = '{$year}' AND province = '{$province}' AND city = '{$city}'";
        $row = $this->db->query($sql)->row_array();
        return $row;
    }


}