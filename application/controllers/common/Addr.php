<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 地址接口
 * @author  liuweilong
 * +2016-03-22
 */
class Addr extends MY_Controller
{
    /**
     * 首页接口
     * @return json
     */
    public function index()
    {
        $pid = (int) (isset($_GET['pid']) ? $_GET['pid'] : 0);
        $this->load->Model("default/OtCityInfoModel", "OtCityInfoModel");
        $data = $this->OtCityInfoModel->getList('parent_city_id='.$pid, 'city_id, city_name');
        $newData = array();
        foreach ($data as $key => $value)
        {
            $newData[] = array_values($value);
        }
        echo json_encode($newData);
    }


}