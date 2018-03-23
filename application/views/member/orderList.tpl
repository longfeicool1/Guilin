<div class="bjui-pageHeader" style="marign:5px;">
    <form id="pagerForm" class="frm_order" data-toggle="ajaxsearch" action="/member/member/orderList" method="post">
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
            <select name="status" id="status" data-toggle="selectpicker">
                <option {{if empty($search['status'])}}selected{{/if}} value="">--审核状态--</option>
                <option value="1" {{if !empty($search['status']) && $search['status'] == 1}}selected{{/if}}>待审核</option>
                <option value="2" {{if !empty($search['status']) && $search['status'] == 2}}selected{{/if}}>在审中</option>
                <option value="3" {{if !empty($search['status']) && $search['status'] == 3}}selected{{/if}}>已拒款</option>
                <option value="4" {{if !empty($search['status']) && $search['status'] == 4}}selected{{/if}}>客户已拒签</option>
                <option value="5" {{if !empty($search['status']) && $search['status'] == 5}}selected{{/if}}>未进件</option>
                <option value="6" {{if !empty($search['status']) && $search['status'] == 6}}selected{{/if}}>已收款</option>
            </select>
            <select name="uid" id="uid" data-toggle="selectpicker">
                <option {{if empty($search['uid'])}}selected{{/if}} value="">--业务员--</option>
                {{foreach $users as $v}}
                <option {{if !empty($search['uid']) && $search['uid'] == $v['uid']}}selected{{/if}} value="{{$v['uid']}}">{{$v['name']}}</option>
                {{/foreach}}
            </select>
            <input type="text" value="{{if !empty($search['content'])}}{{$search['content']}}{{/if}}" name="content" class="form-control" placeholder="搜索(手机、姓名、城市)">
            <button type="submit" class="btn-green" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo">清空查询</a>
            <div style="margin-top: 5px">
                <div class="pull-right">
                {{if checkAuth(182)}}
                    <a class="btn btn-blue" href="javascript:;" onclick="checkOrderListExport()" target="_blank" data-icon="cloud-download">导出</a>
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
                <th>姓名</th>
                <th>手机</th>
                <th>进件渠道</th>
                <th>贷款产品</th>
                <th>贷款额度</th>
                <th>费率</th>
                <th>定金</th>
                <th>业务员</th>
                <th>退定金?</th>
                {{if checkAuth(164)}}
                <th>批款额度</th>
                <th>创收</th>
                <th>收款时间</th>
                {{/if}}
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
                <td>{{$v['rate']}}%</td>
                <td>{{$v['deposit']}}</td>
                <td>{{$v['firstName']}}</td>
                {{if !in_array($userinfo['position'],[1,5])|| $userinfo['role_id'] == 1}}{{/if}}
                <td>{{$v['isBackMoney']}}</td>
                {{if checkAuth(164)}}
                <td>{{$v['sendMoney']}}</td>
                <td>{{$v['income']}}</td>
                <td>{{$v['sendTime']}}</td>
                {{/if}}
                <td>{{$v['orderStatus']}}</td>
                <td>{{date('Y-m-d',strtotime($v['created']))}}</td>
                <td>
                    {{if checkAuth(161)}}
                    <a href="/member/member/editOrder?id={{$v['id']}}" class="btn btn-green" data-width="500" data-height="450" data-id="editOrder" data-toggle="dialog" data-title="编辑审件">编辑</a>
                    {{/if}}
                    {{if checkAuth(160)}}
                        <a href="/member/member/orderInfo?id={{$v['id']}}" class="btn btn-blue" data-width="600" data-height="510" data-id="orderInfo" data-toggle="dialog" data-title="审核审件">审核</a>
                    {{/if}}
                    {{if checkAuth(181)}}
                        <a href="/member/member/delOrder?delids={{$v['id']}}" class="btn btn-red"  data-toggle="doajax" data-confirm-msg="是否删除该记录">删除</a>
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