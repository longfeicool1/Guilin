<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/5
 * Time: 19:32
 */
class WalletTask_model extends MY_Model
{
    public $_table = 'pm_wallet_v2_task';
    const BAOHE_DB = 'baohe';
    public $_fields = [
        'alias_id', #别名ID
        'status',   # 1 待执行 2 执行成功 3 执行失败 4 执行中
        'pid',      # 用户ID
        'bindid',   # 车辆ID
        'type',     #   金额类型(1 现金, 2 虚拟现金)
        'created',  #
        'updated',  #
        'amount',   #
        'pay_type', #
        'pay_key',
        'pay_user_key',
        'promoter',
        'response',
        'remark',   #备注(成功会写入流水)
        'action',   # 1 提取 2 充值
        'history_amount', # 历史余额
    ];

    public $_listsConfig = [
        'start_time' => ['?', 'created', "created >= '?'"],
        'end_time' => ['?', 'created', "created <= '? 23:59:59'"],
    ];

    public $_orderBy = 'id desc';

    public function __construct()
    {
        parent::__construct();

        parent::setTable($this->_table, self::BAOHE_DB);
    }

}