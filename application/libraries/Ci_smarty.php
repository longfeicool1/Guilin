<?php

class Ci_smarty extends Smarty{
    public function __construct(){
        parent::__construct();

        $this->template_dir= APPPATH . "/views/";  //指定模版存放目录
        $this->compile_dir= APPPATH . "/cache/templates_c/"; //指定编译文件存放目录
        $this->cache_dir= APPPATH . "/cache/"; //指定缓存存放目录
        $this->caching=false; //关闭缓存（设置为true表示启用缓存）
        $this->use_sub_dirs = true;
        $this->left_delimiter="{{";
        $this->right_delimiter="}}";
//        $this->compile_check = true;
//        $this->force_compile = false;
//        $this->debugging = false;
    }

}
