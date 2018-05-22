<?php
defined('BASEPATH') OR exit('No direct script access allowed');
    //打印函数
    function D($array){
    	echo '<pre>';
    	print_r($array);
    	echo '</pre>';
    	die;
    }

    /*
    * 判断权限（$id 操作时需要的权限ID）
    */
    function checkAuth($id){
        $auth = $_SESSION['account']['menu_id'];
        if(in_array($id,$auth)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * [sms 发送短信]
     * @param  [type] $mobile [手机]
     * @param  [type] $txt   [内容]
     * @return [type]         [description]
     */
    function sms($mobile,$code)
    {
        $cpid  = 'kvtz69';
        $cppwd = '0KzhAfKo';
        $txt = urlencode("您的验证码为{$code}");
        // http接口，支持 https 访问，如有安全方面需求，可以访问 https开头
        $api = "http://api2.santo.cc/submit?command=MT_REQUEST&cpid={$cpid}&cppwd={$cppwd}&da=86{$mobile}&sm={$txt}";
        // 建议记录 $resp 到日志文件，$resp里有详细的出错信息
        $request = Requests::get($api);
        if ($request->status_code == 200) {
            $resp = json_decode($request->body);
            preg_match('/mtmsgid=(.*?)&/', $resp, $re);
            if (!empty($re) && count($re) >= 2)
                return $re[1];
        } else {
            log_message('error', '发送短信短信[' . $code . ']至[' . $mobile . ']失败,服务器返回错误码[' . $request->status_code . ']');
        }
        return false;
    }

    function random_str($length)
    {
        //生成一个包含 大写英文字母, 小写英文字母, 数字 的数组
        $arr = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));

        $str     = '';
        $arr_len = count($arr);
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $arr_len - 1);
            $str .= $arr[$rand];
        }

        return $str;
    }

    // function debugInfo($level,$msg)
    // {
    //     return $this->db->insert('md_error_info',[
    //         'level' => 'debug',
    //         'msg'   => $msg,
    //     ]);
    // }
