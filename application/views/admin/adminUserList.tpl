<div class="bjui-pageHeader">
    <form id="pagerForm" class="frm_cashRequest" data-toggle="ajaxsearch" action="/admin/user/adminUserList" method="post">
        <input type="hidden" name="pageSize" value="${model.pageSize}">
        <input type="hidden" name="pageCurrent" value="${model.pageCurrent}">
        <div class="bjui-searchBar">
            <input type="text" value="{{if !empty($search['content'])}}{{$search['content']}}{{/if}}" name="content" class="form-control" placeholder="搜索(账户名、姓名)">&nbsp;
            <button type="submit" class="btn-green" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo">清空查询</a>
            <div class="pull-right">
                <div class="bjui-searchBar">
                    <div class="alert alert-info search-inline"><i class="fa fa-info-circle"></i> 双击行可编辑</div>&nbsp;
                    <button type="button" class="btn-green" data-toggle="tableditadd" data-target="#tabledit1" data-num="1" data-icon="plus">添加用户</button>&nbsp;
                </div>
            </div>
        </div>
    </form>
</div>
<div class="bjui-pageContent">
    <form action="/admin/user/addUser" class="pageForm" data-toggle="validate" method="post">
        <table id="tabledit1" class="table table-bordered table-hover table-striped table-top" data-toggle="tabledit" data-initnum="0" data-action="/admin/user/addUser">
            <thead>
                <tr>
                    <th width="30" title="No.">
                        <input type="text" name="userList[#index#][uid]" value="" readonly>
                    </th>
                    <th title="账号">
                        <input type="text" name="userList[#index#][username]" data-rule="required"  placeholder="输入新账号" value="">
                    </th>
                    <th  title="密码">
                        <input type="text" name="userList[#index#][password]" data-rule="required" placeholder="输入新密码" value="">
                    </th>
                    <th  title="用户组">
                        <select name="userList[#index#][role_id]" data-toggle="selectpicker" data-rule="required">
                            {{foreach $roles as $v}}
                            <option value="{{$v['id']}}">{{$v['role_name']}}</option>
                            {{/foreach}}
                        </select>
                    </th>
                    <th  title="姓名">
                        <input type="text" name="userList[#index#][name]" data-rule="required" placeholder="输入姓名" value="">
                    </th>
                    <th  title="性别">
                        <select name="userList[#index#][sex]" data-toggle="selectpicker" data-rule="required">
                            <option value="1">男</option>
                            <option value="2">女</option>
                        </select>
                    </th>
                    <th  title="职位">
                        <select name="userList[#index#][position]" data-toggle="selectpicker" data-rule="required">
                            <option value="1">闲杂人</option>
                            <option value="2">区域总负责人</option>
                            <option value="3">城市经理</option>
                            <option value="4">团队长</option>
                            <option value="5">业务员</option>
                        </select>
                    </th>
                    <th  title="我的团队">
                        <select name="userList[#index#][team]" data-toggle="selectpicker" data-rule="required">
                            <option value="1">放养中</option>
                            <option value="2">猛虎队</option>
                            <option value="3">精英队</option>
                            <option value="4">突破队</option>
                            <option value="5">新人队</option>
                        </select>
                    </th>
                    <th  title="创建时间"></th>
                    <th  data-addtool="true">
                        <a href="javascript:;" class="btn btn-green" data-toggle="dosave">保存</a>
                        <input type="hidden" name="userList[#index#][act]" value="add">
                        <a href="/admin/user/delUser" class="btn btn-red row-del" data-confirm-msg="确定要删除该行信息吗？">删</a>
                    </th>
                </tr>
            </thead>
            <tbody>
                {{foreach $users as $v}}
                    <tr data-id="{{$v['uid']}}">
                        <td>{{$v['uid']}}</td>
                        <td>{{$v['username']}}</td>
                        <td>密码已加密隐藏</td>
                        <td data-val="{{$v['role_id']}}">--</td>
                        <td>{{if !empty($v['name'])}}{{$v['name']}}{{else}}-{{/if}}</td>
                        <td>{{$v['sex']}}</td>
                        <td>{{$v['position']}}</td>
                        <td>{{$v['team']}}</td>
                        <td>{{$v['regtime']}}</td>
                        <td data-noedit="true">
                            <button type="button" class="btn-green" data-toggle="doedit">编辑</button>
                            {{if $v['uid'] != 1}}
                            <a href="/admin/user/delUser?uid={{$v['uid']}}" class="btn btn-red row-del" data-confirm-msg="确定要删除该行信息吗？">删</a>
                            {{/if}}
                        </td>
                    </tr>
                {{/foreach}}
            </tbody>
        </table>
    </form>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
        <li><button type="submit" class="btn-default" data-icon="save">全部保存</button></li>
    </ul>
    <div class="pages">
        <span>每页&nbsp;</span>
        <div class="selectPagesize">
            <select data-toggle="selectpicker" data-toggle-change="changepagesize">
                <option value="30">30</option>
                <option value="60">60</option>
                <option value="120">120</option>
                <option value="150">150</option>
            </select>
        </div>
        <span>&nbsp;条，共 {{$count}} 条</span>
    </div>
    <div class="pagination-box"
        data-toggle="pagination"
        data-total="{{$count}}"
        data-page-size="{{if !empty($limit)}}{{$limit}}{{else}}30{{/if}}"
        data-page-current="{{if !empty($search['pageCurrent'])}}{{$search['pageCurrent']}}{{else}}1{{/if}}">
    </div>
</div>