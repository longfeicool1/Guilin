<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require dirname(__FILE__) . '/Service.php';
/**
 *每天1点统计
 * 0 1 * * * /usr/bin/php70 index.php crontab/total/start
 */
class Personal extends Service
{
    public function __construct()
    {
        parent::__construct();
    }

    public function start()
    {
        $this->personal();
    }

    public function personal()
    {
        echo 'succ start';
        $size = 30;
        $page = 0;
        while (true) {
            $page ++;
            $offset = ($page - 1) * $size;
            //取出所有业务员
            $sql = "SELECT uid FROM md_user
                    WHERE is_show = 1 AND position >= 3
                    ORDER BY uid ASC
                    LIMIT {$offset},{$size}";
            $result = $this->db->query($sql)->result_array();
            if (empty($result)) {
                break;
            }
            foreach ($result as $v) {
                $result = $this->getPersonalData($v['uid']);
            }
        }
        echo 'succ end';
    }

    public function getPersonalData($uid)
    {
        $startTime  = date('Y-m-d',strtotime('-1 day'));
        $endTime    = date('Y-m-d');
        $startMonth = date('Y-m-01',strtotime('-1 day'));
        $endMonth   = date('Y-m-31',strtotime('-1 day'));
        $return     = [
            'uid'            => $uid,
            'collectDate'    => $startTime,
            'dayOutMoney'    => 0,
            'dayInMoney'     => 0,
            'dayDetail'      => 0,
            'dayFinshCall'   => 0,
            'dayAllotCustom' => 0,
            'dayCheckDetail' => 0,
            'dayPhone'       => 0,

            'monthOutMoney'    => 0,
            'monthInMoney'     => 0,
            'monthDetail'      => 0,
            'monthFinshCall'   => 0,
            'monthAllotCustom' => 0,
            'monthCheckDetail' => 0,
            'monthPhone'       => 0,

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
            WHERE uid = ? AND status = 6 AND sendTime >= ? AND sendTime <= ?";
        $result1 = $this->db->query($sql,[$uid,$startTime,$endTime])->row_array();

        //取出当天创建审件数
        $sql = "SELECT
            count(*) AS dayCheckDetail
            FROM md_check_order
            WHERE uid = ? AND created >= ? AND created <= ?";
        $result2 = $this->db->query($sql,[$uid,$startTime,$endTime])->row_array();

        //取出当天处理订单数
        $sql = "SELECT
            count(*) AS dayFinshCall
            FROM md_custom_list
            WHERE firstOwer = ? AND updated >= ? AND updated <= ?";
        $result3 = $this->db->query($sql,[$uid,$startTime,$endTime])->row_array();

        //取出当天分配订单数
        $sql = "SELECT
            count(*) AS dayAllotCustom
            FROM md_custom_list
            WHERE firstOwer = ? AND give_time >= ? AND give_time <= ?";
        $result4 = $this->db->query($sql,[$uid,$startTime,$endTime])->row_array();

        //取出审件中所有已收款的数据
        $sql = "SELECT
            count(*) AS totalDetail,
            IFNULL(SUM(sendMoney),0) AS totalOutMoney,
            IFNULL(SUM(income),0) AS totalInMoney
            FROM md_check_order
            WHERE uid = ? AND status = 6";
        $result5 = $this->db->query($sql,[$uid])->row_array();

        //取出总创建审件数
        $sql = "SELECT
            count(*) AS totalCheckDetail
            FROM md_check_order
            WHERE uid = ?";
        $result6 = $this->db->query($sql,[$uid])->row_array();

        //取出所有处理订单数
        $sql = "SELECT
            count(*) AS totalFinshCall
            FROM md_custom_list
            WHERE firstOwer = ? AND updated != '0000-00-00 00:00:00'";
        $result7 = $this->db->query($sql,[$uid])->row_array();

        //取出所有分配订单数
        $sql = "SELECT
            count(*) AS totalAllotCustom
            FROM md_custom_list
            WHERE firstOwer = ?";
        $result8     = $this->db->query($sql,[$uid])->row_array();

        //取出审件中已收款时间为当月的数据
        $sql = "SELECT
            count(*) AS monthDetail,
            IFNULL(SUM(sendMoney),0) AS monthOutMoney,
            IFNULL(SUM(income),0) AS monthInMoney
            FROM md_check_order
            WHERE uid = ? AND status = 6 AND sendTime >= ? AND sendTime <= ?";
        $result9 = $this->db->query($sql,[$uid,$startMonth,$endMonth])->row_array();

        //取出当月创建审件数
        $sql = "SELECT
            count(*) AS monthCheckDetail
            FROM md_check_order
            WHERE uid = ? AND created >= ? AND created <= ?";
        $result10 = $this->db->query($sql,[$uid,$startMonth,$endMonth])->row_array();

        //取出当月处理订单数
        $sql = "SELECT
            count(*) AS monthFinshCall
            FROM md_custom_list
            WHERE firstOwer = ? AND updated >= ? AND updated <= ?";
        $result11 = $this->db->query($sql,[$uid,$startMonth,$endMonth])->row_array();

        //取出当月分配订单数
        $sql = "SELECT
            count(*) AS monthAllotCustom
            FROM md_custom_list
            WHERE firstOwer = ? AND give_time >= ? AND give_time <= ?";
        $result12 = $this->db->query($sql,[$uid,$startMonth,$endMonth])->row_array();

        //取出当天拨打的数据(即当天备注量)
        $sql = "SELECT
            count(*) AS dayPhone
            FROM md_comment
            WHERE uid = ? AND created >= ? AND created <= ?";
        $result13 = $this->db->query($sql,[$uid,$startTime,$endTime])->row_array();

        //取出当月拨打的数据(即当月备注量)
        $sql = "SELECT
            count(*) AS monthPhone
            FROM md_comment
            WHERE uid = ? AND created >= ? AND created <= ?";
        $result14 = $this->db->query($sql,[$uid,$startMonth,$endMonth])->row_array();

        $finalResult = array_merge($return,$result1,$result2,$result3,$result4,
                               $result5,$result6,$result7,$result8,
                               $result9,$result10,$result11,$result12,
                               $result13,$result14);
        if (!empty($finalResult)) {
            try {
                $this->db->insert('md_rank_personal',$finalResult);
            } catch (Exception $e) {
                $this->db->delete('md_rank_personal',['collectDate' => $startTime]);
                $this->db->insert('md_rank_personal',$finalResult);
            }
        }
        return null;
    }
}