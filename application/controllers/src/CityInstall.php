<?php

/**
 * Created by PhpStorm.
 * User: Yijw
 * Date: 2017-12-22
 * Time: 16:52
 */
class CityInstall extends MY_Controller
{
    public $_tabId = '';
    public function __construct()
    {
        parent::__construct();

        $this->load->model('srcInstall_model', 'SrcInstallModel');
        $this->_tabId  = ENVIRONMENT == 'development' ? 'id302' : 'id336';
    }

    public function lists()
    {
        $data = $this->post();
        $query = http_build_query($data);

        list($pageCurrent, $pageSize) = $this->getPageSizeAndCurrent($data);

        $count = $this->SrcInstallModel->counts($data);
        $list = $this->SrcInstallModel->lists($data, $pageCurrent, $pageSize);

        $startNum = ($pageCurrent -1) * $pageSize;
        foreach($list as $k =>$v) {
            $list[$k]['xu'] = $startNum + $k + 1;
        }

        $this->assign('query', $query);
        $this->assign('search', $data);
        $this->assign('count' ,$count);
        $this->assign('list' ,$list);
        $this->display('src_install/list.tpl');
    }

    /**
     * 导出设备信息
     */
    public function export()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');

        $data = $this->input->get();
        $page = 1;
        $size = 65535; # excel 2003 最大行数
        $list = $this->SrcInstallModel->lists($data, $page, $size);

        $filed = "src,org_id,org_name,province,city,account_login,username,account_type,active_time,status_abbr,remark,cTime";
        $header = "设备ID号,设备类型,设备机构,激活时间,安装时间,用户手机号,用户名称,用户类别,备注\n";

        $body = '';
        foreach ($list as $k => $v) {
            # $srcStatus = '在线'; # 查询是否在线
//            $srcStatus = $obdStatus = $this->getObdStatus($v['src']);
//            $sex = $v['sex']; # 性别

            $tmp = []; # 重置临时数组
            $tmp[] = $v['src'];                 # 设备ID号
            $tmp[] = $v['org_id'] == 60 ? 'U驾宝设备' : '非U驾宝设备';              # 设备类型
            $tmp[] = $v['org_name'];            # 机构类型
            $tmp[] = $v['active_time'];       # 激活时间
            $tmp[] = $v['install_time'];       # 安装时间
            $tmp[] = $v['account_login'];       # 用户手机号
            $tmp[] = $v['username'];            # 用户名称
            $tmp[] = !empty($v['account_type']) ? ($v['account_type'] == 4 ? '阳光用户' : '非阳光用户') : '';        # 用户类别
            $tmp[] = $v['remark'];              # 备注
            $body .= join(',', $tmp);
            $body .=  "\n";
        }

        $content = $header . $body;
        $this->load->library("CsvHelper");
        $csv = new CsvHelper();
        $csv->exportCsv('安装设备导出_' . date('Y-m-d') . '.csv', $content);
        unset($content); # 销毁内存中变量
    }

}