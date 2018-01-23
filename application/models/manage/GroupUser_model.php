<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 系统用户组模型
 * @author  liuweilong
 * +2016-03-16
 */
class GroupUser_model extends MY_Model
{
    protected $_dbName = 'default';

    protected $_table = 'pm_group_user';

    protected $_fields = array(
                'id',
                'group_id',
                'account_id',
    );
}
