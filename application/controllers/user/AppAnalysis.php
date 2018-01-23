<?php

/**
 * Created by PhpStorm.
 * User: Yijw
 * Date: 2017-12-28
 * Time: 9:51
 */
class AppAnalysis extends MY_Controller
{
    public $system = [
        'iOS' => '苹果',
        'android' => '安卓',
        '' => '其他'
    ];

    public $channel = [
        'xiaomi' => '小米',
        '360' => '360',
        'appStore' => 'app store',
        'baidu' => '百度',
        'huawei' => '华为',
        'meizu' => '魅族',
        'wandoujia' => '豌豆夹',
        'website' => '官网',
        'yingyongbao' => '应用宝',
        '' => '其他',
    ];

    public $device = [
        'iPhone' => ['iphone', 'ipad'], // 苹果
        'HuaWei' => [
            'mha', 'huawei', 'eva', 'frd', 'bln', 'vtr', 'vky', 'stf', 'pra', 'plk', 'vie', 'knt', 'kiw',
            'pe', 'che2', 'nem', 'was', 'chm', 'trt', 'bac', 'dig', 'h60', 'lon', 'ath', 'che1', 'cun',
            'pic', 'cam', 'che', 'nce', 'scl', 'ale', 'bla', 'bnd', 'edi', 'g621', 'gem', 'jmm', 'mediapad',
            'nts', 'rne', 'sla'
        ], // 华为
        'OPPO' => ['oppo', 'x9007', 'r7plus', 'r7c', 'r7plusm', 'n5207', 'r7007', 'r8007', 'a31', 'a31c',
            'n1t', 'r7t'
        ], // oppo
        'vivo' => ['vivo'],         // vivo
        'Mi' => ['mi', 'redmi', 'mix', 'hm', '2014501'],
        'SanXing' => ['sm', 'gt'],        // 三星
        'LeShi' => ['le', 'letv', 'lex720', 'x600', 'x800', 'x900', 'x900+'],          // 乐视
        'MeiZu' => [
            'mx5', 'm5', 'm3', 'mx4', 'm621c', 'pro', 'm1', 'm2', 'm571c', 'm578c', 'm685c', 'mx6',
            'u20'
        ],         // 魅族
        'JinLi' => [
            'gn5001s', 'gn3003', 'gn5001', 'gn8001', 'gn8003', 'gn9011', 'f100', 'f105', 'f106', 'F5',
            'gn5003', 'gn8002s', 'm2017', 'v183', 'w909'
        ], // 金力
        'ZTE' => ['zte'],   // 中兴
        'YiJia' => ['oneplus'],
        'LianXiang' => ['zuk', 'lenovo'], // 联想
        '360' => ['1505', '1605', '1503'],  // 360
        'HaiXin' => ['hisense'],             // 海信
        'MeiTu' => ['mp1503', 'mp1512'],               // 美图
        'FeiXun' => ['c1330'],               // 斐讯
        'coolpad' => ['coolpad'],           // 酷派
        'NuBiYa' => ['NX549J', 'nx563j', 'nx510j', 'nx535j'],            // 努比亚
        'YuFeiLai' => ['yu'],              // 宇飞来
        'DuoWei' => ['doov'],              // 朵唯
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('YgUser_model', 'UserModel');
    }

    public function index()
    {

        $systemTotal = $this->UserModel->systemTotal();
        foreach ($systemTotal as $k => $v) {
            $systemTotal[$k]['name'] = $this->system[$v['systemName']];
        }

        $channelTotal = $this->UserModel->channelTotal();
        foreach ($channelTotal as $k => $v) {
            $channelTotal[$k]['name'] = $this->channel[$v['channel']];
        }

        $systemVersionTotal = $this->UserModel->systemVersionTotal();
        foreach ($systemVersionTotal as $k => $v) {
            $systemVersionTotal[$k]['name'] = $this->system[$v['systemName']] . ' ' . $v['systemVersion'];
        }

        $appVersionTotal = $this->UserModel->appVersionTotal();
        foreach ($appVersionTotal as $k => $v) {
            $appVersionTotal[$k]['name'] = $this->system[$v['systemName']] . ' ' . $v['version'];
        }

        $deviceTotal = $this->UserModel->deviceTotal();
//print_r($deviceTotal);exit;
        $deviceTmpTotal = [
            'other' => [
                'name' => 'other',
                'num' =>0
            ]
        ];
        foreach ($deviceTotal as $k => $v) {
            $tmpIndex = 0;
            if (!empty($v['deviceName']) && $v['num'] > 5) {
                foreach ($this->device as $key => $value) {
                    if (in_array($v['deviceName'], $value)) {
                        $tmpIndex = 1;

                        if (isset($deviceTmpTotal["{$key}"])) {
                            $deviceTmpTotal["{$key}"]['num'] += $v['num'];
                            $deviceTmpTotal["{$key}"]['name'] = $key;
                        } else {
                            $deviceTmpTotal["{$key}"] =  [
                                'name' => $key,
                                'num' => $v['num'],
                            ];
                        }
                    }
                }
            }

            if ($tmpIndex != 1) {
                $deviceTmpTotal['other']['num'] += $v['num'];
            }
        }

        $this->assign('deviceTotal', $deviceTmpTotal);
        $this->assign('appVersionTotal', $appVersionTotal);
        $this->assign('systemVersionTotal', $systemVersionTotal);
        $this->assign('systemTotal', $systemTotal);
        $this->assign('channelTotal', $channelTotal);
        $this->display('yg_user/appAnalysis.tpl');
    }

}