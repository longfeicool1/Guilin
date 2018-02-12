<div class="bjui-pageHeader" style="marign:5px;">
    <form id="pagerForm" class="frm_member" data-toggle="ajaxsearch" action="/member/member/memberList" method="post">
        <input type="hidden" name="pageSize" value="${model.pageSize}">
        <input type="hidden" name="pageCurrent" value="${model.pageCurrent}">
        <!-- <input type="hidden" name="orderField" value="${param.orderField}">
        <input type="hidden" name="orderDirection" value="${param.orderDirection}"> -->
        <div class="bjui-searchBar">
            <input data-toggle="datepicker" type="text"
                       value="{{if isset($search['bt'])}}{{$search['bt']}}{{/if}}" name="bt" autocomplete="off"
                       placeholder="预约时间开始"/>
            <input data-toggle="datepicker" type="text"
                   value="{{if isset($search['et'])}}{{$search['et']}}{{/if}}"
                   name="et" autocomplete="off" placeholder="预约时间结束"/>
            <select name="dataLevel" id="dataLevel" data-toggle="selectpicker">
                <option {{if empty($search['dataLevel'])}}selected{{/if}} value="">--数据类型--</option>
                <option value="A" {{if !empty($search['dataLevel']) && $search['dataLevel'] == 'A'}}selected{{/if}}>A级</option>
                <option value="B" {{if !empty($search['dataLevel']) && $search['dataLevel'] == 'B'}}selected{{/if}}>B级</option>
            </select>
            <select name="customLevel" id="customLevel" data-toggle="selectpicker">
                <option {{if empty($search['customLevel'])}}selected{{/if}} value="">--名单星级--</option>
                <option value="1" {{if !empty($search['customLevel']) && $search['customLevel'] == 1}}selected{{/if}}>0星</option>
                <option value="2" {{if !empty($search['customLevel']) && $search['customLevel'] == 2}}selected{{/if}}>1星</option>
                <option value="3" {{if !empty($search['customLevel']) && $search['customLevel'] == 3}}selected{{/if}}>2星</option>
                <option value="4" {{if !empty($search['customLevel']) && $search['customLevel'] == 4}}selected{{/if}}>3星</option>
                <option value="5" {{if !empty($search['customLevel']) && $search['customLevel'] == 5}}selected{{/if}}>4星</option>
            </select>
            <input type="text" value="{{if !empty($search['content'])}}{{$search['content']}}{{/if}}" name="content" class="form-control" placeholder="搜索(手机、车牌)">
            <button type="submit" class="btn-green" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo">清空查询</a>
            <div style="margin-top: 5px">
                <button type="button" class="btn-blue dq" data-t="1" data-icon="comment"
                    {{if !empty($search['t']) && $search['t'] == 1}}style="background-color: #428bca;color: #FFF;"{{/if}}>今日预约
                </button>
                &nbsp;
                <button type="button" class="btn-blue dq" data-t="2" data-icon="list"
                        {{if !empty($search['t']) && $search['t'] == 2}}style="background-color: #428bca;color: #FFF;"{{/if}}>今日分配
                </button>
                &nbsp;
                <button type="button" class="btn-blue dq" data-t="3" data-icon="plus"
                        {{if !empty($search['t']) && $search['t'] == 3}}style="background-color: #428bca;color: #FFF;"{{/if}}>尚未处理
                </button>
                <input type="hidden" name="t" value="{{if !empty($search['t'])}}{{$search['t']}}{{/if}}"/>
                &nbsp;
                <div class="pull-right">
                {{if checkAuth(155)}}
                <a class="btn btn-blue" href="/static/example.xls" onclick="customFileDown(this);return false;" target="_blank" data-icon="cloud-download">下载模板</a>
                {{/if}}
                {{if checkAuth(144)}}
                    <a class="btn btn-blue" href="javascript:;" onclick="memberListExport()" target="_blank" data-icon="cloud-download">导出</a>
                {{/if}}
                {{if checkAuth(157)}}
                <a class="btn btn-blue" id="reallotButton" href="javascript:;" target="_blank" data-icon="paper-plane">重分配选中</a>
                {{/if}}
                {{if checkAuth(146)}}
                    <a href="/member/member/delMember" class="btn btn-red" data-toggle="doajaxchecked" data-confirm-msg="确定要删除选中项吗？"
                       data-idname="delids" data-group="ids">删除选中</a>
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
                <th>性别</th>
                <th>年龄</th>
                <th>手机</th>
                <th>城市</th>
                <th>职业</th>
                <th>发薪方式</th>
                <th>收入</th>
                <th>社保</th>
                <th>公积金</th>
                <th>有房</th>
                <th>有车</th>
                <th>业务员</th>
                <th>预约时间</th>
                <th>用户状态</th>
                <th>数据类型</th>
                <th>名单星级</th>
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
                <td>{{$v['name']}}</td>
                <td>{{$v['sex']}}</td>
                <td>{{$v['age']}}</td>
                <td>{{$v['mobile']}}</td>
                <td>{{$v['city']}}</td>
                <td>{{$v['occapation']}}</td>
                <td>{{$v['payType']}}</td>
                <td>{{$v['income']}}</td>
                <td>{{$v['socialSecurity']}}</td>
                <td>{{$v['reservedFunds']}}</td>
                <td>{{$v['haveHouse']}}</td>
                <td>{{$v['haveCar']}}</td>
                <td>{{$v['firstName']}}</td>
                <td>{{$v['meetTime']}}</td>
                <td>{{$v['customStatus']}}</td>
                <td>{{$v['dataLevel']}}</td>
                <td>{{$v['customLevel']}}</td>
                <td>
                    {{if checkAuth(144)}}
                        <a href="/member/member/createCheckOrder?id={{$v['id']}}" class="btn btn-green" data-width="800" data-height="600" data-id="memberInfo" data-toggle="dialog" data-title="用户详情">审件生成</a>
                    {{/if}}
                    {{if checkAuth(144)}}
                        <a href="/member/member/memberInfo?id={{$v['id']}}" class="btn btn-blue" data-width="800" data-height="600" data-id="memberInfo" data-toggle="dialog" data-title="用户详情">详情</a>
                    {{/if}}
                    {{if checkAuth(146)}}
                        <a href="/member/member/delMember?delids={{$v['id']}}" class="btn btn-red"  data-toggle="doajax" data-confirm-msg="是否删除该记录">删除</a>
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
    $('.frm_member select').change(function (){
        $('.frm_member').submit();
    })

    function memberListExport(){
        var str = $('.frm_member').serialize();
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
        $('.frm_member').submit();
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