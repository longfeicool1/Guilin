<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 用户模型
 * @author  loonfiy
 * +2016-03-16
 */
class MemberModel extends MY_Model
{
    public $sex = [
        1 => '男',
        2 => '女',
    ];
    public $payType = [
        1 => '无',
        2 => '现金发薪',
        3 => '银行转账',
        4 => '代发工资',

    ];
    public $socialSecurity = [
        1 => '无',
        2 => '有',
    ];
    public $reservedFunds = [
        1 => '无',
        2 => '有',
    ];
    public $haveHouse = [
        1 => '无',
        2 => '有',
    ];
    public $haveCar = [
        1 => '无',
        2 => '有',
    ];
    public $haveInsure = [
        1 => '无',
        2 => '有'
    ];
    public $customLevel = [
        1 => '--请选择--',
        2 => '0星',
        3 => '1星',
        4 => '2星',
        5 => '3星',
        6 => '4星',
    ];
    public $customStatus = [
        1 => '无',
        2 => '待跟进',
        3 => '已上门',
        4 => '资质不符',
        5 => '已签约',
        6 => '待签约',
        7 => '已放款',
        8 => '已拒绝',
        9 => '新数据',
    ];

    public $callType = [
        1 => '无',
        2 => '已接通',
        3 => '未接',
        4 => '秒挂',
        5 => '通话中',
        6 => '关机',
        7 => '空号',
        8 => '拒接',
        9 => '其他',
    ];

    public $isBackMoney = [
        1 => '未退',
        2 => '已退',
    ];

    public $orderStaus = [
        1 => '待审核',
        2 => '在审中',
        3 => '已拒款',
        4 => '客户已拒签',
        5 => '未进件',
        6 => '已收款',
    ];

    public $uids = [];

    public function getValue($type)
    {
        return !empty($this->$type) ? $this->$type : [];
    }

    public function rules()
    {
        if ($this->userinfo['role_id'] != 1) { //超级管理员
            $sql    = "SELECT uid FROM md_user WHERE FIND_IN_SET(?,path)";
            $result = $this->db->query($sql,[$this->userinfo['uid']])->result_array();
            $uids   = array_column($result,'uid');
            $uids[] = $this->userinfo['uid'];
            return $this->uids = $uids;
        }
    }

    public function getUser()
    {
        $this->rules();
        if (!empty($this->uids)) {
            $this->db->where_in('uid',$this->uids);
        }
        $result = $this->db->get_where('md_user',['position' => 5])->result_array();
        return $result;
    }

