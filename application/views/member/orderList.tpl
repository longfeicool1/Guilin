<div class="bjui-pageHeader" style="marign:5px;">
    <form id="pagerForm" class="frm_order" data-toggle="ajaxsearch" action="/member/member/memberList" method="post">
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
            <select name="dataLevel" id="dataLevel" data-toggle="selectpicker">
                <option {{if empty($search['dataLevel'])}}selected{{/if}} value="">--数据类型--</option>
                <option value="A" {{if !empty($search['dataLevel']) && $search['dataLevel'] == 'A'}}selected{{/if}}>A级</option>
                <option value="B" {{if !empty($search['dataLevel']) && $search['dataLevel'] == 'B'}}selected{{/if}}>B级</option>
            </select>
            <input type="text" value="{{if !empty($search['content'])}}{{$search['content']}}{{/if}}" name="content" class="form-control" placeholder="搜索(手机、车牌)">
            <button type="submit" class="btn-green" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo">清空查询</a>
            <div style="margin-top: 5px">
                <div class="pull-right">
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
                <th>姓名</th>
                <th>手机</th>
                <th>进件渠道</th>
                <th>贷款产品</th>
                <th>贷款额度</th>
                <th>费率</th>
                <th>定金</th>
                <th>业务员</th>
                <th>后勤对接员</th>
                <th>审核状态</th>
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
                <td>{{$v['username']}}</td>
                <td>{{$v['mobile']}}</td>
                <td>{{$v['channel']}}</td>
                <td>{{$v['product']}}</td>
                <td>{{$v['money']}}</td>
                <td>{{$v['rate']}}</td>
                <td>{{$v['deposit']}}</td>
                <td>{{$v['firstName']}}</td>
                <td>{{$v['secondUid']}}</td>
                <td>{{$v['orderStatus']}}</td>
                <td>{{$v['created']}}</td>
                <td>
                    {{if checkAuth(160)}}
                        <a href="/member/member/orderInfo?id={{$v['id']}}" class="btn btn-blue" data-width="800" data-height="600" data-id="orderInfo" data-toggle="dialog" data-title="审件详情">审核</a>
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

    function memberListExport(){
        var str = $('.frm_order').serialize();
        var gourl = '/member/member/memberDownload?' + str;
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