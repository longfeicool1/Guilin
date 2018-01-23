<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('get_citys')) {
    function get_citys()
    {
        return json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'regions.json'));
    }
}

if (!function_exists('get_province_list')) {
    function get_province_list()
    {
        $citys = get_citys();
        $result = array();
        foreach ($citys as $k => $v) {
            if ($v->level == 1) {
                $result[] = array('id' => $v->code, 'text' => $v->name);
            }
        }

        return $result;
    }
}

if (!function_exists('get_city_list')) {
    function get_city_list($province_id)
    {
        $citys = get_citys();
        $result = array();
        foreach ($citys as $k => $v) {
            if ($v->level == 2 && $v->parent_code == $province_id) {
                $result[] = array('id' => $v->code, 'text' => $v->name);
            }
        }

        return $result;
    }
}

if (!function_exists('get_area_list')) {
    function get_area_list($city_id)
    {
        $citys = get_citys();
        $result = array();
        foreach ($citys as $k => $v) {
            if ($v->level == 3 && $v->parent_code == $city_id) {
                $result[] = array('id' => $v->code, 'text' => $v->name);
            }
        }

        return $result;
    }
}
