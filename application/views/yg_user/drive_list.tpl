<div class="bjui-pageHeader">
    <form id="pagerForm" class="frm_ujbinsure" data-toggle="ajaxsearch" action="user/yg/driveLists?bind_id={{$bind_id}}" method="post">
        <input type="hidden" name="pageSize" value="${model.pageSize}">
        <input type="hidden" name="pageCurrent" value="${model.pageCurrent}">
        <div class="bjui-searchBar">
            <label>日期: </label>

            <input type="text" value="{{if isset($search['start_time'])}}{{$search['start_time']}}{{/if}}" size="12"  name="start_time" class="form-control" placeholder="开始时间" data-toggle="datepicker">&nbsp;
            <input type="text" value="{{if isset($search['end_time'])}}{{$search['end_time']}}{{/if}}" size="12"  name="end_time" class="form-control" placeholder="结束时间" data-toggle="datepicker">


            <button type="submit" class="btn-green" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo">清空查询</a>
        </div>
</div>

</form>
</div>
<div class="bjui-pageContent tableContent">
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true">
        <thead>
        <tr>
            <th width="20">日期</th>
            <th width="20">驾驶得分</th>
            <th width="20">急加速(次)</th>
            <th width="20">急减速(次）</th>
            <th width="20">最高行驶速度(Km/h)</th>
            <th width="20">行驶里程(Km)</th>
            <th width="30">行驶时间</th>
            <th width="40">平均行驶速度(Km/h)</th>
        </tr>
        </thead>
        <tbody>
        {{foreach $list as $key => $v }}
        <tr data-id="{{$key}}">
            <td>{{$v['collect_date']}}</td>
            <td>{{$v['score']}}</td>
            <td>{{$v['acce']}}</td>
            <td>{{$v['dece']}}</td>
            <td>{{$v['topSpeed']}}</td>
            <td>{{$v['totalMile']}}</td>
            <td>{{$v['driveTime']}}</td>
            <td>{{$v['avgSpeed']}}</td>
        </tr>
        {{foreachelse}}
        <tr>
            <td colspan="8" align="center">暂无数据</td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
{{include file='public/page.tpl'}}