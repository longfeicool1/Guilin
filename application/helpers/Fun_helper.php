<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//打印函数
function D($array){
	echo '<pre>';
	print_r($array);
	echo '</pre>';
	die;
}
