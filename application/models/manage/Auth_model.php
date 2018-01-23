<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 系统权限模型
 * @author  liuweilong
 * +2016-03-16
 */
class Auth_model extends MY_Model
{
    protected $_dbName = 'default';

    protected $_table = 'pm_auth';

    protected $_fields = array(
                'id',
                'group_id',
                'menu_id',
                'auth',
                'created',
    );
}
