<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class OutsideModel extends MY_Model
{
    protected $appid = [
        'JLX','GF'
    ];
    public function insertCustom($insert)
    {
        if (in_array($insert['source'],$this->appid)) {
            return $this->db->insert('md_custom_list',$insert);
        } else {
            return $this->db->insert('test_base.md_custom_list',$insert);
        }
    }
}