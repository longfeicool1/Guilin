<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require dirname(__FILE__) . '/Service.php';
/**
 *每天1点统计团队业绩
 * 0 1 * * * /usr/bin/php70 index.php crontab/team/start
 */
class Tuan  extends Service
{
    public function __construct()
    {
        parent::__construct();
    }

    public function start()
    {
        echo 'succ start';
        $this->updateTuanConfigDate();
        echo 'succ end';
    }

    public function updateTuanConfigDate()
    {
        $this->db->update('md_tuan_config',[
            'send_num_a' => 0,
            'send_num_b' => 0,
            'send_num_c' => 0,
        ]);
    }
}