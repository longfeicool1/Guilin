<div class="bjui-pageHeader" style="marign:5px;">
    <form id="pagerForm" class="frm_cashRequest" data-toggle="ajaxsearch" action="/finance/financeManage/cashRequest" method="post">
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
            <input type="text" value="{{if !empty($search['content'])}}{{$search['content']}}{{/if}}" name="content" class="form-control" placeholder="搜索(账户名、手机、车牌)">&nbsp;
            <button type="submit" class="btn-green" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo">清空查询</a>
            <a class="btn btn-blue" href="javascript:;" onclick="cashRequestExport()" target="_blank" data-icon="cloud-download">导出</a>
            <div class="pull-right">
                <span style="font-size: 14px;font-weight: 600;">总计：{{$totalMonsy}}元</span>
                <br>
                <span style="font-size: 14px;font-weight: 600;color: green">本日：{{$todayMonsy}}元</span>
            </div>
        </div>
    </form>
</div>
<div class="bjui-pageContent tableContent">
    <table class="table table-bordered table-hover table-striped table-top" data-toggle="tablefixed" data-width="100%" data-nowrap="true">
        <thead>
            <tr>
                <th width="25"><input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck"></th>
                <th>序号</th>
                <th>账户</th>
                <th>车牌</th>
                <th>设备城市</th>
                <th>提现金额</th>
                <th>银行账户</th>
                <th>银行名称</th>
                <th>所在地</th>
                <th>开户行</th>
                <th>账户名</th>
                <th>创建时间</th>
                <th>操作</th>
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
                <td>{{$v['city']}}</td>
                <td>{{$v['amount']}}</td>
                <td>{{$v['bankcode']}}</td>
                <td>{{$v['name']}}</td>
                <td>{{$v['location']}}</td>
                <td>{{$v['deposit_bank']}}</td>

                <td>{{$v['account_name']}}</td>
                <td>{{$v['created']}}</td>
                <td>
                    <a class="btn btn-green" data-toggle="doajax" data-confirm-msg="是否确定转账成功？" href="/finance/financeManage/changeRequestStatus?type=1&id={{$v['id']}}&bankId={{$v['bankId']}}">转账成功</a>
                    <a class="btn btn-red" data-toggle="doajax" data-confirm-msg="是否确定转账失败？" href="/finance/financeManage/changeRequestStatus?type=2&id={{$v['id']}}&bankId={{$v['bankId']}}">失败</a>
                </td>
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
    $('.frm_cashRequest select').change(function (){
        $('.frm_cashRequest').submit();
    })

    function cashRequestExport(){
        var str = $('.frm_cashRequest').serialize();
        var gourl = '/finance/financeManage/cashRequestExport?' + str;
        window.open(gourl);
    }
</script>