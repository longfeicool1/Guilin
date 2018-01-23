<?php
/**
 * 日志类
 */
class CI_LogSender extends LogSender
{
    protected $_config = array();

    public function __construct()
    {
        // $this->init();
    }

    public function init($newConfig = array())
    {
        if (empty($this->_config))
        {
            require __dir__.'/Config/rsyslog.php';
            $this->_config =  $config;
        }
        
        if (!empty($newConfig) && is_array($newConfig))
        {
            // var_dump($this->_config, $newConfig);
            $this->_config['relationship'] = array_merge($this->_config['relationship'], $newConfig);
        }

        return $this;
    }

    public function getRelationship()
    {
        return isset($this->_config['relationship']) ? $this->_config['relationship'] : array();
    }

    public function coreWriter($cmd)
    {

        $numargs = func_num_args();
        if ($numargs < 2) 
        {
            return array(false, '#1');
        }
        $argList = func_get_args();
        $logMessage = implode(' ', $argList);

        // echo $this->_config['host'], $this->_config['port'];
        // exit;
        $this->setHost($this->_config['host'], $this->_config['port']);

        if (!isset($this->_config['relationship'][$cmd]))
        {
            return array(false, '#2');
        }
        $argList[0] =  $this->_config['relationship'][$cmd];
        foreach ($argList as $key => $val) 
        {
            $argList[$key] = empty($val) ? '---' : str_replace(array("\r", "\r\n", "\n"), ' ', $val);
        }
        $logMessage = implode(' ', $argList);
        $this->send($logMessage);
        return array(true, 'succ');
    }
}

/**
 * Created by PhpStorm.
 * User: tyleryang
 * Date: 16/5/12
 * Time: 15:58
 */
class LogSender
{
    /**
     * @desc 日志所属的设备名称
     * @var int 数值在0-23之间
     * @access private
     */
    private $_facility = 23; // 0-23

    /**
     * @desc 日志的严重程度  数值在0-7区间
     * @var int
     * @access private
     */
    private $_severity = 6; // 0-7

    /**
     * @desc 服务器名称
     * @var string a-zA-Z0-9
     * @access private
     */
    private $_hostname = 'PHP-SERVER'; // no embedded space, no domain name, only a-z A-Z 0-9 and other authorized characters

    /**
     * @desc 服务名称
     * @var string
     * @access private
     */
    private $_fqdn;

    /**
     * @desc IP地址
     * @var string
     * @access private
     */
    private $_ipFrom;

    /**
     * @desc 进程名称
     * @var string
     * @access private
     */
    private $_process;

    /**
     * @desc 日志服务器地址
     * @var string
     * @access private
     */
    private $_host;   // Syslog destination server

    /**
     * @desc 日志服务器UDP端口
     * @var int
     * @access private
     */
    private $_port;     // Standard syslog port is 514

    /**
     * @desc UDP链接超时时间
     * @var int
     * @access private
     */
    private $_timeout = 1;  // Timeout of the UDP connection (in seconds)

    // private $_relationship = array();

    // private $_isset = 0;
    
    public function __construct()
    {
        $this->_fqdn = $_SERVER['SERVER_ADDR'];
        $this->_ipFrom = $_SERVER['SERVER_ADDR'];
        $this->_process = 'PHP' . getmypid();
    }

    // public function issets()
    // {
    //     return $this->_isset;
    // }

    // public function setRelationship($config)
    // {
    //     $this->_relationship = array_flip($config);
    // }

    // public function getCmd($key)
    // {
    //     return isset($this->_relationship[$key]) ? $this->_relationship[$key] : false;
    // }

    public function setHost($host, $port)
    {
        $this->_host = $host;
        $this->_port = $port;
        // $this->_isset = 1;
    }

    public function send($message, $severity = 6, $_timeout = 1)
    {
        if ($severity < 0) {
            $severity = 0;
        }
        if ($severity > 7) {
            $severity = 7;
        }

        if ($_timeout > 2) {
            $_timeout = 2;
        }

        $actualtime = time();
        $month = date("M", $actualtime);
        $day = substr("  " . date("j", $actualtime), -2);
        $hhmmss = date("H:i:s", $actualtime);
        $timestamp = $month . " " . $day . " " . $hhmmss;

        $pri = "<" . ($this->_facility * 8 + $this->_severity) . ">";
        $header = $timestamp . " " . $this->_hostname;
        $message = $this->_process . ": " . $this->_fqdn . " " . $this->_ipFrom . " " . $message;
        $message = substr($pri . $header . " " . $message, 0, 1024 * 2);
        $fp = fsockopen("udp://" . $this->_host, $this->_port, $errno, $errstr);

        if ($fp) {
            fwrite($fp, $message);
            fclose($fp);
            return true;
        }
        return false;
    }
}
