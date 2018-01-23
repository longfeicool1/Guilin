<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/2
 * Time: 9:47
 */
class YgUser_model extends MY_Model
{
    public $_table = 'pm_account_list'; # 账户总表
    public $_table_user = 'pub_passport_info'; # 账户基本表
    public $_table_car = 'app_obd_bind'; # 用户车辆表
    public $_table_src = 'pub_src_info'; # 车辆绑定设备表

    public $_table_wallet = 'pm_wallet_v2'; # 用户钱包
    public $_table_factory = 'pm_carfactory'; # 车型品牌

    const YG_TYPE = 4; # 阳光账户类型
    const SRC_ON = 1; # 设备在用中
    const WALLET_VIRTUAL = 2; # 虚拟现金
    const WALLET_FACT = 1; # 现金
    const BAOHE_DB = 'baohe'; # 保核数据库

    public function __construct()
    {
        parent::__construct();

        parent::setTable($this->_table, self::BAOHE_DB);
    }

    /**
     * 用户列表
     * @param array $where
     * @param int $page
     * @param int $size
     * @param string $orderBy
     * @return mixed
     */
    public function lists($where, $page = 1, $size = 30, $orderBy = 'a.id desc')
    {
        $start = ($page - 1) * $size;
        $field = "a.pid,
            a.id,
            m.city,
            c.insure_code,
            IF(c.insure_end = '0000-00-00','',insure_end) AS insure_end,
            a.account_login,
            date_format(a.created,'%Y-%m-%d') as created,
            u.username,
            if(u.sex = 1, '男', '女') as 'sex',
            c.is_check,
            if(c.cardtype = 1, '临时牌', '正常牌') as 'cardtype',
            u.birthday,
            c.CARCARD as car_card,
            s.src,
            if(s.createtime, DATE_FORMAT(s.createtime,'%Y-%m-%d'), '') as src_created,
            if(y.install_time, install_time, '') as src_install_time,
            c.bind_id as bindid,
            a.comment,
            cc.factory,
            mm.demio,
            mm.version,
            (select count(*) from ygbx_app.pm_single_actual where uid = a.pid) as tot,
            if(w.history_amount, round(w.history_amount / 100, 2), 0) as money";
        $sql = $this->getUserSql($field, $where);
        $sql .= " GROUP BY a.pid ORDER BY {$orderBy} LIMIT {$start}, {$size}";
        $list = $this->db->query($sql)->result_array();
        $list = $this->setListId($list, $page, $size);
        // echo '<pre>';print_r($this->db->last_query());die;
        return $list;
    }

    /**
     * 获取用户记录条数
     * @param array $where
     * @return int
     */
    public function counts($where)
    {
        $num = 0; # default
        $filed = "count(DISTINCT(a.pid)) as num";
        $sql = $this->getUserSql($filed, $where);
        $row = $this->db->query($sql)->row_array();
        if ($row) {
            $num = $row['num'];
        }
        // echo '<pre>';print_r($this->db->last_query());die;
        return $num;
    }

