<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 基类
 *
 */
class MY_Model extends CI_Model
{

    /**
     * 表名称
     * @var tring
     */
    protected $_table = '';

    /**
     * 库名
     * @var string
     */
    protected $_dbName = '';

    /**
     * 默认方法里面的排序
     * @var string
     */
    protected $_orderBy = '';

    /**
     * 构造
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 更改当前表名称
     * @param $table
     * @return null
     */
    protected function setTable($table, $db = '')
    {
        $this->_table = $table;
        if ($db) {
            $this->_dbName = $db;
        }

        $this->db = $this->load->database($this->_dbName, true);
    }

    /**
     * 取表名
     * @return string
     */
    public function getTable()
    {
        return $this->_table;
    }

    /**
     * 获取单条记录信息
     *
     * @return mixed
     */
    public function getRow($where, $fileds = '*', $orderBy = '', $groupBy = '', $offset = 0, $limit = 1)
    {
        //        if(empty($where)){
        //            return null;
        //        }
        $this->db->select($fileds);
        if (!empty($orderBy)) {
            $this->db->order_by($orderBy);
        }
        if (!empty($groupBy)) {
            $this->db->group_by($groupBy);
        }
        if (!empty($offset) && !empty($limit)) {
            $this->db->limit($limit, $offset);
        } elseif (!empty($limit)) {
            $this->db->limit($limit);
        }
        $query = $this->db->get_where($this->_table, $where);
        if ($query) {
            $data = $query->row_array();
        }
        if (empty($data)) {
            return array();
        }
        return $data;
    }

    /**
     * 添加记录信息
     *
     * @return mixed
     */
    public function add(array $data = array())
    {
        if (empty($data) || !is_array($data)) {
            return false;
        }
        return $this->db->insert($this->_table, $data);
    }

    /**
     * 批量添加记录信息
     *
     * @return mixed
     */
    public function addBatch(array $data = array())
    {
        if (empty($data) || !is_array($data)) {
            return false;
        }
        return $this->db->insert_batch($this->_table, $data);
    }

    /**
     * 更新记录信息
     *
     * @return mixed
     */
    public function edit($where, array $data)
    {
        if (empty($where)) {
            return false;
        }
        $this->db->where($where);
        return $this->db->update($this->_table, $data);
    }

    /**
     * 获取更新记录信息
     *
     * @return mixed
     */
    public function getAffectedRows()
    {
        return $this->db->affected_rows();
    }

    /**
     * 更新记录递增信息
     *
     * @return mixed
     */
    public function setEdit($where, array $data)
    {
        if (empty($where) || empty($data)) {
            return false;
        }
        $this->db->where($where);
        foreach ($data as $k => $v) {
            $this->db->set($k, $v, false);
        }
        return $this->db->update($this->_table);
    }

    /**
     * 查询列表
     * @param  [type]  $condition [description]
     * @param  string  $fileds    [description]
     * @param  string  $orderBy   [description]
     * @param  string  $groupBy   [description]
     * @param  integer $offset    [description]
     * @param  integer $limit     [description]
     * @return [type]             [description]
     */
    public function getList($condition, $fileds = '*', $orderBy = '', $groupBy = '', $offset = 0, $limit = 1000000)
    {
        if (!empty($condition)) {
            $this->db->where($condition);
        }

        if (empty($fields) || $fileds == '*') {
            $fields = $this->_fields;
        }

        $this->db->select($fileds);
        if (!empty($orderBy)) {
            $this->db->order_by($orderBy);
        }

        if (!empty($groupBy)) {
            $this->db->group_by($groupBy);
        }

        if (!empty($offset) && !empty($limit)) {
            $this->db->limit($limit, $offset);
        } elseif (!empty($limit)) {
            $this->db->limit($limit);
        }

        $query = $this->db->get_where($this->_table);
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
        }

