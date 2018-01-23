<?php

/**
 * 用户银行卡
 * User: Administrator
 * Date: 2017/4/5
 * Time: 11:44
 */
class Bank_model extends MY_Model
{
    public $_table = 'pm_bank_card'; # 用户银行卡表
    const BANK_APPROPE = '认证'; # 银行卡认证
    const BANK_INVALID = '无效'; # 银行卡无效
    const BANK_DELETE = 3; # 银行卡删除

    /**
     * 获取账户银行卡信息
     * @param $userId
     * @return mixed
     */
    public function getUserBank($userId)
    {
        # 数据库切换
        $field = 'bankcode,location,bankname,deposit_bank,account_name, is_show,
                  if(is_show = 2, "'.self::BANK_INVALID.'", 
                  if(is_show = 4, "'.self::BANK_APPROPE.'", "")) as status';
        $sql = "SELECT {$field} FROM {$this->_table} WHERE uid = {$userId} AND is_show != " . self::BANK_DELETE;
        $list = $this->db->query($sql)->result_array();
        return $list;
    }

}