<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/6
 * Time: 9:31
 */
class Notice_model extends MY_Model
{
    public $_table = 'pm_notice';
    public $_fields = [
        'id',
        'name',
        'image',
        'type',
        'url',
        'created',
        'start_time',
        'end_time',
        'to_num',
        'read_num',
        'status',
    ];

    public $_listsConfig = [
        'start_time' => ['?', 'start_time', "start_time >= '?'"],
        'end_time' => ['?', 'end_time', "end_time <= '? 23:59:59'"],
        'created_start' => ['?', 'created', "created >= '?'"],
        'created_end' => ['?', 'created', "created <= '? 23:59:59'"],
        'type' => ['?', 'type', "type in (?)"],
        'name' => ['%', 'name'],
    ];

    public $_orderBy = 'status asc, end_time desc';

}