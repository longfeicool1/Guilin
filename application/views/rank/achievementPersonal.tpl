<div class="bjui-pageHeader">
    <form id="pagerForm" class="frm_personal" data-toggle="ajaxsearch" action="/rank/data/achievementPersonal" method="post">
        <input type="hidden" name="pageSize" value="${model.pageSize}">
        <input type="hidden" name="pageCurrent" value="${model.pageCurrent}">
        <div class="bjui-searchBar">
            <!-- <input data-toggle="datepicker" type="text"
                       value="{{if isset($search['bt'])}}{{$search['bt']}}{{/if}}" name="bt" autocomplete="off"
                       placeholder="创建时间开始"/>
            <input data-toggle="datepicker" type="text"
                   value="{{if isset($search['et'])}}{{$search['et']}}{{/if}}"
                   name="et" autocomplete="off" placeholder="创建时间结束"/>
            <select name="firstOwer" id="firstOwer" data-toggle="selectpicker">
                <option {{if empty($search['firstOwer'])}}selected{{/if}} value="">--业务员--</option>
                {{foreach $users as $v}}
                <option {{if !empty($search['firstOwer']) && $search['firstOwer'] == $v['uid']}}selected{{/if}} value="{{$v['uid']}}">{{$v['name']}}</option>
                {{/foreach}}
            </select>

            <input type="text" value="{{if !empty($search['content'])}}{{$search['content']}}{{/if}}" name="content" class="form-control" placeholder="搜索(手机、姓名)">
            <button type="submit" class="btn-green" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo">清空查询</a>
            <div style="margin-top: 5px">
                <div class="pull-right">
                <button type="button" class="btn-blue dq" data-t="3" data-icon="plus"
                    {{if !empty($search['t']) && $search['t'] == 3}}style="background-color: #428bca;color: #FFF;"{{/if}}>今日
                </button>
                </div>
            </div> -->
            <table class="table table-bordered table-hover table-striped" data-toggle="tablefixed" data-width="100%" data-nowrap="true">
                <thead>
                    <th>本月额度</th>
                    <th>本月创收</th>
                    <th>本月创收单量</th>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$info['monthOutMoney']}}</td>
                        <td>{{$info['monthInMoney']}}</td>
                        <td>{{$info['monthDetail']}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>
<div class="bjui-pageContent tableContent" id="customListTable">
    <table class="table table-bordered table-hover table-striped table-top" data-toggle="tablefixed" data-width="100%" data-nowrap="true">
        <thead>
            <tr>
                <th>截止日期</th>
                <th>姓名</th>
                <th>月额度</th>
                <th>月创收</th>
                <th>月创收单数</th>
            </tr>
        </thead>
        <tbody>
            {{if $list}}
                <tr>
                    <td rowspan="500">{{$list[0]['collectDate']}}</td>
                </tr>
                {{foreach $list as $v}}
                    <tr>
                        <td>{{$v['name']}}</td>
                        <td>{{$v['monthOutMoney']}}</td>
                        <td>{{$v['monthInMoney']}}</td>
                        <td>{{$v['monthDetail']}}</td>
                    </tr>
                {{/foreach}}
            {{else}}
            <tr><td colspan="20" style="text-align: center;">尚未查询到任何相关数据...</td></tr>
            {{/if}}
        </tbody>
    </table>
</div>
{{include file='../public/page.tpl'}}
<script>
    $('.frm_personal select').change(function (){
        $('.frm_personal').submit();
    })

</script>