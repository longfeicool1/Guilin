<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/PasswordHash.php';
/**
 * @author ModelCreater
 * +2016-08-15 12:56:22
 */
class PmWalletModel extends CI_Model
{
    public $table = 'pm_wallet';

    public $logTable = 'pm_wallet_log';
    public $interim_income = 'pm_interim_income';

    public $income_record = 'pm_interim_income_record';

    protected $_defaultLogId = 0;

    protected $_ph;

    /**
     * 数据库定义
     * @var string
     */
    protected $_dbName = 'default';

    /**
     * 字段定义
     * @var array
     */
    protected $_fields = array(
        'id',
        'uid',
        'effective_amount',
        'extracted_amount',
        'hash',
        'openid',
        'updated'
    );
    // private $_wxappid = WX_APPID;
    // private $_wxmchid = WX_MCHID;
    // private $_wxmchkey = WX_MCHKEY;
    private $_wxappid = 'wx93d7098999053e84';
    private $_wxmchid = '1237119702';
    private $_wxmchkey = 'ShenzhenDingranTelematics2015888';
    private $_wxmchca = '/home/www/apiclient_all.pem';
    // private $_prkey = 'asdfqasdfad@#!';

    public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database($this->_dbName, true);
        $this->_ph = new PasswordHash;
    }


    /**
     * 微信周收益返现
     * @param [type] $uid [description]
     */
    public function wxWeekMoney($id, $bindid,$wxopenid, $price,$orderFontWord = 'UJBWF',$tip = '路比周收益提现')
    {
        $time = date('Y-m-d H:i:s');
        // 开启事务
        $this->db->trans_begin();
        try{
            //取出该条记录
            $record   = $this->db->select('uid,collect_date,pay_data')->get_where('pm_ujb_week_income',['id' => $id])->row_array();
            $uid      = $record['uid'];

            // echo '<pre>';print_r($water);die;
            $jpayData = '';
            if (!empty($record['pay_data'])) {
                $jpayData = $record['pay_data'];
                $payData  = json_decode($record['pay_data'],true);
                $createSn = $payData['partner_trade_no'];
            } else {
                $createSn = $this->createSn($id, $orderFontWord);
            }
            $water = array(
                'order_code'   => $createSn,
                'uid'          => $uid,
                'bindid'       => $bindid,
                'type'         => 4,
                'price'        => $price,
                'collect_date' => date('Y-m-d'),
            );
            list($succ, $d, $res, $msg) = $this->_requestWxPay($createSn, $wxopenid, $price * 100, $tip, $jpayData, $checkName = 'NO_CHECK');
            // $succ = 1;
            $topayData = '';
            $topayRes  = '';
            if (!empty($d)) {
                $topayData = json_encode($d);
            }
            if (!empty($res)) {
                $topayRes = json_encode($res);
            }
            if($succ == 1){
                 if ($this->db->trans_status() === FALSE){
                    log_message('error','提现异常30001:'.json_encode(array($uid, $wxopenid, $price)));
                    $this->db->trans_rollback();
                    //储存支付信息
                    $this->db->update('pm_ujb_week_income',['pay_data' => $topayData,'type' => 4, 'back_time' => $time, 'create_order' => $time],['id' => $id]);
                    $this->addLog($bindid,1,'提现异常30001:--数据库事务异常--'.json_encode(array($uid, $wxopenid, $price)));
                    return array('errcode'=>30001,'errmsg'=> '数据写入失败');
                 }else{
                    //先查询本条收益是否已为已返状态？
                    $record = $this->db->select('type')->get_where('pm_ujb_week_income',['id' => $id])->row_array();
                    if ($record['type'] != 2) {
                        //插入流水
                        $this->db->insert('pm_ujb_cashback_record',$water);
                        //将本条周收益置为已返状态
                        $this->db->update('pm_ujb_week_income',['pay_data' => $topayData,'pay_res' => $topayRes,'type' => 2, 'back_time' => $time, 'create_order' => $time, 'success_order' => $time,'tip' => '提取成功'],['id' => $id]);
                    }
                    $this->db->trans_commit();
                    return array('errcode'=>0,'errmsg'=> '提取成功');
                 }
            }else{
                $this->db->trans_rollback();
                //储存支付信息
                $this->db->update('pm_ujb_week_income',['pay_data' => $topayData,'type' => 4, 'back_time' => $time, 'create_order' => $time],['id' => $id]);
                log_message('error','提现异常30002:'.json_encode(array($uid, $wxopenid, $price)));
                $this->addLog($bindid,1,'提现异常30002:'.json_encode(array($uid, $wxopenid, $price)) . '--return_msg::' . $msg);
                return array('errcode'=>30002,'errmsg'=> '微信提现失败');
            }
        }catch(Exception $e){
            $this->db->trans_rollback();
            log_message('error','提现异常30003:'.json_encode(array($uid, $wxopenid, $price)).$e->getMessage());
            $this->addLog($bindid,1,'提现异常30003:'.json_encode(array($uid, $wxopenid, $price)).$e->getMessage());
            return array('errcode'=>30003,'errmsg'=> '支付异常');
        }
    }

    public function addLog($bindid,$type = 1,$content = '')
    {
        $this->db->insert('pm_ujb_cashback_log',['bindid' => $bindid,'type' => $type, 'content' => $content]);
    }

     /**
     * 微信保单即返
     * @param [type] $uid [description]
     */
    public function wxInsureMoney($id, $bindid,$wxopenid, $price,$orderFontWord = 'UJBJF',$tip = '购保返现')
    {
        $time = date('Y-m-d H:i:s');
        // 开启事务
        $this->db->trans_begin();
        try{
            //取出该条记录
            $record   = $this->db->select('uid,pay_data')->get_where('pm_ujb_insure_list',['id' => $id])->row_array();
            // echo '<pre>';print_r($water);die;
            $uid      = $record['uid'];
            $jpayData = '';
            if (!empty($record['pay_data'])) {
                $jpayData = $record['pay_data'];
                $payData  = json_decode($record['pay_data'],true);
                $createSn = $payData['partner_trade_no'];
            } else {
                $createSn = $this->createSn($id, 'UJBJF');
            }
            $water1 = array(
                'uid'          => $record['uid'],
                'bindid'       => $bindid,
                'type'         => 1,
                'price'        => $price,
                'collect_date' => date('Y-m-d'),
            );
            $water2 = array(
                'order_code'   => $createSn,
                'uid'          => $record['uid'],
                'bindid'       => $bindid,
                'type'         => 2,
                'price'        => $price,
                'collect_date' => date('Y-m-d'),
            );
            log_message('debug','wxInsureMoney::' . print_r($water2,true));
            // echo '<pre>';print_r($createSn);die;
            list($succ, $d, $res, $msg) = $this->_requestWxPay($createSn, $wxopenid, $price * 100, '优驾保购保返现', $jpayData, $checkName = 'NO_CHECK');
            // $succ = 1;
            $topayData = '';
            $topayRes  = '';
            if (!empty($d)) {
                $topayData = json_encode($d);
            }
            if (!empty($res)) {
                $topayRes = json_encode($res);
            }
            if($succ == 1){
                 if ($this->db->trans_status() === FALSE){
                    log_message('error','提现异常30001:'.json_encode(array($uid, $wxopenid, $price)));
                    $this->db->trans_rollback();
                    //储存支付信息
                    $this->db->update('pm_ujb_insure_list',['pay_data' => $topayData,'is_cash_back' => 4, 'back_time' => $time, 'create_order' => $time],['id' => $id]);
                    $this->addLog($bindid,1,'提现异常30001:--数据库事务异常--'.json_encode(array($uid, $wxopenid, $price)));
                    return array('errcode'=>30001,'errmsg'=> '数据写入失败');
                 }else{
                    //先查询本条收益是否已为已返状态？
                    $record = $this->db->select('is_cash_back')->get_where('pm_ujb_insure_list',['id' => $id])->row_array();
                    if ($record['is_cash_back'] != 3) {
                        //插入流水
                        $this->db->insert('pm_ujb_cashback_record',$water1);
                        $this->db->insert('pm_ujb_cashback_record',$water2);
                        //将本条收益置为已返状态
                        $this->db->update('pm_ujb_insure_list',['pay_data' => $topayData,'pay_res' => $topayRes,'is_cash_back' => 3, 'back_time' => $time, 'create_order' => $time, 'success_order' => $time,'tip' => '提取成功'],['id' => $id]);
                    }
                    $this->db->trans_commit();
                    return array('errcode'=>0,'errmsg'=> '提取成功');
                 }
            }else{
                $this->db->trans_rollback();
                //储存支付信息
                $this->db->update('pm_ujb_insure_list',['pay_data' => $topayData,'is_cash_back' => 4, 'back_time' => $time, 'create_order' => $time],['id' => $id]);
                log_message('error','提现异常30002:'.json_encode(array($uid, $wxopenid, $price)));
                $this->addLog($bindid,1,'提现异常30002:'.json_encode(array($uid, $wxopenid, $price)) . '--return_msg::' . $msg);
                return array('errcode'=>30002,'errmsg'=> '微信提现失败');
            }
        }catch(Exception $e){
            $this->db->trans_rollback();
            log_message('error','提现异常30003:'.json_encode(array($uid, $wxopenid, $price)).$e->getMessage());
            $this->addLog($bindid,1,'提现异常30003:'.json_encode(array($uid, $wxopenid, $price)).$e->getMessage());
            return array('errcode'=>30003,'errmsg'=> '支付异常');
        }
    }

    /**
     * [cashToLubiWallet 购保返现金额打入路比大使账号的钱包]
     * @return [type] [description]
     */
    // public function cashToLubiWallet($lubiId,$price)
    // {
    //     print_r($price);die;
    // }

    /**
     * checkhash
     * @param  [type] $uid             [description]
     * @param  [type] $walletId        [description]
     * @param  [type] $affectiveAmount [description]
     * @param  [type] $extractedAmount [description]
     * @param  [type] $lastLogId       [description]
     * @param  [type] $hash            [description]
     * @return [type]                  [description]
     */
    public function checkHash($uid, $walletId, $affectiveAmount, $extractedAmount, $lastLogId, $hash)
    {
        $pass = $uid.$walletId.$affectiveAmount.$extractedAmount.$lastLogId;
        log_message('info', 'checkHash: '.$pass. ' hash:'.$hash. ' succ hash:'.$this->createHash($uid, $walletId, $affectiveAmount, $extractedAmount, $lastLogId));
        return $this->_ph->CheckPassword($pass, $hash);
    }

    /**
     * 创建hash
     * @param  [type] $uid             [description]
     * @param  [type] $walletId        [description]
     * @param  [type] $affectiveAmount [description]
     * @param  [type] $extractedAmount [description]
     * @param  [type] $lastLogId       [description]
     * @return [type]                  [description]
     */
    public function createHash($uid, $walletId, $affectiveAmount, $extractedAmount, $lastLogId)
    {
        $pass = $uid.$walletId.$affectiveAmount.$extractedAmount.$lastLogId;
        return $this->_ph->HashPassword($pass);
    }

    /**
     * 发起转账请求
     * @param  string 订单Id
     * @param  string openid
     * @param  int amount 分
     * @param  string desc
     * @param  string 支付数据
     * @param  string 如果check_name设置为FORCE_CHECK或OPTION_CHECK，则必填用户真实姓名   NO_CHECK 不检查
     * @return string re_user_name 收款用户真实姓名
     */
    protected function _requestWxPay($orderSn, $openid, $amount, $desc, $payData = '', $checkName = 'NO_CHECK', $reUserName = '')
    {
        $requestData = array();
        $payData = !empty($payData) ? json_decode($payData, true) : '';
        $isRe = '0';
        try {
            if (!empty($payData)) {
                // $nData = $payData['arg'];
                // $data['spbill_create_ip'] = $_SERVER['SERVER_ADDR'];
                // $isRe = '1';

                $data = array(
                    'mch_appid' => $this->_wxappid,
                    'mchid' => $this->_wxmchid,
                    //                'device_info' => '',
                    'nonce_str' => $payData['nonce_str'],
                    'partner_trade_no' => $orderSn,
                    'openid' => $openid,
                    'check_name' => $checkName,
                    'amount' => $amount, //转账数额  单位分
                    'desc' => $desc,
                    'spbill_create_ip' => $_SERVER['SERVER_ADDR']
                );

                if ($checkName != 'NO_CHECK') {
                    $data['re_user_name'] = $reUserName;
                }
            } else {
                $data = array(
                    'mch_appid' => $this->_wxappid,
                    'mchid' => $this->_wxmchid,
                    //                'device_info' => '',
                    'nonce_str' => $this->randomStr(32),
                    'partner_trade_no' => $orderSn,
                    'openid' => $openid,
                    'check_name' => $checkName,
                    'amount' => $amount,
                    'desc' => $desc,
                    'spbill_create_ip' => $_SERVER['SERVER_ADDR']
                );

                if ($checkName != 'NO_CHECK') {
                    $data['re_user_name'] = $reUserName;
                }
            }

            ksort($data);

            $parmStr = http_build_query($data);
            $parmStr = urldecode($parmStr);
            $parmStr .= '&key=' . $this->_wxmchkey;
            $sign = strtoupper(md5($parmStr));

            $data['sign'] = $sign;

            $data_xml = '<xml>';
            foreach ($data as $k => $v) {
                $data_xml .= '<' . $k . '>';
                $data_xml .= $v;
                $data_xml .= '</' . $k . '>';
            }
            $data_xml .= '</xml>';

            log_message('debug', 'curl_post_ssl request : ' . $data_xml);
            $request = $this->_curl_post_ssl('https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers', $data_xml);
            if ($request) {
                $result = simplexml_load_string($request, 'SimpleXMLElement', LIBXML_NOCDATA);
                if (!isset($result)) {
                    log_message('error', 'request_wx_pay decode body failed: ' . $request);
                    return array(false, $data, false, '解析错误');
                }
                if ($result->return_code != 'SUCCESS') {
                    return array(false, $data, false, is_array($result->return_msg) ? current($result->return_msg) : $result->return_msg . ':' . $isRe);
                }
                if ($result->result_code != 'SUCCESS') {
                    log_message('error', 'request_wx_pay result_code failed: ' . $result->err_code_des);
                    return array(false, $data, false, is_array($result->err_code_des) ? current($result->err_code_des) : $result->err_code_des . ':' . $isRe);
                }

                return array(true, $data, array('partner_trade_no' => $result->partner_trade_no, 'payment_no' => $result->payment_no, 'payment_time' => $result->payment_time),'success');
            } else {
                return array(false, $data, false, '执行异常#3');
            }
        } catch (Exception $e) {
            // log_message('error', 'request exception:' . $e->getMessage());
            return array(false, $data, false, '执行异常#2' . $e->getMessage());
        }
    }

    /**
     * [createSn 生成订单号]
     * @param  [type] $id [周收益id]
     * @param  string $p  [前缀] UIBJF 优驾保即返  UJBWF优驾保周返 ....
     * @return [string]     [最终订单号]
     */
    public function createSn($id,$p = 'UJBJF')
    {
        $orderSn  = '';
        $orderSn .= $p . date('ymdHis');
        $orderSn .= rand(100,999);
        $orderSn .= str_pad($id, 5, '0', STR_PAD_LEFT);
        return $orderSn;
    }

    /**
     *  生成指定长度的随机字符串(包含大写英文字母, 小写英文字母, 数字)
     *
     * @param int $length 需要生成的字符串的长度
     * @return string 包含 大小写英文字母 和 数字 的随机字符串
     */
    public function randomStr($length)
    {
        //生成一个包含 大写英文字母, 小写英文字母, 数字 的数组
        $arr = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));

        $str = '';
        $arr_len = count($arr);
        for ($i = 0; $i < $length; $i++)
        {
            $rand = mt_rand(0, $arr_len - 1);
            $str .= $arr[$rand];
        }

        return $str;
    }

    public function getLogList($uid, $type, $page, $pagesize)
    {
        $page = ($page - 1) * $pagesize;
        $limit = $page.','.$pagesize;
        $type = (int) $type;
        $sql = "SELECT uid, type, amount, source, created FROM {$this->logTable} WHERE type = ? AND uid = ? ORDER BY id DESC LIMIT ".$limit;
        $list = $this->db->query($sql, array($type, $uid))->result_array();
        // var_dump($this->db->last_query());
        return $list;
    }

    private function _curl_post_ssl($url, $vars, $second = 30, $aHeader = array())
    {
        $ch = curl_init();
        //超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        //以下两种方式需选择一种

        //第一种方法，cert 与 key 分别属于两个.pem文件
        //默认格式为PEM，可以注释
        //curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        //curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/cert.pem');
        //默认格式为PEM，可以注释
        //curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        //curl_setopt($ch,CURLOPT_SSLKEY,getcwd().'/private.pem');

        //第二种方式，两个文件合成一个.pem文件
        curl_setopt($ch, CURLOPT_SSLCERT, $this->_wxmchca);

        if (count($aHeader) >= 1) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        $data = curl_exec($ch);
        log_message('info', '_curl_post_ssl'." url:{$url} CURLOPT_SSLCERT:{$this->_wxmchca} POST:{$vars} DATA:{$data}");
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            log_message('error', '_curl_post_ssl call faild, errorCode:' . $error);
            curl_close($ch);
            return false;
        }
    }
}
