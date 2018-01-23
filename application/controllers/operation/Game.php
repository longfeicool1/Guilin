<?php

/**
 * 游戏完成管理
 * User: Yijw
 * Date: 2017-12-14
 * Time: 10:31
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Game extends MY_Controller
{
    public $ok_status = [
        '0' => '无效',
        '1' => '未完成',
        '2' => '完成'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('GameOk_model', 'GameOkModel');
        $this->load->model('YgUser_model', 'YgUserModel');
        $this->_tabId  = ENVIRONMENT == 'development' ? 'id301' : 'id335';
    }

    public function index()
    {
        $data = $this->input->post();
        list($pageCurrent, $pageSize) = $this->getPageSizeAndCurrent($data);

        $offset = ($pageCurrent - 1 ) * $pageSize;
        $count = $this->GameOkModel->counts($data);
        $list = $this->GameOkModel->lists($data, $offset, $pageSize);

        foreach ($list as $k=>$v) {
            $list[$k]['row_id'] = $offset + $k + 1;
            $list[$k]['real_time'] = $v['ok_status'] == 2 ? strtotime($v['updated']) - strtotime($v['created']) : '无';

        }

        $this->assign('list', $list);
        $this->assign('limit', $pageSize);
        $this->assign('count', $count);
        $this->assign('search', $data);
        $this->assign('ok_status', $this->ok_status);
        $this->display('game/list.tpl');
    }

    /**
     * 游戏活动分析
     */
    public function detail()
    {
        $id = $this->input->get('id'); # 活动ID
        $gameInfo = $this->GameOkModel->activityInfo($id);
        $joinInfo = $this->GameOkModel->joinInfo($id);
        $gameInfo['user_num'] = $joinInfo['user_num'];
        $gameInfo['join_num'] = $joinInfo['join_num']; # 游玩次数
        $gameInfo['ok_num'] = $joinInfo['ok_num'];
        $gameInfo['no_ok_num'] = $joinInfo['no_ok_num'];
        $gameInfo['ygNum'] = $this->GameOkModel->ygUserNum($gameInfo['end']);

        $gameInfo['active_num'] = $this->YgUserModel->activeNum();
        $gameInfo['user_rate'] = round($gameInfo['user_num'] / $gameInfo['active_num'], 4) * 100 . '%';
        $gameInfo['join_ok_rate'] = round($gameInfo['ok_num'] / $gameInfo['join_num'], 4) * 100 . '%';
        $gameInfo['join_no_rate'] = round($gameInfo['no_ok_num'] / $gameInfo['join_num'], 4) * 100 . '%';
        $gameInfo['ok_rate'] = round($gameInfo['ok_num'] / min($gameInfo['ok_num'], $gameInfo['no_ok_num']), 2);
        $gameInfo['on_rate'] = round($gameInfo['no_ok_num'] / min($gameInfo['ok_num'], $gameInfo['no_ok_num']), 2);

        $gameInfo['money'] = $this->GameOkModel->totalMoney($id);

        $this->assign('gameInfo', $gameInfo);
        $this->display('game/detail.tpl');




    }
}
