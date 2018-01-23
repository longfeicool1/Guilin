<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/6
 * Time: 9:31
 */
class ImageCode_model extends MY_Model
{
    public $_table = 'pm_img_code';
    public $_fields = [
        'id',
        'name',
        'url',
        'created'
    ];

    /**
     * 查询是否已存在相同的名称
     * @param $name
     * @param int $id
     * @return bool
     */
    public function hasName($name, $id = 0)
    {
        $sql = "select id from {$this->_table} WHERE `name` = '{$name}' AND id != {$id}";
        $row = $this->db->query($sql)->row_array();
        if ($row) {
            return true;
        } else {
            return false;
        }
    }

}