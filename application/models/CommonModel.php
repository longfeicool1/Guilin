<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CommonModel extends MY_Model {
    public function __CONSTRUCT() {
        parent::__CONSTRUCT();
    }

    public function export(array $header,$data = array(),$filename = 'noname.xlsx')
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        if (empty($data)) {
            return;
        }
        $table = "序号\t";
        foreach ($header as $k => $v) {
            $table .= $v . "\t";
        }
        $table .= "\n";
        $n = 0;
        // $data = array_map(function ($v){
        //     return str_replace(" ", '', $v);
        // }, $data);
        foreach ($data as $row) {
            $n ++;
            $table .= "{$n}\t";
            foreach ($header as $k => $v) {
                // $val = trim($row[$k]);
                $val = preg_replace("/[\s]/",'',$row[$k]);
                $table .= "{$val}\t";
            }
            $table .= "\n";
        }
        // echo '<pre>';print_r($table);die;
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Disposition:filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo mb_convert_encoding($table, "GB18030", "utf8");
    }

    public function exportExcel(array $header,$data = array(),$filename = 'noname.xls',$wantcss)
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        if (empty($data)) {
            return;
        }
        $css = [
            'txt'     => 'vnd.ms-excel.numberformat:@',
            'date'    => 'vnd.ms-excel.numberformat:yyyy/mm/dd',
            'number'  => 'vnd.ms-excel.numberformat:#,##0.00',
            'coin'    => 'vnd.ms-excel.numberformat:￥#,##0.00',
            'percent' => 'vnd.ms-excel.numberformat: #0.00%',
        ];
        $table = '<table>';
        $table .= "<tr><td>序号</td>";
        foreach ($header as $k => $v) {
            $table .= "<td>{$v}</td>";
        }
        $table .= "</tr>";
        $n = 0;
        $data = array_map(function ($v){
            return str_replace(" ", '', $v);
        }, $data);
        foreach ($data as $row) {
            $n ++;
            $table .= "<tr><td>{$n}</td>";
            foreach ($header as $k => $v) {
                if (!empty($wantcss[$k]) && !empty($css[$wantcss[$k]])) {
                    $table .= "<td style=\"{$css[$wantcss[$k]]}\">{$row[$k]}</td>";
                } else {
                    $table .= "<td>{$row[$k]}</td>";
                }
            }
            $table .= "</tr>";
        }
        $table .= '</table>';
        // echo '<pre>';print_r($table);die;
        header("Content-Type: application/vnd.ms-excel");
        Header("Accept-Ranges:bytes");
        Header("Content-Disposition:attachment;filename=".$filename.".xls"); //$filename导出的文件名
        header("Pragma: no-cache");
        header("Expires: 0");
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"
        xmlns:x="urn:schemas-microsoft-com:office:excel"
        xmlns="http://www.w3.org/TR/REC-html40">
        <head>
        <meta http-equiv="expires" content="Mon, 06 Jan 1999 00:00:01 GMT">
        <meta http-equiv=Content-Type content="text/html; charset=gb2312">
        <!--[if gte mso 9]><xml>
        <x:ExcelWorkbook>
        <x:ExcelWorksheets>
        <x:ExcelWorksheet>
        <x:Name></x:Name>
        <x:WorksheetOptions>
        <x:DisplayGridlines/>
        </x:WorksheetOptions>
        </x:ExcelWorksheet>
        </x:ExcelWorksheets>
        </x:ExcelWorkbook>
        </xml><![endif]-->

        </head>';
        echo mb_convert_encoding($table, "GB18030", "utf8");
    }

    public function exportCsv(array $header,$data = array(),$filename = 'noname.csv')
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        if (empty($data)) {
            return;
        }
        $table  = "序号,";
        $secRow = "xuhao,";
        foreach ($header as $k => $v) {
            $table  .= $v . ",";
            $secRow .= $k . ",";
        }
        $secRow .= "\n";
        $table  .= "\n";
        $table  .= $secRow;
        $n       = 0;
        // $data    = array_map(function ($v){
        //     return str_replace(" ", '', $v);
        // }, $data);
        foreach ($data as $row) {
            $n ++;
            $table .= "{$n},";
            foreach ($header as $k => $v) {
                $table .= "{$row[$k]},";
            }
            $table .= "\n";
        }
        // echo '<pre>';print_r($table);die;
        header("Content-Type:text/csv");
        header("Content-Disposition:filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");
        header('Pragma:public');
        echo mb_convert_encoding($table, "gbk", "utf8");
    }


    /*
     * 提示模板
     * $status   错误状态码
     * $msg     提示信息
     * $tabid   需要刷新的tab页
     * $close   是否关闭当前标签页
     */
    public function ajaxReturn($status, $msg, $tabid = '', $close = 1, $selfData = array(),$forward = '')
    {
        $back['closeCurrent'] = $close;
        $back['statusCode']   = $status;
        $back['message']      = $msg;
        $back['tabid']        = $tabid;
        $back['forward']      = $forward;
        $back['result']       = $selfData;
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($back));
    }


    public function output($result)
    {
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($result));
    }

    /**
     * [upload 上传文件获取文件内容]
     * $boolen  true 开启移动文件
     * $topath  目标位置
     * @return [type] [description]
     */
    public function upload($boolen = false,$filename = '',$topath = './static/upload_his')
    {
        if (!empty($_FILES)) {
            $tempFile = $_FILES['file']['tmp_name']; //临时文件的存放位置
            // 验证文件类型
            $fileTypes = array('csv','xls','xlsx');
            $fileParts = pathinfo($_FILES['file']['name']);
            $part      = strtolower($fileParts['extension']);
            if (in_array($part, $fileTypes)) {
                // log_message('debug', 'tempFile::' . $tempFile);
                if (!$boolen) {
                    return $tempFile;
                }
                $source = rtrim($topath,'/') .'/'. $filename . '.' . $part;
                if (!move_uploaded_file($tempFile, $source)) {
                    return ['errcode' => 300,'errmsg' => '上传临时文件目录或者权限错误,请联系管理员。'];
                }
                return $source;
            } else {
                return ['errcode' => 300,'errmsg' => '上传失败1'];
            }
        }
    }

    /**
     * [readExecl 读取xlxs/xls文件]
     * @param  [type] $filename [description]
     * @return [type]           [description]
     */
    public function readExecl($filename)
    {
        $this->load->library('ci_phpexcel');
        if (is_file($filename)) {
            $result = $this->ci_phpexcel->getArray($filename);
            return $result;
        }
        return ['errcode' => 300,'errmsg' => '读取失败'];
    }

    public function readCsv($filename)
    {
        try {
            $handle = fopen($filename, 'r');
            $out = array();
            $keys = [];
            $n   = 0;
            while ($data = fgetcsv($handle, 10000)) {
                if ($n > 1) {
                    $num = count($data);
                    for ($i = 0; $i < $num; $i++) {
                        if (!empty($keys[$i] && $keys[$i] != '0')) {
                            $out[$n - 2][$keys[$i]] = iconv('gbk', 'utf-8', $data[$i]); //中文转码   ;
                        }
                    }
                } else {
                    $keys = $data;
                }
                $n++;
            }
            return $out;
        } catch (Exception $e) {
            return ['errcode' => 300,'errmsg' => '读取失败'];
        }
    }

    private function input_csv($handle)
    {
//      $tempFile = $_SERVER['DOCUMENT_ROOT'] . '/11-17 17-20-15_配送单.csv';
        //      $handle = fopen($tempFile,'r');
        $out = array();
        $keys = [];
        $n   = 0;
        while ($data = fgetcsv($handle, 10000)) {
            if ($n > 1) {
                $num = count($data);
                for ($i = 0; $i < $num; $i++) {
                    $out[$n - 2][$keys[$i]] = iconv('gbk', 'utf-8', $data[$i]); //中文转码   ;
                }
            } else {
                $keys = $data;
            }
            $n++;
        }
        return $out;
    }
}