<style type="text/css">
    .fontcolor{color: red}
</style>
<div class="bjui-pageContent">
<form action="/member/member/updateInfo?id={{$data['id']}}" method='post' id="pagerForm" data-toggle="validate">
<fieldset>
    <legend>基本信息</legend>
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true">
        <tr>
            <td colspan="2">
                <label for="name" class="control-label x130">姓名：</label>
                <input type="text" name="name" id="name" value="{{$data['name']}}" class="form-control">
            </td>
            <td colspan="2">
                <label for="mobile" class="control-label x130">手机号码：</label>
                <input type="text" name="mobile" data-rule="required;mobile;" value="{{$data['mobile']}}" class="form-control">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="sex" class="control-label x130">性别：</label>
                <select name="sex" id="sex" data-toggle="selectpicker">
                    <option value="1" {{if $data['sex'] == 1}}selected{{/if}}>男</option>
                    <option value="2" {{if $data['sex'] == 2}}selected{{/if}}>女</option>
                </select>
            </td>
            <td colspan="2">
                <label for="age" class="control-label x130">年龄：</label>
                <input type="text" name="age" value="{{$data['age']}}" class="form-control">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="city" class="control-label x130">城市：</label>
                <input type="text" name="city" id="city" value="{{$data['city']}}" class="form-control">
            </td>
            <td colspan="2">
                <label for="occapation" class="control-label x130">职业：</label>
                <input type="text" name="occapation" value="{{$data['occapation']}}" class="form-control">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="payType" class="control-label x130">发薪方式：</label>
                <select name="payType" id="payType" data-toggle="selectpicker" data-width="200">
                    {{foreach $payType as $k => $v}}
                    <option value="{{$k}}" {{if $data['payType'] == $k}}selected{{/if}}>{{$v}}</option>
                    {{/foreach}}
                </select>
            </td>
            <td colspan="2">
                <label for="income" class="control-label x130">收入：</label>
                <input type="text" name="income" value="{{$data['income']}}" class="form-control">
            </td>
        </tr>
        <tr>
            <td>
                <label for="socialSecurity" class="control-label x130">是否有社保：</label>
                <select name="socialSecurity" id="socialSecurity" data-toggle="selectpicker">
                    <option value="1" {{if $data['socialSecurity'] == 1}}selected{{/if}}>否</option>
                    <option value="2" {{if $data['socialSecurity'] == 2}}selected{{/if}}>是</option>
                </select>
            </td>
            <td>
                <label for="reservedFunds" class="control-label x130">是否有公积金：</label>
                <select name="reservedFunds" id="reservedFunds" data-toggle="selectpicker">
                    <option value="1" {{if $data['reservedFunds'] == 1}}selected{{/if}}>否</option>
                    <option value="2" {{if $data['reservedFunds'] == 2}}selected{{/if}}>是</option>
                </select>
            </td>
            <td>
                <label for="haveHouse" class="control-label x130">是否有房：</label>
                <select name="haveHouse" id="haveHouse" data-toggle="selectpicker">
                    <option value="1" {{if $data['haveHouse'] == 1}}selected{{/if}}>否</option>
                    <option value="2" {{if $data['haveHouse'] == 2}}selected{{/if}}>是</option>
                </select>
                <input type="checkbox" name="hourseDai" data-toggle="icheck" data-label="房贷" {{if $data['hourseDai'] == 2}}checked{{/if}}>
            </td>
            <td>
                <label for="haveCar" class="control-label x130">是否有车：</label>
                <select name="haveCar" id="haveCar" data-toggle="selectpicker">
                    <option value="1" {{if $data['haveCar'] == 1}}selected{{/if}}>否</option>
                    <option value="2" {{if $data['haveCar'] == 2}}selected{{/if}}>是</option>
                </select>
                <input type="checkbox" name="carDai" data-toggle="icheck" data-label="车贷" {{if $data['carDai'] == 2}}checked{{/if}}>
            </td>
        </tr>
        <tr>
            <td>
                <label for="haveCredit" class="control-label x130">是否有信用卡：</label>
                <select name="haveCredit" id="haveCredit" data-toggle="selectpicker">
                    <option value="1" {{if $data['haveCredit'] == 1}}selected{{/if}}>否</option>
                    <option value="2" {{if $data['haveCredit'] == 2}}selected{{/if}}>是</option>
                </select>
            </td>
            <td colspan="2">
                <label for="insureCode" class="control-label x130">是否有寿险保单：</label>
                <select name="insureCode" id="insureCode" data-toggle="selectpicker">
                    <option value="1" {{if $data['insureCode'] == 1}}selected{{/if}}>否</option>
                    <option value="2" {{if $data['insureCode'] == 2}}selected{{/if}}>是</option>
                </select>
            </td>
            <td>
                <label for="weiMoney" class="control-label x130">微粒贷：</label>
                <input type="text" name="weiMoney" value="{{$data['weiMoney']}}" class="form-control">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="daiMoney" class="control-label x130">贷款额度：</label>
                <input type="text" name="daiMoney" value="{{$data['daiMoney']}}" class="form-control">
            </td>
            <td colspan="2">
                <label for="daiTime" class="control-label x130">贷款时间：</label>
                <input type="text" name="daiTime" value="{{$data['daiTime']}}" class="form-control">
            </td>
        </tr>
    </table>
