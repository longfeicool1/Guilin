<?php
/**
 * csv助手方法(请保存文件编码为ansi)
 * @author liuweilong
 * +2016-03-23
 */
class CsvHelper
{
    /**
     * 设定csv导出
     * @param array  $whereData    [description]
     * @param object  $model        [description]
     * @param array  $header       [description]
     * @param string  $countsAction [description]
     * @param string  $listsAction  [description]
     * @param integer $pageSize     [description]
     * @param integer $maxRows      [description]
     * @return string csv-format
     */
    public function setCsvData($whereData, $model, $header, $countsAction = 'counts',
        $listsAction = 'lists', $pageSize = 1500, $maxRows = 100000) {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        $temp = $newHeader = array();
        // create header
        foreach ($header as $k => $v) {
            if ($k == 'checkboxs' || (isset($v['export']) && $v['export'] == false)) {
                continue;
            }

            if (isset($v['exportCallback'])) {
                $v['callback'] = $v['exportCallback'];
                unset($v['exportCallback']);
            }
            $newHeader[$k] = $v;
            $temp[]        = is_array($v) && isset($v['name']) ? $v['name'] : (is_string($v) ? $v : '');
        }

        $rows         = array();
        $rows[]       = join(',', $temp);
        $total        = call_user_func_array(array($model, $countsAction), array($whereData));
        $total        = $total > $maxRows ? $maxRows : $total;
        $pageForCount = ceil($total / $pageSize);
        for ($i = 1; $i <= $pageForCount; $i++) {
            $data = call_user_func_array(array($model, $listsAction), array($whereData, $i, $pageSize));
            $row  = 0;
            if (empty($data)) {
                break;
            }
            foreach ($data as $key => $val) {
                $temp = array();
                foreach ($newHeader as $k => $v) {

                    if ($k == 'rowCounter') {
                        $temp[] = (($row + 1) + (($i - 1) * $pageSize));
                    } else {
                        // get value
                        $value = isset($val[$k]) ? $val[$k] : (isset($v['defaultValue']) ? $v['defaultValue'] : '');
                        // exec callback
                        $value  = isset($v['callback']) ? $v['callback']($row, $val, $k) : $value;
                        $value  = !is_numeric($value) ? str_replace(',', '，', strip_tags($value)) : $value;
                        $value  = !is_numeric($value) ? str_replace(array("\n", "\r"), '', $value) : $value;
                        $value  = is_numeric($value) && $value > 100000 ? $value . "\t" : $value;
                        $temp[] = $value;
                    }
                    // log_message('info', $k.':'.$value);
                }
                $rows[] = join(',', $temp);
                $row++;
            }
        }

        return join("\n", $rows);
    }

    /**
     * 导出csv
     * @param  string $filename
     * @param  string $data
     * @return string
     */
    public function exportCsv($filename, $data)
    {
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=" . $filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        // echo iconv('utf-8', 'gbk//ignore', $data);
        echo mb_convert_encoding($data, 'gbk', 'utf-8');
    }

    /**
     * 解析csv
     * @param  string $filePath
     * @return array
     */
    public function parseCsv($filePath)
    {
        $handle = fopen($filePath, 'r');
        $out    = array();
        $n      = 0;
        while ($data = fgetcsv($handle, 10000)) {
            $num = count($data);
            for ($i = 0; $i < $num; $i++) {
                $out[$n][$i] = str_replace(array("'", '‘', '"', ' '), '', iconv('gb2312', 'utf-8', $data[$i])); //中文转码   ;
            }
            $n++;
        }
        fclose($handle);
        return $out;
    }
}
