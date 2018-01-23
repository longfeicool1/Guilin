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
    public function getArray($filename)
    {
        $objReader = PHPExcel_IOFactory::createReaderForFile($filename,'xls');
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

                if ($row == 1) {
                    $keys[] = $value;
                } else {
                    if($value){
                        $excelData[$row - 1][$keys[$col]] = $value;
                    }else{
                        $excelData[$row - 1][$keys[$col]] = NULL;
                    }
                }
            }
        }
        return $excelData;
    }
}
