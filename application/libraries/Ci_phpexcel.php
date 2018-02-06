<?php

class Ci_phpexcel extends PHPExcel
{
    public function __construct()
    {
        parent::__construct();
    }

    /*
     * 将excel转换成数组
     */
    public function getArray($filename,$dateRows = [])
    {
        $objReader = PHPExcel_IOFactory::createReaderForFile($filename);
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($filename);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $excelData = array();
        $keys = array();
        for ($row = 1; $row <= $highestRow; $row++) {

            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $value = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                if ($row > 1) {
                    if($value){
                        $excelData[$row - 1][] = $value;
                    }else{
                        $excelData[$row - 1][] = NULL;
                    }
                }
            }
        }
        foreach($excelData as $k=>$v){
            if(!empty($v['first_date'])){
                $excelData[$k]['first_date'] = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($v['first_date']));
            }
            if(!empty($v['delivery_time'])){
                $excelData[$k]['delivery_time'] = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($v['delivery_time']));
            }
        }
        return $excelData;
    }
}
