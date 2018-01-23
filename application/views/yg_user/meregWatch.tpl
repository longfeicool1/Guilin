<div class="bjui-pageHeader">


<!-- </div> -->

</div>
<div class="bjui-pageContent tableContent">
    <form id="frm_meregWatch" class="pageForm" method="post" data-toggle="validate" action="user/check/tomeregWatch" data-callback="sumitAfter">
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true">
        <thead>
        <tr>
            <th>序号</th>
            <th>账户</th>
            <th>设备号</th>
            <th>临/正常牌</th>
            <th>车牌</th>
            <th>商业保单号</th>
            <th>保单止期</th>
            <th>认证状态</th>
            <th>删除</th>
        </tr>
        </thead>
        <tbody>
        {{if !empty($list)}}
            {{foreach $list as $key => $v }}
            <tr>
                <td>
                    {{$v['xuhao']}}
                    <input type="hidden" name="list[{{$v['xuhao']}}][bind_id]" value="{{$v['bind_id']}}" />
                </td>
                <td>{{$v['account_login']}}</td>
                <td>{{$v['src']}}</td>
                <td>
                    {{$v['cardtypeName']}}
                    <input type="hidden" name="list[{{$v['xuhao']}}][cardtype]" value="{{$v['cardtype']}}" />
                </td>
                <td>
                    {{$v['carcardName']}}
                    <input type="hidden" name="list[{{$v['xuhao']}}][carcard]" value="{{$v['carcard']}}" />
                </td>
                <td>
                    {{$v['insure_code_name']}}
                    <input type="hidden" name="list[{{$v['xuhao']}}][insure_code]" value="{{$v['insure_code']}}" />
                </td>
                <td>
                    {{$v['insure_end_name']}}
                    <input type="hidden" name="list[{{$v['xuhao']}}][insure_end]" value="{{$v['insure_end']}}" />
                </td>
                <td><span style="color: green">认证</span></td>
                <td><a href="javascript:;" onclick="delAuth(this)">删除</a></td>
            </tr>
            {{/foreach}}
        {{else}}
            <tr>
                <td colspan="8" align="center">暂无数据</td>
            </tr>
        {{/if}}
        </tbody>
    </table>
    </form>
</div>
<div class="bjui-pageFooter" style="height: 50px;">
    <ul style="margin-right: 45%;">
        <li><button type="button" class="btn-close btn-lg" style="height: 40px;">关闭</button></li>
        <li><button type="submit" class="btn-default btn-lg" style="height: 40px;">匹配</a></li>
    </ul>
</div>
<script type="text/javascript">
    function delAuth(obj)
    {
        $(obj).parents('tr').remove();
    }
    function sumitAfter(json)
    {
        $(this)
            .bjuiajax('ajaxDone', json)
            .navtab('reloadFlag', json.tabid)
        if (json.statusCode == 200) {
            $(document).dialog('close','meregWatch');
            $(document).dialog('close','mulitCheck');
        }
    }
</script>