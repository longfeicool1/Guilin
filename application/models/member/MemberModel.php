<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 用户模型
 * @author  loonfiy
 * +2016-03-16
 */
class MemberModel extends MY_Model
{
    public $sex = [
        1 => '男',
        2 => '女',
    ];
    public $payType = [
        1 => '无',
        2 => '现金发薪',
        3 => '银行转账',
        4 => '代发工资',

    ];
    public $socialSecurity = [
        1 => '有',
        2 => '无',
    ];
    public $reservedFunds = [
        1 => '有',
        2 => '无',
    ];
    public $haveHouse = [
        1 => '有',
        2 => '无',
    ];
    public $haveCar = [
        1 => '有',
        2 => '无',
    ];
    public $customLevel = [
        1 => '0星',
        2 => '1星',
        3 => '2星',
        4 => '3星',
        5 => '4星',
    ];
    public $customStatus = [
        1 => '无',
        2 => '资质不符',
    ];

    public function getMemberList($page,$size,$condition)
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $offset = ($page - 1) * $size;
        $result = $this->db
            ->select("a.*,IFNULL(b.name,'无') AS firstName,IFNULL(c.name,'无') AS secondName")
            ->join('md_user b','a.firstOwer = b.uid','left')
            ->join('md_user c','a.secondOwer = c.uid','left')
            ->limit($size,$offset)
            ->order_by('meetTime')
            ->get('md_custom_list a')
            ->result_array();
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            $result[$k]['xuhao']           = $n;
            $result[$k]['sex']             = $this->sex[$v['sex']];
            $result[$k]['payType']         = $this->payType[$v['payType']];
            $result[$k]['socialSecurity']  = $this->socialSecurity[$v['socialSecurity']];
            $result[$k]['reservedFunds']   = $this->reservedFunds[$v['reservedFunds']];
            $result[$k]['haveHouse']       = $this->haveHouse[$v['haveHouse']];
            $result[$k]['haveCar']         = $this->haveCar[$v['haveCar']];
            $result[$k]['customLevel']     = $this->customLevel[$v['customLevel']];
            $result[$k]['meetTime']        = $v['meetTime'] == '0000-00-00 00:00:00' ? '未预约' : $v['meetTime'];
            $result[$k]['customStatus']    = $this->customStatus[$v['customStatus']];
        }
        return $result;
    }

    public function getMemberCount($condition)
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $count = $this->db->count_all_results('md_custom_list');
        return $count;
    }
}