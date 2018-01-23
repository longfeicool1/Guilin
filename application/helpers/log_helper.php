<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * [logger description]
 * @param  
 * @return [type] [description]
 * @example logger('M1', ip, uid, sql)
 */
// function logger()
// {
//     $numargs = func_num_args();
//     if ($numargs < 2) 
//     {
//         return false;
//     }
//     $argList = func_get_args();
//     $logMessage = implode(' ', $argList);
//     get_instance()->load->library('LogSender');
//     $send = new LogSender;
//     get_instance()->config->load('rsyslog', true);
//     $rsyslog = get_instance()->config->item('rsyslog');
//     $send->setRelationship($rsyslog['relationship']);
//     $send->setHost($rsyslog['host'], $rsyslog['port']);
//     $argList[0] = $send->getCmd($argList[0]);
//     if ($argList[0] === false)
//     {
//         return false;
//     }

//     foreach ($argList as $key => $val) 
//     {
//         $argList[$key] = empty($val) ? '---' : str_replace(array("\r", "\r\n", "\n"), '', $val);
//     }
//     $logMessage = implode(' ', $argList);
//     $send->send($logMessage);
// }
