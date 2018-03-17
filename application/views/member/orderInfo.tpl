<style type="text/css">
    .hiddenTr{display:none}
    .showTr{display:block;}
</style>
<div class="bjui-pageContent">
<form action="/member/member/checkOrder?id={{$data['id']}}" method='post' id="pagerForm" data-toggle="validate">
<fieldset>
    <legend>基本信息</legend>
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true">
        <tr>
            <td>
                <label for="username" class="control-label x90">姓名：</label>
                <span>{{$data['username']}}</span>
            </td>
            <td>
                <label for="mobile" class="control-label x90">手机号码：</label>
                <span>{{$data['mobile']}}</span>
            </td>
        </tr>

        <tr>
            <td>
                <label for="channel" class="control-label x90">进件渠道：</label>
                <span>{{$data['channel']}}</span>
            </td>
            <td>
                <label for="product" class="control-label x90">贷款产品：</label>
                <span>{{$data['product']}}</span>
            </td>
        </tr>
        <tr>
            <td>
                <label for="money" class="control-label x90">贷款额度：</label>
                <span>{{$data['money']}}</span>
            </td>
            <td>
                <label for="rate" class="control-label x90">费率：</label>
                <span>{{$data['rate']}}%</span>
            </td>
        </tr>
        <tr>
            <td>
                <label for="deposit" class="control-label x90">诚意金：</label>
                {{if checkAuth(163)}}
                <input type="text" name="deposit" value="{{$data['deposit']}}" class="form-control">
                {{else}}
                <span>{{$data['deposit']}}</span>
                {{/if}}
                {{if checkAuth(162)}}
                <input type="radio" value="1" name="isBackMoney" data-toggle="icheck" data-label="未退款" {{if $data['isBackMoney'] == 1}}checked{{/if}}>
                <input type="radio" value="2" name="isBackMoney" data-toggle="icheck" data-label="已退款" {{if $data['isBackMoney'] == 2}}checked{{/if}}>
                <input type="radio" value="3" name="isBackMoney" data-toggle="icheck" data-label="转创收" {{if $data['isBackMoney'] == 3}}checked{{/if}}>
                {{/if}}
            </td>
            <td>
                <label for="uid" class="control-label x90">业务员：</label>
                <span>{{$data['firstName']}}</span>
            </td>
        </tr>
        <tr>
            <td>
                <label for="secondUid" class="control-label x90">后勤对接人员：</label>
                <span>{{$data['secondUid']}}</span>
            </td>
            <td>
                <label for="status" class="control-label x90">审核状态：</label>
                <select name="status" id="status" data-toggle="selectpicker" onchange="addInfo(this)">
                    <option {{if $data['status'] == 1}}selected{{/if}} value="1">--请选择--</option>
                    <option {{if $data['status'] == 2}}selected{{/if}} value="2">在审中</option>
                    <option {{if $data['status'] == 3}}selected{{/if}} value="3">已拒款</option>
                    <option {{if $data['status'] == 4}}selected{{/if}} value="4">客户已拒款</option>
                    <option {{if $data['status'] == 5}}selected{{/if}} value="5">未进件</option>
                    {{if checkAuth(164)}}
                    <option {{if $data['status'] == 6}}selected{{/if}} value="6">已收款</option>
                    {{/if}}
                </select>
            </td>
        </tr>

    </table>
    <table style="margin-top: 5px" class="table table-bordered table-hover table-striped table-top" data-selected-multi="true">
        <tfoot class="{{if $data['status'] == 6}}showTr{{else}}hiddenTr{{/if}}">
            <tr>
                <td>
                    <label for="sendMoney" class="control-label x90">批款额度：</label>
                    <input type="text" name="sendMoney" value="{{$data['sendMoney']}}" class="form-control">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="sendTime" class="control-label x90">收款时间：</label>
                     <input data-toggle="datepicker" type="text"
                           value="{{if !empty($data['sendTime']) && $data['sendTime'] != '0000-00-00 00:00:00'}}{{$data['sendTime']}}{{/if}}" name="sendTime" autocomplete="off"
                           placeholder="收款时间"/>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <label for="income" class="control-label x90">创收：</label>
                    <input type="text" name="income" value="{{$data['income']}}" class="form-control">
                </td>
            </tr>
        </tfoot>
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
<script type="text/javascript">
    function addInfo(obj)
    {
        var val = $(obj).val();
        if (val == 6) {
            $('.hiddenTr').removeClass('hiddenTr').addClass('showTr');
        } else {
            $('.showTr').removeClass('showTr').addClass('hiddenTr');
        }
    }
</script>