<div class="bjui-pageHeader">
    <p>
        <a class="btn btn-green" href="src/cityInstall/export?{{$query}}" target="_blank">导出设备</a>
    </p>
    <form id="pagerForm" class="frm_ujbinsure" data-toggle="ajaxsearch" action="src/cityInstall/lists" method="post">
        <input type="hidden" name="pageSize" value="${model.pageSize}">
        <input type="hidden" name="pageCurrent" value="${model.pageCurrent}">

        <div class="bjui-searchBar">
            <label>安装时间: </label>
            <input type="text" value="{{if isset($search['start_time'])}}{{$search['start_time']}}{{/if}}" size="12" data-parteern="per" name="start_time" class="form-control" placeholder="开始时间" data-toggle="datepicker">&nbsp;~
            <input type="text" value="{{if isset($search['end_time'])}}{{$search['end_time']}}{{/if}}" size="12"  name="end_time" class="form-control" placeholder="结束时间" data-toggle="datepicker">
            <input type="text" value="{{if isset($search['src'])}}{{$search['src']}}{{/if}}" size="15"  name="src" class="form-control" placeholder="设备ID号">&nbsp;
            <button type="submit" class="btn-green" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo">清空查询</a>
        </div>
</div>

</form>
</div>
<div class="bjui-pageContent tableContent">
    <table class="table table-bordered table-hover table-striped table-top">
        <thead>
        <tr>
            <th>序号</th>
            <th>设备ID号</th>
            <th>设备类型</th>
            <th>设备机构</th>
            <th>激活时间</th>
            <th>安装时间</th>
            <th>省份</th>
            <th>城市</th>
            <th>用户手机号</th>
            <th>用户名</th>
            <th>用户类型</th>
            <th>备注</th>
        </tr>
        </thead>
        <tbody>
        {{foreach $list as $key => $v }}
        <tr>
            <td>{{$v['xu']}}</td>
            <td>{{$v['src']}}</td>
            <td>{{if $v['org_id'] == 60}}U驾宝设备{{else}}<span class="red">非U驾宝设备</span>{{/if}}</td>
            <td>{{$v['org_name']}}</td>
            <td>{{$v['active_time']}}</td>
            <td>{{$v['install_time']}}</td>

            <td>{{$v['province']}}</td>
            <td>{{$v['city']}}</td>

            <td>{{$v['account_login']}}</td>
            <td>{{$v['username']}}</td>
            <td>{{if !empty($v['account_type'])}}{{if $v['account_type'] == 4}}阳光用户{{else}}<span class="red">非阳光用户</span>{{/if}}{{/if}}</td>

            <td>{{$v['remark']}}</td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
{{include file='public/page.tpl'}}