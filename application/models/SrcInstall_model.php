<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/5
 * Time: 14:10
 */
class SrcInstall_model extends MY_Model
{
    public $_table = 'pm_ygbx_src_list';

    public function __construct()
    {
        parent::__construct();
    }

    public function lists($data, $page, $size)
    {
        $sql = "SELECT 
                  c.id, c.src, c.install_time AS install_time,sc.provice AS province, sc.city,  s.createtime AS active_time, 
                  s.status,if(s.status=1,'使用中','未使用') AS status_abbr , s.remark, a.account_login,p.username,
                  a.account_type, o.org_id, org.`name` AS org_name
                FROM {$this->_table} as c 
                LEFT JOIN pm_src_city as sc on sc.src = c.src
                LEFT JOIN baohe.pub_src_info AS s ON s.src = c.src 
                LEFT JOIN baohe.app_obd_bind AS b ON b.BIND_ID = s.bindid
                LEFT JOIN baohe.pm_account_list AS a ON a.pid = b.USER_ID AND a.is_show = 1
                LEFT JOIN baohe.pub_passport_info AS p ON p.pid = a.pid
                LEFT JOIN dc_comm.dc_obd_id AS o ON o.OBD_ID = c.src
                LEFT JOIN baohe.pm_org as org on org.id = o.org_id
                WHERE 1 = 1 ";
        if (isset($data['start_time']) && !empty($data['start_time'])) {
            $sql .= " AND c.install_time >=  '{$data['start_time']}'";
        }

        if (isset($data['end_time']) && !empty($data['end_time'])) {
            $sql .= " AND c.install_time <=  '{$data['end_time']}'";
        }

        if(empty($data) ||  ($data['end_time'] == '' && $data['end_time'] == '')) {
            $sql .= " AND c.install_time != '0000-00-00'";
        }

        if (isset($data['src']) && !empty($data['src'])) {
            $sql .= " AND c.src like '%{$data['src']}%'";
        }

        if (isset($data['org_id']) && $data['org_id'] == 1) {
            $sql .= " AND o.org_id = 60";
        } else if(isset($data['org_id']) && $data['org_id'] == 2) {
            $sql .= " AND o.org_id != 60";
        }
        if (isset($data['account_type']) && $data['account_type'] == 1) {
            $sql .= " AND a.account_type = 4";
        } else if(isset($data['account_type']) && $data['account_type'] == 2) {
            $sql .= " AND a.account_type != 4";
        }

        $start = ($page - 1) * $size;
        $sql .= ' ORDER BY install_time DESC ';
        $sql .= " limit {$start}, {$size}";
        $list = $this->db->query($sql)->result_array();
        return $list;
    }

    public function counts($data)
    {
        $sql = "SELECT count(*) as num FROM {$this->_table} as c 
                LEFT JOIN baohe.pub_src_info as s on s.src = c.src 
                LEFT JOIN baohe.app_obd_bind as b on b.BIND_ID = s.bindid
                LEFT JOIN baohe.pm_account_list as a on a.pid = b.USER_ID AND a.is_show = 1
                LEFT JOIN baohe.pub_passport_info as p on p.pid = a.pid
                LEFT JOIN dc_comm.dc_obd_id AS o ON o.OBD_ID = c.src
                WHERE 1 = 1 
                ";
        if (isset($data['start_time']) && !empty($data['start_time'])) {
            $sql .= " AND c.install_time >=  '{$data['start_time']}'";
        }

        if (isset($data['end_time']) && !empty($data['end_time'])) {
            $sql .= " AND c.install_time <=  '{$data['end_time']}'";
        }

        if(empty($data) ||  ($data['end_time'] == '' && $data['end_time'] == '')) {
            $sql .= " AND c.install_time != '0000-00-00'";
        }

        if (isset($data['src']) && !empty($data['src'])) {
            $sql .= " AND c.src like '%{$data['src']}%'";
        }

        if (isset($data['org_id']) && $data['org_id'] == 1) {
            $sql .= " AND o.org_id = 60";
        } else if(isset($data['org_id']) && $data['org_id'] == 2) {
            $sql .= " AND o.org_id != 60";
        }
        if (isset($data['account_type']) && $data['account_type'] == 1) {
            $sql .= " AND a.account_type = 4";
        } else if(isset($data['account_type']) && $data['account_type'] == 2) {
            $sql .= " AND a.account_type != 4";
        }

        $row = $this->db->query($sql)->row_array();
        return !empty($row) ? $row['num'] : 0;
    }


}