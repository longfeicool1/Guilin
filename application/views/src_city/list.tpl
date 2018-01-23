<div class="bjui-pageHeader">
    <p>
        <a class="btn btn-green" href="src/city/export?{{$query}}" target="_blank">导出设备</a>
        <a href="src/city/edit" class="btn btn-default" data-toggle="dialog" data-width="500" data-height="300">添加城市设备</a>
    </p>
    <form id="pagerForm" class="frm_ujbinsure" data-toggle="ajaxsearch" action="src/city/lists" method="post">
        <input type="hidden" name="pageSize" value="${model.pageSize}">
        <input type="hidden" name="pageCurrent" value="${model.pageCurrent}">

        <div class="bjui-searchBar">
            <select class="form-control"  name="org_id">
                <option value="0" >-设备类型-</option>
                <option value="1" {{if isset($search['org_id']) && $search['org_id'] == 1}}selected{{/if}}>U驾宝设备</option>
                <option value="2" {{if isset($search['org_id']) && $search['org_id'] == 2}}selected{{/if}}>非U驾宝设备</option>
            </select>
            <select class="form-control"  name="account_type">
                <option value="0" >-用户类型-</option>
                <option value="1" {{if isset($search['account_type']) && $search['account_type'] == 1}}selected{{/if}}>阳光用户</option>
                <option value="2" {{if isset($search['account_type']) && $search['account_type'] == 2}}selected{{/if}}>非阳光用户</option>
            </select>
            <select class="form-control"  name="srcStatus">
                <option value="0" >-使用状态-</option>
                <option value="1" {{if isset($search['srcStatus']) && $search['srcStatus'] == 1}}selected{{/if}}>正使用</option>
                <option value="2" {{if isset($search['srcStatus']) && $search['srcStatus'] == 2}}selected{{/if}}>未使用</option>
                <option value="3" {{if isset($search['srcStatus']) && $search['srcStatus'] == 3}}selected{{/if}}>已停用</option>
            </select>
            <select class="form-control"  name="is_test">
                <option value="0" >-使用类型-</option>
                <option value="1" {{if isset($search['is_test']) && $search['is_test'] == 1}}selected{{/if}}>测试设备</option>
                <option value="2" {{if isset($search['is_test']) && $search['is_test'] == 2}}selected{{/if}}>正式设备</option>
            </select>
            <input type="text" value="{{if isset($search['src'])}}{{$search['src']}}{{/if}}" size="15"  name="src" class="form-control" placeholder="设备ID号">&nbsp;
            <input type="text" value="{{if isset($search['province'])}}{{$search['province']}}{{/if}}" size="12" name="province" class="form-control" placeholder="省份">&nbsp;
            <input type="text" value="{{if isset($search['city'])}}{{$search['city']}}{{/if}}" size="12" name="city" class="form-control" placeholder="城市">
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
            <th>省份</th>
            <th>城市</th>
            <th>用户手机号</th>
            <th>用户名</th>
            <th>用户类型</th>
            <th>激活时间</th>
            <th>使用状态</th>
            <th>测试设备</th>
            <th>备注</th>
            <th>添加时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {{foreach $list as $key => $v }}
        <tr>
            <td>{{$v['xu']}}</td>
            <td>{{$v['src']}}</td>
            <td>{{if $v['org_id'] == 60}}U驾宝设备{{else}}<span class="red">非U驾宝设备</span>{{/if}}</td>
            <td>{{$v['org_name']}}</td>
            <td>{{$v['province']}}</td>
            <td>{{$v['city']}}</td>
            <td>{{$v['account_login']}}</td>
            <td>{{$v['username']}}</td>
            <td>{{if !empty($v['account_type'])}}{{if $v['account_type'] == 4}}阳光用户{{else}}<span class="red">非阳光用户</span>{{/if}}{{/if}}</td>
            <td>{{$v['active_time']}}</td>
            <td>{{$v['status_abbr']}}</td>
            <td>{{if $v['is_test'] == 2}}不是{{else}}<span class="red">是</span>{{/if}}</td>
            <td>{{$v['remark']}}</td>
            <td>{{$v['cTime']}}</td>
            <td>
                <a href="src/city/edit?id={{$v['id']}}" class="btn btn-default" data-toggle="dialog" data-width="500" data-height="300">修改城市</a>
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
{{include file='public/page.tpl'}}