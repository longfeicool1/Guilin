<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 系统用户组模型
 * @author  liuweilong
 * +2016-03-16
 */
class Group_model extends MY_Model
{
    protected $_dbName = 'default';

    protected $_table = 'pm_group';

    protected $_fields = array(
                'id',
                'name',
                'is_mobile'
    );

    public function lists($whereData, $pageCurrent, $pageSize)
    {
        return $this->getList($whereData, $this->_fields, $orderBy = 'id DESC', $groupBy = '', ($pageCurrent - 1) * $pageSize, $pageSize);
    }

    public function counts($whereData)
    {
        return $this->getCount($whereData);
    }

    public function getIsMobile($groupIds)
    {
        if (empty($groupIds))
        {
            return false;
        }
        $sql = "SELECT is_mobile FROM pm_group WHERE id IN($groupIds)";
        $data = $this->db->query($sql)->result_array();
        $isMobile = DataExecuter::KeyToArray($data, 'is_mobile');
        if (in_array(2, $isMobile))
        {
            return true;
        }
        return false;
    }
}
