<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Actual_model extends MY_Model
{

    public $key        = '03405362d8fb082746ecdd31d752368c';
    public $batch      = 'true';
    public $radius     = '100';
    public $output     = 'JSON';
    public $extensions = 'base';
    public $roadlevel  = 0;
    public $poitype    = '';

    public function lists($page,$size,$condition = [])
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $offset = ($page - 1) * $size;
        $result = $this->db
            ->select("id,FROM_UNIXTIME(stime,'%Y-%m-%d %H:%i:%s') as stime,FROM_UNIXTIME(etime,'%Y-%m-%d %H:%i:%s') as etime,acce,coce,dece,speedtop,tripmile,(etime-stime) AS triptime")
            ->limit($size,$offset)
            ->order_by('etime desc')
            ->get('ygbx_app.pm_single_actual')
            ->result_array();
        $n = 0;
        foreach ($result as $k => $v) {
            $n++;
            $result[$k]['xuhao']          = $n;
        }
        // echo '<pre>';print_r($this->db->last_query());die;
        return $result;
    }

    public function actualCount($condition = [])
    {
        if ($condition) {
            foreach ($condition as $k=>$v) {
                $this->db->where([$k => $v]);
            }
        }
        $count = $this->db
            ->count_all_results('ygbx_app.pm_single_actual');
        return $count;
    }

    public function info($trailid)
    {
        $result = $this->db
        ->select("id,src,trip_id,FROM_UNIXTIME(stime,'%Y-%m-%d %H:%i:%s') as stime,FROM_UNIXTIME(etime,'%Y-%m-%d %H:%i:%s') as etime,acce,coce,dece,speedtop,ROUND(tripmile/1000,2) AS tripmile,(etime-stime) AS triptime")
        ->get_where('ygbx_app.pm_single_actual',['id' => $trailid])->row_array();
        return $result;
    }

    public function brakes($tripid,$src)
    {
        $result = $this->db
            ->get_where('ygbx_app.pm_single_brake',['trip_id' => $tripid,'src' => $src])->result_array();
        $newResult = [];
        $keys      = [
            1 => 'acce',
            2 => 'dece',
            3 => 'coce',
        ];
        $data = [
            'key'        => $this->key,
            'location'   => '',
            'batch'      => $this->batch,
            'radius'     => $this->radius,
            'output'     => $this->output,
            'extensions' => $this->extensions,
            'roadlevel'  => $this->roadlevel,
            'poitype'    => $this->poitype,
        ];
        $brake = [];
        foreach ($result as $v) {
            $data['location'] = "{$v['longitude']},{$v['latitude']}";
            $uri      = 'http://restapi.amap.com/v3/geocode/regeo?'.http_build_query($data);
            $roadInfo = Requests::get($uri);
            if($roadInfo->status_code == 200) {
                $roadInfo = json_decode($roadInfo->body,true);
                if ($roadInfo['status'] == 1) {
                    $regeocodes = $roadInfo['regeocodes'][0];
                    $province   = !empty($regeocodes['addressComponent']['province']) ? $regeocodes['addressComponent']['province'] : '';
                    $city       = !empty($regeocodes['addressComponent']['city']) ? $regeocodes['addressComponent']['city'] : '';
                    $search     = $province . $city;
                    // echo '<pre>';print_r($regeocodes);die;
                    $address    = str_replace($search,'',$regeocodes['formatted_address']);
                }
            }
            $newResult[$keys[$v['type']]][] = [
                'fomatRtc' => date('H:i:s',$v['rtc']),
                'address'  => !empty($address) ? $address : '',
            ];
        }
        // echo '<pre>';print_r($newResult);die;
        return $newResult;
    }
}