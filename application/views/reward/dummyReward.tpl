<div class="bjui-pageHeader" style="marign:5px;">
    <form id="pagerForm" class="frm_toreward2" data-toggle="ajaxsearch" action="/reward/reward/dummyReward"
          method="post">
        <input type="hidden" name="pageSize" value="${model.pageSize}">
        <input type="hidden" name="pageCurrent" value="${model.pageCurrent}">
        <!-- <input type="hidden" name="orderField" value="${param.orderField}">
        <input type="hidden" name="orderDirection" value="${param.orderDirection}"> -->
        <div class="bjui-searchBar">
            <p>
                <select name="activity_id" id="s_city" data-toggle="selectpicker" data-nextselect="#s_prize_id"
                        data-emptytxt="-- 选择活动名称 --"
                        data-refurl="/reward/reward/prize?type={{$search['type']}}&activity_id={value}">
                    <option value="0">-- 选择活动名称 --</option>
                    {{if $activity}}
                    {{foreach $activity as $k => $v}}
                    <option value="{{$v['id']}}"
                            {{if isset($search['activity_id']) && $search['activity_id'] == $v['id']}}selected{{/if}}>{{$v['name']}}</option>
                    {{/foreach}}
                    {{/if}}
                </select>
                <select name="prize_id" id="s_prize_id" data-toggle="selectpicker" data-emptytxt="-- 等级  奖品  --">
                    <option value="0">-- 等级  奖品 --</option>
                    {{if $prize}}
                    {{foreach $prize as $k => $v}}
                    <option value="{{$v['id']}}"
                            {{if $search['prize_id'] == $v['id']}}selected{{/if}}>{{$v['prize_name']}}</option>
                    {{/foreach}}
                    {{/if}}
                </select>
                <label>
                    发放金额：{{$sumMoney}}
                </label>
            </p>
            <p>
                <input type="hidden" name="type" value="{{$search['type']}}" />
                <input data-toggle="datepicker" type="text"
                       value="{{if isset($search['bt'])}}{{$search['bt']}}{{/if}}" name="bt" autocomplete="off"
                       placeholder="抽奖时间开始"/>
                <input data-toggle="datepicker" type="text"
                       value="{{if isset($search['et'])}}{{$search['et']}}{{/if}}"
                       name="et" autocomplete="off" placeholder="抽奖时间结束"/>
                <input type="text" value="{{if !empty($search['content'])}}{{$search['content']}}{{/if}}" name="content"
                       class="form-control" placeholder="搜索(手机、车牌)">&nbsp;
                <button type="submit" class="btn-green" data-icon="search">查询</button>
                &nbsp;
                <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true"
                   data-icon="undo">清空查询</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="btn btn-blue" href="javascript:;" onclick="torewardExport()" target="_blank"
                   data-icon="cloud-download">导出</a>

            </p>
        </div>
    </form>
</div>
<div class="bjui-pageContent tableContent">
    <table class="table table-bordered table-hover table-striped table-top" data-toggle="tablefixed" data-width="100%"
           data-nowrap="true">
        <thead>
        <tr>
            <th width="10">序号</th>
            <th width="30">账户</th>
            <th width="20">车牌</th>
            <th width="30">姓名</th>
            <th width="30">设备ID</th>
            <th width="30">设备城市</th>
            <th width="50">活动名称</th>
            <th width="30">奖品等级</th>
            <th width="50">奖品</th>
            <th width="40">中奖日期</th>
            <th width="20">状态</th>
        </tr>
        </thead>
        <tbody>
        {{if $list}}
        {{foreach $list as $v}}
        <tr>
            <td>{{$v['xuhao']}}</td>
            <td>{{$v['loginname']}}</td>
            <td>{{$v['carcard']}}</td>
            <td>{{$v['username']}}</td>
            <td>{{$v['src']}}</td>
            <td>{{$v['city']}}</td>
            <td>{{$v['activity_name']}}</td>
            <td>{{$v['prize_level']}}</td>
            <td>{{if $v['is_random'] == 1}}{{floatval($v['money'])}}元随机{{floatval($v['random_money'])}}元红包{{else}}{{$v['prize_name']}}{{/if}}</td>
            <td>{{$v['created']}}</td>
            <td>已到账</td>
        </tr>
        {{/foreach}}
        {{else}}
        <tr>
            <td colspan="20" style="text-align: center;">尚未查询到任何相关数据...</td>
        </tr>
        {{/if}}
        </tbody>
    </table>
</div>
{{include file='../public/page.tpl'}}
<script>
    function torewardExport() {
        var str = $('.frm_toreward2').serialize();
        var gourl = '/reward/reward/torewardExport?type=dummy&' + str;
        window.open(gourl);
    }
</script>