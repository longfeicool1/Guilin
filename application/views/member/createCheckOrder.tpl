<div class="bjui-pageContent">
<form action="/member/member/toCreateOrder" method='post' id="pagerForm" data-toggle="validate">
<input type="hidden" name="customId" value="{{$id}}">
<fieldset>
    <legend>基本信息</legend>
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true">
        <tr>
            <td>
                <label for="username" class="control-label x90">姓名：</label>
                <input type="text" name="username" id="username" data-rule="required;" value="{{if !empty($data) && $data['name']}}{{$data['name']}}{{/if}}" class="form-control">
            </td>
            <td>
                <label for="mobile" class="control-label x90">手机号码：</label>
                <input type="text" name="mobile" data-rule="required;mobile;" value="{{if !empty($data) && $data['mobile']}}{{$data['mobile']}}{{/if}}" class="form-control">
            </td>
        </tr>
        <tr>
            <td>
                <label for="channel" class="control-label x90">进件渠道：</label>
                <input type="text" name="channel" data-rule="required;" value="" class="form-control">
            </td>
            <td>
                <label for="product" class="control-label x90">贷款产品：</label>
                <input type="text" name="product" data-rule="required;" value="" class="form-control">
            </td>
        </tr>
        <tr>
            <td>
                <label for="money" class="control-label x90">贷款额度：</label>
                <input type="text" name="money" data-rule="required;" value="" class="form-control">
            </td>
            <td>
                <label for="rate" class="control-label x90">费率：</label>
                <input type="text" name="rate" data-rule="required;" value="1" class="form-control">
                <span>%</span>
            </td>
        </tr>
        <tr>
            <td>
                <label for="deposit" class="control-label x90">诚意金：</label>
                <input type="text" name="deposit" data-rule="required;" value="" class="form-control">
            </td>
            <td>
                <label for="uid" class="control-label x90">业务员：</label>
                <span>{{$userinfo['name']}}</span>
                <input type="hidden" name="uid" value="{{$userinfo['uid']}}">
            </td>
        </tr>
        <tr>
            <td>
                <label for="secondUid" class="control-label x90">后勤对接人员：</label>
                <input type="text" name="secondUid" data-rule="required;" value="" class="form-control">
            </td>
            <td>
                <label for="status" class="control-label x90">审核状态：</label>
                <select name="status" id="status" data-toggle="selectpicker">
                    <option value="1">--请选择--</option>
                    <option value="2">在审中</option>
                    <option value="3">已拒款</option>
                    <option value="4">客户已拒款</option>
                    <option value="5">未进件</option>
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
        <li>
            <button type="submit" class="btn-default" data-icon="save">保存</button>
        </li>
    </ul>
</div>