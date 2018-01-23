<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Check_model extends MY_Model
{
    public function carInfo($bindid)
    {
        $info = $this->db
            ->select('USER_ID AS uid,BIND_ID AS bindid,CARCARD AS carcard,insure_code,is_check,cardtype')
            ->get_where('baohe.app_obd_bind',['BIND_ID' => $bindid])
            ->row_array();
        // print_r($info);die;
        return $info;
    }

    public function updateCar($bindid,$data)
    {
        if (!empty($data['is_check']) && $data['is_check'] == 2) {
            $carInfo = $this->db
                ->select('USER_ID AS uid,is_pay,b.src')
                ->join('baohe.pub_src_info b','a.BIND_ID = b.bindid and b.status = 1','left')
                ->get_where('baohe.app_obd_bind a',['a.BIND_ID' => $bindid])
                ->row_array();
            if (empty($carInfo["src"])) {
                return ['errcode' => 300,'errmsg' => '未绑定设备'];
            }
            // return $this->db->update('baohe.app_obd_bind',$data,['BIND_ID' => $bindid]);
            if (!empty($carInfo) && $carInfo['is_pay'] == 1) {
                $result             = $this->toWallet($carInfo['uid'],$bindid,10);
                $data['is_pay']     = 2;
                $data['check_time'] = date('Y-m-d H:i:s');
                return empty($result['errcode']) ? $this->db->update('baohe.app_obd_bind',$data,['BIND_ID' => $bindid]) : $result;
            }
            return ['errcode' => 300,'errmsg' => '未知原因,认证失败'];
        } else {
            // unset($data['is_check']);
            return $this->db->update('baohe.app_obd_bind',$data,['BIND_ID' => $bindid]);
        }
    }

    public function userInfo($pid)
    {
        $info = $this->db
            ->get_where('baohe.pm_account_list',['pid' => $pid,'is_show' => 1])
            ->row_array();
        // print_r($info);die;
        return $info;
    }

    // public function updateInsureCode($bindid,$insureCode)
    // {
    //     $data = [
    //         'insure_code' => $insureCode,
    //     ];
    //     return $this->db->update('baohe.app_obd_bind',$data,['BIND_ID' => $bindid]);
    // }

    public function noAuthList($page,$size,$condition = [])
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $offset = ($page - 1) * $size;
        $result = $this->db
            ->select("a.bind_id,b.account_login,a.carcard,a.cardtype AS cardtype2,if(a.cardtype = 1,'临时牌','正常牌') AS cardtype,c.src,a.insure_code,a.insure_end")
            ->join('baohe.pm_account_list b','b.pid = a.user_id and b.account_type = 4 and b.is_show = 1')
            ->join('baohe.pub_src_info c','c.bindid = a.bind_id and c.status = 1')
            ->limit($size,$offset)
            ->order_by('a.cardtype asc,a.CREATE_TIME desc')
            ->get('baohe.app_obd_bind a')
            ->result_array();
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            $result[$k]['xuhao'] = $n;
            // $result[$k]['insure_code'] = !empty($v['insure_code']) ? "`".$v['insure_code'] : '';
            $result[$k]['insure_code'] = !empty($v['insure_code']) ? $v['insure_code']: '';
            // $result[$k]['insure_code'] = html_entity_decode("&iuml;&raquo;&iquest;".$v['insure_code']);
        }
        // echo '<pre>';print_r($resu lt);die;
        return $result;
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
            'remark'     => '认证奖励',
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

    public function updateUserInfo($pid,$data)
    {
        $userInfo = $this->userInfo($pid);
        $rs1 = $this->db->get_where('baohe.pm_account_list',['account_login' => $data['newLogin'],'is_show' => 1,'account_type' => 4])->row_array();
        if (!empty($rs1)) {
            return ['errcode' => 300, 'errmsg' => '该用户已注册1'];
        }
        $rs2 = $this->db->get_where('baohe.pub_passport_info',['loginname' => 'yg'.$data['newLogin']])->row_array();
        if (!empty($rs2)) {
            return ['errcode' => 300, 'errmsg' => '该用户已注册2'];
        }
        $insert = [
            'account_login' => $data['newLogin'],
            'account_type'  => 4,
            'pid'           => $pid,
            'comment'       => '后台账户修改',
        ];
        $update1 = [
            'is_show' => 2,
            'comment' => "更换到{$data['newLogin']}",
        ];
        $update2 = [
            'loginname' => 'yg'.$data['newLogin'],
        ];
        //旧账户is_show = 2
        $this->db->update('baohe.pm_account_list',$update1,['id' => $userInfo['id']]);
        //新账户添加
        $this->db->insert('baohe.pm_account_list',$insert);
        //pub_passport_info修改
        $this->db->update('baohe.pub_passport_info',$update2,['pid' => $pid]);
        return ['errcode' => 0, 'errmsg' => '操作成功'];
    }

}