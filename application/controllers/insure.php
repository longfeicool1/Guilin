<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 路比大使保单
 * @author  Loonfiy
 * +2016-03-07
 */

class Phone extends MY_Controller
{
    public function __CONSTRUCT()
    {
        parent::__CONSTRUCT();
        error_reporting(E_ALL ^ E_NOTICE);
    }

    /*
     * 保单列表
     */
    public function insureList()
    {

    }

    /*
     * 提示模板
     * $status   错误状态码
     * $msg     提示信息
     * $tabid   需要刷新的tab页
     * $close   是否关闭当前标签页
     */
    private function ajaxReturn($status, $msg, $tabid = '', $close = 1, $selfData = array())
    {
        $back['closeCurrent'] = $close;
        $back['statusCode'] = $status;
        $back['message'] = $msg;
        $back['tabid'] = $tabid;
        $back['result'] = $selfData;
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($back));
    }

    //前台分页排序的一些通用操作
    private function createPage($data, $where = '', $limit = 30, $qz = '', $defaultOrder = 'id')
    {
        if (is_array($where)) {
            $condition = $qz . 'id > 0';
            foreach ($where as $k => $v) {
                if (is_array($v[0])) {
                    foreach ($v as $vv) {
                        switch ($vv[0]) {
                            case 'gt':
                                $condition .= " and `{$k}` > '{$vv[1]}'";
                                break;
                            case 'egt':
                                $condition .= " and `{$k}` >= '{$vv[1]}'";
                                break;
                            case 'lt':
                                $condition .= " and `{$k}` < '{$vv[1]}'";
                                break;
                            case 'elt':
                                $condition .= " and `{$k}` <= '{$vv[1]}'";
                                break;
                        }
                    }
                } else {
                    switch ($v[0]) {
                        case 'like' :
                            $condition .= " and `{$k}` like '%{$v[1]}%'";
                            break;
                        case 'in' :
                            $condition .= " and `{$k}` in ({$v[1]})";
                            break;
                        case 'gt':
                            $condition .= " and `{$k}` > '{$v[1]}'";
                            break;
                        case 'egt':
                            $condition .= " and `{$k}` >= '{$v[1]}'";
                            break;
                        case 'lt':
                            $condition .= " and `{$k}` < '{$v[1]}'";
                            break;
                        case 'elt':
                            $condition .= " and `{$k}` <= '{$v[1]}'";
                            break;
                        default :
                            $condition .= " and `{$k}` {$v[0]} '{$v[1]}'";
                    }
                }

            }
        } else {
            $condition = $where;
        }
        $orderField = $data['orderField'] ? $data['orderField'] : $defaultOrder;
        $orderDirection = $data['orderDirection'] ? $data['orderDirection'] : 'desc';
        $this->where = $condition;
        $this->limit = $data['pageSize'] ? $data['pageSize'] : $limit;
        //每页显示条数
        $this->page = $data['pageCurrent'] ? $data['pageCurrent'] : 1;
        //当前页码
        if (in_array($orderDirection, array('desc', 'asc'))) {
            $this->orderBy = "{$orderField} {$orderDirection}";
        } else {
            $this->orderBy = 'id desc';
        }
        //排序字段
    }
}