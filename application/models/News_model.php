<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News_model extends MY_Model {
    public function __CONSTRUCT()
    {
        parent::__CONSTRUCT();
    }

        //前台分页排序的一些通用操作
    public function createPage($data, $where = '', $limit = 30,$qz = '')
    {
        if (is_array($where)) {
            $condition = $qz . 'id > 0';
            foreach ($where as $k => $v) {
                if(is_array($v[0])){
                    foreach($v as $vv){
                        switch($vv[0]){
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
                }else{
                    switch($v[0]) {
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
        $res = new stdClass();
        $orderField = $data['orderField'] ? $data['orderField'] : 'id';
        $orderDirection = $data['orderDirection'] ? $data['orderDirection'] : 'desc';
        $res->where = $condition;
        $res->limit = $data['pageSize'] ? $data['pageSize'] : $limit;
        //每页显示条数
        $res->page = $data['pageCurrent'] ? $data['pageCurrent'] : 1;
        //当前页码
        $res->orderBy = "{$orderField} {$orderDirection}";
        //排序字段
        return $res;
    }

    public function getInfoById($id)
    {
        $sql = "SELECT * FROM pm_news WHERE id='{$id}' LIMIT 1";
        return $this->db->query($sql)->row_array();
    }

    /**
     * 修改内容
     * @param  integer $id 绑定ID
     * @param  array $data 修改内容
     * @return integer
     */
    public function updateById($id, $data)
    {
        if (!isset($id) || !isset($data)) {
            return false;
        }
        $this->db->where(array('id' => $id));
        return $this->db->update('pm_news', $data);
    }

    public function insertNews($data)
    {
        return $this->db->insert('pm_news', $data);
    }


    /*
     * 获取反馈
     */
    public function getFeedback($condition = '',$page = 1,$limit,$orderby = 'addtime desc')
    {

        $page = $page < 0 ? ($page - 1) * $page : 0;
        $sql = "select a.*,b.username,b.loginname from pm_feedbacks a";
        $sql .= " left join pub_passport_info b on a.user_id = b.pid";
        if($condition){
            $sql .= " where {$condition}";
        }
        $sql .= " order by {$orderby}";
        $sql .= " limit {$page},{$limit}";
        $res = $this->db->query($sql)->result_array();
        return $res ? $res : null;
    }
}