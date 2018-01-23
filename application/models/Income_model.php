<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Income_model extends CI_Model
{
    public $db;

    public $table = 'pm_income_gain';

    // public $struct = array();

    public function __construct()
    {
        error_reporting(E_ALL^E_NOTICE^E_WARNING);
        parent::__construct();
        $this->db = get_instance()->load->database('default', true);
    }

    public function getIncome($carcard)
    {
        return $this->_getIncome($carcard);
    }

    /**
     * 开始计算用户收益入口
     * @param  [type] $bindId [description]
     * @return [type]         [description]
     */
    public function crontabCreated($bindId)
    {
        $data = $this->_getDevs($bindId);
        if (!$data[0]) {
            return $data[1];
        }
        $data            = $data[2];
        list($ret, $res) = $this->_getDetail($data['carcard'], $data['obdIds'], $data['deviceSlots'], $data['orgId']);

        $output      = array();
        $table       = 'pm_tmp_user_income_' . ($bindId % 64);
        $totalIncome = $c51Income = $c01Income = 0;
        if (!empty($res)) {
//            echo 'RES___:', PHP_EOL;
//            print_r($res);
            $this->db->delete($table, array('bindid' => $bindId));
            foreach ($res as $k => $v) {
                $totalIncome = round($v['total_income'], 2);
                $output[]    = array('bindid' => $bindId,
                    'c51_money'                   => round($v['c51_income'], 2),
                    'c01_money'                   => round($v['c01_income'], 2),
                    'total_income'                => $totalIncome,
                    'income_date'                 => $v['income_date'],
                    'type'                        => 0,
                );

                if ($v['r_c51_income'] != 0 || $v['r_c01_income'] != 0) {
                    $totalIncome = round($totalIncome - $v['c51_income'] - $v['c01_income'], 2);
                    $output[]    = array('bindid' => $bindId,
                        'c51_money'                   => round(abs($v['r_c51_income']), 2),
                        'c01_money'                   => round(abs($v['r_c01_income']), 2),
                        'total_income'                => $totalIncome,
                        'income_date'                 => $v['income_date'],
                        'type'                        => 1,
                    );
                }

                if ($v['g_c51_income'] != 0 || $v['g_c01_income'] != 0) {
                    $totalIncome = round($totalIncome - abs($v['g_c51_income']) - abs($v['g_c51_income']), 2);
                    $output[]    = array('bindid' => $bindId,
                        'c51_money'                   => round(abs($v['g_c51_income']), 2),
                        'c01_money'                   => round(abs($v['g_c01_income']), 2),
                        'total_income'                => $totalIncome,
                        'income_date'                 => $v['income_date'],
                        'type'                        => 2,
                    );
                }
            }

            if (!empty($output)) {
//                echo '@output', PHP_EOL;
                foreach ($output as $k => $v) {
                    $c51Income += ($v['type'] == 0 ? $v['c51_money'] : 0 - $v['c51_money']);
                    $c01Income += ($v['type'] == 0 ? $v['c01_money'] : 0 - $v['c01_money']);
                }
                $this->db->insert_batch($table, $output);
            }
        }

        //计算历史收益
        $historyIncome = 0;
        if (!empty($output)) {
            $sumC01 = 0;
            $sumC51 = 0;
            foreach ($output as $k => $v) {
                if ($v['type'] == 0) {
                    $sumC01 += $v['c01_money'];
                    $sumC51 += $v['c51_money'];
                }
            }
            $historyIncome = $sumC01 + $sumC51;
        }

        $totalIncome = $c51Income + $c01Income;
        if (ENVIRONMENT == 'development') {
            $url = 'http://dev-api.ubi001.com/v1/privated/underwritingNew?obd_id=' . $data['defaultObdId'];
        } else {
            $url = 'http://api.ubi001.com/v1/privated/underwritingNew?obd_id=' . $data['defaultObdId'];
        }
        $response = Requests::get($url);
        if ($response->status_code == 200) {
            $content = json_decode($response->body, true);
        } else {
            return false;
        }
        $struct = array(
            'bindid'                => $bindId,
            'history_income'        => $historyIncome,
            'total_income'          => $totalIncome,
            'last_week_income'      => isset($res[0]) ? (abs($res[0]['c51_income']) + abs($res[0]['c01_income'])) : 0,
            'last_week_lost_income' => isset($res[0]) ? (abs($res[0]['g_c51_income']) + abs($res[0]['g_c01_income']) + abs($res[0]['r_c01_income']) + abs($res[0]['r_c51_income'])) : 0,
            'ready_lost_income'     => isset($res[0]) ? (abs($res[0]['rl_c51_income']) + abs($res[0]['rl_c01_income'])) : 0,
            'c51_money'             => $c51Income,
            'c01_money'             => $c01Income,
            'c51_premium'           => $content['compulsory_ins'],
            'c01_premium'           => $content['commercial_ins'],
            'c01_start_date'        => $content['commercial_start'],
            'c01_end_date'          => $content['commercial_end'],
            'c51_start_date'        => $content['compulsory_start'],
            'c51_end_date'          => $content['commercial_end'],
            'obds'                  => implode(',', $data['obdIds']),
        );
        foreach ($struct as $k => $v) {
            if (is_float($v)) {
                $struct[$k] = round($v,2);
            }
        }
//        echo $data['carcard'], PHP_EOL;
//        print_r($struct);
        $sql = "SELECT bindid FROM pm_tmp_user_income WHERE bindid = ?";
        $row = $this->db->query($sql, array($bindId))->row_array();
        if (empty($row)) {
//            echo '插入', PHP_EOL;
            $struct['created'] = date('Y-m-d H:i:s');
            $this->db->insert('pm_tmp_user_income', $struct);
        } else {
//            echo '更新', PHP_EOL;
            unset($struct['bindid']);
            $this->db->update('pm_tmp_user_income', $struct, array('bindid' => $bindId));
        }
    }

    /**
     * 取详细
     * @param  [type] $carcard [description]
     * @param  [type] $obds    [description]
     * @return [type]          [description]
     */
    protected function _getDetail($carcard, $obds, $deviceSlots, $orgId)
    {
        // 黑龙江体验用户
        $orgIds = array(27, 29, 33, 34, 37);
        if (in_array($orgId, $orgIds)) {
            $calMAXWeeks = array(
                0 => 12,
                1 => 52,
            );
        } else {
            $calMAXWeeks = array(
                0 => 52,
                1 => 52,
            );
        }
//        echo 'calMAXWeeks:', PHP_EOL;
//        print_r($calMAXWeeks);
        $weekCount = 500;
        // echo $obds, PHP_EOL;
        // $calMAXWeek = 5;
        list($res, $msg) = $this->_serverIncome($obds, $deviceSlots, $weekCount);
        // echo 'SERVERINcome', PHP_EOL;
        // print_r($msg);
        // echo 'SERVERINcome END', PHP_EOL;
        // log_message('info', 'serverIncome:' . json_encode(array($res, $msg)));
        if ($res) {

            $testC01RT = array();

            // 取得时间分片
            list($c01TimeSlice, $c51TimeSlice) = $this->_getGainList($carcard);
            // echo 'c01TimeSlice', PHP_EOL;
            // print_r($c01TimeSlice);
            // echo 'c51TimeSlice', PHP_EOL;
            // print_r($c51TimeSlice);
            $list = $msg['week_income_list'];

            // print_r($list);
            if (empty($list)) {
                return array(true, $list);
            }
            $c01TimeSliceData          = $c51TimeSliceData          =
            $c01TimeSliceRData         = $c51TimeSliceRData         =
            $c01TimeSliceKeyData       = $c51TimeSliceKeyData       =
            $c01TimeSliceReadyLostData = $c51TimeSliceReadyLostData = array();

            // 将数据分片
            foreach ($c01TimeSlice as $ks => $vs) {
                $st         = strtotime($vs['start_date']);
                $et         = strtotime($vs['end_date']);
                $calMAXWeek = isset($calMAXWeeks[$ks]) ? $calMAXWeeks[$ks] : end($calMAXWeeks);
                // echo $calMAXWeek, PHP_EOL;
                // 计算是否超过规则周数
                $rt = $et - ($calMAXWeek * 7 + 6) * 86400;
                $rt = $rt > $st ? $rt : $st;

                // 预测是否超过规则周数
                $rrt = $et - ($calMAXWeek * 7 + 6) * 86400 + 7 * 86400;
                $rrt = $rrt > $st ? $rrt : $st;

                $testC01RT[] = date('Y-m-d', $rt);
                // echo date('Y-m-d', $rt),'-', date('Y-m-d', $st);
                $c01TimeSliceData[$ks]          = array();
                $c01TimeSliceRData[$ks]         = array();
                $c01TimeSliceReadyLostData[$ks] = array();
                foreach ($list as $k => $v) {
                    $nt = strtotime($v['income_date']);
                    if ($nt > $st && $nt <= $et) {
                        $c01TimeSliceData[$ks][] = $v;
                    }

                    // echo $k,'当前时间:', date('Y-m-d', $nt), '长度时间:', date('Y-m-d', $rt),'开始时间:', date('Y-m-d', $st),'结束时间:', date('Y-m-d', $et),"\n";
                    if ($nt > $st && $nt <= $et && $nt < $rt) {
                        $c01TimeSliceRData[$ks][] = $v;
                    }

                    if ($nt > $st && $nt <= $et && $nt < $rrt) {
                        $c01TimeSliceReadyLostData[$ks][] = $v;
                    }

                    $c01TimeSliceRData[$ks] = array_reverse($c01TimeSliceRData[$ks]);
                }

                array_pop($c01TimeSliceReadyLostData[$ks]);
            }
            // echo 'c01TimeSliceData@1', PHP_EOL;
            // print_r($c01TimeSliceData);
            // 将数据分片
            foreach ($c51TimeSlice as $ks => $vs) {
                $st         = strtotime($vs['start_date']);
                $et         = strtotime($vs['end_date']);
                $calMAXWeek = isset($calMAXWeeks[$ks]) ? $calMAXWeeks[$ks] : end($calMAXWeeks);
                // 计算是否超过规则周数
                $rt = $et - ($calMAXWeek * 7 + 6) * 86400;
                $rt = $rt > $st ? $rt : $st;

                // 预测是否超过规则周数
                $rrt = $et - ($calMAXWeek * 7 + 6) * 86400 + 7 * 86400;
                $rrt = $rrt > $st ? $rrt : $st;

                $c51TimeSliceData[$ks]          = array();
                $c51TimeSliceRData[$ks]         = array();
                $c51TimeSliceReadyLostData[$ks] = array();
                foreach ($list as $k => $v) {
                    $nt = strtotime($v['income_date']);
                    if ($nt > $st && $nt <= $et) {
                        $c51TimeSliceData[$ks][] = $v;
                    }

                    if ($nt > $st && $nt <= $et && $nt < $rt) {
                        $c51TimeSliceRData[$ks][] = $v;
                    }

                    if ($nt > $st && $nt <= $et && $nt < $rrt) {
                        $c51TimeSliceReadyLostData[$ks][] = $v;
                    }

                    $c51TimeSliceRData[$ks] = array_reverse($c51TimeSliceRData[$ks]);
                }

                array_pop($c51TimeSliceReadyLostData[$ks]);
            }

            $temp = array();
            // $c01TimeSliceRData = array_reverse($c01TimeSliceRData);

            // 计算收益扣减
            foreach ($c01TimeSlice as $ks => $vs) {

                foreach ($c01TimeSliceData[$ks] as $k => $v) {
                    $v['r_c01_income']       = 0;
                    $v['r_c01_income_date']  = '';
                    $v['g_c01_income']       = 0;
                    $v['rl_c01_income']      = 0;
                    $v['rl_c01_income_date'] = '';

                    if (isset($c01TimeSliceRData[$ks][$k])) {
                        $v['r_c01_income']      = -$c01TimeSliceRData[$ks][$k]['income_comm'];
                        $v['r_c01_income_date'] = $c01TimeSliceRData[$ks][$k]['income_date'];
                    }

                    if (isset($c01TimeSliceReadyLostData[$ks][$k])) {
                        $v['rl_c01_income']      = -$c01TimeSliceReadyLostData[$ks][$k]['income_comm'];
                        $v['rl_c01_income_date'] = $c01TimeSliceReadyLostData[$ks][$k]['income_date'];
                    }

                    if ($vs['end_date'] == $v['income_date'] && isset($vs['money']) && $vs['money'] != 0) {
                        $v['g_c01_income'] = $vs['money'] / 100;
                    }

                    // $c01TimeSliceData[$ks][$k] = $v;
                    $temp[$v['income_date']] = $v;
                }
            }
            // echo 'c01TimeSlice', PHP_EOL;
            // print_r($c01TimeSlice);
            // echo 'c01TimeSliceRData', PHP_EOL;
            // print_r($c01TimeSliceRData);
            // echo 'c01TimeSliceReadyLostData', PHP_EOL;
            // print_r($c01TimeSliceReadyLostData);
            $c01TimeSliceData = $temp;

            // echo 'c01TimeSliceData', PHP_EOL;
            // print_r($c01TimeSliceData);
            $temp = array();
            // 计算收益扣减
            foreach ($c51TimeSlice as $ks => $vs) {
                foreach ($c51TimeSliceData[$ks] as $k => $v) {
                    $v['r_c51_income']       = 0;
                    $v['r_c51_income_date']  = '';
                    $v['g_c51_income']       = 0;
                    $v['rl_c51_income']      = 0;
                    $v['rl_c51_income_date'] = '';

                    if (isset($c51TimeSliceRData[$ks][$k])) {
                        $v['r_c51_income']      = -$c51TimeSliceRData[$ks][$k]['income_comp'];
                        $v['r_c51_income_date'] = $c51TimeSliceRData[$ks][$k]['income_date'];
                    }

                    if (isset($c51TimeSliceReadyLostData[$ks][$k])) {
                        $v['rl_c51_income']      = -$c51TimeSliceReadyLostData[$ks][$k]['income_comp'];
                        $v['rl_c51_income_date'] = $c51TimeSliceReadyLostData[$ks][$k]['income_date'];
                    }

                    if ($vs['end_date'] == $v['income_date'] && isset($vs['money']) && $vs['money'] != 0) {
                        $v['g_c51_income'] = $vs['money'] / 100;
                        // $v['g_c51_income'] = 0;
                    }

                    // $c51TimeSliceData[$ks][$k] = $v;
                    $temp[$v['income_date']] = $v;
                }
            }
//            echo 'c51TimeSlice', PHP_EOL;
//            print_r($c51TimeSlice);
//            echo 'c51TimeSliceRData', PHP_EOL;
//            print_r($c51TimeSliceRData);
//            echo 'c51TimeSliceReadyLostData', PHP_EOL;
//            print_r($c51TimeSliceReadyLostData);
            $c51TimeSliceData = $temp;

            // 计算累计收益
            $list           = array_reverse($list);
            $totalC51Income = 0;
            $totalC01Income = 0;
            // print_r($list);
            // exit;
            foreach ($list as $k => $v) {
                $v['rl_c01_income_date'] = $v['rl_c51_income_date'] = $v['rl_c01_income'] = $v['rl_c51_income'] = $v['g_c51_income'] = $v['g_c01_income'] = $v['r_c01_income'] = $v['r_c51_income'] = 0;
                if (isset($c01TimeSliceData[$v['income_date']])) {
                    $v['g_c01_income']      = $c01TimeSliceData[$v['income_date']]['g_c01_income'];
                    $v['r_c01_income']      = $c01TimeSliceData[$v['income_date']]['r_c01_income'];
                    $v['r_c01_income_date'] = $c01TimeSliceData[$v['income_date']]['r_c01_income_date'];

                    $v['rl_c01_income']      = $c01TimeSliceData[$v['income_date']]['rl_c01_income'];
                    $v['rl_c01_income_date'] = $c01TimeSliceData[$v['income_date']]['rl_c01_income_date'];
                }

                if (isset($c51TimeSliceData[$v['income_date']])) {
                    $v['g_c51_income']      = $c51TimeSliceData[$v['income_date']]['g_c51_income'];
                    $v['r_c51_income']      = $c51TimeSliceData[$v['income_date']]['r_c51_income'];
                    $v['r_c51_income_date'] = $c51TimeSliceData[$v['income_date']]['r_c51_income_date'];

                    $v['rl_c51_income']      = $c51TimeSliceData[$v['income_date']]['rl_c51_income'];
                    $v['rl_c51_income_date'] = $c51TimeSliceData[$v['income_date']]['rl_c51_income_date'];
                }

                $tempC01Income = $v['r_c01_income'] + $v['income_comm'];
                $tempC51Income = $v['r_c51_income'] + $v['income_comp'];

                $totalC51Income += $tempC51Income;
                $totalC01Income += $tempC01Income;

                $tempC51 = 0;
                $tempC01 = 0;
                if ($v['g_c01_income'] > 0) {
                    $tempC01        = $totalC01Income - $tempC01Income;
                    $totalC01Income = 0;
                }

                if ($v['g_c51_income'] > 0) {
                    $tempC51        = $totalC51Income - $tempC51Income;
                    $totalC51Income = 0;
                }

                // $v['income'] = $tempC51Income + $tempC01Income -  $tempC51 - $tempC01;
                $v['income']       = $v['income_comp'] + $v['income_comm'];
                $v['total_income'] = $totalC51Income + $totalC01Income;
                $v['total_income'] = $v['total_income'] < 0 ? 0 : $v['total_income'];
                $v['c01_income']   = $v['income_comm'];
                $v['c51_income']   = $v['income_comp'];
                unset($v['income_comm']);
                unset($v['income_comp']);
                $list[$k] = $v;
                // echo '$v', PHP_EOL;
                // print_r($c01TimeSliceData['2016-12-04']);
                // echo 'C%!', PHP_EOL;
                // print_r($c51TimeSliceData['2016-12-04']);
                // print_r($v);
                // exit;
            }

            $list = array_reverse($list);

            return array(true, $list);

        } else {
            return array($res, $msg);
        }
    }

    /**
     * 计算时间分片
     * @param  [type] $carcard [description]
     * @return [type]          [description]
     */
    protected function _getGainList($carcard)
    {
        $newC01Data = array();
        $newC51Data = array();
        $endDate    = date('Y-m-d', time() - (date('w') + 0) * 86400);
        // $data = $this->db->select('c01_end_date, c05_end_date')->get_where($this->table, array('carcard' => $carcard))->result_array();
        //
        $data = $this->db->query("SELECT  DATE(c01_end_date) AS c01_end_date, DATE(c51_end_date) AS c51_end_date, c01_money, c51_money FROM {$this->table} WHERE carcard = ? ORDER BY created ASC", array($carcard))->result_array();
        if (empty($data)) {
            $newC51Data[] = $newC01Data[] = array('start_date' => '', 'end_date' => $endDate, 'money' => 0);
            return array($newC01Data, $newC51Data);
        }

        $c01K = 0;
        $c51K = 0;
        foreach ($data as $k => $v) {
            $ct = strtotime($v['c01_end_date']);
            if ($ct > strtotime('2016-01-01') && $v['c01_money'] > 0) {
                // 纠正不是周日的提取时间
                $cd = date('w', $ct);
                if ($cd != 0) {
                    $v['c01_end_date'] = date('Y-m-d', $ct - $cd * 86400);
                }
                // $key = 'c01_'.$v['c01_end_date'];
                $newC01Data[$c01K] = array('start_date' => isset($newC01Data[$c01K - 1]) ? $newC01Data[$c01K - 1]['end_date'] : '', 'end_date' => $v['c01_end_date'], 'money' => $v['c01_money']);
                $c01K++;
            }

            $ct = strtotime($v['c51_end_date']);
            if ($ct > strtotime('2016-01-01') && $v['c51_money'] > 0) {
                // 纠正不是周日的提取时间
                $cd = date('w', $ct);
                if ($cd != 0) {
                    $v['c51_end_date'] = date('Y-m-d', $ct - $cd * 86400);
                }
                // $key = 'c51_'.$v['c51_end_date'];
                $newC51Data[$c51K] = array('start_date' => isset($newC01Data[$c01K - 1]) ? $newC01Data[$c01K - 1]['end_date'] : '', 'end_date' => $v['c51_end_date'], 'money' => $v['c51_money']);
                $c51K++;
            }
        }

        if (empty($newC01Data)) {
            $newC01Data[] = array('start_date' => '1970-01-01', 'end_date' => $endDate);
        } else {
            $end = end($newC01Data);
            if ($end['end_date'] != $endDate) {
                $newC01Data[] = array('start_date' => $end['end_date'], 'end_date' => $endDate);
            }
        }

        if (empty($newC51Data)) {
            $newC51Data[] = array('start_date' => '1970-01-01', 'end_date' => $endDate, 'money' => 0);
        } else {
            $end = end($newC51Data);
            if ($end['end_date'] != $endDate) {
                $newC51Data[] = array('start_date' => $end['end_date'], 'end_date' => $endDate, 'money' => 0);
            }
        }

        return array($newC01Data, $newC51Data);
    }

    /**
     * 收益
     * @param $bindId
     * @param $weekCount
     * @param $uid
     * @param $token
     * @return mixed|null
     */
    protected function _serverIncome($obdIds, $deviceSlots, $weekCount)
    {
        $endDate = date('Y-m-d', time() - (date('w') + 0) * 86400);
        if (empty($obdIds)) {
            return array(false, '设备ID为空');
        }
        $urls = $obdData = $devData = array();
        foreach ($obdIds as $k => $v) {
            if ($deviceSlots[$k] == 0) {
                $obdData[] = $v;
            } else {
                $devData[] = $v;
            }
        }

        if (!empty($obdData)) {
            $ids    = implode(',', $obdData);
            $urls[] = OBD_SERVER . "GetWeekIncomeInfo.app?obd_id={$ids}&weeks={$weekCount}&end_date={$endDate}";
        }

        if (!empty($devData)) {
            $ids    = implode(',', $devData);
            $urls[] = OBD_SERVER . "DGetWeekIncomeInfo.app?obd_id={$ids}&weeks={$weekCount}&end_date={$endDate}";
        }

        $data = array();
        $k    = 0;
        foreach ($urls as $v) {
            $response = Requests::get($v);
            if ($response->status_code == 200) {
                $content = json_decode($response->body, true);
                // print_r($urls);
                // print_r($content);
                if ($content['result'] == 0) {
                    // return array(true, $content);
                } elseif ($content['result'] == -900) {
                    continue;
                    // return array(false, $content['result'] . "非法错误");
                } elseif ($content['result'] == -901) {
                    continue;
                    // return array(false, $content['result'] . "系统错误");
                } else {
                    continue;
                    // return array(false, '201非法错误');
                }
                $k++;
                if (empty($data)) {
                    $data = $content;
                } else {
                    $data['income_comm_sum'] += $content['income_comm_sum'];
                    $data['total_count'] += $content['total_count'];
                    $data['income_sum'] += $content['income_sum'];
                    $data['income_comp_sum'] += $content['income_comp_sum'];
                    $data['week_income_list'] = array_merge($data['week_income_list'], $content['week_income_list']);
                    // echo $obdIds, '-', $weekCount, '#9 MERGE', PHP_EOL;
                }
            }
        }

        if (!empty($data) && $k == 2 && !empty($devData) && !empty($obdData) && !empty($data['week_income_list'])) {
            // $weekIncomeList = $data['week_income_list'];
            $incomeDates = array();
            foreach ($data['week_income_list'] as $v) {
                $incomeDates[] = strtotime($v['income_date']);
            }
            $weekIncomeList = $data['week_income_list'];
            array_multisort($weekIncomeList, SORT_ASC, $incomeDates);
            // echo $obdIds, '-', $weekCount, '#10 SORT', PHP_EOL;
        }

        if (!empty($data)) {
            return array(true, $data);
        } else {
            return array(false, '202非法错误');
        }
    }

    /**
     * 取设备相关数据
     * @param  [type] $bindId [description]
     * @return [type]         [description]
     */
    protected function _getDevs($bindId)
    {
        $struct = array(
            'bindId'       => 0,
            'obdIds'       => array(),
            'carcard'      => '',
            'deviceSlots'  => array(),
            'defaultObdId' => '',
            'orgId'        => '',
        );
        // 查询 bindid 和 obd_id
        $data = $this->db->query("SELECT bind_id, carcard FROM app_obd_bind WHERE bind_id = ? LIMIT 1", array($bindId))->row_array();
        if (empty($data)) {
            return array(false, '车牌不正确', $struct);
        }
        $struct['carcard'] = $data['carcard'];
        $struct['bindId']  = $bindId;
        $data              = $this->db->query("SELECT src, createtime, status FROM pub_src_info WHERE bindid = ? ORDER BY createtime ASC", array($struct['bindId']))->result_array();
        if (empty($data)) {
            return array(false, '车牌没有绑定设备ID', $struct);
        }

        $srcs = array();
        foreach ($data as $v) {
            $srcs[] = "'{$v['src']}'";
            if ($v['status'] == 1) {
                $struct['defaultObdId'] = $v['src'];
            }
        }
        // print_r($srcs);
        // exit;
        $strs = implode(',', $srcs);
        // exit;
        $sql  = "SELECT src, device_slot FROM pm_tmp_src_msg WHERE src IN ({$strs})";
        $data = $this->db->query($sql)->result_array();
        foreach ($data as $v) {
            $struct['obdIds'][]      = $v['src'];
            $struct['deviceSlots'][] = $v['device_slot'];
        }

        if (!empty($struct['defaultObdId'])) {
            $data = $this->db->query("SELECT org_id FROM dc_comm.dc_obd_id WHERE OBD_ID = ? LIMIT 1", array($struct['defaultObdId']))->row_array();
            if (!empty($data)) {
                $struct['orgId'] = current($data);
            }
        }

        return array(true, 'succ', $struct);
    }

    /**
     * 取得当前累计总收益
     * @param  string 车牌
     * @return array c51_money 交强险  c01_money 商业险 total_money start_date end_date
     */
    protected function _getIncome($carcard, $startDate = '', $endDate = '')
    {
        $struct = array(
            'c51Money'     => 0,
            'c01Money'     => 0,
            'totalMoney'   => 0,
            'c01StartDate' => '',
            'c01EndDate'   => '',
            'c51StartDate' => '',
            'c51EndDate'   => '',
            'bindId'       => 0,
            'obds'         => '',
        );

        $struct['c01EndDate']   = date('Y-m-d', time() - (date('w') + 0) * 86400);
        $struct['c01StartDate'] = date('Y-m-d', time() - (date('w') + 52 * 7 + 6) * 86400);

        $struct['c51EndDate']   = date('Y-m-d', time() - (date('w') + 0) * 86400);
        $struct['c51StartDate'] = date('Y-m-d', time() - (date('w') + 52 * 7 + 6) * 86400);

        // 接爱入参日期
        if ($startDate != '' && $endDate != '') {
            $struct['c01EndDate']   = $endDate;
            $struct['c01StartDate'] = $startDate;

            $struct['c51EndDate']   = $endDate;
            $struct['c51StartDate'] = $startDate;
        }

        // 查询 bindid 和 obd_id
        $data = $this->db->query("SELECT bind_id FROM app_obd_bind WHERE carcard = ? LIMIT 1", array($carcard))->row_array();

        if (empty($data)) {
            return array(false, '车牌不正确', $struct);
        }

        $struct['bindId'] = current($data);
        $data             = $this->db->query("SELECT src, createtime FROM pub_src_info WHERE bindid = ? ORDER BY createtime ASC", array($struct['bindId']))->result_array();
        if (empty($data)) {
            return array(false, '车牌没有绑定设备ID', $struct);
        }

        $obds = array();
        foreach ($data as $v) {
            $obds[] = trim(current($v));
        }
        $struct['obds'] = implode(',', $obds);
        //  查询开始日期
        $data = $this->db->query("SELECT MAX(c01_end_date) AS c01_end_date  FROM {$this->table} WHERE carcard = ? LIMIT 1", array($carcard))->row_array();

        if (!empty($data)) {

            $dbC01StartDate = strtotime($data['c01_end_date']) + 86400;
            if (strtotime($struct['c01StartDate']) < $dbC01StartDate) {
                $struct['c01StartDate'] = date('Y-m-d', $dbC01StartDate);
            }
        }

        $data = $this->db->query("SELECT MAX(c51_end_date) AS c51_end_date  FROM {$this->table} WHERE carcard = ? LIMIT 1", array($carcard))->row_array();
        if (!empty($data)) {
            $dbC51StartDate = strtotime($data['c51_end_date']) + 86400;
            if (strtotime($struct['c51StartDate']) < $dbC51StartDate) {
                $struct['c51StartDate'] = date('Y-m-d', $dbC51StartDate);
            }
        }

        // 商业险和交强起始时间相同只取一次后端
        if ($struct['c01EndDate'] == $struct['c51EndDate'] && $struct['c01StartDate'] == $struct['c51StartDate']) {
            // 查询后端接口
            $response = Requests::get(OBD_SERVER . "GetIncomeSumNew.app?obd_id={$struct['obds']}&start_time={$struct['c01StartDate']}&end_time={$struct['c01EndDate']}");
            if ($response->status_code == 200) {
                $content = json_decode($response->body, true);
                if ($content['result'] == 0) {
                    $struct['c51Money']   = $content['income_comp'];
                    $struct['c01Money']   = $content['income_comm'];
                    $struct['totalMoney'] = $struct['c51Money'] + $struct['c01Money'];
                } else {
                    return array(false, OBD_SERVER . "GetIncomeSumNew.app?obd_id={$struct['obds']}&start_time={$struct['c01StartDate']}&end_time={$struct['c01EndDate']}" . '没有查询到收益数据' . print_r($response, true), $struct);
                }
            }
        } else {
            // 查询商业险
            $response = Requests::get(OBD_SERVER . "GetIncomeSumNew.app?obd_id={$struct['obds']}&start_time={$struct['c01StartDate']}&end_time={$struct['c01EndDate']}");
            if ($response->status_code == 200) {
                $content = json_decode($response->body, true);
                if ($content['result'] == 0) {
                    // $struct['c51Money'] = $content['income_comp'];
                    $struct['c01Money'] = $content['income_comm'];
                    // $struct['totalMoney'] = $struct['c51Money'] + $struct['c01Money'];
                } else {
                    return array(false, '没有查询到收益数据', $struct);
                }
            }

            // 查询交强险
            $response = Requests::get(OBD_SERVER . "GetIncomeSumNew.app?obd_id={$struct['obds']}&start_time={$struct['c51StartDate']}&end_time={$struct['c51EndDate']}");
            if ($response->status_code == 200) {
                $content = json_decode($response->body, true);
                if ($content['result'] == 0) {
                    $struct['c51Money'] = $content['income_comp'];
                    // $struct['c01Money'] = $content['income_comm'];
                    // $struct['totalMoney'] = $struct['c51Money'] + $struct['c01Money'];
                } else {
                    return array(false, '没有查询到收益数据', $struct);
                }
            }

            $struct['totalMoney'] = $struct['c51Money'] + $struct['c01Money'];
        }

        return array(true, '成功', $struct);
    }

    /**
     * 取得十二周收益
     * @param  [type] $carcard [description]
     * @return [type]          [description]
     */
    public function getHLJIncome($carcard, $end)
    {
        $se        = strtotime($end);
        $endDate   = date('Y-m-d', $se - (date('w', $se) + 0) * 86400);
        $startDate = date('Y-m-d', $se - (date('w', $se) + 12 * 7 + 6) * 86400);
        return $this->_getIncome($carcard, $startDate, $endDate);
    }

    /**
     * 取得五十二周收益
     * @param  [type] $carcard [description]
     * @return [type]          [description]
     */
    public function getOTIncome($carcard, $end)
    {
        $se        = strtotime($end);
        $endDate   = date('Y-m-d', $se - (date('w', $se) + 0) * 86400);
        $startDate = date('Y-m-d', $se - (date('w', $se) + 52 * 7 + 6) * 86400);
        return $this->_getIncome($carcard, $startDate, $endDate);
    }

    /**
     * 生成提现单时生成提取收益记录
     * @param  [type] $carcard   [description]
     * @param  [type] $c01Money  [description]
     * @param  [type] $c51Money  [description]
     * @param  [type] $startDate [description]
     * @param  [type] $endDate   [description]
     * @return [type]            [description]
     */
    public function saveGainIncome($carcard, $c01Money, $c51Money, $c01StartDate, $c01EndDate, $c51StartDate, $c51EndDate)
    {
        $this->db->insert('pm_income_gain',
            array('carcard'  => $carcard,
                'c01_money'      => $c01Money * 100,
                'c51_money'      => $c51Money * 100,
                'c01_start_date' => $c01StartDate,
                'c01_end_date'   => $c01EndDate,
                'c51_start_date' => $c51StartDate,
                'c51_end_date'   => $c51EndDate,
                'total_money'    => ($c01Money + $c51Money) * 100,
                'created'        => date('Y-m-d H:i:s')));
        return $this->db->affected_rows();
    }

    public function getDBIncome($bindId)
    {
        $sql = "SELECT * FROM pm_tmp_user_income WHERE bindid = ?";
        log_message('info', $sql);
        $data = $this->db->query($sql, array($bindId))->row_array();
        return $data;
    }
}
