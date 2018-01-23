<div class="bjui-pageHeader" style="marign:5px;">
    <form id="pagerForm" class="frm_transferAccounts" data-toggle="ajaxsearch" action="/finance/financeManage/transferAccounts" method="post">
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
            <input type="text" value="{{if !empty($search['content'])}}{{$search['content']}}{{/if}}" name="content" class="form-control" placeholder="搜索(手机、车牌)">&nbsp;
            <button type="submit" class="btn-green" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo">清空查询</a>
            <div class="pull-right">
                <a class="btn btn-blue" href="javascript:;" onclick="transferAccountsExport()" target="_blank" data-icon="cloud-download">导出</a>
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
                <th width="50">账户</th>
                <th width="35">车牌</th>
                <th width="40">提现金额(元)</th>
                <th width="80">银行账户</th>
                <th width="30">银行名称</th>
                <th width="60">所在地</th>
                <th width="60">开户行</th>
                <th width="30">账户名</th>
                <th width="30">银行卡状态</th>
                <th width="30">转账状态</th>
                <th width="70">创建时间</th>
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
                <td>{{$v['account_login']}}</td>
                <td>{{$v['carcard']}}</td>
                <td>{{$v['amount']}}</td>
                <td>{{$v['bankcode']}}</td>
                <td>{{$v['name']}}</td>
                <td>{{$v['location']}}</td>
                <td>{{$v['deposit_bank']}}</td>
                <td>{{$v['account_name']}}</td>
                <td>{{$v['bankCardStatus']}}</td>
                <td>{{$v['auditName']}}</td>
                <td>{{$v['updated']}}</td>
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
    $('.frm_transferAccounts select').change(function (){
        $('.frm_transferAccounts').submit();
    })

    function transferAccountsExport(){
        var str = $('.frm_transferAccounts').serialize();
        var gourl = '/finance/financeManage/transferAccountsExport?' + str;
        window.open(gourl);
    }
</script>