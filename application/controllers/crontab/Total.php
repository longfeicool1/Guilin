<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require dirname(__FILE__) . '/Service.php';
/**
 *每天1点统计团队业绩
 * 10 1 * * * /usr/bin/php70 index.php crontab/team/start
 */
class Total extends Service
{
    public $uids;

    public function __construct()
    {
        parent::__construct();
    }

    public function start()
    {
        echo 'succ start';
        $this->getTeamData();
        echo 'succ end';
    }

    public function getTeamData()
    {
        $startTime  = date('Y-m-d',strtotime('-1 day'));
        $endTime    = date('Y-m-d');
        $startMonth = date('Y-m-01',strtotime('-1 day'));
        $endMonth   = date('Y-m-31',strtotime('-1 day'));
        $return     = [
            'collectDate'      => $startTime,
            'dayOutMoney'      => 0,
            'dayInMoney'       => 0,
            'dayDetail'        => 0,
            'dayFinshCall'     => 0,
            'dayAllotCustom'   => 0,
            'dayCheckDetail'   => 0,

            'monthOutMoney'    => 0,
            'monthInMoney'     => 0,
            'monthDetail'      => 0,
            'monthFinshCall'   => 0,
            'monthAllotCustom' => 0,
            'monthCheckDetail' => 0,

            'totalAllotCustom' => 0,
            'totalFinshCall'   => 0,
            'totalDetail'      => 0,
            'totalOutMoney'    => 0,
            'totalInMoney'     => 0,
            'totalCheckDetail' => 0,
        ];
        //取出审件中已收款时间为当天的数据
        $sql = "SELECT
            count(*) AS dayDetail,
            IFNULL(SUM(sendMoney),0) AS dayOutMoney,
            IFNULL(SUM(income),0) AS dayInMoney
            FROM md_check_order
            WHERE status = 6 AND sendTime >= ? AND sendTime <= ?";
        $result1 = $this->db->query($sql,[$startTime,$endTime])->row_array();
        print_r($this->db->last_query());
        // D($this->db->last_query());die;
        //取出当天创建审件数
        $sql = "SELECT
            count(*) AS dayCheckDetail
            FROM md_check_order
            WHERE created >= ? AND created <= ?";
        $result2 = $this->db->query($sql,[$startTime,$endTime])->row_array();

        //取出当天处理订单数
        $sql = "SELECT
            count(*) AS dayFinshCall
            FROM md_custom_list
            WHERE updated >= ? AND updated <= ?";
        $result3 = $this->db->query($sql,[$startTime,$endTime])->row_array();

        //取出当天分配订单数
        $sql = "SELECT
            count(*) AS dayAllotCustom
            FROM md_custom_list
            WHERE give_time >= ? AND give_time <= ?";
        $result4 = $this->db->query($sql,[$startTime,$endTime])->row_array();

        //取出审件中所有已收款的数据
        $sql = "SELECT
            count(*) AS totalDetail,
            IFNULL(SUM(sendMoney),0) AS totalOutMoney,
            IFNULL(SUM(income),0) AS totalInMoney
            FROM md_check_order
            WHERE status = 6";
        $result5 = $this->db->query($sql)->row_array();

        //取出总创建审件数
        $sql = "SELECT
            count(*) AS totalCheckDetail
            FROM md_check_order";
        $result6 = $this->db->query($sql)->row_array();

        //取出所有处理订单数
        $sql = "SELECT
            count(*) AS totalFinshCall
            FROM md_custom_list
            WHERE updated != '0000-00-00 00:00:00'";
        $result7 = $this->db->query($sql)->row_array();

        //取出所有分配订单数
        $sql = "SELECT
            count(*) AS totalAllotCustom
            FROM md_custom_list";
        $result8     = $this->db->query($sql)->row_array();

        //取出审件中已收款时间为当月的数据
        $sql = "SELECT
            count(*) AS monthDetail,
            IFNULL(SUM(sendMoney),0) AS monthOutMoney,
            IFNULL(SUM(income),0) AS monthInMoney
            FROM md_check_order
            WHERE status = 6 AND sendTime >= ? AND sendTime <= ?";
        $result9 = $this->db->query($sql,[$startMonth,$endMonth])->row_array();

        //取出当月创建审件数
        $sql = "SELECT
            count(*) AS monthCheckDetail
            FROM md_check_order
            WHERE created >= ? AND created <= ?";
        $result10 = $this->db->query($sql,[$startMonth,$endMonth])->row_array();

        //取出当月处理订单数
        $sql = "SELECT
            count(*) AS monthFinshCall
            FROM md_custom_list
            WHERE updated >= ? AND updated <= ?";
        $result11 = $this->db->query($sql,[$startMonth,$endMonth])->row_array();

        //取出当月分配订单数
        $sql = "SELECT
            count(*) AS monthAllotCustom
            FROM md_custom_list
            WHERE give_time >= ? AND give_time <= ?";
        $result12 = $this->db->query($sql,[$startMonth,$endMonth])->row_array();

         //取出当天拨打的数据(即当天备注量)
        $sql = "SELECT
            count(*) AS dayPhone
            FROM md_comment
            WHERE created >= ? AND created <= ?";
        $result13 = $this->db->query($sql,[$startTime,$endTime])->row_array();

        //取出当月拨打的数据(即当月备注量)
        $sql = "SELECT
            count(*) AS monthPhone
            FROM md_comment
            WHERE created >= ? AND created <= ?";
        $result14 = $this->db->query($sql,[$startMonth,$endMonth])->row_array();
        $finalResult = array_merge($return,$result1,$result2,$result3,$result4,
                                   $result5,$result6,$result7,$result8,
                                   $result9,$result10,$result11,$result12,
                                   $result13,$result14);
        if (!empty($finalResult)) {
            try {
                $this->db->insert('md_rank_total',$finalResult);
            } catch (Exception $e) {
                $this->db->delete('md_rank_total',['collectDate' => $startTime]);
                $this->db->insert('md_rank_total',$finalResult);
            }
        }
        return null;
    }
}