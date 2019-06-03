<div class="bjui-pageContent">
    <form action="/admin/auth/addRole?role_id={{$role['id']}}" class="pageForm" data-toggle="validate">
        <div id="editContent">
            <!--存放被选中节点的ID<input name="rule_id[]" type="hidden" value="1">-->
            {{foreach $rid as $v}}
                <input name="rule_id[]" type="hidden" value="{{$v}}">
            {{/foreach}}
        </div>
        <table class="table table-condensed table-hover">
                <tr>
                    <td>
                        <label for="role_name" class="control-label x85">角色名称：</label>
                        <input type="text" name="role_name" id="role_name" value="{{$role['role_name']}}"class="form-control"  data-rule="required" data-tip="该项不能为空" placeholder="角色名称" style="width: 200px;">
                    </td>
                </tr>
                <tr id="roleType2">
                            <td>
                                <label class="control-label x85">角色类型：</label>
                                <input type="radio" name="is_finance" value="1" data-toggle="icheck" data-label="普通角色" {{if $role['is_finance'] == 1}}checked {{/if}}>
                                <input type="radio" name="is_finance" value="2" data-toggle="icheck" data-label="财务角色" {{if $role['is_finance'] == 2}}checked {{/if}}>
                            </td>
                        </tr>
                        <tr id="checkcity2" {{if $role['is_finance'] == 1}} style="display: none" {{/if}}>
                            <td>
                                <label class="control-label x85">审件城市：</label>
                                {{foreach $city as $v}}
                                <input type="checkbox" name="look_city[]" value="{{$v['city']}}" data-toggle="icheck" data-label="{{$v['city']}}" {{if in_array($v['city'],explode(',',$role['look_city']))}}checked{{/if}}>
                                {{/foreach}}
                            </td>
                        </tr>
                <tr>
                    <td>
                        <label class="control-label x85">是否启用：</label>
                        <input type="radio" name="isshow" value="1" data-toggle="icheck" data-label="启用" {{if $role['isshow'] == 1}}checked{{/if}}>
                        <input type="radio" name="isshow" value="0" data-toggle="icheck" data-label="禁用" {{if $role['isshow'] == 0}}checked{{/if}}>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="info" class="control-label x85">描述：</label>
                        <textarea id="info" name="info" style="width: 200px;">{{$role['info']}}</textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="info" class="control-label x85">权限：</label>
                        <ul id="ztree_edit" class="ztree" data-toggle="ztree" data-on-check="treeCheckEdit" data-check-enable="true" data-expand-all="true"  data-on-click="MainMenuClick" style="margin: -23px 0 0 83px;">

                            {{foreach $rule as $v}}
                                <li data-id="{{$v['id']}}" data-pid="{{$v['parent_id']}}" {{if in_array($v['id'],$rid)}}data-checked="true"{{/if}}{{if !checkAuth($v['id'])}}data-chk-disabled="true" {{/if}} >
                                    {{$v['rule_title']}}
                                </li>
                            {{/foreach}}
                        </ul>
                    </td>
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
<script type="text/javascript">
    // $('radio[name=is_finance]').change(function() {
    //     console.log(11)
    //     // if ($('radio[name=is_finance]:checked').val() == 2) {
    //     //     $('#carinfo').addClass('hidden')
    //     // } else {
    //     //     $('#carinfo').removeClass('hidden')
    //     // }
    // });

    $('#roleType2 input[type="radio"]:checked').on('ifChanged', function(e) {
        if ($('#roleType2 input[type="radio"]:checked').val() == 1) {
            $('#checkcity2').hide()
        } else {
            $('#checkcity2').show()
        };
    })
</script>