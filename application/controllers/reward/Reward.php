<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 线下服务商后台
 */
class Reward extends MY_Controller
{
    # 不发放礼品的ID
    public $notPrize = [
        1,  # 谢谢参与
    ];

    public function __construct()
    {
        parent::__construct();
        // 引入model
        $this->load->model('Reward_model', 'rewardModel');
        $this->load->model('Common_model', 'commonModel');
    }

    public function entityReward()
    {
        $type = 2; # 实物礼品
        $data = $this->input->post();
        $condition = ['d.type' => $type];
        $data['type'] = $type;
        if (!empty($data['activity_id'])) {
            $condition['a.activity_id'] = $data['activity_id'];
        }
        if (!empty($data['bt'])) {
            $condition['a.created >= '] = $data['bt'];
        }
        if (!empty($data['et'])) {
            $condition['a.created <= '] = $data['et'] . ' 23:59:59';
        }
        if (!empty($data['prize_id'])) {
            $condition['a.prize_id'] = $data['prize_id'];
        }

        if (!empty($data['content'])) {
            $condition['CONCAT(c.loginname,b.carcard) like'] = "%{$data['content']}%";
        }
        $page = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
        $size = !empty($data['pageSize']) ? $data['pageSize'] : 30;
        $list = $this->rewardModel->rewardList($page, $size, $condition, $this->notPrize);
        $count = $this->rewardModel->rewardCount($condition, $this->notPrize);


        $this->ci_smarty->assign('activity', $this->getActivity());
        $this->ci_smarty->assign('prize', $this->getPrize($type));
        $this->ci_smarty->assign('list', $list);
        $this->ci_smarty->assign('count', $count);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->display('reward/entityReward.tpl');
    }

    public function dummyReward()
    {
        $type = 1;# 虚拟礼品
        $data = $this->input->post();
        $condition = ['d.type' => $type];
        $data['type'] = $type;
        if (!empty($data['activity_id'])) {
            $condition['a.activity_id'] = $data['activity_id'];
        }
        if (!empty($data['bt'])) {
            $condition['a.created >= '] = $data['bt'];
        }
        if (!empty($data['et'])) {
            $condition['a.created <= '] = $data['et'] . ' 23:59:59';
        }
        if (!empty($data['prize_id'])) {
            $condition['a.prize_id'] = $data['prize_id'];
        }

        if (!empty($data['content'])) {
            $condition['CONCAT(c.loginname,b.carcard) like'] = "%{$data['content']}%";
        }
        $page = !empty($data['pageCurrent']) ? $data['pageCurrent'] : 1;
        $size = !empty($data['pageSize']) ? $data['pageSize'] : 30;
        $list = $this->rewardModel->rewardList($page, $size, $condition, $this->notPrize);
        $count = $this->rewardModel->rewardCount($condition, $this->notPrize);
        $sumMoney = $this->rewardModel->getSumMoney($condition);

        $this->ci_smarty->assign('activity', $this->getActivity());
        $this->ci_smarty->assign('prize', $this->getPrize($type));
        $this->ci_smarty->assign('list', $list);
        $this->ci_smarty->assign('count', $count);
        $this->ci_smarty->assign('search', $data);
        $this->ci_smarty->assign('sumMoney', $sumMoney);
        $this->ci_smarty->display('reward/dummyReward.tpl');
    }

    /**
     * 获取活动主题
     * @return mixed
     */
    public function getActivity()
    {
        $sql = "select id, name from pm_activity_list";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    /**
     * 获取活动礼品
     * @param $type
     * @return mixed
     */
    public function getPrize($type)
    {
        $id = $this->post_get('activity_id');
        $notPrize = implode(',', $this->notPrize);
        $sql = "select p.id, concat(p.prize_level,'  ',p.prize_name) as prize_name from pm_activity_prize p 
                left join pm_activity_list l on l.id = p.activity_id
                where p.type = {$type} and l.id = '{$id}' and p.id not in ($notPrize)";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    /**
     * 获取礼品
     */
    public function prize()
    {
        $id = $this->post_get('activity_id');
        $type = $this->post_get('type');
        $notPrize = implode(',', $this->notPrize);
        $sql = "select p.id, concat(p.prize_level,'  ',p.prize_name) as prize_name from pm_activity_prize p 
                left join pm_activity_list l on l.id = p.activity_id
                where p.type = {$type} and l.id = {$id} and p.id not in ($notPrize)";
        $list = $this->db->query($sql)->result_array();
        $data = [['value' => '', 'label' => '-- 等级  奖品 --']];
        foreach ($list as $k => $v) {
            $data[] = ["value" => $v['id'], "label" => $v['prize_name']];
        }

        echo json_encode($data);
        return;
    }

    /**
     * 导出
     */
    public function torewardExport()
    {
        $data = $this->input->get();
        $condition = ['d.type' => $data['type']];
        if (!empty($data['activity_id'])) {
            $condition['a.activity_id'] = $data['activity_id'];
        }
        if (!empty($data['bt'])) {
            $condition['a.created >= '] = $data['bt'];
        }
        if (!empty($data['et'])) {
            $condition['a.created <= '] = $data['et'];
        }
        if (!empty($data['prize_id'])) {
            $condition['a.prize_id'] = $data['prize_id'];
        }
        if (!empty($data['content'])) {
            $condition['CONCAT(c.loginname,b.carcard) like'] = "%{$data['content']}%";
        }
        $list = $this->rewardModel->rewardList(1, 99999, $condition, $this->notPrize);
        $header = array(
            'loginname' => '账户',
            'carcard' => '车牌',
            'username' => '姓名',
            'src' => '设备ID',
            'city' => '设备城市',
            'activity_name' => '活动名称',
            'prize_level' => '奖品等级',
            'prize_name' => '奖品',
            'created' => '中奖日期',
        );
        $css = [
            'src' => 'txt',
            'loginname' => 'txt',
        ];
        // echo '<pre>';print_r($data);die;
        $filename = date('Y-m-d') . '抽奖奖励名单.xls';
        $this->commonModel->exportExcel($header, $list, $filename, $css);
    }
}