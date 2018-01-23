<?php

/**
 * banner限制可见模块
 * User: Administrator
 * Date: 2017/4/6
 * Time: 9:31
 */
class BannerAllow_model extends MY_Model
{
    public $_table = 'pm_banner_allow';
    public $_fields = [
        'id',
        'banner_id',
        'user_id',
        'created',
        'updated'
    ];

    public $_listsConfig = [
//        'start_time' => ['?', 'start_time', "start_time >= '?'"],
//        'end_time' => ['?', 'end_time', "end_time <= '? 23:59:59'"],
    ];

    public function updateAllow($data, $id)
    {
        # 清楚旧限制
        $sql = "DELETE FROM {$this->_table} WHERE banner_id = ?";
        $param = [$id];
        $this->db->query($sql, $param);

        # 添加新限制
        if ($data) {
            $insertData = [];
            foreach($data as $k => $v) {
                $insertData[]  = [
                    'banner_id' => $id,
                    'user_id' => $v
                ];

                # 每一百更新一次
                if ($k % 100 == 99) {
                    $this->addBatch($insertData);
                    $insertData = [];
                }
            }

            if ($insertData) {
                $this->addBatch($insertData);
            }
        }
    }

    public function allowCounts($data)
    {
        $sql = "SELECT count(a.id) as num FROM {$this->_table} as b 
                LEFT JOIN baohe.pm_account_list as a on a.pid = b.user_id AND a.account_type = 4 ";
        $sql .= "WHERE b.banner_id = {$data['banner_id']} ";
        if (!empty($data['name'])) {
            $sql .= "AND a.account_login like '%{$data['name']}%'";
        }
        $info = $this->db->query($sql)->row_array();
        return $info['num'];
    }

    public function allowLists($data, $pageCurrent, $pageSize)
    {
        $sql = "SELECT b.id, ba.name,a.account_login, b.created FROM {$this->_table} as b 
                LEFT JOIN baohe.pm_account_list as a on a.pid = b.user_id AND a.account_type = 4 
                LEFT JOIN pm_banner as ba on ba.id = b.banner_id ";
        $sql .= "WHERE b.banner_id = {$data['banner_id']} ";
        if (!empty($data['name'])) {
            $sql .= "AND a.account_login like '%{$data['name']}%' ";
        }
        $sql .= "LIMIT ".($pageCurrent -1) * $pageSize.", $pageSize";
        $list = $this->db->query($sql)->result_array();
        return $list;
    }


}