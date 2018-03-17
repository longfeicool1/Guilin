<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    /**
     * 不需要登录过滤的路径
     * @var array
     */
    protected $_noLoginFilter = array(
        'login',
        'login/captcha',
        'login/loginOut',
        'login/phoneCode',
    );

    /**
     * 构造方法
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('AccountHelper', null, 'AccountHelper');
        $isLogin = $this->AccountHelper->isLogin();
        $urlString = $this->uri->uri_string();
        if (!$isLogin && $urlString != 'login') {
            if (!in_array($urlString, $this->_noLoginFilter)) {
                redirect('/login');
            }
        }
        $this->userinfo = $this->session->userdata('account');
        $this->ci_smarty->assign('userinfo',$this->session->userdata('account'));
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
    *message string  可选。信息内容。
    *tabid   string  可选。待刷新navtab id，多个id以英文逗号分隔开，当前的navtab id不需要填写，填写后可能会导致当前navtab重复刷新。
    * dialogid    string  可选。待刷新dialog id，多个id以英文逗号分隔开，请不要填写当前的dialog id，要控制刷新当前dialog，请设置dialog中表单的reload参数。
    *divid   string  可选。待刷新div id，多个id以英文逗号分隔开，请不要填写当前的div id，要控制刷新当前div，请设置该div中表单的reload参数。
    *closeCurrent    boolean 可选。是否关闭当前窗口(navtab或dialog)。
    * forward string  可选。跳转到某个url。
    *forwardConfirm  string  可选。跳转url前的确认提示信息。
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

}
