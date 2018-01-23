<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/5
 * Time: 14:10
 */
class Src_model extends MY_Model
{
    public $_table = 'pub_src_info';
    const BAOHE_DB = 'baohe';

    public function __construct()
    {
        parent::__construct();
        parent::setTable($this->_table, self::BAOHE_DB);
    }

    /**
     * 更换设备
     * @param $bindId
     * @param $obdId
     * @return array
     */
    public function replaceSrc($bindId, $obdId)
    {
        if (!isset($bindId) || !isset($obdId)) {
            return array(false, '参数不正确');
        }

        $sql     = "SELECT src FROM pub_src_info WHERE bindid='{$bindId}' AND status = 1";
        $data    = $this->db->query($sql)->row_array();
        $oldData = $data;
        if (empty($data)) {
            return array(false, '无法正确查询用户旧设备ID');
        }

        $sql  = "SELECT src FROM pub_src_info WHERE src = '{$obdId}'";
        $data = $this->db->query($sql)->row_array();
        if (!empty($data)) {
            return array(false, '设备被绑定');
        }

        $sql  = "SELECT obd_id FROM dc_comm.dc_obd_id WHERE obd_id = '{$obdId}'";
        $data = $this->db->query($sql)->row_array();

        if (empty($data)) {
            return array(false, '设备ID有误');
        }


        // 激活设备
        $this->db->update('dc_comm.dc_obd_id', array('activatedate' => date('Y-m-d H:i:s', time()), 'type' => 1), array('OBD_ID' => $obdId, 'type' => 0));
        $row = $this->db->affected_rows();
        if ($row == 0) {
            return array(false, $obdId . '不是可更换的设备');
        }

        // 更新用户旧设备为无效
        $this->db->update('pub_src_info', array('status' => 0, 'updatetime' => date('Y-m-d H:i:s')), array('bindid' => $bindId));
        $this->db->update('dc_comm.dc_obd_id', array('type' => 2), array('OBD_ID' => $oldData['src']));
        //插入事件表

        $account = get_instance()->AccountHelper->info();

        $this->load->Model("SrcLog_model", "SrcLogModel");
        $data = [
            'account_id' => $account['id'],
            'bind_id' => $bindId,
            'old_src' => $oldData['src'],
            'new_src' => $obdId,
            'comment' => '设备更换'
        ];
        $this->SrcLogModel->addLog($data);

        // 插入新设备
        $ret = $this->db->insert('pub_src_info', array('bindid' => $bindId, 'src' => $obdId, 'createtime' => date('Y-m-d H:i:s'), 'status' => 1));
        return array(true, $ret);

    }

}