</fieldset>
<fieldset>
    <legend>数据属性</legend>
    <table class="table table-bordered table-hover table-striped table-top table_width" data-selected-multi="true">
        <tr>
            <td>
                <label class="control-label x130">导入时间：</label>
                <span>{{$data['created']}}</span>
            </td>
            <td>
                <label class="control-label x130">数据类型：</label>
                <span>{{$data['dataLevel']}}</span>
            </td>
        </tr>
        <tr>
            <td>
                <label for="insureCode" class="control-label x130">渠道来源：</label>
                <span>{{$data['source']}}</span>
            </td>
            <td>
                <label for="weiMoney" class="control-label x130">跟单人员：</label>
                <span>{{if !empty($data['firstName'])}}{{$data['firstName']}}{{else}}{{$data['secondName']}}{{/if}}</span>
            </td>
        </tr>
    </table>
</fieldset>
<fieldset>
    <legend>我的操作</legend>
    <table class="table table-bordered table-hover table-striped table-top table_width" data-selected-multi="true">
        <tr>
            <td>
                <label for="callType" class="control-label x130">
                <span class="fontcolor"><i class="fa fa-star" aria-hidden="true"></i></span>
                通话记录：</label>
                <select name="callType" id="callType" data-toggle="selectpicker" data-rule="required;">
                    {{foreach $callType as $k => $v}}
                    <option value="{{$k}}" {{if $data['callType'] == $k}}selected{{/if}}>{{$v}}</option>
                    {{/foreach}}
                </select>
            </td>
            <td>
                <label for="meetTime" class="control-label x130">预约时间：</label>
                <input type="text" name="meetTime" id="meetTime"
                value="{{$data['meetTime']}}" class="form-control" placeholder="尚未预约"
                data-toggle="datepicker" data-pattern="yyyy-MM-dd HH:mm:ss" data-min-date="%y-%M-%d">
            </td>
        </tr>
        <tr>
            <td>
                <label for="customLevel" class="control-label x130">
                <span class="fontcolor"><i class="fa fa-star" aria-hidden="true"></i></span>
                名单等级：</label>
                <select name="customLevel" id="customLevel" data-toggle="selectpicker" data-width="200" data-nextselect="#customStatus" data-refurl="/member/member/changeCustomStatus?customLevel={value}">
                    {{foreach $customLevel as $k => $v}}
                    <option value="{{$k}}" {{if $data['customLevel'] == $k}}selected{{/if}}>{{$v}}</option>
                    {{/foreach}}
                </select>
            </td>
            <td>
                <label for="customStatus" class="control-label x130">
                    <span class="fontcolor"><i class="fa fa-star" aria-hidden="true"></i></span>
                状态：</label>
                <select name="customStatus" id="customStatus" data-toggle="selectpicker">
                    <!-- <option>--选择状态--</option> -->
                    {{if !empty($customStatus)}}
                        {{foreach $customStatus as $v}}
                        <option value="{{$v['value']}}" {{if $data['customStatus'] == $v['value']}}selected{{/if}}>{{$v['label']}}</option>
                        {{/foreach}}
                    {{/if}}
                </select>
                <span><span style="color: red">*</span>请先选择名单等级</span>
            </td>
        </tr>

    </table>
    <table class="table table-bordered table-hover table-striped table-top table_width" data-selected-multi="true" style="margin-top:5px ">
        <tr>
            <td colspan="3">
                <label for="lastComment" class="control-label x130">
                <span class="fontcolor"><i class="fa fa-star" aria-hidden="true"></i></span>
                新增备注：</label>
                <textarea class="form-control autosize" name="lastComment" rows="3" cols="45" data-rule="required;"></textarea>
            </td>
        </tr>
        <!-- <tr>
            <td colspan="3">
                <label for="content" class="control-label x130">历史备注：</label>
            </td>
        </tr> -->
        {{if !empty($comment)}}
            {{foreach $comment as $v}}
            <tr>
                <td>
                    {{$v['content']}}
                </td>
                <td>
                    {{$v['created']}}
                </td>
                <td>
                    {{$v['name']}}
                </td>
            </tr>
            {{/foreach}}
        {{else}}
            <tr>
                <td colspan="3">暂无备注</td>
            </tr>
        {{/if}}
    </table>
</fieldset>
</form>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li>
            <button type="button" class="btn-close" data-icon="close">取消</button>
        </li>
        {{if $watch !=1}}
        <li>
            <button type="submit" class="btn-default" data-icon="save">保存</button>
        </li>
        {{/if}}
    </ul>
</div>