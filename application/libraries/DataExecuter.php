<?php
/**
 * 数据处理类
 * @author liuweilong
 * + 2016-03-16
 * @example
$test1 = Array( 
0 => Array( 
'id' => 9478137, 
'create_time' => 1394760724 
), 
1 => Array( 
'id' => 9478138, 
'create_time' => 1394760725 
), 
2 => Array( 
'id' => 9478138, 
'create_time' => 1394760725 
) 
); 
$test2 = array( 
0 => array( 
'id' => 9478137, 
'message' => 'love you' 
), 
1 => array( 
'id' => 9478138, 
'message' => 'miss you' 
) 
);

$test3 = array( 
0 => array( 
'id' => 9478137, 
'tr' => 'love youttt' 
), 
1 => array( 
'id' => 9478138, 
'tr' => 'miss yourrrr' 
) 
); 
 // DataExecuter::mergerArray($test1, $test2);
$data = DataExecuter::mergerArray($test1, $test2, $test3, 'id', 'id', 'id');
print_r($data);

// same key where
$data = DataExecuter::mergerArray($test1, $test2, $test3, 'id');

Res:
Array
(
    [0] => Array
        (
            [id] => 9478137
            [tr] => love youttt
            [message] => love you
            [create_time] => 1394760724
        )

    [1] => Array
        (
            [id] => 9478138
            [tr] => miss yourrrr
            [message] => miss you
            [create_time] => 1394760725
        )

    [2] => Array
        (
            [id] => 9478138
            [tr] => miss yourrrr
            [message] => miss you
            [create_time] => 1394760725
        )

)


$data = DataExecuter::keyToArray($test1, 'id');
RES:
Array
(
    [0] => 9478137
    [1] => 9478138
    [2] => 9478138
)    

 */
class DataExecuter
{   

    /**
     * 生成select/radio/checkbox表单时使用
     * @param  array $data       [description]
     * @param  string $keyField   [description]
     * @param  string $valueField [description]
     * @return array             [description]
     * @example 
     * DATA: Array ( [0] => Array ( [id] => 1 [name] => 后台管理 ) [1] => Array ( [id] => 2 [name] => 客服组 ) [2] => Array ( [id] => 3 [name] => 运营组 ) ) 
     *  DataExecuter::keyToField($data, 'id', 'name');
     * RES:Array ( [1] => 后台管理 [2] => 客服组 [3] => 运营组 )
     */
    public static function keyToField($data, $keyField, $valueField)
    {
        $output = array();
        foreach ($data as $key => $val)
        {
            $output[$val[$keyField]] = $val[$valueField];
        }
        return $output;
    }
    /**
     * 一维数组过滤数据(查询条件过滤时使用)
     * @param  [type] $data         [description]
     * @param  array  $commonFilter [description]
     * @param  array or string   $choiceKey [description]
     * @param  array  $specifies    [description]
     * @return array
     * @example 
     * DataExecuter::filter($data, array('', -1), 'id,msg');
     */
    public static function filter($data, $commonFilter = array('', -1), $choiceKey = array(), $specifies = array())
    {
        $output = array();
        if (empty($data))
        {
            return $output;
        }

        $choiceKey = is_array($choiceKey) ? $choiceKey : explode(',', $choiceKey);
        foreach ($data as $key => $value)
        {
            if (!in_array($key, $choiceKey) || in_array(trim($value), $commonFilter))
            {
                continue;
            }

            if(isset($specifies[$key]))
            {
                $res = $specifies[$key]($value);
                if (!$res)
                {
                    continue;
                }
            }
            $output[$key] = $value;
        }
        return $output;
    }

    /**
     * 取值
     * @param  array $data    [description]
     * @param  string $field   [description]
     * @param  mixed $default [description]
     * @return mixed
     */
    public static function get($data, $field, $default)
    {
        return isset($data[$field]) ? $data[$field] : $default;
    }

    /**
     * 取出二维数组的key并生成数组
     * @param  array $data
     * @param  string $field
     * @return array
     */
    public static function keyToArray($data, $field = 'id', $bf = '', $ba = '')
    {
        $output = array();
        if(empty($data) || !is_array($data))
        {
            return $output;
        }

        foreach ($data as $key => $value)
        {
            if (!isset($value[$field]))
            {
                continue;
            }

            if (!empty($bf) || ($ba))
            {
                $output[] = $bf.$value[$field].$ba;
            }
            else
            {
                $output[] = $value[$field];
            }
            
        }
        return $output;
    }


    /**
     * 改变数组的key
     * @param  array $data
     * @param  string $field
     * @return array
     */
    public static function changeKey($data, $field = 'id')
    {
        $output = array();
        if(empty($data) || !is_array($data))
        {
            return $output;
        }

        foreach ($data as $key => $value)
        {
            if (!isset($value[$field]))
            {
                continue;
            }
            $output[$value[$field]] = $value;
        }
        return $output;
    }

