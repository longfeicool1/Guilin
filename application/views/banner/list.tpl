<div class="bjui-pageHeader">
    <a class="btn btn-default" href="operation/banner/set"  data-toggle="navtab"  data-id="form" data-width="600" data-height="400">新增</a>
    <form id="pagerForm" class="frm_ujbinsure" data-toggle="ajaxsearch" action="operation/banner/lists" method="post">
        <input type="hidden" name="pageSize" value="${model.pageSize}">
        <input type="hidden" name="pageCurrent" value="${model.pageCurrent}">
        <div class="bjui-searchBar">

            <label>状态: </label>
            <select name="status" data-toggle="selectpicker">
                <option value="">-全部-</option>
                <option value="1" {{if isset($search['status']) && $search['status'] == 1}}selected{{/if}}>上架</option>
                <option value="2" {{if isset($search['status']) && $search['status'] == 2}}selected{{/if}}>下架</option>
            </select>
            <label>名称: </label>
            <input type="text" value="{{if isset($search['name'])}}{{$search['name']}}{{/if}}" name="name" class="form-control" placeholder="名称">&nbsp;

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
            <th width="70">名称</th>
            <th width="40">图片</th>
            <th width="50">链接</th>
            <th width="30">开始时间</th>
            <th width="30">结束时间</th>
            <th width="30">展示状态</th>
            <th width="30">用户限制</th>
            <th width="20">状态</th>
            <th width="30">操作</th>
        </tr>
        </thead>
        <tbody>
        {{foreach $list as $key => $v }}
        <tr data-id="{{$key}}">
            <td>{{$v['row_id']}}</td>
            <td>{{$v['name']}}</td>
            <td><image src="{{$v['android_img']}}" height="40"/></td>
            <td><a href="{{$v['url']}}" target="_blank">{{$v['url']}}</a></td>
            <td>{{$v['start_time']}}</td>
            <td>{{$v['end_time']}}</td>
            <td>
                {{if $v['show_status'] == 1}}
                    <span class="green">展示</span>
                {{else}}
                    <span style="color: #aaaaaa;">不展示</span>
                    {{if $v['show_txt_status'] == 1}}<span style="color:forestgreen"> - 未开始</span> {{elseif $v['show_txt_status'] == 2}} <span> - 已下架</span>{{else}} <span style="color:#aaaaaa"> - 已结束</span>{{/if}}

                {{/if}}
                </td>
            <td>{{$isLimit[$v['is_limit']]}}{{if $v['is_limit'] ==2}}
                <a href="operation/banner/allow?id={{$v['id']}}" data-toggle="dialog" data-id="limitList" data-width="500" data-height="600" >[名单]</a>{{/if}}
            </td>
            <td>{{if $v['status'] == 1}}上架{{else}}下架{{/if}}</td>
            <td>
                {{if $v['status'] == 1}}
                <a href="operation/banner/line?id={{$v['id']}}&status=2" class="btn btn-red" data-toggle="doajax">下架</a>
                {{else}}
                <a href="operation/banner/line?id={{$v['id']}}&status=1" class="btn btn-blue" data-toggle="doajax">上架</a>
                {{/if}}
                <a href="operation/banner/set?id={{$v['id']}}" class="btn btn-green" data-toggle="navtab" data-id="form" data-width="600" data-height="500" >编辑</a>
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
{{include file='public/page.tpl'}}