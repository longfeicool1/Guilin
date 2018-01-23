<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 系统用户模型
 * @author  liuweilong
 * +2016-03-11
 */
class Account_model extends MY_Model
{
    protected $_dbName = 'default';

    protected $_table = 'pm_account';

    protected $_fields = array(
                'id',
                'username',
                'password',
                'mobile',
                'email',
                'real_name',
                'departments',
                'login_num',
                'last_login_time',
                'remark',
                'enable',
                'update_password_num',
                'last_update_time',
                'exten',
                'queue',


    );

     /**
     * 查询条件
     * @var array 查询条件
     */
    protected $_listsConfig = array(
        'username' => array('%'),
        'real_name' => array('%'), // = % > < >= <= cs
        'enable' => array('!-1'),
        'departments' => array('!-1'),
    );

    public $_orderBy = "FIELD(`enable`,1,0,2),departments DESC ";
    // public function lists($whereData, $pageCurrent, $pageSize)
    // {
    //     return $this->getList($whereData, $this->_fields, $orderBy = 'id DESC', $groupBy = '', ($pageCurrent - 1) * $pageSize, $pageSize);
    // }

    // public function counts($whereData)
    // {
    //     return $this->getCount($whereData);
    // }
    // 

}
