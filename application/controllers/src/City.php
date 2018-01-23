<?php

/**
 * Created by PhpStorm.
 * User: Yijw
 * Date: 2017-12-22
 * Time: 16:52
 */
class City extends MY_Controller
{
    public $_tabId = '';
    public function __construct()
    {
        parent::__construct();

        $this->load->model('srcCity_model', 'SrcCityModel');
        $this->_tabId  = ENVIRONMENT == 'development' ? 'id302' : 'id336';
    }

    public function lists()
    {
        $data = $this->post();
        $query = http_build_query($data);

        list($pageCurrent, $pageSize) = $this->getPageSizeAndCurrent($data);

        $count = $this->SrcCityModel->counts($data);
        $list = $this->SrcCityModel->lists($data, $pageCurrent, $pageSize);

        $startNum = ($pageCurrent -1) * $pageSize;
        foreach($list as $k =>$v) {
            $list[$k]['xu'] = $startNum + $k + 1;
        }

        $this->assign('query', $query);
        $this->assign('search', $data);
        $this->assign('count' ,$count);
        $this->assign('list' ,$list);
        $this->display('src_city/list.tpl');
    }

    /**
     * 添加或者修改
     */
    public function edit()
    {

        if ($this->isPost()) {
            $id = intval($this->post('id'));
            $data = [
                'src' => $this->post('src'),
                'provice' => $this->post('province'),
                'city' => $this->post('city'),
                'is_test' => $this->post('is_test')
            ];

            if ($id) { # 更新
                $otherSrc = $this->SrcCityModel->otherSrcInfo($this->post('src'), $id);
                if ($otherSrc) {
                    echo $this->bjuiRes(false, '设备ID号已存在，请核对设备号信息~', $this->_tabId);
                    return;
                }
                $this->SrcCityModel->update($data, 'id = ' . $id);
            } else { # 添加
                $otherSrc = $this->SrcCityModel->srcInfo($this->post('src'));
                if ($otherSrc) {
                    echo $this->bjuiRes(false, '设备ID号已存在，请核对设备号信息~', $this->_tabId);
                    return;
                }
                $this->SrcCityModel->add($data);
            }
            echo $this->bjuiRes(true, '保存成功', $this->_tabId);
            return;

        } else {
            $info = ['id' => '', 'src'=> '', 'province' => '', 'city' => '', 'is_test' => 2];
            $id = $this->get('id');
            if ($id) {
                $info = $this->SrcCityModel->info($id);
            }
        }

        $this->assign('info', $info);
        $this->display('src_city/edit.tpl');

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
        $list = $this->SrcCityModel->lists($data, $page, $size);

        $filed = "src,org_id,org_name,province,city,account_login,username,account_type,active_time,status_abbr,remark,cTime";
        $header = "设备ID号,设备类型,设备机构,省份,城市,用户手机号,用户名称,用户类别,激活时间,使用状态,测试设备,备注,添加时间\n";

        $body = '';
        foreach ($list as $k => $v) {
            # $srcStatus = '在线'; # 查询是否在线
//            $srcStatus = $obdStatus = $this->getObdStatus($v['src']);
//            $sex = $v['sex']; # 性别

            $tmp = []; # 重置临时数组
            $tmp[] = $v['src'];                 # 设备ID号
            $tmp[] = $v['org_id'] == 60 ? 'U驾宝设备' : '非U驾宝设备';              # 设备类型
            $tmp[] = $v['org_name'];            # 机构类型
            $tmp[] = $v['province'];            # 省份
            $tmp[] = $v['city'];                # 城市
            $tmp[] = $v['account_login'];       # 用户手机号
            $tmp[] = $v['username'];            # 用户名称
            $tmp[] = !empty($v['account_type']) ? ($v['account_type'] == 4 ? '阳光用户' : '非阳光用户') : '';        # 用户类别
            $tmp[] = $v['active_time'];         # 激活时间
            $tmp[] = $v['status_abbr'];         # 使用状态
            $tmp[] = $v['is_test'] == 2 ? '不是' : '是' ; # 备注
            $tmp[] = $v['remark'];              # 备注
            $tmp[] = $v['cTime'];               # 添加时间
            $body .= join(',', $tmp);
            $body .=  "\n";
        }

        $content = $header . $body;
        $this->load->library("CsvHelper");
        $csv = new CsvHelper();
        $csv->exportCsv('城市设备导出_' . date('Y-m-d') . '.csv', $content);
        unset($content); # 销毁内存中变量
    }

}