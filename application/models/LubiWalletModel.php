<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require_once APPPATH.'libraries/PasswordHash.php';

/**
 * @author ModelCreater
 * +2016-08-15 12:56:22
 */
class LubiWalletModel extends CI_Model
{
    public $table = 'lubidashi.pm_wallet';

    public $logTable = 'lubidashi.pm_wallet_log';

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
        'updated'
    );

    public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database($this->_dbName, true);
        $this->_ph = new PasswordHash;
    }

    /**
     * 创建钱包
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public function createWallet($uid)
    {
        $data = $this->getWallet($uid);
        if ($data['id'] == 0)
        {
            $sql = "INSERT INTO {$this->table}(uid, effective_amount, extracted_amount) VALUES(?,?,?)";
            $this->db->query($sql, array($data['uid'], $data['effective_amount'], $data['extracted_amount']));
            $data['id'] = $this->db->insert_id();
            $lastLog = $this->getLastLog($uid);
            $hash = $this->createHash($uid, $data['id'], $data['effective_amount'], $data['extracted_amount'], $lastLog['id']);
            $sql = "UPDATE {$this->table} SET hash = ? WHERE id = ?";
            $this->db->query($sql, array($hash, $data['id']));
            log_message('info', '创建钱包:'.$uid, ' hash:'.$hash);
        }

        return $data['id'];
    }

    /**
     * 查询钱包
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public function getWallet($uid)
    {
        $sql = "SELECT id, uid, effective_amount, extracted_amount FROM {$this->table} WHERE uid = ?";
        $data = $this->db->query($sql, array($uid))->row_array();
        if (!empty($data))
        {
            return $data;
        }
        return array('id' => 0,'uid' => $uid, 'effective_amount' => 0, 'extracted_amount' => 0);
    }

    /**
     * 增加收入
     * @param [type] $uid    [description]
     * @param [type] $aid    [description]
     * @param [type] $amount [description]
     */
    public function addAffectiveAmount($uid, $amount, $aid, $sourceType = 0, $source = '', $key = 0)
    {
        list($uid, $amount, $aid, $sourceType, $source, $key) = array((int) $uid, (int) $amount, (int) $aid, (int) $sourceType, trim($source), (int) $key);

        $walletId = $this->createWallet($uid);
        // 开启事务
        $this->db->trans_begin();
        try
        {
            // 使用主键索引进行innodb(FOR UPDATE 排他锁)行级锁
            $sql = "SELECT id, uid, effective_amount, extracted_amount, hash FROM {$this->table} WHERE id = ? FOR UPDATE";
            $data = $this->db->query($sql, array($walletId))->row_array();
            $lastLog = $this->getLastLog($uid);

            $ok = $this->checkHash($uid, !empty($lastLog['wallet_id']) ? $lastLog['wallet_id'] : $walletId, $data['effective_amount'], $data['extracted_amount'], $lastLog['id'], $data['hash']);
            if (!$ok)
            {
                $this->db->trans_rollback();
                log_message('info','增加收入失败 hash效验失败#0:'.json_encode(array($uid, $aid, $amount, $sourceType, $source)));
                return array(false, '增加收入失败#0 hash效验失败');
            }

            $effectiveAmount = $data['effective_amount'] + $amount;
            $extractedAmount = $data['extracted_amount'];

            $sql = "INSERT {$this->logTable}(wallet_id, uid, aid, amount, source_type, source, `key`, type) VALUES(?,?,?,?,?,?,?,?)";
            $this->db->query($sql, array($walletId, $uid, $aid, $amount, $sourceType, $source, $key, 0));
            $lastLogId = $this->db->insert_id();

            $hash = $this->createHash($uid, $walletId, $effectiveAmount, $extractedAmount, $lastLogId);
            $sql = "UPDATE {$this->table} SET effective_amount = ?, hash = ? WHERE id = ?";
            $this->db->query($sql, array($effectiveAmount, $hash, $walletId));
        }
        catch(Exception $e)
        {
            $this->trans_rollback();
            log_message('info','增加收入失败#1:'.json_encode(array($uid, $aid, $amount, $sourceType, $source)).$e->message);
            return array(false, '增加收入失败#1');
        }

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            log_message('info','增加收入失败#2:'.json_encode(array($uid, $aid, $amount, $sourceType, $source)));
            return array(false, '增加收入失败#2');
        }
        else
        {
            $this->db->trans_commit();
        }
        log_message('info','增加收入成功:'.json_encode(array($uid, $aid, $amount, $sourceType, $source)));
        return array(true, '操作成功');
    }

    /**
     * 取得last日志ID
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public function getLastLog($uid)
    {
        // $sql = "SELECT id, wallet_id FROM {$this->logTable} WHERE uid = ? AND type IN (0, 1) ORDER BY id DESC LIMIT 1";
        $sql = "SELECT id, wallet_id FROM {$this->logTable} WHERE uid = ? ORDER BY id DESC LIMIT 1";
        $data = $this->db->query($sql, array($uid))->row_array();
        if (empty($data))
        {
            return array('id' => $this->_defaultLogId, 'wallet_id' => 0);
        }
        return $data;
    }

    /**
     * 增加支出
     * @param [type] $uid [description]
     */
    public function addExtractedAmount($uid, $openid, $amount = 0, $payType = 'wx', $sourceType = 0, $source = '提现')
    {
        list($uid, $openid, $amount, $payType, $sourceType, $source) = array((int) $uid, trim($openid), (int) $amount, trim($payType), (int) $sourceType, trim($source));

        $data = $this->getWallet($uid);
        $walletId = $data['id'];

        if ($uid == 0 || empty($openid))
        {
            return array(false, '用户数据不正确');
        }

        if ($walletId == 0)
        {
            return array(false, '没有可提现金');
        }

        // 开启事务
        $this->db->trans_begin();
        try
        {
            $sql = "SELECT id, uid, effective_amount, extracted_amount, hash FROM {$this->table} WHERE id = ? FOR UPDATE";
            $walletData = $this->db->query($sql, array($walletId))->row_array();
            $lastLog = $this->getLastLog($uid);
            $ok = $this->checkHash($uid, !empty($lastLog['wallet_id']) ? $lastLog['wallet_id'] : $walletId, $data['effective_amount'], $data['extracted_amount'], $lastLog['id'], $walletData['hash']);
            if (!$ok)
            {
                $this->db->trans_rollback();
                log_message('info','提现失败 hash效验失败#0:'.json_encode(array($uid, $openid, $amount, $payType, $sourceType, $source)));
                return array(false, '提现失败 hash效验失败');
            }

            if ($amount == 0)
            {
                $amount = $walletData['effective_amount'];
            }

            if ($amount > $walletData['effective_amount'] || $walletData['effective_amount'] == 0)
            {
                $this->trans_rollback();
                log_message('info','提取金额不正确:'.json_encode(array($uid, $openid, $amount, $payType, $sourceType, $source)).' 实际钱包金额:'.$walletData['effective_amount']);
                return array(false, '提取金额不正确');
            }
            $effectiveAmount = $walletData['effective_amount'] - $amount;
            $extractedAmount = $walletData['extracted_amount'] + $amount;

            $sql = "SELECT id, pay_data, pay_count FROM {$this->logTable} WHERE type = 2 AND uid = ?";
            $data = $this->db->query($sql, array($uid))->row_array();

            if (empty($data))
            {
                $sql = "INSERT {$this->logTable}(wallet_id, uid, pay_type, amount, source_type, source, type) VALUES(?,?,?,?,?,?,?)";
                $this->db->query($sql, array($walletId, $uid, $payType, $amount, $sourceType, $source, 2));
                $lastLogId = $this->db->insert_id();
                $payCount = 0;
            }
            else
            {
                $lastLogId = $data['id'];
                $payCount = $data['pay_count'];
            }

            $type = 2;
            if ($payType == 'wx')
            {
                list($succ, $d, $res, $msg) = $this->_requestWxPay($this->createSn($lastLogId), $openid, $amount, $sourceType, $data['pay_data'], $checkName = 'NO_CHECK');

                if ($succ)
                {
                    $type = 1;
                }

                $payCount++;
            }
            else
            {
                log_message('info','不支持的支付方式:'.json_encode(array($uid, $openid, $amount, $payType, $sourceType, $source)));
                $this->trans_rollback();
                return array(false, '不支持的支付方式');
            }

            $payData['arg'] = $d;
            $payData['res'] = $res;
            $sql = "UPDATE {$this->logTable} SET amount = ?, pay_type = ?, pay_data = ?, pay_count = ? , type = ? WHERE id = ?";
            $this->db->query($sql, array($amount, $payType, json_encode($payData), $payCount, $type , $lastLogId));

            if ($type == 1)
            {
                $hash = $this->createHash($uid, $walletId, $effectiveAmount, $extractedAmount, $lastLogId);
                $sql = "UPDATE {$this->table} SET effective_amount = ?, extracted_amount = ?, hash = ? WHERE id = ?";
                $this->db->query($sql, array($effectiveAmount, $extractedAmount, $hash, $walletId));
            }
            else
            {
                $hash = $this->createHash($uid, $walletId, $walletData['effective_amount'], $walletData['extracted_amount'], $lastLogId);
                $sql = "UPDATE {$this->table} SET hash = ? WHERE id = ?";
                $this->db->query($sql, array($hash, $walletId));
            }
        }
        catch(Exception $e)
        {
            $this->trans_rollback();
            log_message('info','提现异常:'.json_encode(array($uid, $openid, $amount, $payType, $sourceType, $source)).$e->message);
            return array(false, '提现异常');
        }

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            log_message('info','提现失败#2:'.json_encode(array($uid, $openid, $amount, $payType, $sourceType, $source)));
            return array(false, '提现失败#2');
        }
        else
        {
            $this->db->trans_commit();
        }

        if ($type == 1)
        {
            log_message('info','提现成功:'.json_encode(array($uid, $openid, $amount, $payType, $sourceType, $source)));
            return array(true, '提现成功');
        }
        else
        {
            // $payData = array();
            // $payData['arg'] = $d;
            // $payData['res'] = $res;
            // $payData = json_encode($payData);
            // $sql = "UPDATE {$this->logTable} SET amount = ?, pay_data = ?, type = ? WHERE id = ?";
            log_message('info','微信提取失败:'.$msg.json_encode(array($uid, $openid, $amount, $payType, $sourceType, $source)));
            return array(false, '微信提取失败:'.$msg);
        }
    }

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
                $nData = $payData['arg'];
                // $data['spbill_create_ip'] = $_SERVER['SERVER_ADDR'];
                // $isRe = '1';

                $data = array(
                    'mch_appid' => WX_APPID,
                    'mchid' => WX_MCHID,
                    //                'device_info' => '',
                    'nonce_str' => $nData['nonce_str'],
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
            } else {
                $data = array(
                    'mch_appid' => WX_APPID,
                    'mchid' => WX_MCHID,
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
            $parmStr .= '&key=' . WX_MCHKEY;
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
            $request = $this->curl_post_ssl('https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers', $data_xml);
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

                return array(true, $data, array('partner_trade_no' => $result->partner_trade_no, 'payment_no' => $result->payment_no, 'payment_time' => $result->payment_time));
            } else {
                return array(false, $data, false, '执行异常#3');
            }
        } catch (Exception $e) {
            // log_message('error', 'request exception:' . $e->getMessage());
            return array(false, $data, false, '执行异常#2' . $e->getMessage());
        }
    }

    public function createSn($id)
    {
        $idStrL = strlen(strval($id));
        $str = '';
        for ($i = 0; $i < 8 - $idStrL; $i++)
        {
            $str .= '0';
        }

        return 'DS'.$str.strval($id);
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

    private function curl_post_ssl($url, $vars, $second = 30, $aHeader = array())
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
        curl_setopt($ch, CURLOPT_SSLCERT, WX_MCHCA);

        if (count($aHeader) >= 1) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        $data = curl_exec($ch);
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            log_message('error', 'call faild, errorCode:' . $error);
            curl_close($ch);
            return false;
        }
    }
}
