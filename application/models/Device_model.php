<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Device_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function incomesum($srcInfos)
    {
        $device = array();
        foreach ($srcInfos as $src) {
            if (isset($src->obd_id) &&
                !empty($src->obd_id)
            ) {
                $device[] = $src->obd_id;
            }
        }

//        log_message('debug', 'srcinfos:' . print_r($device, true));

        $srcs = implode(',', $device);
        if (!empty($srcs)) {
            $result = $this->request('GetIncomeSum.app', array('obd_id' => $srcs), 'obd');

            if (isset($result) && $result->result == 0) {
                return array(
                    'totalincome' => $result->income_sum,
                    'ydincome' => $result->yesterday_income,
                    'wdincome' => $result->last_seven_day_income,
                    'mdincome' => $result->cur_month_income,
                    'scoreoflastmonth' => 0
                );
            }
        }

        return null;
    }

    public function incomeinfo($srcInfos, $starttime, $endtime, $curPage, $perPage)
    {
        $device = array();
        foreach ($srcInfos as $src) {
            if (isset($src->obd_id) &&
                !empty($src->obd_id)
            ) {
                $device[] = $src->obd_id;
            }
        }

//        log_message('debug', 'srcinfos:' . print_r($device, true));

        $srcs = implode(',', $device);
        if (!empty($srcs)) {
            $result = $this->request('GetIncomeInfo.app', array('obd_id' => $srcs, 'start_time' => $starttime, 'end_time' => $endtime, 'cur_page' => $curPage, 'per_page' => $perPage), 'obd');

            if (isset($result) && $result->result == 0) {
                return $result;
            }
        }

        return null;
    }

    public function month($srcInfos)
    {
        $device = array();
        foreach ($srcInfos as $src) {
            if (isset($src->obd_id) &&
                !empty($src->obd_id)
            ) {
                $device[] = $src->obd_id;
            }
        }

//        log_message('debug', 'srcinfos:' . print_r($device, true));

        $srcs = implode(',', $device);
        if (!empty($srcs)) {
            $result = $this->request('GetMonthIncome.app', array('obd_id' => $srcs), 'obd');

            if (isset($result) && $result->result == 0) {
                return $result;
            }
        }

        return null;
    }

    public function everyday($srcInfos, $starttime, $endtime, $curPage, $perPage)
    {
        $device = array();
        foreach ($srcInfos as $src) {
            if (isset($src->obd_id) &&
                !empty($src->obd_id)
            ) {
                $device[] = $src->obd_id;
            }
        }

//        log_message('debug', 'srcinfos:' . print_r($device, true));

        $srcs = implode(',', $device);
        if (!empty($srcs)) {
            $result = $this->request('GetEverydayCollectInfo.app', array('obd_id' => $srcs, 'start_time' => $starttime, 'end_time' => $endtime, 'cur_page' => $curPage, 'per_page' => $perPage), 'obd');

            if (isset($result) && $result->result == 0) {
                return $result;
            }
        }

        return null;
    }

    public function trip($srcInfos, $starttime, $endtime, $curPage, $perPage)
    {
        $device = array();
        foreach ($srcInfos as $src) {
            if (isset($src->obd_id) &&
                !empty($src->obd_id)
            ) {
                $device[] = $src->obd_id;
            }
        }

//        log_message('debug', 'srcinfos:' . print_r($device, true));

        $srcs = implode(',', $device);
        if (!empty($srcs)) {
            $result = $this->request('GetTripInfo.app', array('obd_id' => $srcs, 'start_time' => $starttime, 'end_time' => $endtime, 'cur_page' => $curPage, 'per_page' => $perPage), 'obd');

            if (isset($result) && $result->result == 0) {
                return $result;
            }
        }

        return null;
    }

    public function check($src)
    {
        if (!empty($src)) {
            $result = $this->request('CheckObdIdValidity.app', array('obd_id' => $src), 'obd');

            if (isset($result) && $result->result == 0) {
                return $result;
            }
        }

        return null;
    }

    public function type($src, $type)
    {
        if (!empty($src) && !empty($type)) {
            $result = $this->request('SetObdIdType.app', array('obd_id' => $src, 'type' => $type), 'obd');

            if (isset($result) && $result->result == 0) {
                return $result;
            }
        }

        return null;
    }

    public function faultcode($srcInfos, $starttime, $endtime, $curPage, $perPage)
    {
        get_instance()->load->driver('cache');

        $faultCodeList = get_instance()->cache->redis->get('faultCodeList');
        if ($faultCodeList === FALSE) {
            $db = get_instance()->load->database('dc_comm', true);
            $sql = 'SELECT code,cndefine FROM obd_code_data';
            $dbresult = $db->query($sql)->result();
            foreach ($dbresult as $v) {
                $faultCodeList[$v->code] = $v->cndefine;
            }
            get_instance()->cache->redis->save('faultCodeList', $faultCodeList, 86400);
        }

        $device = array();
        foreach ($srcInfos as $src) {
            if (isset($src->obd_id) &&
                !empty($src->obd_id)
            ) {
                $device[] = $src->obd_id;
            }
        }

//        log_message('debug', 'srcinfos:' . print_r($device, true));

        $srcs = implode(',', $device);
        if (!empty($srcs)) {
            $result = $this->request('GetFaultInfo.app', array('obd_id' => $srcs, 'start_time' => $starttime, 'end_time' => $endtime, 'cur_page' => $curPage, 'per_page' => $perPage), 'obd');

            if (isset($result) && !empty($result->fault_code_list)) {
                foreach ($result->fault_code_list as $fault) {
                    if (array_key_exists($fault->code, $faultCodeList)) {
                        $fault->msg = $faultCodeList[$fault->code];
                    } else {
                        $fault->msg = '未知错误';
                    }
                }

                return $result;
            }
        }

        return null;
    }
}
