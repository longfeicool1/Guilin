<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * [csrf_hidden description]
 * @return [type] [description]
 */
function csrf_hidden()
{
    $ci = &get_instance();
    $name = $ci->security->get_csrf_token_name();
    $val = $ci->security->get_csrf_hash();
    echo "<input id=\"t_token\" type=\"hidden\" name=\"$name\" value=\"$val\" />";
}