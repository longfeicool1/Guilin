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
        $this->load->model('manage/Auth_model','auth');
    }

    /**
     * 主页布局
     * @return html
     */
    public function index()
    {
        $userinfo = $this->session->userdata('account');
        $this->ci_smarty->assign('item', $this->auth->createMenu($userinfo['menu_id']));
        $this->ci_smarty->assign('userinfo', $userinfo);
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
//         $exten = $this->extenid ? $this->_account['exten'] : '';
//         $output = <<<EOT
//         <script>
//             getTodayRecord({$exten});
//         </script>
// EOT;
//         echo $output;
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
