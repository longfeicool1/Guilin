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
        'managersql' => 'BLOG_MANAGERTEST_SQL2',
		'manager' => 'BLOG_MANAGERTEST',
		'www' => 'BLOG_WWWTEST',
		'ac' => 'BLOG_ACTEST',
		'user' => 'BLOG_USERTEST',
		'm' => 'BLOG_MTEST',
        'api' => 'BLOG_APITEST',
		'other' => 'BLOG_OTHERTEST',
    );

}
else
{
    $config['host'] = '10.169.103.99';
    $config['relationship'] = array(
		'managersql' => 'BLOG_MANAGER_SQL',
		'manager' => 'BLOG_MANAGER',
		'www' => 'BLOG_WWW',
		'ac' => 'BLOG_AC',
		'user' => 'BLOG_USER',
		'm' => 'BLOG_M',
        'api' => 'BLOG_API',
		'other' => 'BLOG_OTHER',
    );
}