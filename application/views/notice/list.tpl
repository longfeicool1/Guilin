<div class="bjui-pageHeader">
    <p>
        <a class="btn btn-default" href="notice/notice/setImage" data-toggle="navtab"  data-id="notice-add" data-title="新建图文消息" >新建图文</a>&nbsp;
        <a class="btn btn-default" href="notice/notice/setTxt" data-toggle="navtab"  data-id="notice-add" data-title="新建文本消息" >新建文本</a>
    </p>
    <form id="pagerForm" class="frm_ujbinsure" data-toggle="ajaxsearch" action="notice/notice/lists" method="post">
        <input type="hidden" name="pageSize" value="${model.pageSize}">
        <input type="hidden" name="pageCurrent" value="${model.pageCurrent}">
        <div class="bjui-searchBar">

            <label>内容类型: </label>
            <select name="type_status" data-toggle="selectpicker">
                <option value="">-全部-</option>
                <option value="2" {{if isset($search['type_status']) && $search['type_status'] == 2}}selected{{/if}}>图文</option>
                <option value="3" {{if isset($search['type_status']) && $search['type_status'] == 3}}selected{{/if}}>文本</option>
            </select>
            <label>名称: </label>
            <input type="text" value="{{if isset($search['name'])}}{{$search['name']}}{{/if}}" name="name" class="form-control" placeholder="名称">&nbsp;

            <label>推送时间: </label>
            <input type="text" value="{{if isset($search['created_start'])}}{{$search['created_start']}}{{/if}}" size="12" data-parteern="per" name="created_start" class="form-control" placeholder="开始时间" data-toggle="datepicker">&nbsp;~
            <input type="text" value="{{if isset($search['created_end'])}}{{$search['created_end']}}{{/if}}" size="12" data-parteern="per" name="created_end" class="form-control" placeholder="结束时间" data-toggle="datepicker">&nbsp;

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
            <th width="40">名称</th>
            <th width="50">内容</th>
            <th width="20">内容类型</th>
            <th width="20">推送数</th>
            <th width="20">阅读数</th>
            <th width="20">推送时间</th>
            <th width="20">操作</th>
        </tr>
        </thead>
        <tbody>
        {{foreach $list as $key => $v }}
        <tr data-id="{{$key}}">
            <td>{{$v['row_id']}}</td>
            <td>{{$v['name']}}</td>
            <td>{{if $v['type'] == 2}}<image src="{{$v['image']}}" height="40"/>{{elseif  $v['type'] == 3}}{{$v['sub_txt']}}{{/if}}</td>
            <td>{{if $v['type'] == 2}}图文消息{{elseif  $v['type'] == 3}}文本{{/if}}</td>
            <td>{{$v['to_num']}}</td>
            <td>{{$v['read_num']}}</td>
            <td>{{$v['created']}}</td>
            <td><a href="notice/notice/info?id={{$v['id']}}" data-toggle="dialog" data-width="700" data-height="500" class="btn btn-blue">详情</a></td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
{{include file='public/page.tpl'}}