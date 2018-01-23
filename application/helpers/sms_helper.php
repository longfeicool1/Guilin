<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('sms_mobset')) {
    function sms_mobset($mobile, $content)
    {
        try {
            $wsdl = 'http://sms3.mobset.com:8080/Api?wsdl';
            $client = new SoapClient($wsdl);
            $client->soap_defencoding = 'utf-8';
            $client->decode_utf8 = false;
//            $errMsg = "";
//            $strSign = "";
            $addnum = "";
            $timer = "";
            $lCorpID = '302329';
            $strLoginName = 'Admin';
            $strPasswd = '10600083@qq.com';
            $mobPhone = $mobile;
            $smsContent = $content;
            $longSms = 1;
            $strTimeStamp = date('mdHis');
            $strInput = $lCorpID . $strPasswd . $strTimeStamp;
            $strMd5 = md5($strInput);
//            $groupId = $client->ArrayOfSmsIDList[1];
//            print_r($groupId);
            $group = array();
//            $group->pushMobileListGroup(new MobileListGroup($mobPhone, ''));
            $group[0]['Mobile'] = $mobPhone;

            $param = array(
                'CorpID' => $lCorpID,
                'LoginName' => $strLoginName,
                'Password' => $strMd5,
                'TimeStamp' => $strTimeStamp,
                'AddNum' => $addnum,
                'Timer' => $timer,
                'LongSms' => $longSms,
                'MobileList' => $group,
                'Content' => $smsContent
            );
            $result = $client->Sms_Send($param);
            log_message('debug', print_r($result, true));
            if ($result->Count > 0) {
                log_message('info', '发送短信:' . $content . ',至:' . $mobile . ',成功');
                return null;
            } else {
                log_message('error', '发送短信:' . $content . ',至:' . $mobile . ',失败:' . $result->ErrMsg);
                return $result->ErrMsg;
            }

        } catch (SoapFault $fault) {
            log_message('error', '发送短信:' . $content . ',至:' . $mobile . ',失败:' . $fault->faultstring);
            return $fault->faultstring;
        }
    }
}

if (!function_exists('sms')) {
    function sms($type, $mobile, $code)
    {
        $real_type = 1;

        if (strcasecmp($type, 'forgot') == 0) {
            $real_type = 2;
        }

        $args = array(
            'mobileNumber' => $mobile,
            'type' => $real_type,
            'params' => $code . '|' . REDIS_CAPTCHA_TTL
        );
        $args_str = '';
        ksort($args);
        foreach ($args as $k => $v) {
            $args_str .= $k . $v;
        }
        $token = md5('jindanlicai.com' . $args_str . 'jindanlicai.com');
        $args = array_merge($args, array('mac' => $token));

//        log_message('debug', 'args:' . print_r($args, true));

        $request = Requests::post('http://112.74.133.204:8080/dingran-common-1.0-SNAPSHOT/sms/sendAsyncTemplate.do', array(), $args);
//        log_message('debug', 'sms:' . print_r($request, true));
        if ($request->status_code == 200) {
            $result = json_decode($request->body);
            if (isset($result) &&
                $result->responseCode == 200
            ) {
                log_message('info', '发送短信短信[' . $code . ']至[' . $mobile . ']成功');
                return true;
            } else if (isset($result)) {
                log_message('error', '发送短信短信[' . $code . ']至[' . $mobile . ']失败,服务器返回[' . $result->msg . ']');
            } else {
                log_message('error', '发送短信短信[' . $code . ']至[' . $mobile . ']失败,解析服务器返回失败');
            }
        } else {
            log_message('error', '发送短信短信[' . $code . ']至[' . $mobile . ']失败,服务器返回错误码[' . $request->status_code . ']');
        }

        return false;
    }
}
