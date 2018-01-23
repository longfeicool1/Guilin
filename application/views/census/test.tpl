<!DOCTYPE html>
<html>
<head>
    <title>测试</title>
    <script src="/static/BJUI/js/jquery-1.11.3.min.js"></script>
    <script src="/static/js/echarts/echarts.common.min.js"></script>
</head>
<body>
<div id="main" style="width: 600px;height:400px;"></div>
</body>
</html>
<script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('main'));

        // 指定图表的配置项和数据
        var option = {
    xAxis: {
        type: 'value'
    },
    yAxis: {
        type: 'value'
    },
    dataZoom: [
        {   // 这个dataZoom组件，默认控制x轴。
            type: 'slider', // 这个 dataZoom 组件是 slider 型 dataZoom 组件
            start: 10,      // 左边在 10% 的位置。
            end: 60         // 右边在 60% 的位置。
        }
    ],
    series: [
        {
            type: 'line', // 这是个『散点图』
            itemStyle: {
                normal: {
                    opacity: 0.8
                }
            },
            symbolSize: function (val) {
                return val[2] * 40;
            },
            data: [["14.616","7.241","0.896"],["3.958","5.701","0.955"],["2.768","8.971","0.669"],["9.051","9.710","0.171"],["14.046","4.182","0.536"],["12.295","1.429","0.962"],["4.417","8.167","0.113"],["0.492","4.771","0.785"],["7.632","2.605","0.645"],["14.242","5.042","0.368"]]
        }
    ]
};
//
        // var option = $.parseJSON({{$data}});

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    </script>