    /**
     * 获取列表sql
     * @param string $field
     * @param string $where
     * @return string
     */
    private function getUserSql($field = '*', $where = '')
    {
        $sql = "SELECT {$field} FROM {$this->_table} as a ";
        if (!empty($where['is_check']) && $where['is_check'] == 1) {
            $sql .= " JOIN {$this->_table_user} AS u ON u.pid =  a.pid ";
            $sql .= " JOIN {$this->_table_car} AS c ON c.USER_ID =  u.pid ";
            $sql .= " JOIN {$this->_table_src} AS s ON s.bindid =  c.BIND_ID  AND s.status = " . self::SRC_ON;
            $sql .= " LEFT JOIN pm_carfactory cc ON cc.factory_id = c.FACTORYID";
            $sql .= " LEFT JOIN pm_carmodel mm ON mm.model_id = c.MODELID";
            $sql .= " LEFT JOIN ygbx_app.pm_ygbx_src_list y ON y.src = s.src";
        } else {
            $sql .= " LEFT JOIN {$this->_table_user} AS u ON u.pid =  a.pid ";
            $sql .= " LEFT JOIN {$this->_table_car} AS c ON c.USER_ID =  u.pid ";
            $sql .= " LEFT JOIN {$this->_table_src} AS s ON s.bindid =  c.BIND_ID  AND s.status = " . self::SRC_ON;
            $sql .= " LEFT JOIN pm_carfactory cc ON cc.factory_id = c.FACTORYID";
            $sql .= " LEFT JOIN pm_carmodel mm ON mm.model_id = c.MODELID";
            $sql .= " LEFT JOIN ygbx_app.pm_ygbx_src_list y ON y.src = s.src";
        }
        $sql .= " LEFT JOIN ygbx_app.pm_src_city m ON s.src = m.src";
        $sql .= " LEFT JOIN {$this->_table_wallet} AS w ON w.bindid =  c.BIND_ID AND w.type = " . self::WALLET_VIRTUAL . " AND u.pid = w.pid ";
        $sql .= " WHERE a.account_type = " . self::YG_TYPE; # . " AND s.status = " . self::SRC_ON;

        $sqlWhere = $this->listWhere($where);
        $sql .= $sqlWhere ? $sqlWhere : '';

        return $sql;
    }

    /**
     * 拼接查询条件
     * @param $post
     * @return string
     */
    public function listWhere($post)
    {
        $where = '';
        if (!empty($post)) {
            extract($post);
            $where .= !empty($account_login) ? " AND a.account_login like '%{$account_login}%'" : '';
            if (!empty($is_check) && $is_check == 4) {
                $where .= !empty($is_check) ? " AND (c.is_check = 1 or c.is_check is null) and s.bindid is null" : '';
            } else {
                $where .= !empty($is_check) ? " AND c.is_check = {$is_check}" : '';
            }
            $where .= !empty($username) ? " AND u.username like '%{$username}%'" : '';
            $where .= !empty($car_card) ? " AND c.CARCARD like '%{$car_card}%'" : '';
            $where .= !empty($src) ? " AND s.src like '%{$src}%'" : '';
            $where .= !empty($pid) ? " AND a.pid = '{$pid}'" : '';
        }
        return $where;
    }

    /**
     * 设置列表的序号
     * @param $list
     * @param int $page
     * @param int $size
     * @return mixed
     */
    protected function setListId($list, $page = 1, $size = 30)
    {
        if (!empty($list)) {
            foreach ($list as $k => $v) {
                $list[$k]['row_id'] = ($page - 1) * $size + $k + 1;
            }
        }
        return $list;
    }

    /**
     * 获取账户的基本信息
     * @param $userId
     * @return mixed
     */
    public function getUserRow($userId)
    {
        $where = ['pid' => $userId];
        $field = "a.pid, a.id, a.account_login,a.created, u.username, if(u.sex = 1, '男', '女') as 'sex', u.birthday, c.CARCARD as car_card, s.src, s.bindid, a.comment, if(w.history_amount, round(w.history_amount / 100, 2), 0) as money";
        $sql = $this->getUserSql($field, $where);
        $sql .= " GROUP BY a.pid";
        $row = $this->db->query($sql)->row_array();

        return $row;
    }

    /**
     * 获取车辆信息
     * @param $userId
     * @return mixed
     */
    public function getUserCar($userId)
    {
        $sql = "SELECT s.bindid, f.factory AS models,c.CARCARD AS car_card,c.VIN AS vin,c.ENGINECODE AS enginecode,DATE_FORMAT(c.DEBUTDATE, '%Y-%m-%d') as debutdate,
                s.src
                FROM {$this->_table_car} as c
                LEFT JOIN {$this->_table_src} as s on s.bindid = c.BIND_ID AND s.status = 1
                LEFT JOIN {$this->_table_factory} AS f on f.factory_id = c.FACTORYID
                WHERE c.USER_ID = {$userId} GROUP BY c.USER_ID limit 1";
        $row = $this->db->query($sql)->row_array();
        return $row;
    }

