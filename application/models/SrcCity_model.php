<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/5
 * Time: 14:10
 */
class SrcCity_model extends MY_Model
{
    public $_table = 'pm_src_city';

    public function __construct()
    {
        parent::__construct();
    }

    public function cityList()
    {
        $sql = "SELECT provice,city FROM {$this->_table} WHERE provice != '' AND  city != '' AND is_test = 2 group by provice asc, city asc";
        $list = $this->db->query($sql)->result_array();
        return $list;
    }

    public function CitySrc($province, $city)
    {
        $sql = "SELECT 
                  c.id, c.src, sum(if(sa.id, 1, 0)) AS actual_num, a.account_login as loginName, u.username, u.birthday,
                  if(u.sex = 1, '男', '女') as 'sex', b.CARCARD AS carNumber,if(b.cardtype = 1, '临时牌', '正常牌') as 'carStatus',
                   date_format(s.createtime,'%Y-%m-%d') AS activeTime,ys.install_time AS installTime, date_format(a.created,'%Y-%m-%d') AS 'regDate',
                  b.is_check,if(w.history_amount, round(w.history_amount / 100, 2), 0) as money,
                  b.insure_code as insureNo, a.comment
                FROM {$this->_table} AS c 
                LEFT JOIN baohe.pub_src_info AS s ON s.src = c.src 
                LEFT JOIN pm_single_actual AS sa ON sa.src = c.src
                LEFT JOIN baohe.app_obd_bind AS b ON b.BIND_ID = s.bindid
                LEFT JOIN baohe.pub_passport_info AS u ON u.pid = b.USER_ID
                LEFT JOIN baohe.pm_account_list AS a ON a.pid = u.pid AND is_show = 1 -- AND account_type = 4
                LEFT JOIN pm_ygbx_src_list AS ys ON ys.src = c.src
                LEFT JOIN baohe.pm_wallet_v2 AS w ON w.bindid =  b.BIND_ID AND w.type = 2 AND u.pid = w.pid 
                WHERE c.provice = '{$province}' AND c.city = '{$city}' AND c.is_test = 2 AND s.`status` = 1
                GROUP BY c.src 
                ORDER BY NULL
                ";
        $list = $this->db->query($sql)->result_array();
        return $list;
    }

    /**
     * 获取城市设备信息
     * @param $id
     * @return mixed
     */
    public function info($id)
    {
        $sql = "SELECT id,src,provice as province,city,is_test FROM {$this->_table} WHERE id = $id";
        $row = $this->db->query($sql)->row_array();
        return $row;
    }

    public function srcInfo($src)
    {
        $sql = "SELECT id,src,provice as province,city FROM {$this->_table} WHERE src = '{$src}'";
        $row = $this->db->query($sql)->row_array();
        return $row;
    }

    public function otherSrcInfo($src, $id)
    {
        $sql = "SELECT id,src,provice as province,city FROM {$this->_table} WHERE src = '{$src}' AND id != {$id}";
        $row = $this->db->query($sql)->row_array();
        return $row;
    }

    public function lists($data, $page, $size)
    {
        $sql = "SELECT 
                  c.id, c.src, c.provice AS province, c.city, c.created AS 'cTime', s.createtime AS active_time, 
                  s.status,if(s.status=1,'使用中',if(s.status=0, '已停用', '未使用')) AS status_abbr , s.remark, a.account_login,p.username,
                  a.account_type, o.org_id, org.`name` AS org_name,c.is_test
                FROM {$this->_table} as c 
                LEFT JOIN baohe.pub_src_info AS s ON s.src = c.src 
                LEFT JOIN baohe.app_obd_bind AS b ON b.BIND_ID = s.bindid
                LEFT JOIN baohe.pm_account_list AS a ON a.pid = b.USER_ID AND a.is_show = 1
                LEFT JOIN baohe.pub_passport_info AS p ON p.pid = a.pid
                LEFT JOIN dc_comm.dc_obd_id AS o ON o.OBD_ID = c.src
                LEFT JOIN baohe.pm_org as org on org.id = o.org_id
                WHERE 1 = 1 ";
        if (isset($data['src']) && !empty($data['src'])) {
            $sql .= " AND c.src like '%{$data['src']}%'";
        }

        if (isset($data['province']) && !empty($data['province'])) {
            $sql .= " AND c.provice like '%{$data['province']}%'";
        }

        if (isset($data['city']) && !empty($data['city'])) {
            $sql .= " AND c.city like '%{$data['city']}%'";
        }
        if (isset($data['srcStatus']) && $data['srcStatus'] == 1) {
                $sql .= " AND s.status = {$data['srcStatus']}";
        } else if(isset($data['srcStatus']) && $data['srcStatus'] == 2) {
            $sql .= " AND s.status is null ";
        } else if(isset($data['srcStatus']) && $data['srcStatus'] == 3) {
            $sql .= " AND s.status = 0 ";
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
        if (isset($data['is_test']) && $data['is_test'] == 1) {
            $sql .= " AND c.is_test = 1";
        } else if(isset($data['is_test']) && $data['is_test'] == 2) {
            $sql .= " AND c.is_test = 2";
        }

        $start = ($page - 1) * $size;
        $sql .= ' ORDER BY id DESC ';
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
        if (isset($data['src']) && !empty($data['src'])) {
            $sql .= " AND c.src like '%{$data['src']}%'";
        }

        if (isset($data['province']) && !empty($data['province'])) {
            $sql .= " AND c.provice like '%{$data['province']}%'";
        }

        if (isset($data['city']) && !empty($data['city'])) {
            $sql .= " AND c.city like '%{$data['city']}%'";
        }

        if (isset($data['srcStatus']) && $data['srcStatus'] == 1) {
            $sql .= " AND s.status = {$data['srcStatus']}";
        } else if(isset($data['srcStatus']) && $data['srcStatus'] == 2) {
            $sql .= " AND s.status is null ";
        } else if(isset($data['srcStatus']) && $data['srcStatus'] == 3) {
            $sql .= " AND s.status = 0 ";
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
        if (isset($data['is_test']) && $data['is_test'] == 1) {
            $sql .= " AND c.is_test = 1";
        } else if(isset($data['is_test']) && $data['is_test'] == 2) {
            $sql .= " AND c.is_test = 2";
        }

        $row = $this->db->query($sql)->row_array();
        return !empty($row) ? $row['num'] : 0;
    }


}