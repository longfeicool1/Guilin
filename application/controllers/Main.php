<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 首页控制器
 * @author  liuweilong
 * +2016-03-03
 */
class Main extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->extenid = in_array(2, $this->_account['group']) && count($this->_account['group']) == 1 ? $this->_account['id'] : '';
        $this->ci_smarty->assign('account', $this->_account);
    }

    public function test()
    {
        echo 1;exit;
        // $this->load->library('LogSender');
        // $sen = new LogSender();
        // // 10.169.103.99

        // $sen->setHost('120.24.99.41', 514);
        // echo $sen->send('BLOG_MANAGERTEST_SQL hello world');
//        log_message('debug', $result);

        // exit;
        // 
        // logger('M1', 'hello worldk1');
        // logger('M1', 'hello worldk2');
    }

    /**
     * 主页布局
     * @return html
     */
    public function index()
    {
        $this->ci_smarty->assign('menu', $this->AuthHelper->createTree($this->_account));
        $this->ci_smarty->display('main/index.tpl');
    }

    /*
     * 客服登入
     */
    public function homePage()
    {
        $this->ci_smarty->display('main/homePage.tpl');
    }

//  private

    /*
     * 今天预约
     */
    public function todayMeeting()
    {
        echo '';
    }

    /*
     * 客户生日--二周内的生日
     */
    public function customBirth()
    {
        echo '';
    }

    /*
     * 今日通话
     */
    public function todayRecord()
    {
        $exten = $this->extenid ? $this->_account['exten'] : '';
        $output = <<<EOT
        <script>
            getTodayRecord({$exten});
        </script>
EOT;
        echo $output;
    }

    /*
     * 预约客户统计
     */
    public function meetingCustom()
    {
        echo '';
    }

    /*
     * 数据漏斗
     */
    public function dataSet()
    {
        echo '';
    }

    /*
     * 名单栏统计
     */
    public function nameListCount()
    {
        echo '';
    }
}