    public function getUserDevice($userId)
    {
        $sql = "SELECT a.pid,s.bindid,u.uniqueId,u.appkey,u.systemName,u.deviceName,u.version FROM baohe.pm_account_list AS a
                        LEFT JOIN baohe.pub_passport_info AS u ON u.pid = a.pid
                        LEFT JOIN baohe.app_obd_bind AS c ON c.USER_ID = u.pid
                        LEFT JOIN baohe.pub_src_info AS s ON s.bindid = c.BIND_ID AND s. STATUS = 1
                        WHERE a.account_type = 4 AND s.id > 0 AND a.pid = {$userId} ";

        $Info = $this->db->query($sql)->row_array();
        return $Info;
    }

    /**
     * 激活会员数
     * @return int
     */
    public function activeNum()
    {
        $sql = "
              SELECT count(*) as 'active_num' FROM baohe.pm_account_list as a 
              LEFT JOIN baohe.app_obd_bind as b on b.USER_ID = a.pid AND is_show = 1
              LEFT JOIN baohe.pub_src_info as s on s.bindid = b.BIND_ID
              LEFT JOIN ygbx_app.pm_ygbx_src_list as ys on ys.src = s.src 
              where a.account_type = 4 AND a.is_show =1 AND b.BIND_ID > 0 AND s.`status` = 1 AND ys.first_heart_time != '0000-00-00'";
        $row = $this->db->query($sql)->row_array();
        return !empty($row) ? $row['active_num'] : 0;
    }

    /**
     * 手机系统
     * @return mixed
     */
    public function systemTotal()
    {
        $sql = "select systemName, count(systemName) as num from baohe.pm_account_list as a 
                left join baohe.pub_passport_info as p on p.pid =a.pid 
                where a.account_type = 4 and a.is_show = 1 
                group by systemName";
        $list = $this->db->query($sql)->result_array();
        return $list;
    }

    /**
     * 安装包版本
     * @return mixed
     */
    public function appVersionTotal()
    {
        $sql = "select systemName, version, count(version) as num from baohe.pm_account_list as a 
                left join baohe.pub_passport_info as p on p.pid =a.pid 
                where a.account_type = 4 and a.is_show = 1 
                group by systemName asc,version desc";
        $list = $this->db->query($sql)->result_array();
        return $list;
    }

    /**
     * 手机型号
     * @return mixed
     */
    public function deviceTotal()
    {
        $sql = "select LOWER(substring_index(substring_index(deviceName,' ',1), '-',1)) as deviceName, count(*) as num 
                from baohe.pm_account_list as a 
                left join baohe.pub_passport_info as p on p.pid =a.pid 
                where a.account_type = 4 and a.is_show = 1 
                group by LOWER(substring_index(substring_index(deviceName,' ',1), '-',1)) 
                ORDER BY NULL";
        $list = $this->db->query($sql)->result_array();
        return $list;
    }

    /**
     * 下载渠道
     * @return mixed
     */
    public function channelTotal()
    {
        $sql = "select channel, count(channel) as num from baohe.pm_account_list as a 
                left join baohe.pub_passport_info as p on p.pid =a.pid 
                where a.account_type = 4 and a.is_show = 1 
                group by channel;";
        $list = $this->db->query($sql)->result_array();
        return $list;
    }

    /**
     * 手机系统版本
     * @return mixed
     */
    public function systemVersionTotal()
    {
        $sql = "select 
                  systemName, substring_index(systemVersion,'.',1) as systemVersion, count(*) as num 
                from baohe.pm_account_list as a 
                left join baohe.pub_passport_info as p on p.pid =a.pid 
                where a.account_type = 4 and a.is_show = 1 
                group by systemName asc, substring_index(systemVersion,'.',1) desc ;
                ";
        $list = $this->db->query($sql)->result_array();
        return $list;
    }
}
