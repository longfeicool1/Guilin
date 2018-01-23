<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 优驾保保单
 */
class DeduCheck_model extends MY_Model {

    public $isCheck = [
        1 => '未审',
        2 => '待审',
        3 => '通过',
    ];
    public function __CONSTRUCT() {
        parent::__CONSTRUCT();
    }


    public function deduCheckList($page,$size,$condition = [])
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $offset = ($page - 1) * $size;
        $result = $this->db
            ->select('a.*,b.name as owerName,b.mobile as owerMobile,IFNULL(c.use_price,0) as use_price')
            ->select('(SELECT SUM(total_money) FROM `pm_ujb_week_income` e WHERE e.`bindid` = a.`bindid`) as total_money')
            ->join('lubidashi.pm_shop b', 'a.shop_code = b.code','left')
            ->join('pm_ujb_week_useup c', 'a.bindid = c.bindid','left')
            ->limit($size,$offset)
            ->order_by('a.is_check,a.created desc')
            ->get('pm_offline_deduction a')
            ->result_array();
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            if ($v['cw_check_time'] == '0000-00-00 00:00:00') {
                $result[$k]['cw_check_time'] = '-';
            }
            $result[$k]['xuhao'] = $n;
            $result[$k]['ischeckName'] = $this->isCheck[$v['is_check']];
            $result[$k]['canUseMoney'] = $v['total_money'] - $v['use_price'];
        }
        // echo '<pre>';print_r($result);die;
        return $result;
    }

    //检查该比抵扣是否有效
    public function checkDedu($deduInfo)
    {
        if (empty($deduInfo['mobile'])) {
            return ['errcode' => 30001, 'errmsg' => '用户手机不能为空'];
        }
        if (empty($deduInfo['carcard'])) {
            return ['errcode' => 30002, 'errmsg' => '车牌不能为空'];
        }
        if ($deduInfo['dedu_price'] <= 0) {
            return ['errcode' => 30003, 'errmsg' => '消费必须大于零'];
        }
        //判断是否存在用户
        $user = $this->db->select('pid')->get_where('pub_passport_info',['loginname' => $deduInfo['mobile']])->row_array();
        if (empty($user)) {
            return ['errcode' => 30004, 'errmsg' => '不存在该用户'];
        }
        //检查是否存在该车
        $car = $this->db->select('BIND_ID AS bindid')->get_where('app_obd_bind',['USER_ID' => $user['pid'],'CARCARD' => $deduInfo['carcard']])->row_array();
        if (empty($car)) {
            return ['errcode' => 30005, 'errmsg' => '该用户没有此车牌'];
        }
        //判断收益情况
        $src = $this->db->select('src')->get_where('pub_src_info',['bindid' => $car['bindid']])->row_array();
        if (empty($src)) {
            return ['errcode' => 30006, 'errmsg' => '此车牌没有绑定设备'];
        }
        //查看收益是否够
        $income = $this->db->select('SUM(total_money) as total_money')->get_where('pm_ujb_week_income',['bindid' => $car['bindid']])->row_array();
        // print_r($income);die;
        if ($income['total_money'] < $deduInfo['dedu_price']) {
            return ['errcode' => 30007, 'errmsg' => '收益不足'];
        }
        //查看是否存在路比大使账号
        if (empty($deduInfo['uid'])) {
            return ['errcode' => 30008, 'errmsg' => '不存在该路比大使账户'];
        }
        // print_r(['bindid' => $car['bindid']]);die;
        return ['bindid' => $car['bindid']];

    }

    public function useup($bindid,$price)
    {
        $res = $this->db->where(['bindid' => $bindid])->get('pm_ujb_week_useup')->row_array();
        if(!empty($res)) {
            $sql = "UPDATE pm_ujb_week_useup SET use_price = use_price + ? WHERE bindid = ?";
            $res = $this->db->query($sql,[$price,$bindid]);
        } else {
            $res = $this->db->insert('pm_ujb_week_useup',['use_price' => $price,'bindid' => $bindid]);
        }
        return $res;
    }


}