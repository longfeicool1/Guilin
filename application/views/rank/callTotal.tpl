<div class="bjui-pageHeader">
    <form id="pagerForm" class="frm_callTotal" data-toggle="ajaxsearch" action="/rank/data/callTotal" method="post">
        <input type="hidden" name="pageSize" value="${model.pageSize}">
        <input type="hidden" name="pageCurrent" value="${model.pageCurrent}">
        <div class="bjui-searchBar">
            <input data-toggle="datepicker" type="text"
                       value="{{if isset($search['collectDate'])}}{{$search['collectDate']}}{{/if}}" name="collectDate" autocomplete="off"
                       placeholder="查看日期" readonly />
            <select name="uid" id="uid" data-toggle="selectpicker">
                <option {{if empty($search['uid'])}}selected{{/if}} value="">--业务员--</option>
                {{if !empty($users)}}
                {{foreach $users as $v}}
                <option {{if !empty($search['uid']) && $search['uid'] == $v['uid']}}selected{{/if}} value="{{$v['uid']}}">{{$v['name']}}</option>
                {{/foreach}}
                {{/if}}
            </select>

            <!-- <input type="text" value="{{if !empty($search['name'])}}{{$search['name']}}{{/if}}" name="name" class="form-control" placeholder="搜索(姓名)"> -->
            <button type="submit" class="btn-green" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo">清空查询</a>
            <div style="margin-top: 5px">
                <div class="pull-right">
                    <a class="btn btn-blue" href="javascript:;" onclick="downloadTotal()" target="_blank" data-icon="cloud-download">导出</a>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="bjui-pageContent tableContent" id="customListTable">
    <table class="table table-bordered table-hover table-striped table-top" data-toggle="tablefixed" data-width="100%" data-nowrap="true">
        <thead>
            <tr>
                <th>日期</th>
                <th>姓名</th>
                <th>总量</th>
                <th>当天下发量</th>
                <th>当天拨打次数</th>
            </tr>
        </thead>
        <tbody>
            {{if $list}}
                {{foreach $list as $v}}
                    <tr>
                        <td>{{$v['collectDate']}}</td>
                        <td>{{$v['name']}}</td>
                        <td>{{$v['monthAllotCustom']}}</td>
                        <td>{{$v['dayAllotCustom']}}</td>
                        <td>{{$v['dayPhone']}}</td>
                    </tr>
                {{/foreach}}
            {{else}}
            <tr><td colspan="20" style="text-align: center;">尚未查询到任何相关数据...</td></tr>
            {{/if}}
        </tbody>
    </table>
</div>
<!-- {{include file='../public/page.tpl'}} -->
<script>
    $('.frm_callTotal select').change(function (){
        $('.frm_callTotal').submit();
    })

    function downloadTotal()
    {
        params = $('.frm_callTotal').serialize();
        var gourl = '/rank/data/downLoadCallData?' + params;
        window.open(gourl);
    }

</script>