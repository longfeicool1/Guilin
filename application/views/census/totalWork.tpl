<style type="text/css">
.font{font-size: 16px;font-weight: 700}
</style>
<div class="bjui-pageContent">
    <fieldset>
        <legend>安装统计</legend>
        <div class="bjui-pageHeader">
            <form id="pagerForm" class="frm_install" data-toggle="ajaxsearch" action="/census/census/totalWork" method="post">
                <input type="hidden" name="pageSize" value="${model.pageSize}">
                <input type="hidden" name="pageCurrent" value="${model.pageCurrent}">
                <!-- <input type="hidden" name="orderField" value="${param.orderField}">
                <input type="hidden" name="orderDirection" value="${param.orderDirection}"> -->
                <div class="bjui-searchBar">
                    <input data-toggle="datepicker" type="text"
                               value="{{if isset($search['bt'])}}{{$search['bt']}}{{/if}}" name="bt" autocomplete="off"
                               placeholder="统计时间开始"/>
                    <input data-toggle="datepicker" type="text"
                           value="{{if isset($search['et'])}}{{$search['et']}}{{/if}}"
                           name="et" autocomplete="off" placeholder="统计时间结束"/>
                    <button type="submit" class="btn-green" data-icon="search">查询</button>&nbsp;
                    <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo">清空查询</a>
                    <div class="pull-right">
                        <!-- <a class="btn btn-blue" href="javascript:;" onclick="rewardListExport()" target="_blank" data-icon="cloud-download">导出</a> -->
                        <p style="margin-top: 5px">
                            <span class="font">设备激活数：</span>
                            <span class="font" style="color: green">{{$num['sumActiveNum']}}</span>
                            <span class="font">设备安装数：</span>
                            <span class="font" style="color: green">{{$num['sumWorkNum']}}</span>
                        </p>
                    </div>
                </div>
            </form>
        </div>
        <!-- Tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a href="#install_table" role="tab" data-toggle="tab" data-target="#install_table">图表</a></li>
            <li><a href="#install_data" role="tab" data-toggle="tab">数据</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane fade active in" id="install_table">
                <div id="mainChart" style="width: 100%;height:400px;"></div>
                <!-- <div style="mini-width:400px;height:350px" data-toggle="echarts" data-type="bar,line" data-url="/census/table/workTable"></div> -->
            </div>
            <div class="tab-pane fade" id="install_data">

                <!-- <div class="bjui-pageContent tableContent"> -->
                    <table class="table table-bordered table-hover table-striped table-top" data-toggle="tablefixed" data-width="100%" data-nowrap="true">
                        <thead>
                            <tr>
                                <th width="9">序号</th>
                                <th width="30">时间</th>
                                <th width="20">设备激活数(台)</th>
                                <th width="50">设备安装数(台)</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{if $list}}
                            {{foreach $list as $v}}
                            <tr data-id="{{$v['id']}}">
                                <td>{{$v['xuhao']}}</td>
                                <td>{{$v['collect_date']}}</td>
                                <td>{{$v['active_num']}}</td>
                                <td>{{$v['work_num']}}</td>
                            </tr>
                            {{/foreach}}
                            {{else}}
                            <tr><td colspan="20" style="text-align: center;">尚未查询到任何相关数据...</td></tr>
                            {{/if}}
                        </tbody>
                    </table>
                    {{include file='../public/page.tpl'}}
                <!-- </div> -->
            </div>
        </div>
    </fieldset>
</div>
<script>
    createChart('mainChart','/census/table/workTable?bt={{$search['bt']}}&et={{$search['et']}}');
    // 基于准备好的dom，初始化echarts实例
    // var myChart = echarts.init(document.getElementById('mainChart'));

    // // 指定图表的配置项和数据
    // // var option = {
    // $.get('/census/table/workTable', function(chartData){
    //     myChart.setOption(chartData)
    // }, 'json')
    // myChart.setOption(option);
    </script>