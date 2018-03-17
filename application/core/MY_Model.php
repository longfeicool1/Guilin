<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 基类
 *
 */
class MY_Model extends CI_Model
{
    public $uids = [];
    public $sameLevel = [];
    public $userinfo;

    public function __construct()
    {
        parent::__construct();
        $this->userinfo = $this->session->userdata('account');
    }

    public function rules()
    {
        if ($this->userinfo['role_id'] != 1) { //超级管理员
            $uids   = [];
            $sql    = "SELECT uid FROM md_user WHERE FIND_IN_SET(?,path)";
            $result = $this->db->query($sql,[$this->userinfo['uid']])->result_array();
            if (!empty($result)) {
                $uids   = array_column($result,'uid');
            }
            $uids[] = $this->userinfo['uid'];
            // D($uids);
            return $this->uids = $uids;
        }
    }

    public function sameLevel()
    {
        if ($this->userinfo['role_id'] != 1) { //超级管理员
            $uids   = [];
            $sql    = "SELECT uid FROM md_user WHERE city LIKE '%{$this->userinfo['city']}%' AND position >= 3";
            $result = $this->db->query($sql)->result_array();
            if (!empty($result)) {
                $uids   = array_column($result,'uid');
            }
            // $uids[] = $this->userinfo['uid'];
            // D($uids);
            return $this->sameLevel = $uids;
        }
    }
}
