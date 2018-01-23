<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Table extends MY_Controller
{
    public $title = [
        '设备激活数',
        '设备安装数',
    ];

    public $date = [
        '在线量',
        '在线总量',
        '在线率',
    ];

    public function __construct()
    {
        parent::__construct();
        // 引入model
        $this->load->model('Census_model', 'censusModel');
        $this->load->model('Common_model', 'commonModel');
    }

    public function workTable()
    {
        $rs        = $this->getData($this->input->get());
        $xAxisData = $rs['xAxisData'];
        $data1     = $rs['data1'];
        $data2     = $rs['data2'];
        // print_r($data1);die;
        $result    = [
            'tooltip' => [
                'trigger' => 'axis',
            ],
            'legend' => [
                'data' => $this->title,
            ],
            'toolbox' => [
                'show'    => true,
                'feature' => [
                    'mark'     => [
                        'show' => true
                    ],
                    'dataView' => [
                        'show'     => true,
                        'readOnly' => true
                    ],
                    'magicType' => [
                        'show' => true,
                        'type' => ['line','bar']
                    ],
                    'restore' => [
                        'show' => true
                    ],
                    'saveAsImage' => [
                        'show' => true
                    ],
                ],
            ],
            'calculable' => true,
            'xAxis' => [
                'type' => 'category',
                'data' => $xAxisData,
            ],
            'yAxis' => [
                'type' => 'value',
            ],
            'dataZoom' => [
                'type'       => 'slider',
                'start'      => 70,
                'end'        => 100,
                'xAxisIndex' => 0,
                // 'zoomLock'   => true,
            ],
            'series' => [
                [
                    'name' => $this->title[0],
                    'type' => 'bar',
                    'data' => $data1,
                ],
                [
                    'name' => $this->title[1],
                    'type' => 'bar',
                    'data' => $data2,
                ],
            ],
        ];
        $this->commonModel->output($result);
    }

    public function getData($data)
    {
        $condition = [];
        if (!empty($data['bt'])) {
            $condition['collect_date >= '] = $data['bt'];
        }
        if (!empty($data['et'])) {
            $condition['collect_date <= '] = $data['et'];
        }
        $list      = $this->censusModel->installList(1,999,$condition);
        $xAxisData = array_column($list,'collect_date');
        $data1     = array_column($list,'active_num');
        $data2     = array_column($list,'work_num');
        foreach ($xAxisData as $k => $v) {
            $xAxisData[$k] = date('n-d',strtotime($v));
        }
        return [
            'xAxisData' => array_reverse($xAxisData),
            'data1'     => array_reverse($data1),
            'data2'     => array_reverse($data2),
        ];
    }

    public function onlineTable()
    {
        $rs        = $this->getOnlineDate($this->input->get());
        $xAxisData = $rs['xAxisData'];
        $data1     = $rs['online'];
        $data2     = $rs['total'];
        $data3     = $rs['percent'];
        // print_r($data1);die;
        $result    = [
            'tooltip' => [
                'trigger' => 'axis',
            ],
            'legend' => [
                'data' => $this->date,
            ],
            'toolbox' => [
                'show'    => true,
                'feature' => [
                    'mark'     => [
                        'show' => true
                    ],
                    'dataView' => [
                        'show'     => true,
                        'readOnly' => true
                    ],
                    'magicType' => [
                        'show' => true,
                        'type' => ['line','bar']
                    ],
                    'restore' => [
                        'show' => true
                    ],
                    'saveAsImage' => [
                        'show' => true
                    ],
                ],
            ],
            'calculable' => true,
            'xAxis' => [
                'type' => 'category',
                'data' => $xAxisData,
            ],
            'yAxis' => [
                [
                    'type' => 'value',
                    'name' => '设备数(台)',
                    // 'min'      => 0,
                    // 'max'      => 3000,
                    // 'interval' => 500,
                ],
                [
                    'type'     => 'value',
                    'name'     => '在线率(%)',
                    'min'      => 0,
                    'max'      => 100,
                    'interval' => 10,
                ],
            ],
            'dataZoom' => [
                'type'       => 'slider',
                'start'      => 70,
                'end'        => 100,
                'xAxisIndex' => 0
            ],
            'series' => [
                [
                    'name' => $this->date[0],
                    'type' => 'bar',
                    'data' => $data1,
                ],
                [
                    'name' => $this->date[1],
                    'type' => 'bar',
                    'data' => $data2,
                ],
                [
                    'name'       => $this->date[2],
                    'type'       => 'line',
                    'yAxisIndex' => 1,
                    'data'       => $data3,
                ],
            ],
        ];
        $this->commonModel->output($result);
    }


    public function getOnlineDate($data)
    {
        $condition = [];
        if (!empty($data['bt'])) {
            $condition['collect_date >= '] = $data['bt'];
        }
        if (!empty($data['et'])) {
            $condition['collect_date <= '] = $data['et'];
        }
        if ($data['type'] == 3) {
            $list      = $this->censusModel->threeOnlineList(1,999,$condition);
        } else {
            $list      = $this->censusModel->onlineList(1,999,$condition);
        }
        $xAxisData = array_column($list,'total_time');
        $data1     = array_column($list,'percent');
        $data2     = array_column($list,'online');
        $data3     = array_column($list,'total_obd');
        foreach ($xAxisData as $k => $v) {
            $xAxisData[$k] = date('n-d',strtotime($v));
        }
        return [
            'xAxisData' => array_reverse($xAxisData),
            'percent'   => array_reverse($data1),
            'online'    => array_reverse($data2),
            'total'     => array_reverse($data3),
        ];
    }

}