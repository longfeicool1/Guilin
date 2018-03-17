<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 自动任务控制器 -1 -1
 * @author  liuweilong
 * +2016-03-15
 */
class Service extends CI_Controller
{
    protected $_logger;

    public function __construct()
    {
        parent::__construct();

        if (!is_cli())
        {
         // die("Not allowed to run!");
        }

        // if (class_exists('CI_LogSender'))
        // {
        //     $this->_logger = new CI_LogSender;
        //     $this->_logger->init();
        // }
    }

    public function logs($level, $module, $msg = '')
    {
        // if (class_exists('CI_LogSender'))
        // {
        //     $this->_logger->writer('BLOG_CRONTAB_MANAGER '.$module.' '.$level.' '.$msg);
        // }
        // else
        // {
            log_message($level, $module.' '.$msg);
        // }
    }
}