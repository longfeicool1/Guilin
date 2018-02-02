<fieldset>
    <legend>基本信息</legend>
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true">
        <tr>
            <td>
                <label for="name" class="control-label x90">姓名</label>
                <input type="text" name="name" id="name" value="{{$data['name']}}" class="form-control">
            </td>
            <td>
                <label for="mobile" class="control-label x90">手机号码：</label>
                <input type="text" name="mobile" data-rule="required;mobile;" value="{{$data['mobile']}}" class="form-control">
            </td>
        </tr>
        <tr>
            <td>
                <label for="sex" class="control-label x90">性别：</label>
                <select name="sex" id="sex" data-toggle="selectpicker">
                    <option value="1" {{if $data['sex'] == 1}}selected{{/if}}>男</option>
                    <option value="2" {{if $data['sex'] == 2}}selected{{/if}}>女</option>
                </select>
            </td>
            <td>
                <label for="age" class="control-label x90">年龄：</label>
                <input type="text" name="age" value="{{$data['age']}}" class="form-control">
            </td>
        </tr>
        <tr>
            <td>
                <label for="city" class="control-label x90">城市：</label>
                <input type="text" name="city" id="city" value="{{$data['city']}}" class="form-control">
            </td>
            <td>
                <label for="mobile" class="control-label x90">职业：</label>
                <input type="text" name="mobile" value="{{$data['occapation']}}" class="form-control">
            </td>
        </tr>
        <tr>
            <td>
                <label for="city" class="control-label x90">发薪方式：</label>
                <select name="sex" id="sex" data-toggle="selectpicker" data-width="200">
                    {{foreach $payType as $k => $v}}
                    <option value="{{$k}}" {{if $data['payType'] == $k}}selected{{/if}}>{{$v}}</option>
                    {{/foreach}}
                </select>
            </td>
            <td>
                <label for="income" class="control-label x90">收入：</label>
                <input type="text" name="income" value="{{$data['income']}}" class="form-control">
            </td>
        </tr>
        <tr>
            <td>
                <label for="socialSecurity" class="control-label x90">是否有社保：</label>
                <select name="socialSecurity" id="socialSecurity" data-toggle="selectpicker">
                    <option value="1" {{if $data['socialSecurity'] == 1}}selected{{/if}}>否</option>
                    <option value="2" {{if $data['socialSecurity'] == 2}}selected{{/if}}>是</option>
                </select>
            </td>
            <td>
                <label for="reservedFunds" class="control-label x90">是否有公积金：</label>
                <select name="reservedFunds" id="reservedFunds" data-toggle="selectpicker">
                    <option value="1" {{if $data['reservedFunds'] == 1}}selected{{/if}}>否</option>
                    <option value="2" {{if $data['reservedFunds'] == 2}}selected{{/if}}>是</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="haveHouse" class="control-label x90">是否有房：</label>
                <select name="haveHouse" id="haveHouse" data-toggle="selectpicker">
                    <option value="1" {{if $data['haveHouse'] == 1}}selected{{/if}}>否</option>
                    <option value="2" {{if $data['haveHouse'] == 2}}selected{{/if}}>是</option>
                </select>
            </td>
            <td>
                <label for="haveCar" class="control-label x90">是否有车：</label>
                <select name="haveCar" id="haveCar" data-toggle="selectpicker">
                    <option value="1" {{if $data['haveCar'] == 1}}selected{{/if}}>否</option>
                    <option value="2" {{if $data['haveCar'] == 2}}selected{{/if}}>是</option>
                </select>
            </td>
            <tr>
            <td>
                <label for="insureCode" class="control-label x90">保单：</label>
                <input type="text" name="insureCode" value="{{$data['insureCode']}}" class="form-control">
            </td>
            <td>
                <label for="weiMoney" class="control-label x90">微粒贷额度：</label>
                <input type="text" name="weiMoney" value="{{$data['weiMoney']}}" class="form-control">
            </td>
        </tr>
        </tr>
    </table>
</fieldset>
<fieldset>
    <legend>数据属性</legend>
    <table class="table table-bordered table-hover table-striped table-top table_width" data-selected-multi="true">
        <tbody>
        </tbody>
    </table>
</fieldset>
<fieldset>
    <legend>我的操作</legend>
    <table class="table table-bordered table-hover table-striped table-top table_width" data-selected-multi="true">
        <tbody>
        </tbody>
    </table>
</fieldset>