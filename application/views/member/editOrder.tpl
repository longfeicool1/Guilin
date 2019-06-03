<div class="bjui-pageContent">
<form action="/member/member/checkOrder?id={{$data['id']}}" method='post' id="pagerForm" data-toggle="validate">
<fieldset>
    <legend>基本信息</legend>
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true">
        <tr>
            <td>
                <label for="mobile" class="control-label x90">手机号码：</label>
                <input type="text" name="mobile" value="{{$data['mobile']}}" class="form-control"  {{if !checkAuth(184)}}disabled{{/if}}>
            </td>
        </tr>
        <tr>
            <td>
                <label for="username" class="control-label x90">姓名：</label>
                <input type="text" name="username" value="{{$data['username']}}" class="form-control" {{if !checkAuth(183)}}disabled{{/if}}>
            </td>
        </tr>
        <tr>
            <td>
                <label for="city" class="control-label x90">城市：</label>
                <input type="text" name="city" value="{{$data['city']}}" class="form-control">
            </td>
        </tr>
        <tr>
            <td>
                <label for="source" class="control-label x90">来源：</label>
                <input type="text" name="source" value="{{$data['source']}}" class="form-control">
            </td>
        </tr>
        <tr>
            <td>
                <label for="channel" class="control-label x90">进件渠道：</label>
                <input type="text" name="channel" value="{{$data['channel']}}" class="form-control" {{if $data['status'] == 6}}disabled{{/if}}>
            </td>
        </tr>
        <tr>
            <td>
                <label for="product" class="control-label x90">贷款产品：</label>
                <input type="text" name="product" value="{{$data['product']}}" class="form-control" {{if $data['status'] == 6}}disabled{{/if}}>
            </td>
        </tr>
        <tr>
            <td>
                <label for="money" class="control-label x90">贷款额度：</label>
                <input type="text" name="money" value="{{$data['money']}}" class="form-control" {{if $data['status'] == 6}}disabled{{/if}}>
            </td>
        </tr>
        <tr>
            <td>
                <label for="rate" class="control-label x90">费率：</label>
                <input type="text" name="rate" value="{{$data['rate']}}" class="form-control" {{if $data['status'] == 6}}disabled{{/if}}>
                <span>%</span>
            </td>
        </tr>
        <tr>
            <td>
                <label for="deposit" class="control-label x90">诚意金：</label>
                <span>{{$data['deposit']}}</span>
            </td>
        </tr>
        <tr>
            <td>
                <label for="uid" class="control-label x90">业务员：</label>
                {{if checkAuth(191)}}
                <input type="hidden" name="uid" value="{{if !empty($data['uid'])}}{{$data['uid']}}{{/if}}">
                <input type="text"
                    data-toggle="tags"
                    data-width="180"
                    data-clear="true"
                    data-url="/member/member/searchUser"
                    placeholder="输入业务员自动查找"
                    data-max=1
                    value="{{$data['firstName']}}"
                    autocomplete="off">
                {{else}}
                <span>{{$data['firstName']}}</span>
                {{/if}}
            </td>
        </tr>
        <tr>
            <td>
                <label for="secondUid" class="control-label x90">后勤对接人员：</label>
                <input type="text" name="secondUid" value="{{$data['secondUid']}}" class="form-control" {{if !checkAuth(185)}}disabled{{/if}}>
            </td>
        </tr>
        <tr>
            <td>
                <label for="status" class="control-label x90">审核状态：</label>
                {{if $data['status'] == 6}}
                <span>已收款</span>
                {{else}}
                <select name="status" id="status" data-toggle="selectpicker">
                    <option {{if $data['status'] == 1}}selected{{/if}} value="1">--请选择--</option>
                    <option {{if $data['status'] == 2}}selected{{/if}} value="2">在审中</option>
                    <option {{if $data['status'] == 3}}selected{{/if}} value="3">已拒款</option>
                    <option {{if $data['status'] == 4}}selected{{/if}} value="4">客户已拒款</option>
                    <option {{if $data['status'] == 5}}selected{{/if}} value="5">未进件</option>
                </select>
                {{/if}}

            </td>
        </tr>

    </table>
</fieldset>
{{if $data['status'] == 6}}
<fieldset>
    <legend>结单信息</legend>
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true">
        <tr>
            <td>
                <label for="sendMoney" class="control-label x90">批款额度：</label>
                <span>{{$data['sendMoney']}}</span>
            </td>
            <td>
                <label for="sendTime" class="control-label x90">收款时间：</label>
                <span>{{if $data['sendTime'] != '0000-00-00 00:00:00'}}{{$data['sendTime']}}{{/if}}</span>
            </td>
        </tr>
        <tr>
            <td>
                <label for="income" class="control-label x90">创收：</label>
                <span>{{$data['income']}}</span>
            </td>
            <td>
                <label for="income" class="control-label x90">诚意金去向：</label>
                {{if $data['isBackMoney'] == 1}}<span>未退款</span>{{/if}}
                {{if $data['isBackMoney'] == 2}}<span>已退款</span>{{/if}}
                {{if $data['isBackMoney'] == 3}}<span>转创收</span>{{/if}}
            </td>
        </tr>
    </table>
</fieldset>
{{/if}}
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