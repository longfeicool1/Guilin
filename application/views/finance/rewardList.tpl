<div class="bjui-pageHeader" style="marign:5px;">
    <form id="pagerForm" class="frm_reward" data-toggle="ajaxsearch" action="/finance/financeManage/rewardList" method="post">
        <input type="hidden" name="pageSize" value="${model.pageSize}">
        <input type="hidden" name="pageCurrent" value="${model.pageCurrent}">
        <!-- <input type="hidden" name="orderField" value="${param.orderField}">
        <input type="hidden" name="orderDirection" value="${param.orderDirection}"> -->
        <div class="bjui-searchBar">
            <input data-toggle="datepicker" type="text"
                       value="{{if isset($search['bt'])}}{{$search['bt']}}{{/if}}" name="bt" autocomplete="off"
                       placeholder="创建时间开始"/>
            <input data-toggle="datepicker" type="text"
                   value="{{if isset($search['et'])}}{{$search['et']}}{{/if}}"
                   name="et" autocomplete="off" placeholder="创建时间结束"/>
            <select name="get_income_status" id="get_income_status" data-toggle="selectpicker">
                <option {{if empty($search['get_income_status'])}}selected{{/if}} value="">--领取状态--</option>
                <option value="1" {{if $search['get_income_status'] == '1'}}selected{{/if}}>未领取</option>
                <option value="2" {{if $search['get_income_status'] == '2'}}selected{{/if}}>已领取</option>
                <option value="3" {{if $search['get_income_status'] == '3'}}selected{{/if}}>已过时</option>
            </select>
            <input type="text" value="{{if !empty($search['content'])}}{{$search['content']}}{{/if}}" name="content" class="form-control" placeholder="搜索(手机、车牌)">
            <button type="submit" class="btn-green" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo">清空查询</a>
            <div class="pull-right">
                <a class="btn btn-blue" href="javascript:;" onclick="rewardListExport()" target="_blank" data-icon="cloud-download">导出</a>
            </div>
        </div>
    </form>
</div>
<div class="bjui-pageContent tableContent">
    <table class="table table-bordered table-hover table-striped table-top" data-toggle="tablefixed" data-width="100%" data-nowrap="true">
        <thead>
            <tr>
                <th width="15"><input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck"></th>
                <th width="18">序号</th>
                <th width="30">时间</th>
                <th width="20">周评分</th>
                <th width="50">奖励类型</th>
                <th width="40">奖励金额(元)</th>
                <th width="80">账户</th>
                <th width="30">车牌</th>
                <th width="60">领取状态</th>
            </tr>
        </thead>
        <tbody>
            {{if $list}}
            {{foreach $list as $v}}
            <tr data-id="{{$v['id']}}">
                <td>
                    <input type="checkbox" class="icheckbox_minimal-purple" name="ids" data-toggle="icheck" value="{{$v['id']}}">
                </td>
                <td>{{$v['xuhao']}}</td>
                <td>{{$v['collect_date']}}</td>
                <td>{{$v['score']}}</td>
                <td>{{$v['reawardName']}}</td>
                <td>{{$v['week_income']}}</td>
                <td>{{$v['account_login']}}</td>
                <td>{{$v['carcard']}}</td>
                <td>{{$v['getIncomeStatus']}}</td>
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
    $('.frm_reward select').change(function (){
        $('.frm_reward').submit();
    })

    function rewardListExport(){
        var str = $('.frm_reward').serialize();
        var gourl = '/finance/financeManage/rewardListExport?' + str;
        window.open(gourl);
    }
</script>