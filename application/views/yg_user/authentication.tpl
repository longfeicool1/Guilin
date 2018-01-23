<form action="user/check/toUpdate?bindid={{$bindid}}" method='post' id="pagerForm" data-toggle="validate">
    <div class="bjui-pageContent">
        <table class="table">
            <tbody>
            <tr>
                <td>
                    <label for="carcard" class="control-label x90">新车牌</label>
                    <input name="carcard" value="" />
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
                <td>
                    <label for="insure_code" class="control-label x90">是否认证</label>
                    <select name="is_check" id="is_check" data-toggle="selectpicker">
                        <option value="">--是否认证--</option>
                        <option value="1" {{if !empty($search['is_check']) && $search['is_check'] == 1}}selected{{/if}}>未认证</option>
                        <option value="2" {{if !empty($search['is_check']) && $search['is_check'] == 2}}selected{{/if}}>认证成功</option>
                        <option value="3" {{if !empty($search['is_check']) && $search['is_check'] == 3}}selected{{/if}}>认证失败</option>
                    </select>
                </td>
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

