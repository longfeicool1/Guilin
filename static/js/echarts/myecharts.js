function createChart($id,$url)
{
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById($id));
    // 指定图表的配置项和数据
    $.get($url, function(chartData){
        myChart.setOption(chartData)
    }, 'json')
}