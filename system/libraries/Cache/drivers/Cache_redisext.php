<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__.DIRECTORY_SEPARATOR.'Cache_redis.php';

/**
 * 扩展redis cache
 *
 * @author	   liuweilong
 * @link
 */
class CI_Cache_redisext extends CI_Cache_redis
{
    public function __call($method, $args = Array())
    {
        if (method_exists($this->_redis, $method))
        {
            return call_user_func_array(array($this->_redis, $method), $args);
        }
        else
        {
            throw new BadMethodCallException('No such method: '.$method.'()');
        }
    }
}
