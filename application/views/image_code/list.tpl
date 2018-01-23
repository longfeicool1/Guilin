<div class="bjui-pageHeader">
    <a class="btn btn-default" href="common/imageCode/set" data-toggle="dialog">添加图片</a>
    <!--<form id="pagerForm" class="frm_ujbinsure" data-toggle="ajaxsearch" action="common/imageCode/lists" method="post">
        <input type="hidden" name="pageSize" value="${model.pageSize}">
        <input type="hidden" name="pageCurrent" value="${model.pageCurrent}">
        <div class="bjui-searchBar">
            <label>渠道名: </label><input type="text" value="{{if isset($search['name'])}}{{$search['name']}}{{/if}}" name="name" class="form-control" placeholder="渠道名">&nbsp;
            <label>渠道标识: </label><input type="text" value="{{if isset($search['code'])}}{{$search['code']}}{{/if}}" name="code" class="form-control" placeholder="渠道标识">&nbsp;
            <button type="submit" class="btn-green" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo">清空查询</a>
        </div>
    </form>-->
</div>

<div class="bjui-pageContent tableContent">
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true">
        <thead>
        <tr>
            <th>序</th>
            <th>图片名称</th>
            <th>图片</th>
            <th>添加时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {{foreach $list as $key => $value }}
        <tr data-id="{{$key}}">
            <td>{{$value['row_id']}}</td>
            <td>{{$value['name']}}</td>
            <td><image src="{{$value['url']}}" width="40"/></td>
            <td>{{$value['created']}}</td>
            <td>
                <a href="common/imageCode/set?id={{$value['id']}}" class="btn btn-green" data-toggle="dialog" data-id="form" >编辑</a>
                <a href="common/imageCode/delete?id={{$value['id']}}" class="btn btn-red" data-toggle="doajax" data-confirm-msg="确定要删除该行信息吗？">删</a>
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
{{include file='public/page.tpl'}}