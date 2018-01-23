<?php
/**
 * 计算数据密钥类
 * @author  liuweilong
 * +2017-02-04 created
 **/
class Sign
{

    public function cal($secrect, $data, $signFields = [])
    {
        ksort($data);
        // 开始计算sign
        $newData = [];
        foreach ($data as $k => $v) {
            if ($k == 'sign') {
                continue;
            }

            if (empty($signFields) || in_array($k, $signFields)) {
                $newData[] = is_array($v) ? json_encode($v) : trim($v);
            }
        }
        $values = implode('', array_values($newData));
        return md5(md5($values) . $secrect);
    }

    public function valid($secrect, $data, $sign = '', $signFields = [])
    {
        $sign = trim(empty($sign) ? $data['sign'] : $sign);

        $newSign = $this->cal($secrect, $data, $signFields);
        if (strcasecmp($sign, $newSign) != 0) {
            return false;
        }

        return true;
    }
}
