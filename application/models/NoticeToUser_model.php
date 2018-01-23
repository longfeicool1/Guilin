<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/6
 * Time: 9:31
 */
class NoticeToUser_model extends MY_Model
{
    public $_table = 'pm_notice_to_user_';
    public $_fields = [
        'id',
        'user_id',
        'notice_id',
        'read_time',
        'created',
    ];

    /**
     * 设置表全称
     * @param $userId
     * @return string
     */
    public function getAllName($userId)
    {
        $id = $userId % 10;
        return $this->_table . $id;
    }

    /**
     * 通过消息Id获取用户Id
     * @param $noticeId
     * @return mixed
     */
    public function getUserId($noticeId)
    {
        $sql = "select user_id from pm_notice_to_user_0 where notice_id = {$noticeId} union all 
                        select user_id from pm_notice_to_user_1 where notice_id = {$noticeId} union all 
                        select user_id from pm_notice_to_user_2 where notice_id = {$noticeId} union all 
                        select user_id from pm_notice_to_user_3 where notice_id = {$noticeId} union all 
                        select user_id from pm_notice_to_user_4 where notice_id = {$noticeId} union all 
                        select user_id from pm_notice_to_user_5 where notice_id = {$noticeId} union all 
                        select user_id from pm_notice_to_user_6 where notice_id = {$noticeId} union all 
                        select user_id from pm_notice_to_user_7 where notice_id = {$noticeId} union all 
                        select user_id from pm_notice_to_user_8 where notice_id = {$noticeId} union all 
                        select user_id from pm_notice_to_user_9 where notice_id = {$noticeId}";
        $userList = $this->db->query($sql)->result_array();
        return $userList;
    }

}