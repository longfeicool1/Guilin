<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 财务审核
 * @author  liuweilong
 * +2017-03-22
 */
class Auditing extends MY_Controller
{
    public function index()
    {
        $this->load->library('FormHelper');
        $this->load->library('DataTable');
        $this->load->library('BjuiPager');
        $this->load->Model("UserApply_model");
        $fh       = new FormHelper;
        $dt       = new DataTable();
        $postData = isset($_POST) && !empty($_POST) ? $_POST : array();

        list($formHeaderConfig, $formBodyConfig) = $this->_formSearchConfigProvider($fh);
        list($pageCurrent, $pageSize)            = array((int) isset($postData['pageCurrent']) && !empty($postData['pageCurrent']) ? $postData['pageCurrent'] : 1,
            (int) isset($postData['pageSize']) && !empty($postData['pageSize']) ? $postData['pageSize'] : 30);

        $formSearch = $fh->formCreater($formHeaderConfig, $formBodyConfig)->render('FormSearch',
            array('topButton' => '<p><a href="/user/apply/export" target="_blank" class="btn btn-green"><i class="fa fa-table"></i>导出Csv</a></p>')
        );
        $data  = $this->UserApply_model->lists($postData, $pageCurrent, $pageSize);
        $total = $this->UserApply_model->counts($postData);
        // print_r($data);exit;
        $data = $dt->setAttr(array('class' => "table table-striped table-bordered table-hover nowrap"))
            ->setHeader($this->_dataTableConfigProvider())
            ->setData($data)
            ->setTopContent($formSearch . '<div class="bjui-pageContent tableContent">')
            ->setBottomContent('</div>' . BjuiPager::get($pageCurrent, $total, $pageSize))
            ->setRowCounter($pageCurrent, $pageSize)
            ->render();
        echo $data;
    }

    /**
     * 表单查询配置供给
     * @return array
     */
    protected function _formSearchConfigProvider(FormHelper $fh)
    {
        $formHeaderConfig = array('id' => 'pagerForm', 'data-toggle' => 'ajaxsearch', 'action' => '/wallet/', 'method' => 'post');
        $formBodyConfig   = array(
            array('type' => 'text', 'key' => 'mobile', 'label' => '手机号码', 'data' => null, 'defaultValue' => '', 'order' => 11, 'attr' => array('style' => "width:95px")),
            array('type' => 'text', 'key' => 'obd_id', 'label' => '设备ID', 'data' => null, 'defaultValue' => '', 'order' => 31, 'attr' => array('style' => "width:95px")),
            array('type' => 'text', 'key' => 'carcard', 'label' => '车牌号', 'data' => null, 'defaultValue' => '', 'order' => 41, 'attr' => array('style' => "width:95px")),
            array('type' => 'select', 'key' => 'revisit', 'label' => '回访结果', 'data' => Channel::$revisit, 'defaultValue' => '请选择', 'order' => 51, 'attr' => array('style' => "width:95px")),
            array('type' => 'date', 'key' => 'startDate', 'label' => '开始时间', 'data' => null, 'defaultValue' => '', 'order' => 71, 'attr' => array('style' => "width:120px")),
            array('type' => 'date', 'key' => 'endDate', 'label' => '截止时间', 'data' => null, 'defaultValue' => '', 'order' => 81, 'attr' => array('style' => "width:120px")),
            array('type' => 'button', 'key' => 'submit', 'label' => '<i class="fa fa-search"></i>搜索', 'order' => 999, 'attr' => array('class' => 'btn btn-default')),
        );

        if (isset($_POST) && !empty($_POST)) {
            $formBodyConfig = $fh->mergeData($formBodyConfig, $_POST);
        }
        return array($formHeaderConfig, $formBodyConfig);
    }

    /**
     * 表格配置供给
     * @return array
     */
    protected function _dataTableConfigProvider()
    {
        return array(
            'rowCounter'          => array(),
            'checkboxs'           => array('field' => 'id'),
            'channel_name'        => array('name' => '序号'),
            'obd_id'              => array('name' => '订单号'),
            'carcard'             => array('name' => '提现账户'),
            'model_name'          => array('name' => '提现平台'),
            'status'              => array('name' => '车辆'),
            'applicant'           => array('name' => '提现金额'),
            'mobile'              => array('name' => '申请时间'),
            'area'                => array('name' => '状态'),
            'table_button_action' => array('name' => '操作', 'export' => false, 'callback' => function ($row, $rowData, $field) {
                $id = $rowData['id'];
                return ' <a href="/user/channel/edit?id=' . $id . '" class="btn btn-default" data-toggle="dialog" data-width="800" data-height="400" data-id="dialog">编辑</a>
                <a href="javascript:#" onclick="freeCall(' . $rowData['mobile'] . ',0)" class="btn btn-default" data-toggle="dialog" data-width="800" data-height="400" data-id="dialog">呼叫</a>

             ';
            },
            ),
        );
    }
    /**
     * 数据导出
     * @return csv
     */
    public function export()
    {
        $this->load->library("CsvHelper");

        $csv  = new CsvHelper;
        $data = $csv->setCsvData(array(), $this->PmUserChannelModel, $this->_dataTableConfigProvider(), 'indexCounts', 'indexLists');
        $csv->exportCsv('鼎然信息待回访用户列表导出_' . date('Y-m-d') . '.csv', $data);
    }
}
