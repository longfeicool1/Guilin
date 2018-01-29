<div class="bjui-pageContent">
    <fieldset>
        <legend>角色管理</legend>
        <!-- Tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a href="#roleList" role="tab" data-toggle="tab" data-target="#roleList">角色列表</a></li>
            {{if checkAuth(9)}}
            <li><a href="#addRole" role="tab" data-toggle="tab">添加角色</a></li>
            {{/if}}
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane fade active in" id="roleList">
                    <table data-toggle="tablefixed" data-width="100%" data-nowrap="true">
                        <thead>
                            <tr>
                                <th>角色ID</th>
                                <th>角色名称</th>
                                <th>描述</th>
                                <th style="text-align: center;">权限状态</th>
                                <th style="text-align: center;">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td  class="table_td_align">—</td>
                                <td>超级管理员</td>
                                <td>拥有网站最高管理员权限！</td>
                                <td class="table_td_align">
                                    <a data-icon="unlock-alt" class="btn btn-default">启用</a>
                                </td>
                                <td style="text-align: center;">
                                    无权操作
                                </td>
                            </tr>
                            {{foreach $role as $v}}
                                <tr  data-id="{{$v['id']}}">
                                    <td class="table_td_align">{{$v['id']}}</td>
                                    <td>{{$v['role_name']}}</td>
                                    <td>{{$v['info']}}</td>
                                    <td class="table_td_align">
                                        {{if checkAuth(10)}}      <!--判断是否有权限-->
                                            {{if $v['isshow'] == 1}}
                                                <a href="/admin/auth/changeShow?id={{$v['id']}}" data-icon="unlock-alt" class="btn btn-green" data-toggle="doajax" data-confirm-msg="确定要禁用该权限组？">启用</a>
                                            {{else}}
                                                <a href="/admin/auth/changeShow?id={{$v['id']}}" data-icon="lock" class="btn btn-orange" data-toggle="doajax" data-confirm-msg="确定要启用该权限组？">禁用</a>
                                            {{/if}}
                                        {{else}}
                                            {{if $v['isshow'] == 1}}启用<else/>禁用{{/if}}
                                        {{/if}}
                                    </td>
                                    <td>
                                        {{if checkAuth(10)}}
                                            <a href="/admin/auth/editRole?id={{$v['id']}}" class="btn btn-green to-edit" data-toggle="dialog" data-width="800" data-height="600" data-id="dialog-edit-{{$v['id']}}" data-mask="true">编辑</a>
                                        {{/if}}
                                        {{if checkAuth(15)}}
                                        <a href="/admin/auth/delRole?id={{$v['id']}}" class="btn btn-red" data-toggle="doajax" data-confirm-msg="确定要删除该行信息吗？">删</a>
                                        {{/if}}
                                    </td>
                                </tr>
                            {{/foreach}}
                        </tbody>
                    </table>
            </div>
            <div class="tab-pane fade" id="addRole">
                <form action="/admin/auth/addRole" data-toggle="validate" class="pageForm nice-validator n-red" novalidate="novalidate">
                    <div id="nodeContent">
                        <!--存放被选中节点的ID<input name="rule_id[]" type="hidden" value="1">-->
                    </div>
                    <table class="table table-condensed table-hover">
                        <tr>
                            <td>
                                <label for="role_name" class="control-label x85">角色名称：</label>
                                <input type="text" name="role_name" id="role_name" value=""class="form-control"  data-rule="required" data-tip="该项不能为空" placeholder="角色名称" style="width: 200px;">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="control-label x85">是否启用：</label>
                                <input type="radio" name="isshow" value="1" data-toggle="icheck" data-label="启用" checked>
                                <input type="radio" name="isshow" value="0" data-toggle="icheck" data-label="禁用">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="info" class="control-label x85">描述：</label>
                                <textarea id="info" name="info" style="width: 200px;"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="info" class="control-label x85">权限：</label>
                                <ul id="ztree_add" class="ztree" data-toggle="ztree" data-check-enable="true" data-on-check="treeCheckAdd" data-expand-all="false" data-on-click="MainMenuClick" style="margin: -23px 0 0 83px;">
                                    {{foreach $rule as $v}}
                                        <li data-id="{{$v['id']}}" data-pid="{{$v['parent_id']}}" >{{$v['rule_title']}}</li>
                                    {{/foreach}}
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:13px 0 0 111px ;">
                                <button type="submit" class="btn btn-default" data-icon="save"></i> 保存</button>
                                <button type="button" class="btn btn-close" data-icon="close"> 取消</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </fieldset>
</div>
