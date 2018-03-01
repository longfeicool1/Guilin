<div class="bjui-pageContent">
<form action="/member/member/checkOrder?id={{$data['id']}}" method='post' id="pagerForm" data-toggle="validate">
<fieldset>
    <legend>基本信息</legend>
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true">
        <tr>
            <td>
                <label for="username" class="control-label x90">姓名：</label>
                <input type="text" value="{{$data['username']}}" class="form-control" disabled>
            </td>
            <td>
                <label for="mobile" class="control-label x90">手机号码：</label>
                <input type="text" value="{{$data['mobile']}}" class="form-control" disabled>
            </td>
        </tr>
        <tr>
            <td>
                <label for="channel" class="control-label x90">进件渠道：</label>
                <input type="text" name="channel" value="{{$data['channel']}}" class="form-control">
            </td>
            <td>
                <label for="product" class="control-label x90">贷款产品：</label>
                <input type="text" name="product" value="{{$data['product']}}" class="form-control">
            </td>
        </tr>
        <tr>
            <td>
                <label for="money" class="control-label x90">贷款额度：</label>
                <input type="text" name="money" value="{{$data['money']}}" class="form-control">
            </td>
            <td>
                <label for="rate" class="control-label x90">费率：</label>
                <input type="text" name="rate" value="{{$data['rate']}}" class="form-control">
            </td>
        </tr>
        <tr>
            <td>
                <label for="deposit" class="control-label x90">诚意金：</label>
                <span>{{$data['deposit']}}</span>
            </td>
            <td>
                <label for="uid" class="control-label x90">业务员：</label>
                <span>{{$data['firstName']}}</span>
            </td>
        </tr>
        <tr>
            <td>
                <label for="secondUid" class="control-label x90">后勤对接人员：</label>
                <input type="text" value="{{$data['secondUid']}}" class="form-control" disabled>
            </td>
            <td>
                <label for="status" class="control-label x90">审核状态：</label>
                <select name="status" id="status" data-toggle="selectpicker">
                    <option {{if $data['status'] == 1}}selected{{/if}} value="1">--请选择--</option>
                    <option {{if $data['status'] == 2}}selected{{/if}} value="2">在审中</option>
                    <option {{if $data['status'] == 3}}selected{{/if}} value="3">已拒款</option>
                    <option {{if $data['status'] == 4}}selected{{/if}} value="4">客户已拒款</option>
                    <option {{if $data['status'] == 5}}selected{{/if}} value="5">未进件</option>
                    {{if !in_array($this->userinfo['position'],[1,5])}}
                    <option {{if $data['status'] == 6}}selected{{/if}} value="6">已收款</option>
                    {{/if}}
                </select>
            </td>
        </tr>

    </table>
</fieldset>
</form>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li>
            <button type="button" class="btn-close" data-icon="close">取消</button>
        </li>
        {{if $data['status'] != 6}}
        <li>
            <button type="submit" class="btn-default" data-icon="save">保存</button>
        </li>
        {{/if}}
    </ul>
</div>