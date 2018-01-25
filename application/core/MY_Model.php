<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 基类
 *
 */
class MY_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->userinfo = $this->session->userdata('account');
    }
}
