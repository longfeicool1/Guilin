<div class="bjui-pageHeader">
    <form id="pagerForm" class="frm_ujbinsure" data-toggle="ajaxsearch" action="operation/banner/allow?id={{$search['banner_id']}}" method="post">
        <input type="hidden" name="pageSize" value="${model.pageSize}">
        <input type="hidden" name="pageCurrent" value="${model.pageCurrent}">
        <div class="bjui-searchBar">
            <label>用户账号: </label>
            <input type="text" value="{{if isset($search['name'])}}{{$search['name']}}{{/if}}" name="name" class="form-control" placeholder="用户账号">&nbsp;

            <!--
            <label>展示时间: </label>
            <input type="text" value="{{if isset($search['start_time'])}}{{$search['start_time']}}{{/if}}" size="12" data-parteern="per" name="start_time" class="form-control" placeholder="开始时间" data-toggle="datepicker">&nbsp;~
            <input type="text" value="{{if isset($search['end_time'])}}{{$search['end_time']}}{{/if}}" size="12"  name="end_time" class="form-control" placeholder="结束时间" data-toggle="datepicker">
            -->
            <button type="submit" class="btn-green" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo">清空查询</a>
        </div>
    </form>
</div>

<div class="bjui-pageContent tableContent">
    <table class="table table-bordered table-hover table-striped table-top">
        <thead>
        <tr>
            <th width="10">排序</th>
            <!--<th width="70">Banner名称</th>-->
            <th width="40">用户名称</th>
            <!--<th width="50">添加时间</th>-->
        </tr>
        </thead>
        <tbody>
        {{foreach $list as $key => $v }}
        <tr data-id="{{$key}}">
            <td>{{$v['row_id']}}</td>
            <!--<td>{{$v['name']}}</td>-->
            <td>{{$v['account_login']}}</td>
            <!--<td>{{$v['created']}}</td>-->
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
{{include file='public/page.tpl'}}