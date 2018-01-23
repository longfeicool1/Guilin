<style>
    .upper{text-transform:uppercase;}
</style>
<div class="bjui-pageContent" style="width: 49%;">
    <fieldset >
        <legend>基础信息</legend>
        <table class="table table-condensed table-hover" width="800">
            <tr>
                <th>账户</th>
                <td>{{$baseInfo['account_login']}}</td>
                <th>姓名</th>
                <td>{{$baseInfo['username']}}</td>
                <th>性别</th>
                <td>{{$baseInfo['sex']}}</td>
            </tr>
            <tr>
                <th>生日</th>
                <td>{{$baseInfo['birthday']}}</td>
                <th></th>
                <td></td>
                <th></th>
                <td></td>
            </tr>
            <tr>
                <th>可用银行卡：</th>
                <td colspan="5">
                    {{if $baseInfo['bank']}}
                    <table class="table table-condensed table-hover">
                        {{foreach $baseInfo['bank'] as $k => $v}}
                        <tr>
                            <td>{{if $v['status']}}<span class="btn {{if $v['is_show'] == 4}}btn-green{{elseif $v['is_show'] == 2}}btn-red{{/if}}">{{$v['status']}}</span>{{/if}}</td>
                            <th>银行名称</th>
                            <td>{{$v['bankname']}}</td>
                            <th>银行卡号</th>
                            <td>{{$v['bankcode']}}</td>
                        </tr>
                        {{/foreach}}
                    </table>
                    {{/if}}
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset>
        <legend>
            车辆信息
        </legend>
        <table class="table table-condensed table-hover" id="carinfo">
            <tr>
                <th>车型</th>
                <td>{{$carInfo['models']}}</td>
                <th>车牌号</th>
                <td>{{$carInfo['car_card']}}</td>
                <th>车架号</th>
                <td>{{$carInfo['vin']}}</td>
            </tr>
            <tr>
                <th>发动机号</th>
                <td>{{$carInfo['enginecode']}}</td>
                <th>注册日期</th>
                <td>{{if !empty($carInfo['debutdate']) && $carInfo['debutdate'] != '0000-00-00'}}{{$carInfo['debutdate']}}{{/if}}</td>
                <th></th>
                <td></td>
            </tr>
        </table>
    </fieldset>
    <fieldset>
        <table class="table table-condensed table-hover" id="carinfo">
            <tr>
                <td>设备信息</td>
                <td align="right" colspan="3"><a href="user/yg/srcLists?bind_id={{$carInfo['bindid']}}" class="btn btn-blue" data-toggle="dialog" data-width="600" data-height="300">更换设备记录</a></td>
            </tr>
            <tr>
                <td>设备ID</td>
                <td>{{$carInfo['src']}}</td>
                <td>设备状态</td>
                <td>{{$carInfo['src_status']}}</td>
            </tr>
        </table>
    </fieldset>
    <table class="table table-condensed table-hover" id="carinfo">
        <tr>
            <td>驾驶行为</td>
            <td align="right"><a href="user/yg/driveLists?bind_id={{$carInfo['bindid']}}" data-id="driveLists" class="btn btn-blue" data-width="880" data-height="600" data-toggle="dialog" data-title="[{{$baseInfo['username']}}]驾驶行为">查看</a></td>
        </tr>
        <tr>
            <td>行驶轨迹</td>
            <td align="right"><a href="user/actual/actualLists?bindid={{$carInfo['bindid']}}" class="btn btn-blue" data-id="actualLists" data-width="1080" data-height="600" data-toggle="dialog" data-title="[{{$baseInfo['username']}}]行驶轨迹">查看</a></td>
        </tr>
        <tr>
            <td>账户记录</td>
            <td align="right"><a href="user/yg/walletTask?uid={{$baseInfo['pid']}}&bid={{$carInfo['bindid']}}" data-id="walletTask" class="btn btn-blue" data-width="800" data-height="600" data-toggle="dialog" data-title="[{{$baseInfo['username']}}]账户记录">查看</a></td>
        </tr>
    </table>

    <!--
    <table class="table table-condensed table-hover" id="carinfo">
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td align="center"> <button type="button" class="btn-close" data-icon="close">关闭</button></td>
        </tr>
    </table>
    -->
</div>
