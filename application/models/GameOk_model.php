<?php

/**
 * 游玩游戏模型
 * User: Administrator
 * Date: 2017/4/5
 * Time: 11:44
 */
class GameOk_model extends MY_Model
{
    public $_table = 'pm_game_ok'; # 游玩游戏表
    public $_activity_table = 'pm_activity_list';

    public function lists($data, $offset, $size)
    {
        $sql = "SELECT g.id, g.title, g.user_name, g.car_number, g.start_time, g.end_time, g.ok_time, g.ok_status,
                       g.`time`, g.day_date, g.created, g.updated, a.name AS activity_name 
                FROM {$this->_table} AS g 
                LEFT JOIN {$this->_activity_table} AS a ON a.id = g.activity_id 
                WHERE 1 =1 
                ";

        $sql .= isset($data['ok_status']) && !empty($data['ok_status']) ?  " AND g.ok_status = {$data['ok_status']}" : '';
        $sql .= isset($data['title']) && !empty($data['title']) ?  " AND g.title like '%{$data['title']}%'" : '';
        $sql .= isset($data['car_number']) && !empty($data['car_number']) ?  " AND g.car_number like '%{$data['car_number']}%'" : '';
        $sql .= isset($data['start_time']) && !empty($data['start_time']) ?  " AND g.day_date >= '{$data['start_time']}'" : '';
        $sql .= isset($data['end_time']) && !empty($data['end_time']) ?  " AND g.day_date <= '{$data['end_time']}'" : '';
        $sql .= " ORDER BY day_date desc, g.ok_status desc, `time` asc, id asc";
        $sql .= ' LIMIT ' . $offset . ', ' . $size;
        $list = $this->db->query($sql)->result_array();
        return $list;
    }

    public function counts($data)
    {
        $sql = "SELECT count(g.id) as num 
                FROM {$this->_table} AS g 
                LEFT JOIN {$this->_activity_table} AS a ON a.id = g.activity_id 
                WHERE 1 =1 
                ";

        $sql .= isset($data['ok_status']) && !empty($data['ok_status']) ?  " AND g.ok_status = {$data['ok_status']}" : '';
        $sql .= isset($data['title']) && !empty($data['title']) ?  " AND g.title like '%{$data['title']}%'" : '';
        $sql .= isset($data['car_number']) && !empty($data['car_number']) ?  " AND g.car_number like '%{$data['car_number']}%'" : '';
        $sql .= isset($data['start_time']) && !empty($data['start_time']) ?  " AND g.day_date >= '{$data['start_time']}'" : '';
        $sql .= isset($data['end_time']) && !empty($data['end_time']) ?  " AND g.day_date <= '{$data['end_time']}'" : '';
        $info = $this->db->query($sql)->row_array();
        $num = !empty($info) ? $info['num'] : 0;
        return $num;
    }

    /**
     * 活动信息
     * @param $id
     * @return mixed
     */
    public function activityInfo($id)
    {
        $sql = "SELECT `name`, start, `end` FROM {$this->_activity_table} WHERE id = $id";
        $row = $this->db->query($sql)->row_array();
        return $row;
    }

    /**
     * 总参与会员数、游玩次数、完成次数、未完成次数
     * @param $id
     * @return array
     */
    public function joinInfo($id)
    {
        $sql = "SELECT 
                count(DISTINCT(user_id)) as user_num, -- 总参与会员数 
                count(*) as join_num, -- 游玩次数 
                sum(if(ok_status = 2, 1, 0)) as ok_num,    -- 完成次数
                sum(if(ok_status = 2, 0, 1)) as no_ok_num  -- 未完成次数
                FROM {$this->_table} WHERE activity_id = {$id}";
        $row = $this->db->query($sql)->row_array();
        return $row;
    }

    /**
     * 阳光活动截止总用户
     * @param $end
     * @return int
     */
    public function ygUserNum($end) {
        $sql = "SELECT count(*) as num FROM baohe.pm_account_list WHERE account_type = 4 AND is_show = 1 and created < '{$end}'";
        $row = $this->db->query($sql)->row_array();
        return !empty($row) ? intval($row['num']) : 0;
    }

    public function totalMoney($id)
    {
        $sql ="SELECT day_date,sum(money) as send_money, sum(IF(`status` =2, money, 0)) as receive_money 
                FROM `pm_game_money`
                 WHERE activity_id = $id
                GROUP BY day_date
                ORDER BY day_date desc";
        $list = $this->db->query($sql)->result_array();
        return $list;
    }
}
