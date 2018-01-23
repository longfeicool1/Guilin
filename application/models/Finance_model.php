<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance_model extends MY_Model
{
    public $bankstatus = [
        1 => '初次添加',
        2 => '无效卡',
        3 => '客户删除',
        4 => '认证',
    ];

    public $auditName = [
        1 => '审核中',
        2 => '审核成功',
        3 => '审核失败',
    ];

    public $getIncomeStatus = [
        1 => '未领取',
        2 => '已领取',
        3 => '已过期',
        4 => '无法领取',
    ];

    public function __CONSTRUCT() {
        parent::__CONSTRUCT();
    }

    public function requestList($page,$size,$condition = [],$type = 1)
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $offset = ($page - 1) * $size;
        if ($type == 2) {
            $orderBy = 'updated desc';
        } else {
            $orderBy = 'created asc';
        }
        $result = $this->db
            ->select('b.id as bankId,n.city,f.carcard,a.audit,a.id,c.account_login,a.amount,a.created,a.updated,b.bankcode,b.location,d.name,b.deposit_bank,b.account_name,b.is_show')
            // ->join('pm_account_list b','a.pid = b.pid and b.account_type = 4 and is_show = 1')
            ->join('pm_bank_card b','a.pay_user_key = b.id')
            ->join('baohe.pm_account_list c','a.pid = c.pid and c.is_show = 1')
            ->join('pm_banks d', 'b.bankname = d.short_name','left')
            ->join('baohe.app_obd_bind f','a.bindid = f.bind_id','left')
            ->join('baohe.pub_src_info m','a.bindid = m.bindid and m.status = 1','left')
            ->join('pm_src_city n','m.src = n.src','left')
            ->limit($size,$offset)
            ->order_by($orderBy)
            ->get('baohe.pm_wallet_v2_task a')
            ->result_array();
        // log_message('debug',$this->db->last_query());
        // print_r($this->db->last_query());die;
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            $result[$k]['xuhao']          = $n;
            $result[$k]['bankcode']       = $v['bankcode'];
            $result[$k]['amount']         = round($v['amount'] /100,2);
            $result[$k]['bankCardStatus'] = $this->bankstatus[$v['is_show']];
            $result[$k]['auditName']      = $this->auditName[$v['audit']];
        }
        // echo '<pre>';print_r($result);die;
        return $result;
    }

    public function requestCount($condition = [])
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $count = $this->db
            // ->join('pm_account_list b','a.pid = b.pid and b.account_type = 4 and is_show = 1')
            ->join('pm_bank_card b','a.pay_user_key = b.id')
            ->join('baohe.pm_account_list c','a.pid = c.pid')
            ->join('baohe.app_obd_bind f','a.bindid = f.bind_id','left')
            ->count_all_results('baohe.pm_wallet_v2_task a');
        return $count;
    }

    public function rewardList($page,$size,$condition = [])
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $offset = ($page - 1) * $size;
        $result = $this->db
            ->select('a.id,a.score,a.collect_date,c.account_login,a.week_income,a.carcard,a.get_income_status')
            ->join('baohe.app_obd_bind b','a.bindid = b.bind_id','left')
            ->join('baohe.pm_account_list c','b.user_id = c.pid and c.is_show = 1','left')
            ->limit($size,$offset)
            ->order_by('collect_date desc')
            ->get('pm_week_report a')
            ->result_array();
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            $result[$k]['xuhao']           = $n;
            $result[$k]['reawardName']     = '周驾驶奖励';
            $result[$k]['getIncomeStatus'] = $this->getIncomeStatus[$v['get_income_status']];
        }
        // echo '<pre>';print_r($result);die;
        return $result;
    }

    public function rewardCount($condition = [])
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $count = $this->db
            ->join('baohe.app_obd_bind b','a.bindid = b.bind_id','left')
            ->join('baohe.pm_account_list c','b.user_id = c.pid and c.is_show = 1','left')
            ->count_all_results('pm_week_report a');
        return $count;
    }


    public function toChangeRequestStatus($id, $type = 1,$bankId = '')
    {
        if (empty($id)) {
            return;
        }

        // print_r($card);die;
        if ($type == 1) { //成功
            $this->db->update('baohe.pm_wallet_v2_task',['audit' => 2,'updated' => date('Y-m-d H:i:s')],['id' =>$id]);
            if (!empty($bankId)) {
               $this->db->update('pm_bank_card',['is_show' => 4],['id' =>$bankId,'is_show !=' => 3]);
               if ($this->db->affected_rows()) {
                   return ['errcode' => 200,'errmsg' => '操作成功'];
               }
            }
        }
        if ($type == 2) { //失败
            $info = $this->db->select('pid,bindid,amount')->get_where('baohe.pm_wallet_v2_task',['id' =>$id])->row_array();
            // print_r($info);die;
            $this->db->update('baohe.pm_wallet_v2_task',['audit' => 3],['id' =>$id]);
            if (!empty($bankId)) {
                $this->db->update('pm_bank_card',['is_show' => 2],['id' =>$bankId,'is_show !=' => 4]);
                if ($this->db->affected_rows()) {
                    if (!empty($info)) {
                        $result = $this->toWallet($info['pid'],$info['bindid'],$info['amount'] /100);
                        return ['errcode' => 200,'errmsg' => '操作成功'];
                    }
                }
            }
        }

        return ['errcode' => 300,'errmsg' => '操作失败'];
    }

    public function totalOutMoney()
    {
        $result = $this->db
        ->select('sum(amount) as money')
        ->where(['a.audit' => 2, 'a.action' => 1, 'a.pay_type' => 'bank'])
        ->get('baohe.pm_wallet_v2_task a')
        ->row_array();
        return !empty($result['money']) ? $result['money'] /100 : '0.00';
    }

    public function todayOutMoney()
    {
        $date = date('Y-m-d');
        $result = $this->db
        ->select('sum(amount) as money')
        ->where(['a.audit' => 2, 'a.action' => 1, 'a.pay_type' => 'bank','updated >= ' => $date])
        ->get('baohe.pm_wallet_v2_task a')
        ->row_array();
        return !empty($result['money']) ? $result['money']/100 : '0.00';
    }

    public function toWallet($uid, $bindid,$amount)
    {
        require_once APPPATH . 'libraries/Sign.php';
        $url  = ENVIRONMENT != 'production' ? 'http://dev-api2.ubi001.com/v1/wallet/sys-task/recharge' : 'http://api2.ubi001.com/v1/wallet/sys-task/recharge';
        $data = [
            "u"          => $uid,
            'bindid'     => $bindid,
            'type'       => 2,
            'payKey'     => 'h5',
            'remark'     => '提现退款',
            'amount'     => $amount * 100,
            'payType'    => 'bank',
            'payUserKey' => 'admin',
        ];
        $data['appId'] = 'admin';
        $data['sign']  = (new Sign)->cal(APP_SIGN_SECRET, $data);
        // echo '<pre>';print_r($data);die;
        $result = Requests::post($url, array(), $data);

        // echo '<pre>';print_r(json_decode($result->body,true));die;
        if (empty($result->body)) {
            return ['errcode' => 300, 'errmsg' => '调用钱包接口错误'];
        }
        $result = json_decode($result->body,true);
        if ($result['errcode'] != 0) {
            return ['errcode' => 300, 'errmsg' => $result['msg']];
         }
        return ['errcode' => 0, 'errmsg' => '操作成功'];
    }

}