<style>
    .appMap{
        float: left;
        border:1px solid #CCCCCC;
        margin:5px;
    }
</style>
<div class="bjui-pageContent">
    <div class="appMap" id="system" style="width: 30%;height:400px;"></div>
    <div class="appMap" id="channel" style="width: 30%;height:400px;"></div>
    <div class="appMap" id="systemVersion" style="width: 30%;height:400px;"></div>
    <div class="appMap" id="appVersion" style="width: 30%;height:400px;"></div>
    <div class="appMap" id="device" style="width: 30%;height:400px;"></div>
</div>

<!-- 为ECharts准备一个具备大小（宽高）的Dom -->

<script type="text/javascript">
    // 安装系统统计
    var myChart = echarts.init(document.getElementById('system'));
    var option = {
        title: {
            text: '安装系统',
            x: 'left'
        },
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            x: 'left',
            // data:['直达','营销广告','搜索引擎','邮件营销','联盟广告','视频广告','百度','谷歌','必应','其他']
        },
        series: [
            {
                name: '系统',
                type: 'pie',
                selectedMode: 'single',
                radius: [0, '70%'],
                label: {
                    normal: {
                        position: 'inner',
                        formatter: '{b}：{c}\n  {d}%  ',
                    }
                },
                labelLine: {
                    normal: {
                        show: false
                    }
                },
                data: [
                    {{foreach $systemTotal as $k => $v}}
                    {value:{{$v['num']}}, name:'{{$v['name']}}'},
                    {{/foreach}}
                ]
            }
        ]
    };
    myChart.setOption(option);

    // 下载渠道
    var myChart = echarts.init(document.getElementById('channel'));
    var option = {
        title: {
            text: '下载渠道',
            x: 'left'
        },
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            x: 'left',
        },
        series: [
            {
                name: '渠道',
                type: 'pie',
                radius: '70%',
                label: {
                    normal: {
                        formatter: '{b}：{c}\n  {d}%',
                        backgroundColor: '#eee',
                        borderColor: '#aaa',
                        borderWidth: 1,
                        borderRadius: 4,
                        rich: {
                            a: {
                                color: '#999',
                                lineHeight: 22,
                                align: 'center'
                            },
                            hr: {
                                borderColor: '#aaa',
                                width: '100%',
                                borderWidth: 0.5,
                                height: 0
                            },
                            b: {
                                fontSize: 16,
                                lineHeight: 33
                            },
                            per: {
                                color: '#eee',
                                backgroundColor: '#334455',
                                padding: [2, 4],
                                borderRadius: 2
                            }
                        }
                    }
                },
                data: [
                    {{foreach $channelTotal as $k => $v}}
                    {value:{{$v['num']}}, name:'{{$v['name']}}'},
                    {{/foreach}}
                ],
            }
        ]
    };
    myChart.setOption(option);

    // 手机系统版本
    var myChart = echarts.init(document.getElementById('systemVersion'));
    var option = {
        title: {
            text: '手机系统版本',
            x: 'left'
        },
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            x: 'left',
        },
        series: [
            {
                name: '版本',
                type: 'pie',
                radius: '70%',
                label: {
                    normal: {
                        formatter: '{b}：{c}\n  {d}%',
                        backgroundColor: '#eee',
                        borderColor: '#aaa',
                        borderWidth: 1,
                        borderRadius: 4,
                        rich: {
                            a: {
                                color: '#999',
                                lineHeight: 22,
                                align: 'center'
                            },
                            hr: {
                                borderColor: '#aaa',
                                width: '100%',
                                borderWidth: 0.5,
                                height: 0
                            },
                            b: {
                                fontSize: 16,
                                lineHeight: 33
                            },
                            per: {
                                color: '#eee',
                                backgroundColor: '#334455',
                                padding: [2, 4],
                                borderRadius: 2
                            }
                        }
                    }
                },
                data: [
                    {{foreach $systemVersionTotal as $k => $v}}
                    {value:{{$v['num']}}, name:'{{$v['name']}}'},
                    {{/foreach}}
                ],
            }
        ]
    };
    myChart.setOption(option);

    // 安装包版本
    var myChart = echarts.init(document.getElementById('appVersion'));
    var option = {
        title: {
            text: '安装包版本',
            x: 'left'
        },
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            x: 'left',
        },
        series: [
            {
                name: '版本',
                type: 'pie',
                radius: '70%',
                label: {
                    normal: {
                        formatter: '{b}：{c}\n  {d}%',
                        backgroundColor: '#eee',
                        borderColor: '#aaa',
                        borderWidth: 1,
                        borderRadius: 4,
                        rich: {
                            a: {
                                color: '#999',
                                lineHeight: 22,
                                align: 'center'
                            },
                            hr: {
                                borderColor: '#aaa',
                                width: '100%',
                                borderWidth: 0.5,
                                height: 0
                            },
                            b: {
                                fontSize: 16,
                                lineHeight: 33
                            },
                            per: {
                                color: '#eee',
                                backgroundColor: '#334455',
                                padding: [2, 4],
                                borderRadius: 2
                            }
                        }
                    }
                },
                data: [
                    {{foreach $appVersionTotal as $k => $v}}
                    {value:{{$v['num']}}, name:'{{$v['name']}}'},
                    {{/foreach}}
                ],
            }
        ]
    };
    myChart.setOption(option);

    // 品牌设备
    var myChart = echarts.init(document.getElementById('device'));
    var option = {
        title: {
            text: '设备品牌',
            subtext:"少于5归于其他",
            x: 'left'
        },
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            x: 'left',
        },
        series: [
            {
                name: '品牌',
                type: 'pie',
                radius: '70%',
                label: {
                    normal: {
                        formatter: '{b}：{c}\n  {d}%',
                        backgroundColor: '#eee',
                        borderColor: '#aaa',
                        borderWidth: 1,
                        borderRadius: 4,
                        rich: {
                            a: {
                                color: '#999',
                                lineHeight: 22,
                                align: 'center'
                            },
                            hr: {
                                borderColor: '#aaa',
                                width: '100%',
                                borderWidth: 0.5,
                                height: 0
                            },
                            b: {
                                fontSize: 16,
                                lineHeight: 33
                            },
                            per: {
                                color: '#eee',
                                backgroundColor: '#334455',
                                padding: [2, 4],
                                borderRadius: 2
                            }
                        }
                    }
                },
                data: [
                    {{foreach $deviceTotal as $k => $v}}
                    {value:{{$v['num']}}, name:'{{$v['name']}}'},
                    {{/foreach}}
                ],
            }
        ]
    };
    myChart.setOption(option);

</script>
<script>


</script>