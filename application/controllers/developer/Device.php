<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 设备在线状态控制器
 * @author  liuweilong
 * +2016-03-04
 */
class Device extends MY_Controller
{   
    protected $_tabid = 'id86';

    protected $_prefix = 'dev_';

    /**
     * 首页
     * @return html
     */
    public function index()
    {
        $this->load->library('FormHelper');
        $this->load->Model("default/PmOrgModel", "PmOrgModel");
        $this->load->Model("dcComm/DcObdIdModel", "DcObdIdModel");

        

        if ($this->isPost())
        {
            $this->load->driver('cache');
            $obdid = str_replace(' ', '', $this->input->get_post('obdid'));

            if (!empty($obdid))
            {
                $request = Requests::get('http://api.ubi001.com/v1/device?obd_id='.$obdid);
                $res = json_decode($request->body, true);
                $con = '';
                if (!empty($res))
                {
                    $success = true;
                    $con = ("<p>上一次心跳: ".date('Y-m-d H:i:s', $res['heart'])." </p><p>OBD: ".($res['obd'] ? '插入' : '没有插上').
                    " </p><p>点火:".($res['launch'] ? '已点' : '未点')." </p><p>OBD上传时间:".date('Y-m-d H:i:s', $res['obddata'])).'</p>';
                }
                else
                {
               
                    $success = false;
                }

                $con2 = '';
                $res = $this->DcObdIdModel->getRow(array('obd_id' => $obdid));
                if (!empty($res))
                {
                    $res2 = $this->PmOrgModel->getRow(array('id' => $res['org_id']));
                    if (empty($res2))
                    {
                        $res2 = array('name' => '');
                    }
                    $con2 = "<p>激活时间: {$res['activatedate']} </p>
                             <p>所在机构: {$res2['name']}</p>
                             <p>生产日期: {$res['makedate']}</p>
                             <p>生产商: {$res['creater']}</p>
                             <p>型号: {$res['model']}</p>";
                }
                // echo "<script>\$('#jsstatus').html('{$con}');</script>";
                // echo "<script>\$('#jsstatus2').html('{$con2}');</script>";
                echo json_encode(array('con' => $con, 'con2' => $con2, 'obdid' => $obdid));
            }
            //echo $this->bjuiRes($success, $success ? '查询成功' : "没有找到数据", $this->_tabid, false);
            // return;
        }
        else
        {
            $fh = new FormHelper;
            list($formHeaderConfig, $formBodyConfig) = $this->_formConfigAddProvider($fh);
            echo $fh->formCreater($formHeaderConfig, $formBodyConfig)->render('Table');
        }
    }

        /**
     * 表单配置供给
     * @return array
     */
    protected function _formConfigAddProvider($fh)
    {
        $formHeaderConfig = array('method' => 'post', 'onsubmit' => 'return false;');

        $formBodyConfig = array(
            array('type' => 'block', 'key' => 'display', 'content' => '<tr><td align="right">当前查询设备ID:</td><td><div id="sobd"></div></td></tr>','label' => '设备状态', 'data' => null ,'defaultValue' => '', 'order' => 1, 'attr' => array('data-rule' => 'require;')),

            array('type' => 'block', 'key' => 'display', 'content' => '<tr><td align="right">设备状态:</td><td><div id="jsstatus"></div></td></tr>','label' => '设备状态', 'data' => null ,'defaultValue' => '', 'order' => 11, 'attr' => array('data-rule' => 'require;')),
            array('type' => 'block', 'key' => 'display', 'content' => '<tr><td align="right">库存信息:</td><td><div id="jsstatus2"></div></td></tr>','label' => '设备状态', 'data' => null ,'defaultValue' => '', 'order' => 13, 'attr' => array('data-rule' => 'require;')),
            array('type' => 'text', 'key' => 'obdid', 'label' => '设备ID', 'data' => null ,'defaultValue' => '', 'order' => 21, 'attr' => array('data-rule' => 'require;', 'id' => 'obdid')),
            // array('type' => 'block', 'key' => 'display', 'label' => '', 'content' => '<a href="javascript:void(0)" class="btn btn-blue">查询</a>', 'order' => 999),
            // 
            array('type' => 'block', 'key' => 'display', 'content' => '<tr><td align="right"><a href="javascript:void(0)" id="js-btn-query" class="btn btn-blue">查询</a></td><td></td></tr>
            <script>
            $("#obdid").bind("keypress", function(event){
                if(event.keyCode == "13")    
                {
                    $("#js-btn-query").click();
                }
            });

            $("#js-btn-query").bind("click", function() {
                $.ajax({
                    method: "POST",
                    data: {obdid: $("#obdid").val().replace(/[\r\n]/g,"")},
                    url: "/developer/device",
                    dataType: "json",
                    success: function(data) {
                        $("#jsstatus").html(data["con"]);
                        $("#jsstatus2").html(data["con2"]);
                        $("#sobd").text($("#obdid").val());
                        $("#obdid").val("");
                        $("#obdid").focus();
                    }
                });
            });
            $("#obdid").focus();
            </script>
            ','label' => '设备状态', 'data' => null ,'defaultValue' => '', 'order' => 55, 'attr' => array('data-rule' => 'require;')),
        );

        if (isset($_POST) && !empty($_POST))
        {
            $formBodyConfig = $fh->mergeData($formBodyConfig, $_POST);
        }
        return array($formHeaderConfig, $formBodyConfig);
    }
}