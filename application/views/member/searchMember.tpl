<style type="text/css">
    .font-color{color: orange}
    .font-del{color: red}
</style>
<div class="bjui-pageHeader" style="marign:5px;">
    <form id="pagerForm" class="frm_member" data-toggle="ajaxsearch" action="/member/member/searchMember" method="post">
        <input type="hidden" name="pageSize" value="${model.pageSize}">
        <input type="hidden" name="pageCurrent" value="${model.pageCurrent}">
        <!-- <input type="hidden" name="orderField" value="${param.orderField}">
        <input type="hidden" name="orderDirection" value="${param.orderDirection}"> -->
        <div class="bjui-searchBar">
            <input type="text" value="{{if !empty($search['content'])}}{{$search['content']}}{{/if}}" name="content" class="form-control" placeholder="搜索(手机、姓名)" style="width:300px">
            <button type="submit" class="btn-green" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo">清空查询</a>
            </div>
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
                <th>删除?</th>
                <th>姓名</th>
                <th>性别</th>
                <th>年龄</th>
                <th>手机</th>
                <th>城市</th>
                {{if checkAuth(166)}}
                <th>职业</th>
                {{/if}}
                {{if checkAuth(167)}}
                <th>发薪方式</th>
                {{/if}}
                {{if checkAuth(168)}}
                <th>收入</th>
                {{/if}}
                {{if checkAuth(169)}}
                <th>社保</th>
                {{/if}}
                {{if checkAuth(170)}}
                <th>公积金</th>
                {{/if}}
                {{if checkAuth(171)}}
                <th>有房</th>
                {{/if}}
                {{if checkAuth(172)}}
                <th>有车</th>
                {{/if}}
                <th>业务员</th>
                <th>预约时间</th>
                <th>用户状态</th>
                {{if checkAuth(173)}}
                <th>数据类型</th>
                {{/if}}
                {{if checkAuth(174)}}
                <th>数据来源</th>
                {{/if}}
                <th>名单星级</th>
                <th>N天未联系</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            {{if $list}}
            {{foreach $list as $v}}
            <tr data-id="{{$v['id']}}" {{if $v['isShow'] == 2}}class="font-del"{{else}}{{if $v['isRepeat'] == 2}}class="font-color"{{/if}}{{/if}}>
                <td>
                    <input type="checkbox" class="icheckbox_minimal-purple" name="ids" data-toggle="icheck" value="{{$v['id']}}">
                </td>
                <td>{{$v['xuhao']}}</td>
                <td>{{$v['isShowName']}}</td>
                <td>{{$v['name']}}</td>
                <td>{{$v['sex']}}</td>
                <td>{{$v['age']}}</td>
                <td>{{$v['mobile']}}</td>
                <td>{{$v['city']}}</td>
                {{if checkAuth(166)}}
                <td>{{$v['occapation']}}</td>
                {{/if}}
                {{if checkAuth(167)}}
                <td>{{$v['payType']}}</td>
                {{/if}}
                {{if checkAuth(168)}}
                <td>{{$v['income']}}</td>
                {{/if}}
                {{if checkAuth(169)}}
                <td>{{$v['socialSecurity']}}</td>
                {{/if}}
                {{if checkAuth(170)}}
                <td>{{$v['reservedFunds']}}</td>
                {{/if}}
                {{if checkAuth(171)}}
                <td>{{$v['haveHouse']}}</td>
                {{/if}}
                {{if checkAuth(172)}}
                <td>{{$v['haveCar']}}</td>
                {{/if}}
                <td>{{$v['firstName']}}</td>
                <td>{{$v['meetTime']}}</td>
                <td>{{$v['customStatus']}}</td>
                {{if checkAuth(173)}}
                <td>{{$v['dataLevel']}}</td>
                {{/if}}
                {{if checkAuth(174)}}
                <td>{{$v['source']}}</td>
                {{/if}}
                <td>{{$v['customLevel']}}</td>
                <td>{{$v['dayNoCall']}}</td>
                <td>
                    {{if checkAuth(144)}}
                        <a href="/member/member/memberInfo?id={{$v['id']}}{{if $v['firstName'] != $userinfo['uid']}}&watch=1{{/if}}" class="btn btn-blue" data-width="1200" data-height="800" data-id="memberInfo" data-toggle="dialog" data-title="用户详情">详情</a>
                    {{/if}}
                    <!-- {{if checkAuth(146)}}
                        <a href="/member/member/delMember?delids={{$v['id']}}" class="btn btn-red"  data-toggle="doajax" data-confirm-msg="是否删除该记录">删除</a>
                    {{/if}} -->
                </td>
            </tr>
            {{/foreach}}
            {{else}}
            <tr><td colspan="30" style="text-align: center;">尚未查询到任何相关数据...</td></tr>
            {{/if}}
        </tbody>
    </table>
</div>
{{include file='../public/page.tpl'}}
<script>
    $('.frm_member select').change(function (){
        $('.frm_member').submit();
    })
</script>