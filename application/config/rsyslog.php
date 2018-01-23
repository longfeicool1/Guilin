<?php
/**
 * rsys日志配置
 */
defined('BASEPATH') OR exit('No direct script access allowed');
$config = array();
$config['port'] = 514;
if (ENVIRONMENT == 'development')
{
    /*
     * log config
     */
    $config['host'] = '120.24.99.41';
    // 配置格式 BLOG_站点简写_对应业务  => 简写名称
    $config['relationship'] = array(
        'BLOG_MANAGERTEST_SQL' => 'M1',
    );

}
else
{
    $config['host'] = '10.169.103.99';
    $config['relationship'] = array(
        'BLOG_MANAGER_SQL' => 'M1',
    );
}

return $config;