    /**
     * 数据合并
     * @return array
     */
    public static function mergerArray()
    {
        $dataNum = 0;
        $fieldNum = 0;
        $numargs = func_num_args();
        if ($numargs < 3)
        {
            echo 'Error:参数不允许小于3个';
            return array();
        }

        $argList = func_get_args();
        for ($i = 0; $i < $numargs; $i++)
        {
            if(is_array($argList[$i]))
            {
                $dataNum++;
            }
            else
            {
                $fieldNum++;
            }
        }

        if ($dataNum == 0 || $fieldNum == 0)
        {
            echo 'Error:参数不正确#1';
            return array();
        }

        if ($fieldNum != 1 && $fieldNum != $dataNum)
        {
            echo 'Error:参数不正确#2';
            return array();
        }

        
        $ret = $newArray = array();
        for($i = 1; $i < $dataNum; $i++)
        {
            $newArray[$i] = array();
            foreach ($argList[$i] as $key => $value)
            { 
                $field = $fieldNum == 1 ? $argList[$dataNum] : $argList[$dataNum + $i];
                if (isset($value[$field]))
                {
                    $newArray[$i][trim($value[$field])] = $value; 
                }
            }
        }

        // print_r($newArray);
        foreach ($argList[0] as $key => $value)
        {
            $temp = array();
            for($i = 1; $i < $dataNum; $i++)
            {
                $field = $fieldNum == 1 ? $argList[$dataNum] : $argList[$dataNum + $i];
                if (isset($value[$field]))
                {
                    $value[$field] = trim($value[$field]);
                }
                if (isset($value[$field]) && isset($newArray[$i][$value[$field]]))
                {
                    $temp = array_merge($newArray[$i][$value[$field]], $temp); 
                }
            }
            $ret[] = array_merge($temp, $value); 
        }
        return $ret;
    }

     /**
     * 数据合并
     * @return array
     */
    public static function mergerArray2()
    {
        $dataNum = 0;
        $fieldNum = 0;
        $numargs = func_num_args();
        $mutiField = false;
        $maxFor = 0;
        if ($numargs < 3)
        {
            echo 'Error:参数不允许小于3个';
            return array();
        }

        $argList = func_get_args();
        for ($i = 0; $i < $numargs; $i++)
        {
            if(is_array($argList[$i]))
            {
                $maxFor = count($argList[$i]) > $maxFor ? count($argList[$i]) : $maxFor;
                $dataNum++;
            }
            else
            {
                $argList[$i] = str_replace(' ','', $argList[$i]);
                $fieldNum++;
                // 判断是否多字段匹配
                if ($fieldNum == 1 && count(explode(',', $argList[$i])) > 1)
                {
                    $mutiField = true;
                }
            }
        }

        if ($dataNum == 0 || $fieldNum == 0)
        {
            echo 'Error:参数不正确#1';
            return array();
        }

        if ($fieldNum != 1 && $fieldNum != $dataNum)
        {
            echo 'Error:参数不正确#2';
            return array();
        }

        
        $ret = $keyList = $newArray = array();
        for($i = 0; $i < $dataNum; $i++)
        {
            $newArray[$i] = array();
            foreach ($argList[$i] as $key => $value)
            { 
                $field = $fieldNum == 1 ? $argList[$dataNum] : $argList[$dataNum + $i];

                if (!$mutiField)
                {
                    if (isset($value[$field]))
                    {
                        $newArray[$i][$value[$field]] = $value; 
                        $keyList[] = $value[$field];
                    }
                }
                else
                {
                    $uniqueKey = array();
                    foreach (explode(',', $field) as $v)
                    {
                        $uniqueKey[] = isset($value[$v]) ? $value[$v] : '0';
                    }
                    $newArray[$i][join('-', $uniqueKey)] = $value;
                    $keyList[] = join('-', $uniqueKey);
                }
                
            }
        }
        $keyList2 = $keyList; 
        $keyList = array_unique($keyList);
        $ret = array();
        for($i = 0; $i < $dataNum; $i++)
        {   
            foreach ($keyList as $k => $vs)
            {

                $temp = array();
                if (isset($newArray[$i][$vs]))
                {
                    $temp = array_merge($newArray[$i][$vs], $temp);
                }
                
                $ret[$k] = isset($ret[$k]) ? array_merge($ret[$k], $temp) : $temp;
            }
        }
        
        return $ret;
    }


     /**
     * @desc arraySort php二维数组排序 按照指定的key 对数组进行排序
     * @param array $arr 将要排序的数组
     * @param string $keys 指定排序的key
     * @param string $type 排序类型 asc | desc
     * @return array
     */
    public static function arraySort($arr, $keys, $type = 'date', $sort = 'desc')
    {
        $keysvalue = $new_array = array();
        foreach ($arr as $k => $v)
        {
            if ($type == 'date' || $type == 'datetime')
            {
                $keysvalue[$k] = strtotime($v[$keys]);
            }
            else
            {
                $keysvalue[$k] = $v[$keys];
            }
        }
        $sort == 'asc' ? asort($keysvalue) : arsort($keysvalue);
        reset($keysvalue);
        foreach ($keysvalue as $k => $v) 
        {
           $new_array[$k] = $arr[$k];
        }
        return $new_array;
    }
}
