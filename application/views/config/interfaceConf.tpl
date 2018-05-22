<div class="bjui-pageHeader" style="marign:5px;">
    <form id="pagerForm" class="frm_order" data-toggle="ajaxsearch" action="/config/config/interfaceConf" method="post">
        <input type="hidden" name="pageSize" value="${model.pageSize}">
        <input type="hidden" name="pageCurrent" value="${model.pageCurrent}">
        <!-- <input type="hidden" name="orderField" value="${param.orderField}">
        <input type="hidden" name="orderDirection" value="${param.orderDirection}"> -->
        <div class="bjui-searchBar">
            <div style="margin-top: 5px">
                <div class="pull-right">
                {{if checkAuth(189)}}
                    <a href="/config/config/addConfName" class="btn btn-green" data-width="600" data-height="300" data-id="addConfName" data-toggle="dialog" data-title="编辑审件">添加合作商</a>
                {{/if}}
                </div>
            </div>
        </div>
    </form>
</div>
<div class="bjui-pageContent tableContent" id="customListTable">
    <table class="table table-bordered table-hover table-striped table-top" data-toggle="tablefixed" data-width="100%" data-nowrap="true">
        <thead>
            <tr>
                <th><input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck"></th>
                <th>序号</th>
                <th>合作商名称</th>
                <th>合作商标识</th>
                <th>秘钥</th>
                <th>数据等级</th>
                <th>创建时间</th>
                <th>状态</th>
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
                <td>{{$v['comName']}}</td>
                <td>{{$v['appid']}}</td>
                <td>{{$v['appSecrect']}}</td>
                <td>{{$v['dataLevel']}}</td>
                <td>{{$v['created']}}</td>
                <td><span {{if $v['status'] == 2}}style="color: green"{{/if}}>{{$v['statusName']}}</span></td>
                <td>
                    {{if checkAuth(190)}}
                    <a href="/config/config/addConfName?id={{$v['id']}}" class="btn btn-green" data-width="600" data-height="300" data-id="addConfName" data-toggle="dialog" data-title="编辑审件">编辑</a>
                    {{/if}}
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
    $('.frm_order select').change(function (){
        $('.frm_order').submit();
    })

    function checkOrderListExport(){
        var str = $('.frm_order').serialize();
        var gourl = '/member/member/checkOrderListExport?' + str;
        window.open(gourl);
    }

    $('.dq').click(function () {
        var t = $(this).attr('data-t');
        $(this).text('正在加载..');
        if ($('input[name=t]').val() == t) {
            $('input[name=t]').val('');
        } else {
            $('input[name=t]').val(t);
        }
        $('.frm_order').submit();
    });

    function customFileDown()
    {
        $.fileDownload($(a).attr('href'), {
            failCallback: function(responseHtml, url) {
                if (responseHtml.trim().startsWith('{')) responseHtml = responseHtml.toObj()
                    $(a).bjuiajax('ajaxDone', responseHtml)
                }
        })
    }

    $('#reallotButton').click(function () {
        var str = '';
        $('#customListTable input[type="checkbox"]').each(function (k, v) {
            if ($(this).is(':checked')) {
                str += $(this).val() + ',';
            }
        });
        if (!str) {
            $(document).alertmsg('warn', '请先对数据勾选！')
            return;
        }
        // alert(str);return;
        $(this).dialog({
            id: 'reallot',
            url: '/fun/fun/reallot',
            title: '数据重分配',
            width: 350,
            height: 200,
            mask: true,
            data: {'ids': str}
        });
    });
</script>