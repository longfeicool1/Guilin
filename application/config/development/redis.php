<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * redis config
 */
$config['socket_type'] = 'tcp'; //`tcp` or `unix`
$config['socket'] = '/var/run/redis.sock'; // in case of `unix` socket type
$config['host'] = '120.24.99.41';
$config['password'] = '';
$config['port'] = 6379;
$config['timeout'] = 0;
