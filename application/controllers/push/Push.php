<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 话务系统
 * @author  Loonfiy
 * +2016-07-26
 */

class Push extends MY_Controller
{
    public function __CONSTRUCT()
    {
        parent::__CONSTRUCT();
        error_reporting(E_ALL ^ E_NOTICE);
        $this->load->driver('cache');
    }

    /*
     * 配置页面
     */
    public function config()
    {
        $res = $this->db->get('pm_push_config')->result_array();
        $res = array_map(function ($v){
            $v['defaultUid'] = $v['defaultUid'] ? explode(',', $v['defaultUid']) :array();
            return $v;
        }, $res);
        //后台所有用户
        $user = $this->db->select('id,username,real_name')->get_where('pm_account',array('enable' => 1))->result_array();
        $this->ci_smarty->assign('user',$user);
        $this->ci_smarty->assign('config',$res);
        $this->ci_smarty->display('push/config.tpl');
    }

    /*
     * 更新配置
     */
    public function updateConfig()
    {
        $data = $this->input->post();
        $pushId = $data['pushId'];
        unset($data['pushId']);
        if(is_array($data['defaultUid'])){
            foreach($data['defaultUid'] as $k => $v){
                if(!$v){
                    unset($data['defaultUid'][$k]);
                }
            }
            $data['defaultUid'] = implode(',', $data['defaultUid']);
        }
        $data['updateTime'] = date('Y-m-d H:i:s');
        if($this->db->update('pm_push_config',$data,array('pushId' => $pushId)) !== false){
            //清除redis缓存
            $this->cache->redis->delete('pushId_' . $pushId);
            $this->output_json('ok');
        }
        $this->output_json('error');
    }

    /**
     * 推送
     */
    public function push()
    {
        //
        $pushId = $this->input->post_get('pushId');
        $msg = $this->input->post_get('msg');
        $config = $this->rule($pushId);
//      print_r($config);die;
        if($msg){
            $config['msg'] = $msg;
        }
        if($config['toUid'] == 'close') return;
        $result = $this->startPush($config);
        if($result == 'ok'){
            $this->output_json(array('errcode' => 0,'errmsg' => $result));
        }
        $this->output_json(array('errcode' => 30002,'errmsg' => 'Failed!'));
    }

    /*
     * 推送规则
     * @param toUid 指定推送到某个用户，为空时发送给所有的用户
     * @param pushType 发送到指定组的用户
     * @param msg 通知内容
     * NetEnquiry为网销报价页面通知
     */
    private function rule($pushId)
    {
        $defaultConfig = array(
            'toUid' => '',
            'pushGroup' => '',
            'msg' => 'Failed!',
        );
        $getConfig = $this->cache->redis->get('pushId_' . $pushId);
        if($getConfig){
            $res = json_decode($getConfig);
        }else{
            $res = $this->db->get_where('pm_push_config',array('pushId' => $pushId))->first_row();
            if($res){
                $this->cache->redis->save('pushId_' . $pushId, json_encode($res), 7200);
            }
        }
        if($res){
            if($res->pushType == 2){
                $defaultConfig['toUid'] = $res->defaultUid;
            }
            if($res->pushType == 3){
                $defaultConfig['toUid'] = 'close';
            }
            $defaultConfig['pushGroup'] = $res->pushGroup;
            $defaultConfig['msg'] = $res->defaultMsg;
        }
        return $defaultConfig;
    }

    /**
     * 推送方法
     * @param $to_uid 指明给谁推送，为空表示向所有在线用户推送
     */
    private function startPush($data)
    {
        // 推送的url地址，上线时改成自己的服务器地址
//      $push_api_url = "http://www.admin.com:2121/";
        $push_api_url = "http://112.74.95.105:2121/";
        $content = array(
            'pushGroup' => $data['pushGroup'],
            'msg' => $data['msg']
        );
        $uid = explode(',', $data['toUid']);
//      print_r($uid);die;
        foreach($uid as $v){
            $post_data = array(
                'type' => 'publish',
                'to' => 'admin_' . $v,
                'content' => $content
            );
            Requests::post($push_api_url, array(), $post_data);
        }
        return TRUE;
    }
}