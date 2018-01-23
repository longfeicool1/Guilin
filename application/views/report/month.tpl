<div class="bjui-pageHeader" style="marign:5px;">
    <form id="pagerForm" class="frm_monthList" data-toggle="ajaxsearch" action="/report/report/monthList" method="post">
        <input type="hidden" name="pageSize" value="${model.pageSize}">
        <input type="hidden" name="pageCurrent" value="${model.pageCurrent}">
        <!-- <input type="hidden" name="orderField" value="${param.orderField}">
        <input type="hidden" name="orderDirection" value="${param.orderDirection}"> -->
        <div class="bjui-searchBar">
            <select name="get_income_status" id="get_income_status" data-toggle="selectpicker">
                <option {{if empty($search['get_income_status'])}}selected{{/if}} value="">--领取状态--</option>
                <option value="1" {{if !empty($search['get_income_status']) && $search['get_income_status'] == '1'}}selected{{/if}}>未领取</option>
                <option value="2" {{if !empty($search['get_income_status']) && $search['get_income_status'] == '2'}}selected{{/if}}>已领取</option>
                <option value="3" {{if !empty($search['get_income_status']) && $search['get_income_status'] == '3'}}selected{{/if}}>已过时</option>
                <option value="4" {{if !empty($search['get_income_status']) && $search['get_income_status'] == '4'}}selected{{/if}}>无法领取</option>
            </select>
            <input data-toggle="datepicker" type="text"
                       value="{{if isset($search['bt'])}}{{$search['bt']}}{{/if}}" name="bt" autocomplete="off"
                       placeholder="日期开始"/>
            <input data-toggle="datepicker" type="text"
                   value="{{if isset($search['et'])}}{{$search['et']}}{{/if}}"
                   name="et" autocomplete="off" placeholder="日期结束"/>
            <input type="text" value="{{if !empty($search['content'])}}{{$search['content']}}{{/if}}" name="content" class="form-control" placeholder="搜索(车牌,周报标题)">&nbsp;
            <button type="submit" class="btn-green" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo">清空查询</a>
            <div class="pull-right">
                <a class="btn btn-blue" href="javascript:;" onclick="tirpExport()" target="_blank" data-icon="cloud-download">用户总行程导出</a>
            </div>
        </div>
    </form>
</div>
<div class="bjui-pageContent tableContent">
    <table class="table table-bordered table-hover table-striped table-top" data-toggle="tablefixed" data-width="100%" data-nowrap="true">
        <thead>
            <tr>
                <th width="15">序号</th>
                <th width="40">日期</th>
                <th width="30">车牌</th>
                <th width="50">月报标题</th>
                <!-- <th width="40">周评分/评比</th> -->
                <th width="30">称号列表</th>
                <th width="40">加速次数/升降</th>
                <th width="40">减速次数/升降</th>
                <th width="40">急转次数/升降</th>
                <th width="40">行程里程(m)/升降</th>
                <th width="40">行驶时间(s)/升降</th>
                <th width="40">最高速度/升降</th>
            </tr>
        </thead>
        <tbody>
            {{if $list}}
            {{foreach $list as $v}}
            <tr data-id="{{$v['id']}}">
                <td rowspan="3">{{$v['xuhao']}}</td>
                <td rowspan="3">{{$v['collect_date']}}</td>
                <td rowspan="3">{{$v['carcard']}}</td>
                <td rowspan="3">{{$v['report_title']}}</td>
                <!-- <td rowspan="3">{{$v['score']}}/{{$v['score_rate']}}</td> -->
                <td rowspan="3">
                    {{foreach $v['score_title_list'] as $vv}}
                        {{$vv}}<br/>
                    {{/foreach}}
                </td>
                <td>{{$v['week_acce']}}</td>
                <td>{{$v['week_dece']}}</td>
                <td>{{$v['week_coce']}}</td>
                <td>{{$v['trip_mile']}}</td>
                <td>{{$v['trip_time']}}</td>
                <td>{{$v['top_speed']}}</td>
            </tr>
            <tr>
                <td>{{$v['week_acce_ud']}}</td>
                <td>{{$v['week_dece_ud']}}</td>
                <td>{{$v['week_coce_ud']}}</td>
                <td>{{$v['trip_mile_ud']}}</td>
                <td>{{$v['trip_time_ud']}}</td>
                <td>{{$v['top_speed_ud']}}</td>
            </tr>
            <tr>
                <td>{{$v['week_acce_rate']}}</td>
                <td>{{$v['week_dece_rate']}}</td>
                <td>{{$v['week_coce_rate']}}</td>
                <td>{{$v['trip_mile_rate']}}</td>
                <td>{{$v['trip_time_rate']}}</td>
                <td>{{$v['top_speed_rate']}}</td>
            </tr>
            {{/foreach}}
            {{else}}
            <tr><td colspan="20" style="text-align: center;">尚未查询到任何相关数据...</td></tr>
            {{/if}}
        </tbody>
    </table>
</div>
{{include file='../public/page.tpl'}}
<script>
    $('.frm_monthList select').change(function (){
        $('.frm_monthList').submit();
    })

    function tirpExport(){
        // var str = $('.frm_weekList').serialize();
        var str = '';
        var gourl = '/report/report/reportExport?' + str;
        window.open(gourl);
    }
</script>