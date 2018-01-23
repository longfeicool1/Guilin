<?php
defined('BASEPATH') OR exit('No direct script access allowed');
set_time_limit(0);

/**
 * 安装报告
 */
class CityReport extends MY_Controller
{
    const OBD_STATUS_URL = 'http://api.ubi001.com/v1/device?obd_id=';
    const OBD_ONLINE = '在线';
    const OBD_OFFLINE = '离线';

    public function __construct()
    {
        parent::__construct();
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);

        define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

        $this->load->model('SrcCityDay_model', 'CityDayModel');
        $this->load->model('SrcCityMonth_model', 'CityMonthModel');
        $this->load->model('SrcCityYear_model', 'CityYearModel');
        $this->load->model('SrcCityOnline_model', 'SrcCityOnlineModel');
        $this->load->model('srcCity_model', 'SrcCityModel');
    }

    /**
     * 安装使用情况
     */
    public function installUse()
    {
        $srcList = [];
        $isCheck = [
            1 => '未认证',
            2 => '已认证',
            3 => '认证失败',
        ];
        # 直接redis中取值
        $redis = new Redis();
        $redis->connect('89dbf8b1ee994eab.m.cnsza.kvstore.aliyuncs.com', 6379);
        $redis->auth('89dbf8b1ee994eab:Ns6p5JupCs6htQNtCRZg8t96');

        $title = "截至" . date("Y年m月d日") . "各地区设备安装使用情况";
        $tableDate = array(
            [$title],
            ['地区', '', '共激活设备', '未安装设备', '已安装设备', '设备拨出/无信号', '正常使用', '离线', '在线', '已认证', '在线率', '未安装率'],
            ['省', '市'],
        );

        $objPHPExcel = new PHPExcel();
        $objWorksheet = $objPHPExcel->getActiveSheet();

        $cityList = $this->CityDayModel->cityList();
        $total = [
            'province' => '',
            'total' => '总计',
            'active' => 0,
            'noInstall' => 0,
            'installNum' => 0,
            'pullOutNum' => 0,
            'validNum' => 0,
            'offlineNum' => 0,
            'onlineNum' => 0,
            'enterNum' => 0,
            'onlineRate' => 0,
            'noInstallRate' => 0,
        ];

        # 所有总计
        $allTotal = [
            'province' => '总计',
            'total' => '',
            'active' => 0,
            'noInstall' => 0,
            'installNum' => 0,
            'pullOutNum' => 0,
            'validNum' => 0,
            'offlineNum' => 0,
            'onlineNum' => 0,
            'enterNum' => 0,
            'onlineRate' => 0,
            'noInstallRate' => 0,
        ];

//        print_r($cityList);exit;
//        echo count($cityList);exit;
        $tmpSwitch = '';
        $indexLength = count($cityList) - 1;
        $tmpNumEnd = 4;
        foreach ($cityList as $k => $v) {
            if ($k == 0) {
                $tmpNum = 4;
                $tmpSwitch = $v['province'];
                $tmpProvince = $v['province'];
            } else {
                $tmpProvince = $tmpSwitch != $v['province'] ? $v['province'] : '';
            }

            # 换省
            if ($tmpSwitch != $v['province']) {
                $tmpSwitch = $v['province'];
                if (empty($total['noInstall'])) {
                    $total['noInstall'] = '0';
                }

                # 总计
                $total['onlineRate'] = $total['installNum'] && !empty($total['onlineNum']) ? round($total['onlineNum'] / $total['installNum'], 4) : '0.00';
                $total['noInstallRate'] = $total['installNum'] && !empty($total['noInstall']) ? round($total['noInstall'] / $total['installNum'], 4) : '0.00';
//                $total = $this->formatTotal($total);
                array_push($tableDate, $total);

                # 省合并（结束为止不确定）和居中显示
                $objWorksheet->mergeCells("A" . $tmpNum . ":A" . $tmpNumEnd);
                $objWorksheet->getStyle('A' . $tmpNum)->getAlignment()
                    ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $tmpNumEnd += 1;

                # 合并归位
                $tmpNum = $tmpNumEnd;
                $total = [
                    'province' => '',
                    'total' => '总计',
                    'active' => 0,
                    'noInstall' => 0,
                    'installNum' => 0,
                    'pullOutNum' => 0,
                    'validNum' => 0,
                    'offlineNum' => 0,
                    'onlineNum' => 0,
                    'enterNum' => 0,
                    'onlineRate' => 0,
                    'noInstallRate' => 0,
                ];
//                print_r($v);exit;
            }
            $tmpNumEnd += 1;

            $pullOutNum = 0; #拔出统计(设备离线有行程)
            $onlineNum = 0;
            $offlineNum = 0;
            $validNum = 0; # 正常使用的设备：激活中+行程的
            $citySrc = $this->SrcCityModel->CitySrc($v['province'], $v['city']);
            foreach ($citySrc as $sk => $sv) {
                # 直接读取redis库数据
                $res = $redis->hGetAll('dev_' . $sv['src']);
                $res['online'] = isset($res['heart']) ? (intval($res['heart']) >= (time() - 3 * 86400) ? 'Y' : 'N') : 'N';

                # 在线离线统计
                if ($res['online'] == 'Y') { # 在线
                    $onlineNum += 1;
                } else { # 离线
                    $offlineNum += 1;
                }

                # 拔出统计(设备离线有行程)
                if ($res['online'] != 'Y' && $sv['actual_num'] > 0) {
                    $pullOutNum += 1;
                }

                if ($res['online'] == 'Y' && $sv['actual_num'] > 0) {
                    $validNum += 1;
                }

                if ($res['online'] == 'Y' && $sv['actual_num'] > 0) {//在线&&行驶
                    $useStatus = '正常使用';
                } elseif ($res['online'] == 'Y' && $sv['actual_num'] == 0) {//在线&&未行驶
                    $useStatus = '已装设备未开车';
                } elseif ($res['online'] != 'Y' && $sv['actual_num'] > 0) {//离线&&行驶
                    $useStatus = '设备拔出或无信号';
                } elseif ($res['online'] != 'Y' && $sv['actual_num'] == 0) {//离线&&未行驶
                    $useStatus = '未安装设备';
                } else {
                    $useStatus = '无';
                }

                $srcList[] = [
                    'loginName' => $sv['loginName'],
                    'username' => $sv['username'],
                    'sex' => $sv['sex'],
                    'birthday' => $sv['birthday'],
                    'carNumber' => $sv['carNumber'],
                    'carStatus' => $sv['carStatus'],
                    'src' => $sv['src'],
                    'srcStatus' => $res['online'] == 'Y' ? '在线' : '离线',
                    'activeTime' => $sv['activeTime'],
                    'installTime' => $sv['installTime'],
                    'money' => $sv['money'],
                    'regDate' => $sv['regDate'],
                    'enterStatus' => !empty($sv['is_check']) ? $isCheck[$sv['is_check']] : '未认证', # 认证,
                    'city' => $v['city'],
                    'driveStatus' => $sv['actual_num'] > 0 ? '已行驶' : '未行驶', # 是否行驶
                    'useStatus' => $useStatus,
                    'insureNo' => $sv['insureNo'],
                    'comment' => $sv['comment'],
                ];
            }
            $onlineRate = $v['install_num'] && !empty($onlineNum) ? round($onlineNum / $v['install_num'], 4) : '0.00';
            $noInstallRate = $v['install_num'] && !empty($v['no_install_num']) ? round($v['no_install_num'] / $v['install_num'], 4) : '0.00';

            $tmp = [
                $tmpProvince,
                $v['city'],
                "{$v['active_num']}",
                "{$v['no_install_num']}",
                "{$v['install_num']}",
                "{$pullOutNum}",
                "{$validNum}",
                "{$offlineNum}",
                "{$onlineNum}",
                "{$v['enter_num']}",
                $onlineRate,
                $noInstallRate
            ];
            array_push($tableDate, $tmp);
//            print_r($tmp);

            # 小计累计
            $total['active'] += $v['active_num'];
            $total['noInstall'] += $v['no_install_num'];
            $total['installNum'] += $v['install_num'];
            $total['pullOutNum'] += $pullOutNum;
            $total['validNum'] += $validNum;
            $total['offlineNum'] += $offlineNum;
            $total['onlineNum'] += $onlineNum;
            $total['enterNum'] += $v['enter_num'];


            # 所有累计
            $allTotal['active'] += $v['active_num'];
            $allTotal['noInstall'] += $v['no_install_num'];
            $allTotal['installNum'] += $v['install_num'];
            $allTotal['pullOutNum'] += $pullOutNum;
            $allTotal['validNum'] += $validNum;
            $allTotal['offlineNum'] += $offlineNum;
            $allTotal['onlineNum'] += $onlineNum;
            $allTotal['enterNum'] += $v['enter_num'];


            # 结束总计
            if ($indexLength == $k) {
                if (empty($total['noInstall'])) {
                    $total['noInstall'] = '0';
                }
                $total['onlineRate'] = $total['installNum'] && !empty($total['onlineNum']) ? round($total['onlineNum'] / $total['installNum'], 4) : '0.00';
                $total['noInstallRate'] = $total['installNum'] && !empty($total['noInstall']) ? round($total['noInstall'] / $total['installNum'], 4) : '0.00';
//                $total = $this->formatTotal($total);
                array_push($tableDate, $total);

//                # 省合并（结束为止不确定）和居中显示
                $objWorksheet->mergeCells("A" . $tmpNum . ":A" . $tmpNumEnd);
                $objWorksheet->getStyle('A' . $tmpNum)->getAlignment()
                    ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }
        }

//        print_r($tableDate);exit;
        # 所有总计
        $allTotal['onlineRate'] = $allTotal['installNum'] && !empty($allTotal['onlineNum']) ? round($allTotal['onlineNum'] / $allTotal['installNum'], 4) : '0.00';
        $allTotal['noInstallRate'] = $allTotal['installNum'] && !empty($allTotal['noInstall']) ? round($allTotal['noInstall'] / $allTotal['installNum'], 4) : '0.00';
        if (empty($allTotal['noInstall'])) {
            $allTotal['noInstall'] = '0';
        }
//        $allTotal = $this->formatTotal($allTotal);
        array_push($tableDate, $allTotal);

        # 所有总计合并
        $tmpNumEnd += 1;
        $objWorksheet->mergeCells("A" . $tmpNumEnd . ":B" . $tmpNumEnd);
        $objWorksheet->getStyle('A' . $tmpNumEnd)->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        # 设置显示为数字
        $objWorksheet->getStyle('C4:J' . ($tmpNumEnd + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
        $objWorksheet->getStyle('K4:L' . ($tmpNumEnd + 1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);


        # 填充excel数据
        $objWorksheet->fromArray($tableDate);

        # 设置宽度
        $objWorksheet->getColumnDimension('A')->setWidth(10);
        $objWorksheet->getColumnDimension('B')->setWidth(10);
        $objWorksheet->getColumnDimension('C')->setWidth(12);
        $objWorksheet->getColumnDimension('D')->setWidth(12);
        $objWorksheet->getColumnDimension('E')->setWidth(12);
        $objWorksheet->getColumnDimension('F')->setWidth(16);
        $objWorksheet->getColumnDimension('G')->setWidth(12);
        $objWorksheet->getColumnDimension('H')->setWidth(8);
        $objWorksheet->getColumnDimension('I')->setWidth(8);
        $objWorksheet->getColumnDimension('J')->setWidth(8);
        $objWorksheet->getColumnDimension('K')->setWidth(8);
        $objWorksheet->getColumnDimension('L')->setWidth(10);

        # 标题合并
        $objWorksheet->mergeCells("A1:L1");
        $objWorksheet->getStyle('A1')->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        # 地区合并
        $objWorksheet->mergeCells("A2:B2");
        $objWorksheet->getStyle('A2')->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        # 合并和对齐
        $rangeCell = range('C', 'L');
        foreach ($rangeCell as $k => $v) {
            # 合并单元格
            $objWorksheet->mergeCells($v . "2:" . $v . "3");

            # 设置对齐样式（水平垂直居中）
            $objWorksheet->getStyle($v . '2')->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }

        /*保存到第一个sheet*/
        $objPHPExcel->getActiveSheet()->setTitle('设备汇总');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        //第一sheet入职 -end


        //第二sheet转正 设备明细
        $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex(1);
        $second = 1;
        $sheetTitle = '设备明细';
        $A2Head = array(
            array(
                'loginName' => "账户",
                'username' => "姓名",
                'sex' => "性别",
                'birthday' => '生日',
                'carNumber' => '车牌',
                'carStatus' => '临/正牌',
                'src' => '设备ID',
                'srcStatus' => '设备状态',
                'activeTime' => '激活日期',
                'installTime' => '安装日期',
                'money' => '账户余额',
                'regDate' => '注册日期',
                'enterStatus' => '认证状态',
                'city' => '设备城市',
                'driveStatus' => '是否行使',
                'useStatus' => '使用情况',
                'insureNo' => '保单',
                'comment' => '备注'
            ),
        );
        $this->makeSrcList($objPHPExcel, $second, $srcList, $sheetTitle, $A2Head);


        $objPHPExcel->setActiveSheetIndex(0);

        $fileName = 'dayCitySrc_' . date('Ymd') . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    /*
     * 设备明细
     */
    public function makeSrcList($objPHPExcel, $num, $srcList, $sheetTitle, $A2Head)
    {
        $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex($num);

        $new_arr = array_merge($A2Head, $srcList);
        //写入到第n个sheet
        $i = 0;
        foreach ($new_arr as $k => $v) {
            if ($i == 0) {
                $xu = '序号';
            } else {
                $xu = $i;
            }
            $i = $i + 1;
            $objPHPExcel->getactivesheet()->setcellvalue('A' . $i, $xu);                    //第A列   序号
            $objPHPExcel->getactivesheet()->setcellvalue('B' . $i, $v['loginName']);        //第B列   账户
            $objPHPExcel->getactivesheet()->setcellvalue('C' . $i, $v['username']);         //第C列   用户名
            $objPHPExcel->getactivesheet()->setcellvalue('D' . $i, $v['sex']);              //第D列   性别
            $objPHPExcel->getactivesheet()->setcellvalue('E' . $i, $v['birthday']);         //第E列   生日
            $objPHPExcel->getactivesheet()->setcellvalue('F' . $i, $v['carNumber']);        //第F列   车牌
            $objPHPExcel->getactivesheet()->setcellvalue('G' . $i, $v['carStatus']);        //第G列   车牌状态（临/正）
            $objPHPExcel->getactivesheet()->setcellvalue('H' . $i, $v['src']);              //第H列   设备ID
            $objPHPExcel->getactivesheet()->setcellvalue('I' . $i, $v['srcStatus']);        //第I列   设备状态
            $objPHPExcel->getactivesheet()->setcellvalue('J' . $i, $v['activeTime']);       //第J列   激活时间
            $objPHPExcel->getactivesheet()->setcellvalue('K' . $i, $v['installTime']);      //第K列   安装时间
            $objPHPExcel->getactivesheet()->setcellvalue('L' . $i, $v['money']);            //第L列   账户余额
            $objPHPExcel->getactivesheet()->setcellvalue('M' . $i, $v['regDate']);          //第M列   注册日期
            $objPHPExcel->getactivesheet()->setcellvalue('N' . $i, $v['enterStatus']);      //第N列   认证状态
            $objPHPExcel->getactivesheet()->setcellvalue('O' . $i, $v['city']);             //第O列   城市
            $objPHPExcel->getactivesheet()->setcellvalue('P' . $i, $v['driveStatus']);      //第P列   行驶状态
            $objPHPExcel->getactivesheet()->setcellvalue('Q' . $i, $v['useStatus']);        //第Q列   使用状态
            $objPHPExcel->getactivesheet()->setcellvalue('R' . $i, $v['insureNo']);         //第R列   保单号
            $objPHPExcel->getactivesheet()->setcellvalue('S' . $i, $v['comment']);          //第S列   备注
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        /*保存到本地*/
        $objPHPExcel->getActiveSheet()->setTitle($sheetTitle);
        return true;
    }

    /**
     * 各省在线率走势图
     */
    public function onlineChar()
    {
        $objPHPExcel = new PHPExcel();
        $objWorksheet = $objPHPExcel->getActiveSheet();

        $title = "截至" . date('Y年m月d日') . "各省设备在线率走势图";

        $titleDay = [''];
        $objWorksheet->getColumnDimension('A')->setWidth(12);
        $provinceList = $this->SrcCityOnlineModel->provinceList();
        $endMark = range('A', 'Z');
        foreach ($provinceList as $k => $v) {
            $titleDay[] = $v['province'];
            $objWorksheet->getColumnDimension($endMark[$k + 1])->setWidth(12);
        }

        $tableDate = array(
            [$title],
            $titleDay,
        );

        for ($i = 30; $i > 0; $i--) {
            $day = date('Y-m-d', strtotime("-{$i} days"));
            if (date('m-d', strtotime("-{$i} days")) == '01-01') {
                $tmpDay = $day;
            } else {
                $tmpDay = date('m-d', strtotime("-{$i} days"));
            }
            $tableDate[$i + 2] = [$tmpDay];
            foreach ($provinceList as $k => $v) {
                $provinceNum = $this->SrcCityOnlineModel->provinceNum($v['province'], $day);
                $tmpRate = !empty($provinceNum) && !empty($provinceNum['all_num']) ? round($provinceNum['online_num'] / $provinceNum['all_num'], 4) : '0';
                $tableDate[$i + 2][] = $tmpRate;
            }
        }

//        # 标题合并
        $objWorksheet->mergeCells("A1:" . $endMark[count($provinceList)] . '1');
        $objWorksheet->getStyle('A1:' . $endMark[count($provinceList)] . '32')->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $objWorksheet->getStyle('B3:' . $endMark[count($provinceList)] . '32')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);

        # 填充excel数据
        $objWorksheet->fromArray($tableDate);
//        print_r($tableDate);exit;

        // Set the Labels for each data series we want to plot 为我们想要绘制的每个数据系列设置标签（系列)
        $dataSeriesLabels = [];
        foreach ($provinceList as $k => $v) {
            $dataSeriesLabels[] = new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$' . $endMark[$k + 1] . '$2', NULL, 1);    // 省份
        }

        // Set the X-Axis Labels 设置X轴标签(类别)
        $xAxisTickValues = array(
            new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$A$3:$A$32', NULL, 30),    // 时间开始和结束
        );

        // Set the Data values for each data series we want to plot 为我们想要绘制的每个数据系列设置标签
        $dataSeriesValues = [];
        foreach ($provinceList as $k => $v) {
            $cellChar = $endMark[$k + 1];
            $dataSeriesValues[] = new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$' . $cellChar . '$3:$' . $cellChar . '$32', NULL, 30);
        }

        // Build the dataseries 构建数据库
        $series = new PHPExcel_Chart_DataSeries(
            PHPExcel_Chart_DataSeries::TYPE_LINECHART,        //  plotType 情节类型
            PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,    //  plotGrouping 情节分组
            range(0, count($dataSeriesValues) - 1),            //  plotOrder 剧情顺序
            $dataSeriesLabels,                                //  plotLabel 绘图系列
            $xAxisTickValues,                                //  plotCategory 绘图类别
            $dataSeriesValues                                //  plotValues 绘图值
        );

        $layout = new PHPExcel_Chart_Layout(['y' => 10]);
        // Set the series in the plot area 在绘图区域中设置系列
        $plotArea = new PHPExcel_Chart_PlotArea(NULL, array($series));

        // Set the chart legend 绘制图表图例
        $legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

        // 绘制图表标题
        $title = new PHPExcel_Chart_Title('设备在线率走势图');
        // 绘制图片Y轴标题
        $yAxisLabel = new PHPExcel_Chart_Title('百分比');


        // Create the chart 创建图表
        $chart = new PHPExcel_Chart(
            'chart1',        //  name
            $title,            //  title
            $legend,        //  legend 图例
            $plotArea,        //  plotArea 绘图区域
            true,            //  plotVisibleOnly
            0,                //  displayBlanksAs
            NULL,            //  xAxisLabel
            $yAxisLabel        //  yAxisLabel
        );

        // Set the position where the chart should appear in the worksheet 设置图表在工作表中出现的位置
        $chart->setTopLeftPosition($endMark[count($provinceList) + 3] . '6');
        $endCell = (count($provinceList) + 18 > 26) ? 'A' . $endMark[count($provinceList) - 18] : $endMark[count($provinceList) + 18];
        $chart->setBottomRightPosition($endCell . '26');


        // Add the chart to the worksheet 将突变添加到工作表
        $objWorksheet->addChart($chart);

        //  Save Excel 2007 file 保存 excel 2007文件
        $tmpFileName = 'lineMap_' . date('Ymd') . '.xlsx';
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  # Excel2007版本
        $objWriter->setIncludeCharts(TRUE);

        $this->browser_export('Excel2007', $tmpFileName); //浏览器输出
        $this->SaveViaTempFile($objWriter);
    }

    public function browser_export($type, $filename)
    {
        if ($type == "Excel5") {
            header('Content-Type: application/vnd.ms-excel'); //excel2003
        } else {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //excel2007
        }
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
    }

    /*解决Excel2007不能导出图表*/
    public function SaveViaTempFile($objWriter)
    {
        $filePath = dirname(__FILE__) . rand(0, getrandmax()) . rand(0, getrandmax()) . ".tmp";
        $objWriter->save($filePath);
        readfile($filePath);
        unlink($filePath);
    }

    /**
     * 安装统计
     */
    public function installTotal()
    {
        $title = "截至" . date("Y年m月d日") . "各地区设备安装情况总计";
        $preYear = date('Y年', strtotime('-1 year'));

        $titleMonth = ['地区', '', $preYear];
        $total = ['province' => '', 'total' => '总计', 'y' => 0]; # 小计
        $allTotal = ['province' => '总计', 'total' => '', 'y' => 0];  # 总计
        $month = date('m');
        for ($i = 1; $i <= $month; $i++) {
            $titleMonth[] = $i != $month ? $i . '月' : date('m月d日');
            $total['m' . $i] = 0;
            $allTotal['m' . $i] = 0;
        }
        $titleMonth['lineAll'] = '总计'; # 行小计
        $total['lineAll'] = 0;
        $allTotal['lineAll'] = 0;

        $tableDate = array(
            [$title],
            $titleMonth,
            ['省', '市'],
        );

        $objPHPExcel = new PHPExcel();
        $objWorksheet = $objPHPExcel->getActiveSheet();

        $cityList = $this->SrcCityModel->cityList();

//        print_r($cityList);exit;
        $tmpSwitch = '';
        $indexLength = count($cityList) - 1;
        foreach ($cityList as $k => $v) {
            if ($k == 0) {
                $tmpNum = 4;
                $tmpNumEnd = 4;
                $tmpSwitch = $v['provice'];
                $tmpProvince = $v['provice'];
            } else {
                $tmpProvince = $tmpSwitch != $v['provice'] ? $v['provice'] : '';
            }

            # 换省
            if ($tmpSwitch != $v['provice']) {
                $tmpSwitch = $v['provice'];

                # 总计
                foreach ($total as $tk => $tv) {
                    $total[$tk] = !empty($tv) ? $tv : '0';
                }

                array_push($tableDate, $total);

                # 省合并（结束为止不确定）和居中显示
                $objWorksheet->mergeCells("A" . $tmpNum . ":A" . $tmpNumEnd);
                $objWorksheet->getStyle('A' . $tmpNum)->getAlignment()
                    ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $tmpNumEnd += 1;

                # 合并归位
                $tmpNum = $tmpNumEnd;
                $total = ['province' => '', 'total' => '总计', 'y' => 0];
                for ($i = 1; $i <= $month; $i++) {
                    $total['m' . $i] = 0;
                }
                $total['lineAll'] = 0;
//                print_r($v);exit;
            }
            $tmpNumEnd += 1;

            $yearRow = $this->CityYearModel->cityNumber($preYear, $v['provice'], $v['city']);
            $yearNum = !empty($yearRow) ? $yearRow['install_num'] : '0';
            $tmp = [$tmpProvince, $v['city'], 'y' => $yearNum];

            $monthList = $this->CityMonthModel->installList($v['provice'], $v['city']);
            $monthNum = array_column($monthList, 'install_num', 'month');

            $total['y'] += $yearNum;         # 小计
            $allTotal['y'] += $yearNum;      # 小计
            $lineTotal = 0;
            for ($i = 1; $i <= $month; $i++) {
                $tmp['m' . $i] = isset($monthNum[$i]) && !empty($monthNum[$i]) ? $monthNum[$i] : '0';
                $total['m' . $i] += $tmp['m' . $i];         # 省份小计
                $allTotal['m' . $i] += $tmp['m' . $i];    # 所有总计
                $lineTotal += $tmp['m' . $i];

            }
            $tmp['lineTotal'] = $lineTotal;
            $total['lineAll'] += $lineTotal;
            $allTotal['lineAll'] += $lineTotal;

            array_push($tableDate, $tmp);

            # 结束总计
            if ($indexLength == $k) {
                foreach ($total as $ak => $av) {
                    $total[$ak] = !empty($av) ? $av : '0';
                }
                array_push($tableDate, $total);

//                # 省合并（结束为止不确定）和居中显示
                $objWorksheet->mergeCells("A" . $tmpNum . ":A" . $tmpNumEnd);
                $objWorksheet->getStyle('A' . $tmpNum)->getAlignment()
                    ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }
        }

//        print_r($tableDate);exit;
        # 所有总计
        foreach ($allTotal as $alk => $alv) {
            $allTotal[$alk] = !empty($alv) ? $alv : '0';
        }
        array_push($tableDate, $allTotal);

        # 所有总计合并
        $tmpNumEnd += 1;
        $objWorksheet->mergeCells("A" . $tmpNumEnd . ":B" . $tmpNumEnd);
        $objWorksheet->getStyle('A' . $tmpNumEnd)->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//        print_r($tableDate);exit;

        # 填充excel数据
        $objWorksheet->fromArray($tableDate);

        # 设置宽度
        $endMark = range('A', 'Z');
        for ($i = 1; $i < $month + 4; $i++) {
            $objWorksheet->getColumnDimension($endMark[$i])->setWidth(10);
        }

        # 标题合并
        $objWorksheet->mergeCells("A1:" . $endMark[$month + 2] . '1');
        $objWorksheet->getStyle('A1')->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        # 地区合并
        $objWorksheet->mergeCells("A2:B2");
        $objWorksheet->getStyle('A2')->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        # 合并和对齐
        $rangeCell = range('C', $endMark[$month + 3]);
        foreach ($rangeCell as $k => $v) {
            # 合并单元格
            $objWorksheet->mergeCells($v . "2:" . $v . "3");

            # 设置对齐样式（水平垂直居中）
            $objWorksheet->getStyle($v . '2')->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }

        $fileName = 'monthInstallSrc_' . date('Ymd') . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

}
