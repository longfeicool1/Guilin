<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/3
 * Time: 16:51
 */
class SrcLog_model extends MY_Model
{
    public $_table = 'pm_src_change_log';

    public $_fields = [
        'id',
        'account_id',
        'binid_id',
        'old_src',
        'new_src',
        'created',
        'comment',
    ];

    /**
     * 添加更换日志
     * @param array $data
     */
    public function addLog($data)
    {
        $this->insert($data);
    }

    /**
     * 设备更换日志
     * @param $bindId
     * @return array
     */
    public function srcLog($bindId)
    {
        $where = ['bind_id' => $bindId];
        $list = $this->getList($where, '*', "id desc");
        return $list;
    }


}