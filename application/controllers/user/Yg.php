<?php

/**
 * 阳光App用户管理模块
 * User: Yijw
 * Date: 2017/4/2
 * Time: 9:34
 */
class Yg extends MY_Controller
{
    const TAB_ID_ONLINE  = ''; # 线上菜单ID
    const TAB_ID_OFFLINE = 'id273'; # 线下菜单ID
    const OBD_STATUS_URL = 'http://api.ubi001.com/v1/device?obd_id=';
    const OBD_ONLINE     = '在线';
    const OBD_OFFLINE    = '离线';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('YgUser_model', 'YgUserModel');

        $this->_tabId = ENVIRONMENT == 'development' ? self::TAB_ID_OFFLINE : self::TAB_ID_ONLINE;
    }

    /**
     * 用户列表
     */
    public function lists()
    {
        $data = $this->input->post();
        if ($data) {
            $this->session->set_userdata('listsSearch', $data);
        } else {
            $data = $this->session->userdata('listsSearch');
        }
        // print_r($data);die;
        list($page, $size) = $this->getPageSizeAndCurrent($data);
        $count             = $this->YgUserModel->counts($data);
        $list              = $this->YgUserModel->lists($data, $page, $size);

        foreach ($list as $k => $v) {
            $obdStatus              = $this->getObdStatus($v['src']);
            $list[$k]['src_status'] = $obdStatus; # 查询是否在线
            if ($obdStatus == self::OBD_ONLINE && $v['tot'] > 0) {
//在线&&行驶
                $list[$k]['use_status'] = '正常使用';
            } elseif ($obdStatus == self::OBD_ONLINE && $v['tot'] == 0) {
//在线&&未行驶
                $list[$k]['use_status'] = '已装设备未开车';
            } elseif ($obdStatus == self::OBD_OFFLINE && $v['tot'] > 0) {
//离线&&行驶
                $list[$k]['use_status'] = '设备拔出或无信号';
            } elseif ($obdStatus == self::OBD_OFFLINE && $v['tot'] == 0) {
//离线&&未行驶
                $list[$k]['use_status'] = '未安装设备';
            } else {
                $list[$k]['use_status'] = '无';
            }
        }
        if (!empty($data)) {
            $query = http_build_query($data); # 构建导出参数
        } else {
            $query = '';
        }
        $this->assign('query', $query);
        $this->assign('search', $data);
        $this->assign('list', $list);
        $this->assign('count', $count);
        $this->display('yg_user/list.tpl');
    }

    /**
     * 导出用户列表
     */
    public function export()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');

        $data    = $this->input->get();
        $page    = 1;
        $size    = 65535; # excel 2003 最大行数
        $list    = $this->YgUserModel->lists($data, $page, $size);
        // echo '<pre>';print_r($list);die;
        $isCheck = [
            1 => '未认证',
            2 => '已认证',
            3 => '认证失败',
        ];
        $filed  = "row_id,account_login,username,sex,birthday,car_card,cardtype,src,src_status,src_created,money,created,is_check,city,tot,use_status,insure_code,comment";
        $header = "序号,账户,姓名,性别,生日,车牌,临/正牌,车型,设备ID,设备状态,激活日期,安装日期,账户余额,注册日期,认证状态,设备城市,是否行驶,使用情况,保单,备注\n";
        //
        $body = '';
        $redis = new Redis();
        $redis->connect('89dbf8b1ee994eab.m.cnsza.kvstore.aliyuncs.com', 6379);
        $redis->auth('89dbf8b1ee994eab:Ns6p5JupCs6htQNtCRZg8t96');
        foreach ($list as $k => $v) {
            $res = $redis->hGetAll('dev_'.$v['src']);
            if (isset($res['heart']) && $res['obd'] == 1){
                $online = intval($res['heart']) >= (time() - 3 * 86400) ? '在线' : '离线';
            } else {
                $online = '离线';
            }
            # $srcStatus = '在线'; # 查询是否在线
            $srcStatus = $online;
            $obdStatus = $online;
            if ($obdStatus == self::OBD_ONLINE && $v['tot'] > 0) {//在线&&行驶
                $userStatus = '正常使用';
            } elseif ($obdStatus == self::OBD_ONLINE && $v['tot'] == 0) {//在线&&未行驶
                $userStatus = '已装设备未开车';
            } elseif ($obdStatus == self::OBD_OFFLINE && $v['tot'] > 0) {//离线&&行驶
                $userStatus = '设备拔出或无信号';
            } elseif ($obdStatus == self::OBD_OFFLINE && $v['tot'] == 0) {//离线&&未行驶
                $userStatus = '未安装设备';
            } else {
                $userStatus = '无';
            }
            $sex   = $v['sex']; # 性别
            $tmp   = []; # 重置临时数组
            $tmp[] = $v['row_id']; # 序号
            $tmp[] = $v['account_login']; # 账户
            $tmp[] = $v['username']; # 姓名
            $tmp[] = $sex; # 性别
            $tmp[] = $v['birthday']; # 生日
            $tmp[] = $v['car_card']; # 车牌
            $tmp[] = $v['cardtype']; # 临/正牌
            $tmp[] = $v['factory'] . $v['demio'] . $v['version']; # 车型
            $tmp[] = $v['src'] . "\t"; # 设备
            $tmp[] = $srcStatus; # 设备状态
            $tmp[] = $v['src_created']; # 激活日期
            $tmp[] = $v['src_install_time']; # 安装日期
            $tmp[] = $v['money']; # 余额
            $tmp[] = $v['created']; # 注册日期
            $tmp[] = !empty($v['is_check']) ? $isCheck[$v['is_check']] : '未认证'; # 认证
            $tmp[] = $v['city']; # 设备城市
            $tmp[] = $v['tot'] > 0 ? '已行驶' : '未行驶'; # 是否行驶
            $tmp[] = $userStatus; # 使用情况
            $tmp[] = $v['insure_code'] . "\t"; # 保单
            $tmp[] = $v['comment']; # 备注
            $body .= join(',', $tmp);
            $body .= "\n";
        }

        $content = $header . $body;
        // echo '<pre>';print_r($content);die;
        $this->load->library("CsvHelper");
        $csv = new CsvHelper();
        $csv->exportCsv('阳光App会员导出_' . date('Y-m-d') . '.csv', $content);
        unset($content); # 销毁内存中变量
    }

    /**
     * 更换设备
     */
    public function setSrc()
    {
        $bindId = $this->post_get('bindId');
        $src    = $this->post_get('src'); //设备ID
        if (!empty($_POST) && $this->isPost()) {
            $oldSrc = $this->post('old_src');
            if (trim($oldSrc) == trim($src)) {
                echo $this->bjuiRes(false, "新设备ID不能和旧设备ID一样", $this->_tabId);
                return;
            }

            $this->load->model('Src_model', 'SrcModel');
            list($succ, $data) = $this->SrcModel->replaceSrc($bindId, $src);
            if ($succ && $data) {
                echo $this->bjuiRes(true, "更换成功", $this->_tabId);
            } else {
                echo $this->bjuiRes(false, $data);
            }
            # 退出
            return;
        } else {
            $this->assign('bindId', $bindId);
            $this->assign('src', $src);
            $this->display('yg_user/set_src.tpl');
        }
    }

    /**
     * 更换设备列表
     */
    public function srcLists()
    {
        $bindId = $this->post_get('bind_id');
        $this->load->model('SrcLog_model', 'SrcLogModel');
        $list = $this->SrcLogModel->srcLog($bindId);

        $this->assign('list', $list);
        $this->display('yg_user/src_log.tpl');
    }

    /**
     * 驾驶行为列表
     */
    public function driveLists()
    {
        $this->load->model('Trip_model', 'TripModel');
        $bindId          = $this->get('bind_id');
        $data            = $this->post();
        $data['bind_id'] = $bindId;

        list($page, $size) = $this->getPageSizeAndCurrent($data);
        $count             = $this->TripModel->counts($data);
        $list              = $this->TripModel->lists($data, $page, $size);

        $this->assign('search', $data);
        $this->assign('bind_id', $bindId);
        $this->assign('list', $list);
        $this->assign('count', $count);
        $this->display('yg_user/drive_list.tpl');
    }

    /**
     * 账户流水
     */
    public function walletTask()
    {

        $this->load->model('WalletTask_model', 'WalletTaskModel');
        $userId         = $this->get('uid'); # 用户Id
        $bindId         = $this->get('bid'); # 车辆Id
        $data           = $this->post();
        $data['pid']    = $userId;
        $data['bindid'] = $bindId;

        list($page, $size) = $this->getPageSizeAndCurrent($data);
        $count             = $this->WalletTaskModel->counts($data);
        $list              = $this->WalletTaskModel->lists($data, $page, $size);
        foreach ($list as $k => $v) {
            $list[$k]['amount']         = $v['amount'] / 100;
            $list[$k]['history_amount'] = $v['history_amount'] / 100;
        }

        $this->assign('uid', $userId);
        $this->assign('bid', $bindId);
        $this->assign('search', $data);
        $this->assign('user_id', $userId);
        $this->assign('list', $list);
        $this->assign('count', $count);
        $this->display('yg_user/wallet_task_list.tpl');
    }

    /**
     * 获取设备在线状态
     * @param $obd
     * @return string
     */
    public function getObdStatus($obd)
    {
        if (empty($obd)) {
            $obdStatus = '无';
        } else {
            $request = Requests::get(self::OBD_STATUS_URL . $obd);
            $obd     = json_decode($request->body, true);
            if (!empty($obd['obd']) && $obd['obd'] == 1) {
                $online = $obd['online'];
            } else {
                $online = 'N';
            }
            $obdStatus = $online == 'Y' ? self::OBD_ONLINE : self::OBD_OFFLINE;
        }
        return $obdStatus;
    }

    /**
     * 用户信息
     */
    public function info()
    {
        $userId  = $this->post_get('pid');
        $carInfo = $this->YgUserModel->getUserCar($userId);
        if (empty($carInfo)) {
            $carInfo = ['models' => '', 'car_card' => '', 'vin' => '', 'enginecode' => '', 'debutdate' => '', 'src' => '', 'bindid' => ''];
        }
        $carInfo['src_status'] = $this->getObdStatus($carInfo['src']);

        $baseInfo = $this->YgUserModel->getUserRow($userId);

        $this->load->model('Bank_model', 'BankModel');
        $baseInfo['bank'] = $this->BankModel->getUserBank($userId);

        $this->assign('baseInfo', $baseInfo);
        $this->assign('carInfo', $carInfo);
        $this->display('yg_user/info.tpl');

    }

    /**
     * 备注修改
     */
    public function comment()
    {
        $id = $this->post_get('id');
        if (!empty($_POST) && $this->isPost()) {
            $comment = trim($this->input->post('comment'));
            if (empty($comment)) {
                echo $this->bjuiRes(false, '请输入备注内容');
                return;
            }
            $data = [
                'comment' => strip_tags($comment),
            ];

            $where = ['id' => $id];
            $this->YgUserModel->update($data, $where);
            echo $this->bjuiRes(true, '修改成功', $this->_tabId);

            # 退出
            return;
        } else {
            $where   = ['id' => $id];
            $info    = $this->YgUserModel->getRow($where, 'comment');
            $comment = !empty($info) ? $info['comment'] : '';
            $this->assign('id', $id);
            $this->assign('comment', $comment);
            $this->display('yg_user/comment.tpl');
        }

    }

}
