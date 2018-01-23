<div class="bjui-pageHeader">
    <form id="pagerForm" class="frm_ujbinsure" data-toggle="ajaxsearch" action="user/actual/actualLists?bindid={{$bindid}}" method="post">
        <input type="hidden" name="pageSize" value="${model.pageSize}">
        <input type="hidden" name="pageCurrent" value="${model.pageCurrent}">
        <div class="bjui-searchBar">
            <label>日期: </label>

            <input type="text" value="{{if isset($search['bt'])}}{{$search['bt']}}{{/if}}" size="12"  name="bt" class="form-control" placeholder="开始时间" data-toggle="datepicker">&nbsp;
            <input type="text" value="{{if isset($search['et'])}}{{$search['et']}}{{/if}}" size="12"  name="et" class="form-control" placeholder="结束时间" data-toggle="datepicker">
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
            <th>轨迹开始时间</th>
            <th>轨迹结束时间</th>
            <th>急加速(次)</th>
            <th>急减速(次）</th>
            <th>急转弯(次）</th>
            <th>最高行驶速度(Km/h)</th>
            <th>行驶里程(m)</th>
            <th>行驶时间(s)</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {{if !empty($list)}}
        {{foreach $list as $key => $v }}
        <tr>
            <td>{{$v['stime']}}</td>
            <td>{{$v['etime']}}</td>
            <td>{{$v['acce']}}</td>
            <td>{{$v['dece']}}</td>
            <td>{{$v['coce']}}</td>
            <td>{{$v['speedtop']}}</td>
            <td>{{$v['tripmile']}}</td>
            <td>{{$v['triptime']}}</td>
            <td><a href="user/actual/actualInfo?trailid={{$v['id']}}" class="btn btn-blue" data-width="980" data-height="400" data-id="actualInfo" data-toggle="dialog" data-title="行驶轨迹">详情</a></td>
        </tr>
        {{/foreach}}
        {{else}}
        <tr>
            <td colspan="8" align="center">暂无数据</td>
        </tr>
        {{/if}}
        </tbody>
    </table>
</div>
{{include file='public/page.tpl'}}