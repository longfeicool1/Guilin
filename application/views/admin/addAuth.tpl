<div class="bjui-pageContent">
    <form action="/admin/auth/toAuth" class="pageForm" data-toggle="validate">
        <table class="table table-condensed table-hover">
                <tr>
                    <td width="380">
                        <label for="rule_title" class="control-label x85">权限名称：</label>
                        <input type="text" name="rule_title" id="rule_title" value=""class="form-control"  data-rule="required" data-tip="该项不能为空" placeholder="中文名称" style="width: 200px;">
                    </td>
                </tr>
                <tr>
                    <td width="380">
                        <label for="action_name" class="control-label x85">控制器名：</label>
                        <input type="text" name="action_name" id="action_name" value=""class="form-control"  data-rule="required" data-tip="该项不能为空" placeholder="action_name" style="width: 200px;">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="url" class="control-label x85">权限地址：</label>
                        <input type="text" name="url" id="url" value=""class="form-control" placeholder="/admin/user/info" style="width: 200px;">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="path" class="control-label x85">父级：</label>
                        <select name="path" id="path" data-toggle="selectpicker">
                            {{foreach $rules as $v}}
                                <option value="{{$v['path']}},{{$v['id']}}">{{if !empty($v['r'])}}{{$v['r']}}{{/if}}{{$v['rule_title']}}</option>
                            {{/foreach}}
                        </select>
                    </td>
                </tr>
                <tr>
                    <!-- <td>
                        <label for="level" class="control-label x85">菜单等级：</label>
                        <select name="level" data-toggle="selectpicker">
                            <option value="1">一级</option>
                            <option value="2">二级</option>
                            <option value="3">三级</option>
                        </select>
                    </td> -->
                    <td>
                        <label for="item_type" class="control-label x85">菜单类型：</label>
                        <select name="item_type" data-toggle="selectpicker">
                            <option value="1">顶级菜单</option>
                            <option value="2">侧边菜单</option>
                            <option value="3">功能按钮</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="control-label x85">是否启用：</label>
                        <input type="radio" name="isshow" value="1" data-toggle="icheck" data-label="启用" checked>
                        <input type="radio" name="isshow" value="0" data-toggle="icheck" data-label="禁用">
                    </td>
                    <td colspan="2">&nbsp;</td>
                </tr>
            </table>
    </form>
</div>

<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close">关闭</button></li>
        <li><button type="submit" class="btn-default">保存</button></li>
    </ul>
</div>