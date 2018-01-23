<div class="bjui-pageHeader">
    <form id="pagerForm" class="frm_ujbinsure" data-toggle="ajaxsearch" action="user/yg/walletTask?uid={{$uid}}&bid={{$bid}}" method="post">
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
            <th width="30">时间</th>
            <th width="30">类型</th>
            <th width="30">金额</th>
            <th width="30">账户余额</th>
        </tr>
        </thead>
        <tbody>
        {{foreach $list as $key => $v }}
        <tr data-id="{{$key}}">
            <td>{{$v['created']}}</td>
            <td>{{$v['remark']}}</td>
            <td>{{if $v['action'] == 1}}-{{else}}+{{/if}}{{$v['amount']}}</td>
            <td>{{if $v['action'] == 1}}{{$v['history_amount'] - $v['amount']}}{{else}}{{$v['history_amount'] + $v['amount']}}{{/if}}</td>
        </tr>
        {{foreachelse}}
        <tr>
            <td colspan="4" align="center">暂无数据</td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
{{include file='public/page.tpl'}}