<div class="bjui-pageContent">

    <fieldset>
        <legend>地区报表下载</legend>
        <div style="marign:5px;">
            <a class="btn btn-blue" href="/census/cityReport/installUse" target="_blank" data-icon="download">日安装报表</a>
            <a class="btn btn-green" href="/census/cityReport/installTotal" target="_blank" data-icon="download">月安装报表</a>
            <a class="btn btn-orange" href="/census/cityReport/onlineChar"  target="_blank" data-icon="download">省份在线走势图</a>
            <span id="myLoadDiv1"></span>
        </div>

    </fieldset>
    <fieldset>
        <legend>安装统计</legend>
        <div class="bjui-pageHeader" style="marign:5px;">
            <form id="pagerForm" class="frm_online" data-toggle="ajaxsearch" action="/census/census/onlineTable" method="post">
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
                    <!-- <input type="text" value="{{if !empty($search['content'])}}{{$search['content']}}{{/if}}" name="content" class="form-control" placeholder="搜索(车主、手机、车牌)">&nbsp; -->
                    <button type="submit" class="btn-green" data-icon="search">查询</button>&nbsp;
                    <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo">清空查询</a>
                    <div class="pull-right">
                        <!-- <a class="btn btn-blue" href="javascript:;" onclick="rewardListExport()" target="_blank" data-icon="cloud-download">导出</a> -->
                    </div>
                </div>
            </form>
        </div>
        <!-- Tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a href="#online_table" role="tab" data-toggle="tab" data-target="#online_table">图表</a></li>
            <li><a href="#online_data" role="tab" data-toggle="tab">数据</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane fade active in" id="online_table">
                <div id="onlineTable" style="width: 100%;height:400px;"></div>
                <!-- <div style="mini-width:400px;height:350px" data-toggle="echarts" data-type="bar,line" data-url="/census/table/workTable"></div> -->

            </div>
            <div class="tab-pane fade" id="online_data">

                <!-- <div class="bjui-pageContent tableContent"> -->
                    <table class="table table-bordered table-hover table-striped table-top" data-toggle="tablefixed" data-width="100%" data-nowrap="true">
                        <thead>
                            <tr>
                                <th width="9">序号</th>
                                <th width="30">时间</th>
                                <th width="20">在线设备数量(台)</th>
                                <th width="50">设备安装总量(台)</th>
                                <th width="50">在线率</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{if $list}}
                            {{foreach $list as $v}}
                            <tr data-id="{{$v['id']}}">
                                <td>{{$v['xuhao']}}</td>
                                <td>{{$v['total_time']}}</td>
                                <td>{{$v['online']}}</td>
                                <td>{{$v['total_obd']}}</td>
                                <td>{{$v['percent']}}</td>
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
    createChart('onlineTable','/census/table/onlineTable?bt={{$search['bt']}}&et={{$search['et']}}');
</script>