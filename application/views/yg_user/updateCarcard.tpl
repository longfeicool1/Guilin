<form action="user/check/toUpdate?bindid={{$bindid}}" method='post' id="pagerForm" data-toggle="validate">
    <div class="bjui-pageContent">
        <table class="table">
            <tbody>
            <tr>
                <td>
                    <label class="control-label x90">现车牌</label>
                    <input value="{{$data['carcard']}}" disabled />
                    &nbsp;
                    {{if $data['cardtype'] == 1}}<span style="color: red">*&nbsp;临时车牌</span>{{/if}}
                </td>
                <td>
                    <label for="carcard" class="control-label x90">新车牌</label>
                    <input name="carcard" value="" />
                    <input type="radio" name="cardtype" data-toggle="icheck" data-label="正常牌" checked>
                    <input type="radio" name="cardtype" data-toggle="icheck" data-label="临时牌">
                </td>
            </tr>
            <tr>
                <td>
                    <label class="control-label x90">商险保单</label>
                    <input value="{{$data['insure_code']}}" placeholder="该用户尚未填写保单" disabled/>
                </td>
                <td>
                    <label for="insure_code" class="control-label x90">修改保单</label>
                    <input name="insure_code" value="" />
                </td>
            </tr>
            <tr>
                <td {{if $data['is_check'] == 2}}colspan="2"{{/if}}>
                    <label class="control-label x90">当前认证状态</label>
                    <select data-toggle="selectpicker" disabled>
                        <option value="1" {{if !empty($search['is_check']) && $search['is_check'] == 1}}selected{{/if}}>未认证</option>
                        <option value="2" {{if !empty($search['is_check']) && $search['is_check'] == 2}}selected{{/if}}>认证成功</option>
                        <option value="3" {{if !empty($search['is_check']) && $search['is_check'] == 3}}selected{{/if}}>认证失败</option>
                    </select>
                    &nbsp;
                    {{if $data['is_check'] == 2}}<span style="color: red">*&nbsp;已认证状态无法更改</span>{{/if}}
                </td>
                {{if $data['is_check'] != 2}}
                <td>
                    <label for="insure_code" class="control-label x90">更改认证状态</label>
                    <select name="is_check" id="is_check" data-toggle="selectpicker">
                        <option value="1" {{if !empty($search['is_check']) && $search['is_check'] == 1}}selected{{/if}}>未认证</option>
                        <option value="2" {{if !empty($search['is_check']) && $search['is_check'] == 2}}selected{{/if}}>认证成功</option>
                        <option value="3" {{if !empty($search['is_check']) && $search['is_check'] == 3}}selected{{/if}}>认证失败</option>
                    </select>
                </td>
                {{/if}}
            </tr>
            </tbody>
        </table>
    </div>
    <div class="bjui-pageFooter">
        <ul>
            <li>
                <button type="button" class="btn-close" data-icon="close">取消</button>
            </li>
            <li>
                <button type="submit" class="btn-default" data-icon="save">保存</button>
            </li>
        </ul>
    </div>
</form>

