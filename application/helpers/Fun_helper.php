<?php
defined('BASEPATH') OR exit('No direct script access allowed');
    //打印函数
    function D($array){
    	echo '<pre>';
    	print_r($array);
    	echo '</pre>';
    	die;
    }

    /*
    * 判断权限（$id 操作时需要的权限ID）
    */
    function checkAuth($id){
        $auth = $_SESSION['account']['menu_id'];
        if(in_array($id,$auth)){
            return true;
        }else{
            return false;
        }
    }