        if (empty($data)) {
            return array();
        }
        return $data;
    }

    public function delete($where)
    {
        if (empty($where)) {
            return false;
        }
        //$this->db->where($where);
        return $this->db->delete($this->_table, $where);
    }

    /**
     * @param string | array $where
     * @return int
     */
    public function getCount($where = '', $groupBy = '')
    {
        $this->db->from($this->_table);
        if ($where) {
            $this->db->where($where);
        }
        if ($groupBy) {
            $this->db->where($groupBy);
        }
        return $this->db->count_all_results();
    }

    public function insert($data)
    {
        $this->db->insert($this->_table, $data);
        return $this->db->insert_id();
    }

    public function insertBatch($data)
    {
        $this->db->insert_batch($this->_table, $data);
        return $this->db->insert_id();
    }

    public function update($data, $where)
    {
        return $this->db->update($this->_table, $data, $where);
    }

    public function updateBatch($data, $key)
    {
        return $this->db->update_batch($this->_table, $data, $key);
    }

    public function autoSet()
    {
        $query      = $this->db->query('desc ' . $this->_table);
        $table_desc = $query->result_array();
        $fields     = array();
        foreach ($table_desc as $v) {
            $fields[] = $v['Field'];
        }
        $post_keys   = array_keys($_POST);
        $enable_keys = array_intersect($post_keys, $fields);
        $data        = array();
        foreach ($enable_keys as $v) {
            $data[$v] = $this->input->post($v);
        }
        $data = $this->cook_data($data);
        if (!empty($data)) {
            $this->db->set($data);
            $this->db->insert($this->_table);
        }
        return true;
    }

    public function autoUpdate($where)
    {
        $query      = $this->db->query('desc ' . $this->_table);
        $table_desc = $query->result_array();
        $fields     = array();
        foreach ($table_desc as $v) {
            $field[] = $v['Field'];
        }
        $post_keys   = array_keys($_POST);
        $enable_keys = array_intersect($post_keys, $fields);
        $data        = array();
        foreach ($enable_keys as $v) {
            $data[$v] = $this->input->post($v);
        }
        $data = $this->cook_data($data);
        if (!empty($data)) {
            $this->db->where($where);
            $this->db->update($this->_table, $data);
        }
        return true;
    }

    /*
     * 自动写入之前处理数据
     */
    protected function cookData($data)
    {
        return $data;
    }

    /**
     * 计算sql limit
     * @author liuweilong
     * @param  integer $page     [description]
     * @param  integer $pageSize [description]
     * @return string
     */
    protected function _limit($page, $pageSize)
    {
        list($page, $pageSize) = array(intval($page), intval($pageSize));
        return (($page - 1) * $pageSize) . ',' . $pageSize;
    }

    /**
     * 返回列表
     * @param  array $whereData
     * @param  integer $page
     * @param  integer $pageSize
     * @return array
     */
    public function listsn($whereData, $page, $pageSize, $orderBy = '', $groupBy = '', $fileds = '*')
    {
        list($offset, $limit) = explode(',', $this->_limit($page, $pageSize));
        return $this->getList($whereData, $fileds, $orderBy, $groupBy, $offset, $limit);
    }

    /**
     * 查询条件
     * @var array 查询条件
     */
    protected $_listsConfig = array(
        '字段' => array('=', 'field_name', 'custom callback'), // = % > < >= <= cs
    );

    /**
     * 查询条件设定
     * @param array 查询条件
     */
    public function setListConfig($config = array())
    {
        $this->_listsConfig = $config;
    }

    /**
     * 返回列表
     * @param  array $whereData
     * @param  integer $page
     * @param  integer $pageSize
     * @return array
     */
    public function lists($whereData, $page, $pageSize)
    {
        list($offset, $limit) = explode(',', $this->_limit($page, $pageSize));
        // print_r($whereData);
        // exit;
        if (!empty($whereData) && is_array($whereData)) {

            if (isset($whereData['orderField'])
                && in_array($whereData['orderField'], $this->_fields)
                && isset($whereData['orderDirection'])
                && in_array(strtolower($whereData['orderDirection']), array('asc', 'desc'))) {
                $this->_orderBy = $whereData['orderField'] . ' ' . strtoupper($whereData['orderDirection']);

            }

            $temp = array();
            foreach ($whereData as $k => $v) {
                $v = trim($v);

                if (empty($v)) {
                    // echo '#1:'.$k.' ';
                    continue;
                }

                $field = isset($this->_listsConfig[$k]) && isset($this->_listsConfig[$k][1]) ? $this->_listsConfig[$k][1] : $k;

                // echo $field;
                // print_r(array_merge($this->_fields, array_keys($this->_listsConfig)));
                if (!in_array($field, array_merge($this->_fields, array_keys($this->_listsConfig)))) {
                    // echo '#2:' . $field . '-' . $k . ' ' . var_export($this->_fields, true);
                    continue;
                }
                // print_r($this->_listsConfig[$k]);
                if (isset($this->_listsConfig[$k])) {
                    switch ($this->_listsConfig[$k][0]) {
                        case '%':
                            $temp[] = "{$field} LIKE '%{$v}%'";
                            break;

                        case '%l':
                            $temp[] = "{$field} LIKE '{$v}%'";
                            break;

                        case '!0':
                            if (0 != $v) {
                                $temp[] = "{$field} = '{$v}'";
                            }

                            break;
                        case '!-2':
                            if (-2 != $v) {
                                $temp[] = "{$field} = '{$v}'";
                            }

                            break;
                        case '!-1':
                            if (-1 != $v) {
                                $temp[] = "{$field} = '{$v}'";
                            }
                            break;
                        case '?':
                            $temp[] = str_replace('?', $v, $this->_listsConfig[$k][2]);
                            break;

                        case 'cs':
                            // print_r($this->_listsConfig[$k][2]);
                            // $temp[] = call_user_func($this->_listsConfig[$k][2], array($v));
                            // $temp[] = $this->_listsConfig[$k][2]($v);
                            break;

                        default:
                            $temp[] = "{$field} {$this->_listsConfig[$k][0]} '{$v}'";
                            break;
                    }
                } else {
                    $temp[] = "{$field} = '{$v}'";
                }
            }
            $whereData = join(' AND ', $temp);
//             echo $whereData; exit;
        }
        return $this->getList($whereData, $fileds = '*', $this->_orderBy, $groupBy = '', $offset, $limit);
    }

    /**
     * 返回数据量
     * @param  array $whereData
     * @return integer
     */
    public function counts($whereData)
    {
        if (!empty($whereData) && is_array($whereData)) {
            if (!empty($whereData) && is_array($whereData)) {
                $temp = array();
                foreach ($whereData as $k => $v) {
                    $v = trim($v);

                    if (empty($v)) {
                        // echo '#1:'.$k.' ';
                        continue;
                    }

                    $field = isset($this->_listsConfig[$k]) && isset($this->_listsConfig[$k][1]) ? $this->_listsConfig[$k][1] : $k;

                    if (!in_array($field, $this->_fields)) {
                        // echo '#2:'.$field.'-'.$k.' '.var_export($this->_fields, true);
                        continue;
                    }

                    if (isset($this->_listsConfig[$k])) {
                        switch ($this->_listsConfig[$k][0]) {
                            case '%':
                                $temp[] = "{$field} LIKE '%{$v}%'";
                                break;

                            case '%l':
                                $temp[] = "{$field} LIKE '{$v}%'";
                                break;

                            case '!0':
                                if (0 != $v) {
                                    $temp[] = "{$field} = '{$v}'";
                                }

                                break;
                            case '!-2':
                                if (-2 != $v) {
                                    $temp[] = "{$field} = '{$v}'";
                                }

                                break;
                            case '!-1':
                                if (-1 != $v) {
                                    $temp[] = "{$field} = '{$v}'";
                                }
                                break;
                            case '?':
                                $temp[] = str_replace('?', $v, $this->_listsConfig[$k][2]);
                                break;

                            case 'cs':
                                $temp[] = call_user_func($this->_listsConfig[$k][2], array($v));
                                break;

                            default:
                                $temp[] = "{$field} {$this->_listsConfig[$k][0]} '{$v}'";
                                break;
                        }
                    } else {
                        $temp[] = "{$field} = '{$v}'";
                    }
                }
                $whereData = join(' AND ', $temp);
                // echo $whereData;
            }
        }
        return $this->getCount($whereData);
    }

    public function request($uri, $parameter, $type = 'user')
    {
        switch ($type) {
            // case 'user':
            //     try {
            //         log_message('debug', 'request url:' . USER_SERVER . $uri);
            //         $request = Requests::post(USER_SERVER . $uri, array(), $parameter);
            //         if ($request->status_code == 200) {
            //             log_message('debug', 'request body json:' . print_r($request->body, true));
            //             $result = json_decode($request->body);
            //             if (isset($result) && isset($result->errcode)) {
            //                 if ($result->errcode == 10002 ||        // token 过期
            //                     $result->errcode == 10003           // token 错误
            //                 ) {
            //                     get_instance()->session->unset_userdata('uid');
            //                     get_instance()->session->unset_userdata('token');
            //                     get_instance()->session->unset_userdata('loginname');
            //                     get_instance()->session->unset_userdata('username');
            //                     redirect('/login');
            //                 }
            //             }
            //             return json_decode($request->body);
            //         } else {
            //             log_message('error', 'request status_code:' . $request->status_code);
            //         }
            //     } catch (Exception $e) {
            //         log_message('error', 'request exception:' . $e->getMessage());
            //     }
            //     break;
            case 'post':
                try {
                    $request = Requests::post($uri, array(), $parameter);
                    if ($request->status_code == 200) {
                        log_message('debug', 'request body json:' . print_r($request->body, true));
                        return $request->body;
                    } else {
                        log_message('error', 'request status_code:' . $request->status_code);
                    }
                } catch (Exception $e) {
                    log_message('error', 'request exception:' . $e->getMessage());
                }
                return null;
            default: //get请求
                try {
                    $real_uri = OBD_SERVER . $uri;
                    if (isset($parameter) && count($parameter) > 0) {
                        $real_uri .= '?' . http_build_query($parameter);
                    }
                    log_message('debug', 'AWS request url:' . $real_uri);
                    $request = Requests::get($real_uri);
                    if ($request->status_code == 200) {
                        log_message('debug', 'AWS request body json:' . print_r($request->body, true));
                        return json_decode($request->body);
                    } else {
                        log_message('error', 'AWS request status_code:' . $request->status_code);
                    }
                } catch (Exception $e) {
                    log_message('error', 'AWS request exception:' . $e->getMessage());
                }
                return null;
        }
        return null;
    }
}
