<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Custom_model extends MY_Model {
    public function __CONSTRUCT() {
        parent::__CONSTRUCT();
        $this -> pm_custom_list = 'pm_custom_list';
        $this -> tel_list = 'pm_account';
        $this->sms = 'pm_sms';
    }

    public function listss()
    {
        $res = $this->createList('pm_delivery');
        return $res;
    }
    public function countss()
    {
        $res = $this->getTotal('pm_delivery');
        return $res;
    }

    /*
     *分机列表
     */
    public function lineList($condition = '', $page = 1, $limit = 30, $orderBy = 'exten asc', $fileds = '*', $groupBy = '') {
        $this -> setTable($this -> tel_list);
        $offset = ($page - 1) * $limit;
        $res = $this -> getList($condition, $fileds, $orderBy, $groupBy, $offset, $limit);
        foreach($res as $k=>$v){
            $res[$k]['name'] = $v['real_name'];
        }
        return $res;
    }

    /*
     * 分机总数
     */
    public function lineCount($condition = '', $groupBy = '') {
        $this -> setTable($this -> tel_list);
        return $this -> getCount($condition, $groupBy);
    }

    /*
     * 客户列表
     */
    public function customList($condition = '', $page = 1, $limit = 30, $orderBy = '', $fileds = '*', $groupBy = '') {
        $this -> setTable($this -> pm_custom_list);
        if ($page < 1) {
            $page = 1;
        }
        if ($limit < 1) {
            $limit = 30;
        }
        $offset = ($page - 1) * $limit;
        return $this -> getList($condition, $fileds, $orderBy, $groupBy, $offset, $limit);
    }

    /*
     * 客户总数
     */
    public function customCount($condition = '', $groupBy = '') {
        $this -> setTable($this -> pm_custom_list);
        return $this -> getCount($condition, $groupBy);
    }

     /*
      * 导出数据列表
      */
     public function exportlist($receive) {
         $parameter = array('startDate' => $receive['startDate'], 'endDate' => $receive['endDate']);
         $httpUrl = 'http://192.168.64.2:10086/call/getexportinfo' . '?' . http_build_query($parameter);
         // $httpUrl = 'http://manager-ht.ubi001/call/getexportinfo' . '?' . http_build_query($parameter);
         return $httpUrl;
//       $list = json_decode(file_get_contents($httpUrl), true);
//       return $list;
     }

     /*
      * 导出数据总数
      */
     public function exportCount($receive) {
         $parameter = array('startDate' => $receive['startDate'], 'endDate' => $receive['endDate']);
         $httpUrl = 'http://192.168.64.2:10086/call/getexportcount' . '?' . http_build_query($parameter);
         $list = file_get_contents($httpUrl);
         return $list;
     }

    /*
     * 根据用户姓名或车牌号查找用户手机号
     */
    public function findMobile($field,$word)
    {
        $res = $this->db->select('mobile,car_code,custom_name')->get_where($this->pm_custom_list,"{$field} like '%{$word}%' and is_delete = 1")->result_array();
        $mobile = array();
        $data = array();
        foreach($res as $v){
            $mobile[] = $v['mobile'];
            $data['mobile_' . $v['mobile']] = array(
                'car_code' => $v['car_code'],
                'custom_name' => $v['custom_name'],
            );
        }
        return array($mobile, $data);
    }

    /*
     * 获取所有的批次
     */
    public function getNameList()
    {
        $sql = "select DISTINCT(name_list) from {$this->pm_custom_list} where is_delete = 1";
        return $this->db->query($sql)->result_array();
    }

    /*
     * 获取特定时间段的所有的批次
     */
    public function getNameList2()
    {
        $nameList = array('0'=>'-所有-');
        $rows = $this->db
            ->select('distinct(name_list)')
            ->order_by('addtime desc')
            ->get($this->pm_custom_list)
            ->result_array();
        foreach($rows as $r) {
            if(trim($r['name_list'])) {
                $nameList[$r['name_list']] = $r['name_list'];
            }
        }
        return $nameList;
    }

    /*
     *  根据id找到客户信息
     */
     public function findCustomInfo($customId)
     {
        $res = $this->db
            ->select('custom_name,
                car_code,
                mobile,
                sex,
                car_model,
                vin,
                engine,
                first_date,
                identity')
            ->get_where('pm_custom_list', array('id' => $customId, 'is_delete' => 1))
            ->result_array();
        return $res;
     }

     /*
      * 当日预约客户
      */
    public function todayMeeting($exten = '')
    {
        $start = date('Y-m-d 00:00:00');
        $end = date('Y-m-d 23:59:00');
        $where = "meet_time >= '{$start}' and meet_time <= '{$end}' and is_delete = 1";
        if($exten){
            $where .= " and who_service = {$exten}";
        }
        $res = $this->db
        ->query('select id,custom_name,mobile,car_code,meet_time from pm_custom_list where ' . $where . ' order by meet_time asc limit 0,5')
        ->result_array();
        return $res;
    }

    /*
     * 短信记录
     */
    public function saveSms($serverId,$mobile,$smsType,$content)
    {
        $msgData = array(
            'server_id' => $serverId,
            'mobile' => $mobile,
            'sms_type' => $smsType,
            'content' => $content,
            'addtime' => date('Y-m-d H:i:s'),
        );
        return $this->db->insert($this->sms,$msgData);
    }

    /*
     * 短信记录列表
     */
    public function smsList($condition = '', $page = 1, $limit = 30, $orderBy = '', $fileds = '*', $groupBy = '') {
        $this -> setTable($this->sms);
        $offset = ($page - 1) * $limit;
        return $this -> getList($condition, $fileds, $orderBy, $groupBy, $offset, $limit);
    }

    /*
     * 客户总数
     */
    public function smsCount($condition = '', $groupBy = '') {
        $this -> setTable($this->sms);
        return $this -> getCount($condition, $groupBy);
    }

    /*
     * 列表
     */
    public function createList($table,$condition = '', $page = 1, $limit = 30, $orderBy = '', $fileds = '*', $groupBy = '') {
        $this -> setTable($table);
        $offset = ($page - 1) * $limit;
        return $this -> getList($condition, $fileds, $orderBy, $groupBy, $offset, $limit);
    }

    /*
     * 总数
     */
    public function getTotal($table,$condition = '',$groupBy = '') {
        $this -> setTable($table);
        return $this -> getCount($condition, $groupBy);
    }

    /*
     * 单条记录
     */
    public function findOneRecord($table,$condition = '',$fileds = '*')
    {
        $res = $this->db->select($fileds)->get_where($table,$condition)->result_array();
        return $res[0];
    }

    /*
     * 添加配送单
     */
    public function addDelivery($array)
    {
        return $this->db->insert('pm_delivery',$array);
    }

    /**
     * 每个客服人员的电话消耗量
     * @param array $where
     */
    public function meetConsumeList($where) {
        $rows = $this->db
            ->where($where)
            ->select('who_service, count(*) as num')
            ->group_by('who_service')
            ->get('pm_custom_list')
            ->result_array();
        $res = array();
        if($rows) {
            foreach($rows as $k => $v) {
                $res[$v['who_service']] = array(
                    'num'   =>  $v['num'],
                );
            }
        }
        return $res;
    }

    /**
     * 需要处理的预约量
     *  select c.who_service, MONTH(c.first_date) as f, DATE(c.meet_time) as m, count(*) as nums
     *  from pm_custom_list c
     *  group by c.who_service, f, m
     */
    public function meetList(){
        // $today = date("Y-m-d");
        $rows = $this->db
            ->select('who_service, MONTH(first_date) as mon, DATE(meet_time) as meetday, count(*) as nums')
            ->where(array('is_delete'=>1))
            ->group_by('who_service, mon, meetday')
            ->get('pm_custom_list')
            ->result_array();
        $res = array();
        $res2 = array();
        if($rows) {
            foreach($rows as $k => $v) {
                $res[$v['who_service']][$v['mon']][$v['meetday']] = $v['nums'];
                $res2[$v['who_service']]['detail'][$v['meetday']] += $v['nums'];
                $res2[$v['who_service']]['total'] += $v['nums'];
                if(!empty($v['meetday']) && strtotime($v['meetday'])>0){
                    $res2[$v['who_service']]['meetTotal'] += $v['nums'];
                }
            }
        }
        return array($res, $res2);
    }

    /**
     * 增量记录列表
     */
    public function genIncrementListRows($where){
        $startDate = $where['startDate'];
        $endDate = $where['endDate'];
        $nameList = $where['nameList'];

        $this->load->model('default/PmServicerModel', 'PmServicerModel');
        $serverList = $this->PmServicerModel->getServerList();

        $this->db->where(array('DATE(update_servicer_time)>=' => $startDate, 'DATE(update_servicer_time)<='=>$endDate));
        if(!empty($where['nameList'])) {
            $this->db->where(array('name_list'=>$nameList));
        }
        $rows = $this->db
            ->get('pm_custom_list')
            ->result_array();
        log_message('debug', sprintf('[genIncrementListRows][sql][%s]', $this->db->last_query()));
        $meetList = array();
        if($rows) {
            foreach($rows as $k => $v) {
                $meetList[$v['who_service']]['p1'] ++;
                if($v['tel_status'] == 'connect' && !isset($meetList[$v['who_service']]['mobileList'][$v['mobile']])) {
                    $meetList[$v['who_service']]['p2'] ++;
                }
                if(!empty($v['tel_status'])) {
                    $meetList[$v['who_service']]['p3'] ++;
                }
                if(!empty($v['update_status_time_list'])){
                    $update_status_time_list = json_decode($v['update_status_time_list'], 1);
                    $first_status = array_shift(array_values($update_status_time_list));
                    if(in_array($first_status['status'], array('2', '4', '5'))){
                        $meetList[$v['who_service']]['p5'] ++;
                    }
                    if($first_status['tel_status'] == 'invalid'){
                        $meetList[$v['who_service']]['p6'] ++;
                    }
                }
                if(in_array($v['status'], array('2', '4', '5'))) {
                    $meetList[$v['who_service']]['p7'] ++;
                }
                if($v['tel_status'] == 'invalid' || $v['is_follow'] == 'nogiveup') {
                    $meetList[$v['who_service']]['p8'] ++;
                }
                if($v['tel_status'] == 'connect') {
                    $meetList[$v['who_service']]['p9'] ++;
                }
                if($v['status'] == 5) {
                    $meetList[$v['who_service']]['p11'] ++;
                }

                $meetList[$v['who_service']]['mobileList'][$v['mobile']] = array(
                    'tel_status' => $v['tel_status'],
                    'is_follow'  => $v['is_follow'],
                    'status'     => $v['status'],
                );
                if($v['is_delete'] == 2){
                    $meetList[$v['who_service']]['delete'] ++;
                }
            }
        }

        $returnRows = array();
        foreach($serverList as $k => $v) {
            $accountId = $serverList[$k]['account_id'];
            $mobileList = isset($meetList[$accountId]['mobileList']) && is_array($meetList[$accountId]['mobileList'])?
                array_keys($meetList[$accountId]['mobileList']):
                array();
            $deliveryValues = $this->genDeliveryValues($startDate, $endDate, $mobileList);
            $p1 = isset($meetList[$accountId]['p1'])?$meetList[$accountId]['p1']:0;
            $delete = isset($meetList[$accountId]['delete'])?$meetList[$accountId]['delete']:0;
            $returnRows[$k] = array(
                'mobileList'=>$mobileList,
                'p1' => isset($meetList[$accountId]['p1'])?$meetList[$accountId]['p1']:0,
                'p2' => isset($meetList[$accountId]['p2'])?$meetList[$accountId]['p2']:0,
                'p3' => isset($meetList[$accountId]['p3'])?$meetList[$accountId]['p3']:0,
                'p5' => isset($meetList[$accountId]['p5'])?$meetList[$accountId]['p5']:0,
                'p6' => isset($meetList[$accountId]['p6'])?$meetList[$accountId]['p6']:0,
                'p7' => isset($meetList[$accountId]['p7'])?$meetList[$accountId]['p7']:0,
                'p8' => isset($meetList[$accountId]['p8'])?$meetList[$accountId]['p8']:0,
                'p9' => isset($meetList[$accountId]['p9'])?$meetList[$accountId]['p9']:0,
                'p11' => isset($meetList[$accountId]['p11'])?$meetList[$accountId]['p11']:0,
                'p12' => $deliveryValues['p12'],
                'p13' => $deliveryValues['p13'],
                'p14' => $deliveryValues['p14'],
                'p15' => $deliveryValues['p15'],
                'p16' => $p1 - $delete,
            );
        }
        return $returnRows;
    }

    /**
     * 增量统计表 - 获取和配送相关的值
     * @param array $phoneList
     */
    public function genDeliveryValues($startDate, $endDate, $phoneList) {
        $p12 = $p13 = $p14 = $p15 = 0;
        if(!empty($phoneList)) {
            $deliveryRows = $this->db
                ->where(array('addtime>=' => $startDate, 'addtime<='=>$endDate))
                ->where_in('mobile', $phoneList)
                ->order_by('addtime desc')
                ->get('pm_delivery')
                ->result_array();
            $phones = array();
            foreach($deliveryRows as $v) {
                if(!isset($phones[$v['mobile']])) {
                    $p12 ++;
                    $p13 += $v['price'];
                }
                if($v['status'] == 3) {
                    $p14 ++;
                    $p15 += $v['price'];
                }
                $phones[$v['mobile']] = 1;
            }
        }
        return array(
            'p12'=>$p12,
            'p13'=>$p13,
            'p14'=>$p14,
            'p15'=>$p15,
        );
    }

    /**
     * 生成预约量统计的行数据
     * @param str $day
     */
    public function genMeetListRows($day){
        $this->load->model('default/PmServicerModel', 'PmServicerModel');
        $serverList = $this->PmServicerModel->getServerList();
        list($meetList, $meetList2) = $this->meetList($day);
        // echo '<pre>';
        // print_r($serverList);
        // print_r($meetList);
        // echo '<pre>';

        $returnRows = array();
        list($nextDay, $next2Day, $next3Day, $next4Day, $mon, $nextMon, $next2Mon, $next3Mon) = $this->getTimes($day);
        foreach($serverList as $k => $v) {
            // echo $k, '  ', $mon, '  ', $nextMon, '  ', $next2Mon, '  ', $day, "<br/>";
            $accountId = $serverList[$k]['account_id'];
            $returnRows[] = array(
                'group' => sprintf("%s(%s)", $v['group_desc'], $serverList[$v['leader_id']]['name']),
                'server'=> $v['name'],
                'serverId'=> $k,
                'meetToday' => isset($meetList2[$accountId]['detail'][$day])?$meetList2[$accountId]['detail'][$day]:0,
                'meetNextToday' => isset($meetList2[$accountId]['detail'][$nextDay])?$meetList2[$accountId]['detail'][$nextDay]:0,
                'meetNext2Today' => isset($meetList2[$accountId]['detail'][$next2Day])?$meetList2[$accountId]['detail'][$next2Day]:0,
                'meetNext3Today' => isset($meetList2[$accountId]['detail'][$next3Day])?$meetList2[$accountId]['detail'][$next3Day]:0,
                'meetNext4Today' => isset($meetList2[$accountId]['detail'][$next4Day])?$meetList2[$accountId]['detail'][$next4Day]:0,
                'meetToday_thisMonth' => isset($meetList[$accountId][$mon][$day])?$meetList[$accountId][$mon][$day]:0,
                'meetToday_nextMonth' => isset($meetList[$accountId][$nextMon][$day])?$meetList[$accountId][$nextMon][$day]:0,
                'meetToday_next2Month' => isset($meetList[$accountId][$next2Mon][$day])?$meetList[$accountId][$next2Mon][$day]:0,
                'meetToday_next3Month' => isset($meetList[$accountId][$next3Mon][$day])?$meetList[$accountId][$next3Mon][$day]:0,
                'meetTotal_thisMonth' => $this->countTotalMeet($meetList[$accountId][$mon], $day),
                'meetTotal_nextMonth' => $this->countTotalMeet($meetList[$accountId][$nextMon], $day),
                'meetTotal_next2Month' => $this->countTotalMeet($meetList[$accountId][$next2Mon], $day),
                'meetTotal_next3Month' => $this->countTotalMeet($meetList[$accountId][$next3Mon], $day),
                'total' => isset($meetList2[$accountId]['total'])?$meetList2[$accountId]['total']:0,
                'meetTotal' => isset($meetList2[$accountId]['meetTotal'])?$meetList2[$accountId]['meetTotal']:0,
            );
        }
        return $returnRows;
    }

    /**
     * 根据日期获取相关联的其他日期
     */
    public function getTimes($day){
        if(empty($day)){
            $day = date("Y-m-d");
        }
        $nextDay = date("Y-m-d", strtotime($day)+3600*24);
        $next2Day = date("Y-m-d", strtotime($day)+3600*24*2);
        $next3Day = date("Y-m-d", strtotime($day)+3600*24*3);
        $next4Day = date("Y-m-d", strtotime($day)+3600*24*4);
        $mon = intval(date('m', strtotime($day)));
        $nextMon = $mon + 1;
        $next2Mon = $mon + 2;
        $next3Mon = $mon + 3;
        if($mon == 10) {
            $nextMon = 11;
            $next2Mon = 12;
            $next3Mon = 1;
        }
        if($mon == 11) {
            $nextMon = 12;
            $next2Mon = 1;
            $next3Mon = 2;
        }
        if($mon == 12) {
            $nextMon = 1;
            $next2Mon = 2;
            $next3Mon = 3;
        }
        return array($nextDay, $next2Day, $next3Day, $next4Day, $mon, $nextMon, $next2Mon, $next3Mon);
    }

    /**
     * 计算某个人 '截止统计时间，需要处理且处于xx月到期的累计预约量'
     * @param array $data 这个人服务的所有处于xx月到期的数据
     * @param string $day
     */
    public function countTotalMeet($data, $day){
        $sum = 0;
        if(!empty($data)) {
            foreach($data as $d => $n) {
                if(strtotime($d) <= strtotime($day)) {
                    $sum += $n;
                }
            }
        }
        return $sum;
    }
}
