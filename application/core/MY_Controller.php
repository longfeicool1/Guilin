<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    /**
     * 配合UI前端使用
     * @var integer
     */
    protected $_tabId = 0;

    /**
     * 当前登录用户数据
     * @var integer
     */
    protected $_account;

    /**
     * 不需要登录过滤的路径
     * @var array
     */
    protected $_noLoginFilter = array(
        'login',
        'login/captcha',
        'login/loginOut',
        'push/push/push',
    );

    /**
     * 构造方法
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->library('AccountHelper', null, 'AccountHelper');
        $this->load->library('AuthHelper', null, 'AuthHelper');
        $isRirect = false;
        $isIndex  = false;

        $isLogin = $this->AccountHelper->isLogin();

        $urlString = $this->uri->uri_string();
        if (empty($urlString) || $urlString == '/') {
            $isIndex = true;
        }

        // 判断登录
        if (!$isLogin && $this->uri->uri_string() != 'login') {
            if (!in_array($urlString, $this->_noLoginFilter)) {
                $isRirect = true;
            }
        } else {
            $this->_account = $this->AccountHelper->info();

        }
//        $this->session->set_userdata('isSendMsg',true);
        $msgCode   = $this->session->userdata('msgCode');
        $isSendMsg = $this->session->userdata('isSendMsg');
//        $msgCode = false;

        if (!$isIndex && !empty($this->_account['group']) && $urlString != 'manage/auth/checkSendMsg') {
            $groupIds  = implode(",", $this->_account['group']);
            $is_mobile = $this->AccountHelper->isSendMsg($groupIds);

            if ($is_mobile && $msgCode && !$isSendMsg) {
                redirect('/manage/auth/checkSendMsg?mobile=' . $this->_account['mobile']);
                return;
//                exit;
            }
        }

        if ($isLogin && !in_array($urlString, $this->_noLoginFilter) && !$isIndex && $urlString != 'manage/account/editPassMe') {
            // 第一次登录修改密码
            if (!$isIndex && $this->_account['update_password_num'] == 0) {

                redirect('/manage/account/editPassMe?msg=' . urlencode('首次登录请修改密码!'));
            }

            // 超过180天强制提示修改密码
            if (!$isIndex && (time() - $this->_account['last_update_time'] > (90 * 86400))) {
                redirect('/manage/account/editPassMe?msg=' . urlencode('超过180天没有修改密码, 请修改密码!'));
            }

            // 判断用户是否拥有权限
            $this->_tabId = $this->AuthHelper->isAllow($this->_account, $urlString);
            if (!$isIndex && $this->_tabId == -1) {

                echo 'Permission denied!';
                exit;
            }

            if ($this->_account['enable'] == 2) {
                echo 'Account is not Active!';
                exit;
            }
        }

        if ($isRirect) {
            if ($isIndex) {

                redirect('/login');
            } else {
                echo 'Please refresh the page!';
                exit;
            }
        }

        // function SQLWriter()
        // {
        //     $account = get_instance()->AccountHelper->info();
        //     $url = get_instance()->uri->uri_string();
        //     foreach(get_instance()->db->queries as $k => $v)
        //     {
        //         $ip = get_instance()->input->ip_address();
        //         $uid = isset($account['id']) ? $account['id'] : '000';
        //         $t = round(get_instance()->db->query_times[$k], 2);
        //         logger('M1', $url, $ip, 'U'.$uid, $t, $v);
        //     }
        // }

        // register_shutdown_function("SQLWriter");
    }

    /**
     * 判断是否POST提交
     * @return bool
     */
    protected function isPost()
    {
        return isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST';
    }
    /**
     * 判断是否GET提交
     * @author laferm
     * @return bool
     */
    protected function isGet()
    {
        return isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'GET';
    }

    /**
     * [bjuiRes description]
     *
     *  statusCode   int 必选。状态码(ok = 200, error = 300, timeout = 301)，可以在BJUI.init时配置三个参数的默认值。
    message string  可选。信息内容。
    tabid   string  可选。待刷新navtab id，多个id以英文逗号分隔开，当前的navtab id不需要填写，填写后可能会导致当前navtab重复刷新。
    dialogid    string  可选。待刷新dialog id，多个id以英文逗号分隔开，请不要填写当前的dialog id，要控制刷新当前dialog，请设置dialog中表单的reload参数。
    divid   string  可选。待刷新div id，多个id以英文逗号分隔开，请不要填写当前的div id，要控制刷新当前div，请设置该div中表单的reload参数。
    closeCurrent    boolean 可选。是否关闭当前窗口(navtab或dialog)。
    forward string  可选。跳转到某个url。
    forwardConfirm  string  可选。跳转url前的确认提示信息。
     * @param  boolen  $statusCode     true or false
     * @param  string  $message
     * @param  string  $tabid
     * @param  boolean $closeCurrent
     * @param  string $forward
     * @param  string $forwardConfirm
     * @return json
     */
    public function bjuiRes($statusCode, $message, $tabid = "", $closeCurrent = true, $forward = "", $forwardConfirm = "", $attr = array())
    {
        if (empty($tabid)) {
            //$tabid = 'id'.$this->AuthHelper->getMenuId($this->uri->uri_string());
        }

        $output = array(
            "statusCode"     => is_bool($statusCode) ? ($statusCode == true ? 200 : 300) : $statusCode,
            "message"        => $message,
            "tabid"          => $tabid,
            "closeCurrent"   => $closeCurrent,
            "forward"        => $forward,
            "forwardConfirm" => $forwardConfirm,
        );

        if (!empty($attr)) {
            $output = array_merge($output, $attr);
        }
        return json_encode($output);
    }

    public function output($error, $error_message = '')
    {
        $output['result']  = $error;
        $output['message'] = $error_message;

        $this->output->set_content_type('application/json', 'utf-8');
        $this->output->set_output(json_encode($output));
        $this->output->_display();
        die;
    }

    public function output_result($data)
    {
        $output['result'] = 0;
        $output['data']   = $data;

        $this->output->set_content_type('application/json', 'utf-8');
        $this->output->set_output(json_encode($output));
        $this->output->_display();
        die;
    }

    public function output_json($output)
    {
        $this->output->set_content_type('application/json', 'utf-8');
        $this->output->set_output(json_encode($output));
        $this->output->_display();
        die;
    }
    //生成token
    public function qiniuToken()
    {
        $configName = 'qiniu';
        $this->config->load($configName, true);
        $drCommon = $this->config->item('dr-common', $configName);
        $auth      = new Qiniu\Auth($drCommon['accessKey'], $drCommon['secretKey']);
        // 要上传的空间
        $bucket = $drCommon['bucket'];
        // 设置put policy的其他参数
        $opts = array();
        // 生成上传 Token
        $token = $auth->uploadToken($bucket, null, 3600, $opts);

        $this->assign('qiniuDomain', $drCommon['domain']);
        return $token;
    }

    protected function request($uri, $parameter, $type = 'user')
    {
        switch ($type) {
            case 'user':
                try {
                    log_message('debug', 'request url:' . USER_SERVER . $uri . print_r($parameter, true));
                    $request = Requests::post(USER_SERVER . $uri, array(), $parameter);
                    if ($request->status_code == 200) {
                        log_message('debug', 'request body json:' . print_r($request->body, true));
                        $result = json_decode($request->body);
                        if (isset($result) && isset($result->errcode)) {
                            if ($result->errcode == 10002 || // token 过期
                                $result->errcode == 10003// token 错误
                            ) {
                                get_instance()->session->unset_userdata('uid');
                                get_instance()->session->unset_userdata('token');
                                get_instance()->session->unset_userdata('loginname');
                                get_instance()->session->unset_userdata('username');
                                redirect('/login');
                            }
                        }
                        return json_decode($request->body);
                    } else {
                        log_message('error', 'request status_code:' . $request->status_code);
                    }
                } catch (Exception $e) {
                    log_message('error', 'request exception:' . $e->getMessage());
                }
                break;
            case 'post':
                try {
                    $request = Requests::post($uri, array(), $parameter);
                    if ($request->status_code == 200) {
                        log_message('debug', 'request body json:' . print_r($request->body, true));
                        return $request;
                    } else {
                        log_message('error', 'request status_code:' . $request->status_code);
                    }
                } catch (Exception $e) {
                    log_message('error', 'request exception:' . $e->getMessage());
                }
                return null;
            default: //get请求
                try {
                    $real_uri = OBD_SERVER . $uri;
                    if (isset($parameter) && count($parameter) > 0) {
                        $real_uri .= '?' . http_build_query($parameter);
                    }
                    log_message('debug', 'request url:' . $real_uri);
                    $request = Requests::get($real_uri);
                    if ($request->status_code == 200) {
                        log_message('debug', 'request body json:' . print_r($request->body, true));
                        return json_decode($request->body);
                    } else {
                        log_message('error', 'request status_code:' . $request->status_code);
                    }
                } catch (Exception $e) {
                    log_message('error', 'request exception:' . $e->getMessage());
                }
                return null;
        }
        return null;
    }


    /**
     * 模板参数设置
     * @param $key
     * @param $data
     */
    public function assign($key, $data)
    {
        $this->ci_smarty->assign($key, $data);
    }

    /**
     * 模板显示
     * @param $fileName
     */
    public function display($fileName)
    {
        $this->ci_smarty->display($fileName);
    }

    /**
     * 获取默认页码和当前页
     * @param $data
     * @return array
     */
    public function getPageSizeAndCurrent($data)
    {
        $pageCurrent = isset($data['pageCurrent']) && intval($data['pageCurrent']) > 1 ? intval($data['pageCurrent']) : 1;
        $pageSize = isset($data['pageSize'])  && intval($data['pageSize']) > 1 ? $data['pageSize'] : 30;
        return [$pageCurrent, $pageSize];
    }

    /**
     * 打印执行的 SQL
     */
    public function sql()
    {
        die($this->db->last_query());
    }


    /**
     * 获取get值
     * @param null $key
     * @return mixed
     */
    public function get($key = null)
    {
        if ($key) {
            return $this->input->get($key);
        } else {
            return $this->input->get();
        }
    }

    /**
     * 获取post值
     * @param null $key
     * @return mixed
     */
    public function post($key = null)
    {
        if ($key) {
            return $this->input->post($key);
        } else {
            return $this->input->post();
        }
    }

    /**
     * 获取post或者get值
     * @param null $key
     * @return mixed
     */
    public function post_get($key = null)
    {
        if ($key) {
            return $this->input->post_get($key);
        } else {
            return $this->input->post_get();
        }
    }

    /**
     * 读取excel文件
     * @param $filename
     * @return array
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    public function _loadFromExcel($filename)
    {
        $objReader = PHPExcel_IOFactory::createReaderForFile($filename);
        $objReader->setReadDataOnly(true);
        $objPHPExcel        = $objReader->load($filename);
        $objWorksheet       = $objPHPExcel->getActiveSheet();
        $highestRow         = $objWorksheet->getHighestRow();
        $highestColumn      = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $excelData          = array();
        for ($row = 2; $row <= $highestRow; $row++) {
            $tempRow = array();
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $value = (string) $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
//                $value     = trim(html_entity_decode($value), chr(0xc2) . chr(0xa0)); # 字符'赵传'会出现乱码
                $tempRow[] = $value;
            }

            // if (count($tempRow)) {
            //     array_splice($tempRow, 0, 1);
            $excelData[] = $tempRow;
            // }
        }
        return $excelData;
    }

    /**
     * 推送消息
     * @param $msg
     * @return Requests_Response
     */
    public function push($msg)
    {
        if (in_array(strtolower($msg['systemName']), array('ios', 'android'))) {

            # 加入推送队列
            $data = array(
                'platform'   => 'ygbx',
                'content'    => [
                    "text"          => $msg['title'],//推送内容标题
                    "extendContent" => "", //额外内容
                    "type"          => "31", #消息类型     32 生日提醒  33 保险过期提醒  34 计划提醒
                    "msgType"       => $msg['msgType'], # 1 文本，2 图文 3 报告
                    "msgUrl"        => $msg['msgUrl'],
                    "data"          => [
                        "extendContent" => "", //额外内容
                        "msgType"       => $msg['msgType'], # 1 文本，2 图文 3 报告
                        "msgUrl"        => $msg['msgUrl'],
                    ],
                ],
                'systemName' => strtolower($msg['systemName']),
                'uid'        => $msg['pid'],
                'uniqueId'   => $msg['uniqueId'],
            );
            require_once APPPATH . 'libraries/Sign.php';
            $data['appId'] = 'admin';
            $data['sign']  = (new Sign)->cal('d41d8cd98f00b204e9800998ecf8427e', $data);
//          print_r($data); exit;
            return Requests::post(ENVIRONMENT == 'development' ? 'http://dev-api2.ubi001.com/api/app-notification' : 'http://api2.ubi001.com/api/app-notification', array(), $data);
        }
    }


}
