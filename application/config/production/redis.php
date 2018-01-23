<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * redis config
 */
$config['socket_type'] = 'tcp'; //`tcp` or `unix`
$config['socket'] = '/var/run/redis.sock'; // in case of `unix` socket type
$config['host'] = '89dbf8b1ee994eab.m.cnsza.kvstore.aliyuncs.com';
$config['password'] = '89dbf8b1ee994eab:Ns6p5JupCs6htQNtCRZg8t96';
$config['port'] = 6379;
$config['timeout'] = 0;
