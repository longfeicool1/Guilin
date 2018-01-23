<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 系统菜单模型
 * @author  liuweilong
 * +2016-03-16
 */
class Menu_model extends MY_Model
{
    protected $_dbName = 'default';

    protected $_table = 'pm_menu';

    protected $_fields = array(
                'id',
                'pid',
                'title',
                'type',
                'url',
                'sorted',
                'account_id',
                'created',
                'visable',
    );
}