    public function getMemberList($page,$size,$condition)
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        if(empty($this->uids)){
            $this->rules();
        }
        if(!empty($this->uids)){
            $this->db->where_in('firstOwer',$this->uids);
        }
        // print_r($this->uids);die;
        $offset = ($page - 1) * $size;
        $result = $this->db
            ->select("a.*,IFNULL(b.name,'未分配') AS firstName")
            ->join('md_user b','a.firstOwer = b.uid','left')
            ->limit($size,$offset)
            ->order_by('meetTime DESC,a.updated DESC,a.created DESC')
            ->get('md_custom_list a')
            ->result_array();
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            $result[$k]['xuhao']           = $n;
            $result[$k]['sex']             = $this->sex[$v['sex']];
            $result[$k]['payType']         = $this->payType[$v['payType']];
            $result[$k]['socialSecurity']  = $this->socialSecurity[$v['socialSecurity']];
            $result[$k]['reservedFunds']   = $this->reservedFunds[$v['reservedFunds']];
            $result[$k]['haveHouse']       = $this->haveHouse[$v['haveHouse']];
            $result[$k]['haveCar']         = $this->haveCar[$v['haveCar']];
            $result[$k]['customLevel']     = $v['customLevel'] == 1 ? '新数据' : $this->customLevel[$v['customLevel']];
            $result[$k]['meetTime']        = $v['meetTime'] == '0000-00-00 00:00:00' ? '未预约' : $v['meetTime'];
            $result[$k]['customStatus']    = $this->customStatus[$v['customStatus']];
        }
        return $result;
    }

    public function getMemberCount($condition)
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        if(empty($this->uids)){
            $this->rules();
        }
        if(!empty($this->uids)){
            $this->db->where_in('firstOwer',$this->uids);
        }
        $count = $this->db->count_all_results('md_custom_list a');
        return $count;
    }

    public function getMemberInfo($id)
    {
        $result = $this->db
            ->select("a.*,IFNULL(b.name,'未分配') AS firstName")
            ->join('md_user b','a.firstOwer = b.uid','left')
            ->get_where('md_custom_list a',['a.id' => $id])->row_array();
        $result['meetTime'] = $result['meetTime'] == '0000-00-00 00:00:00' ? '' : $result['meetTime'];
        return $result;
    }

    public function toUpdateInfo($id,$data)
    {
        foreach ($data as $k => $v) {
            if (empty($v)) {
                unset($data[$k]);
            }
        }
        if (!empty($data['content'])) {
            $content = $data['content'];unset($data['content']);
            $this->db->insert('md_comment',[
                'uid'     => $this->userinfo['uid'],
                'cid'     => $id,
                'content' => $content,
            ]);
        }
        if ($this->db->update('md_custom_list',$data,['id' => $id]) !== false) {
            return ['errcode' => 200, 'errmsg' => '更新成功'];
        }
        return ['errcode' => 300, 'errmsg' => '更新失败'];
    }

    public function getCommentList($cid)
    {
        $result = $this->db
            ->select('a.content,a.created,b.name')
            ->join('md_user b','a.uid = b.uid','left')
            ->order_by('a.created desc')
            ->get_where('md_comment a',['cid' => $cid])
            ->result_array();
        return $result;
    }

    public function toDelMember($ids)
    {
        // print_r($ids);die;
        if ($this->db->where_in('id',explode(',',$ids))->update('md_custom_list',['isShow' => 2])) {
            return ['errcode' => 200, 'errmsg' => '删除成功'];
        }
        return ['errcode' => 300, 'errmsg' => '删除失败'];
    }

    public function toBackData($ids)
    {
        $reuslt = $this->db
            ->where_in('id',explode(',',$ids))
            ->update('md_custom_list',[
                'isShow'    => 1,
                'firstOwer' => 0,
                'meetTime'  => 0,
            ]);
        if ($reuslt) {
            return ['errcode' => 200, 'errmsg' => '恢复成功'];
        }
        return ['errcode' => 300, 'errmsg' => '恢复失败'];
    }

    public function toCreateOrder($data)
    {
        if ($this->db->insert('md_check_order',$data)) {
            return ['errcode' => 200, 'errmsg' => '创建成功'];
        }
        return ['errcode' => 300, 'errmsg' => '创建失败'];
    }

    public function getOrderList($page,$size,$condition = [])
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        if(empty($this->uids)){
            $this->rules();
        }
        if(!empty($this->uids)){
            $this->db->where_in('uid',$this->uids);
        }
        $offset = ($page - 1) * $size;
        $result = $this->db
            ->select("a.*,b.name as firstName")
            ->join('md_user b','a.uid = b.uid','left')
            ->limit($size,$offset)
            ->order_by('a.created DESC')
            ->get('md_check_order a')
            ->result_array();
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            $result[$k]['xuhao']      = $n;
            $result[$k]['orderStatus'] = $this->orderStaus[$v['status']];
            $result[$k]['isBackMoney'] = $this->isBackMoney[$v['isBackMoney']];
            $result[$k]['sendTime'] = $v['sendTime'] == '0000-00-00 00:00:00' ? '暂无' : $v['sendTime'];
        }
        return $result;
    }

    public function getOrderCount($condition)
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        if(empty($this->uids)){
            $this->rules();
        }
        if(!empty($this->uids)){
            $this->db->where_in('uid',$this->uids);
        }
        $count = $this->db->count_all_results('md_check_order a');
        return $count;
    }

    public function getOrderInfo($id)
    {
        $result = $this->db
            ->select("a.*,b.name as firstName")
            ->join('md_user b','a.uid = b.uid','left')
            ->get_where('md_check_order a',['a.id' => $id])
            ->row_array();
        return $result;
    }

    public function editOrder()
    {
        if ($this->db->update('md_check_order',['status' => $status],['id' => $id])) {
            return ['errcode' => 200, 'errmsg' => '编辑成功'];
        }
        return ['errcode' => 300, 'errmsg' => '编辑失败'];
    }

    public function updateOrder($id,$status)
    {
        if ($this->db->update('md_check_order',['status' => $status],['id' => $id])) {
            return ['errcode' => 200, 'errmsg' => '审核成功'];
        }
        return ['errcode' => 300, 'errmsg' => '审核失败'];
    }
}