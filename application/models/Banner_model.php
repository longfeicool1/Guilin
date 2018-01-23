<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/6
 * Time: 9:31
 */
class Banner_model extends MY_Model
{
    public $_table = 'pm_banner';
    public $_fields = [
        'id',
        'name',
        'android_img',
        'ios_img',
        'url',
        'created',
        'start_time',
        'end_time',
        'is_limit',
        'status',
    ];

    public $_listsConfig = [
        'start_time' => ['?', 'start_time', "start_time >= '?'"],
        'end_time' => ['?', 'end_time', "end_time <= '? 23:59:59'"],
    ];

}