<div class="bjui-pageHeader">
    <form id="pagerForm" class="frm_ujbinsure" data-toggle="ajaxsearch" action="operation/game" method="post">
        <input type="hidden" name="pageSize" value="${model.pageSize}">
        <input type="hidden" name="pageCurrent" value="${model.pageCurrent}">
        <div class="bjui-searchBar">
            <a data-toggle="dialog" data-width="600" data-height="600" href="operation/game/detail?id=7" >游戏报告</a>&nbsp;
            <label>状态: </label>
            <select name="ok_status" data-toggle="selectpicker">
                <option value="">-全部-</option>
                <option value="1" {{if isset($search['ok_status']) && $search['ok_status'] == 1}}selected{{/if}}>未完成</option>
                <option value="2" {{if isset($search['ok_status']) && $search['ok_status'] == 2}}selected{{/if}}>完成</option>
            </select>
            <label>称谓: </label>
            <input type="text" value="{{if isset($search['title'])}}{{$search['title']}}{{/if}}" name="title" class="form-control" placeholder="称谓"  size="12" >&nbsp;
            <label>车牌：</label>
            <input type="text" value="{{if isset($search['car_number'])}}{{$search['car_number']}}{{/if}}" name="car_number" class="form-control" placeholder="车牌"  size="12" >&nbsp;


            <label>游玩日期: </label>
            <input type="text" value="{{if isset($search['start_time'])}}{{$search['start_time']}}{{/if}}" size="12" data-parteern="per" name="start_time" class="form-control" placeholder="开始时间" data-toggle="datepicker">&nbsp;~
            <input type="text" value="{{if isset($search['end_time'])}}{{$search['end_time']}}{{/if}}" size="12"  name="end_time" class="form-control" placeholder="结束时间" data-toggle="datepicker">


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
            <th width="70">活动名称</th>
            <th width="20">状态</th>
            <th width="70">称谓</th>
            <th width="20">用户名</th>
            <th width="20">车牌</th>
            <th width="20">游玩日期</th>
            <th width="20">开始时间</th>
            <th width="30">页面耗时</th>
            <th width="20">实际耗时</th>
        </tr>
        </thead>
        <tbody>
        {{foreach $list as $key => $v }}
        <tr data-id="{{$key}}">
            <td>{{$v['row_id']}}</td>
            <td>{{$v['activity_name']}}</td>
            <td>{{$ok_status[$v['ok_status']]}}</td>
            <td>{{$v['title']}}</td>
            <td>{{$v['user_name']}}</td>
            <td>{{$v['car_number']}}</td>
            <td>{{$v['day_date']}}</td>
            <td>{{$v['created']}}</td>
            <td>{{$v['time']}} 秒</td>
            <td>{{$v['real_time']}} 秒</td>

        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
{{include file='public/page.tpl'}}