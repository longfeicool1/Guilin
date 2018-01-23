<div class="bjui-pageContent">
    <table class="table">
        <tbody>
        <tr>
            <td>名称：</td>
            <td colspan="4">{{$gameInfo['name']}}</td>
        </tr>
        <tr>
            <td>开始时间：</td>
            <td>{{$gameInfo['start']}}</td>
            <td>截止时间：</td>
            <td>{{$gameInfo['end']}}</td>
        </tr>
        <tr>
            <td>总会员数：</td>
            <td>{{$gameInfo['ygNum']}}</td>
            <td>安装设备会员数：</td>
            <td>{{$gameInfo['active_num']}}</td>
        </tr>
        <tr>
            <td>安装参与人数：</td>
            <td>{{$gameInfo['user_num']}}</td>
            <td>安装参与率：</td>
            <td>{{$gameInfo['user_rate']}}</td>
        </tr>
        <tr>
            <td>游玩次数：</td>
            <td>{{$gameInfo['join_num']}}</td>
            <td>成功比失败</td>
            <td>{{$gameInfo['ok_rate']}} ：{{$gameInfo['on_rate']}} </td>
        </tr>
        <tr>
            <td>成功数：</td>
            <td>{{$gameInfo['ok_num']}}</td>
            <td>成功比例：</td>
            <td>{{$gameInfo['join_ok_rate']}}</td>
        </tr>
        <tr>
            <td>失败数：</td>
            <td>{{$gameInfo['no_ok_num']}}</td>
            <td>失败比例：</td>
            <td>{{$gameInfo['join_no_rate']}}</td>
        </tr>
        </tbody>
    </table>

    <table class="table">
        <tbody>
        <tr>
            <th colspan="3">每日金额发放统计</th>
        </tr>
        <tr>
            <td>日期</td>
            <td>发放金额：</td>
            <td>抽取金额：</td>
        </tr>
        {{foreach $gameInfo['money'] as $k => $v }}
        <tr>
            <td>{{$v['day_date']}} </td>
            <td>{{$v['send_money'] / 100}}</td>
            <td>{{$v['receive_money'] / 100}}</td>
        </tr>
        {{foreachelse}}
        <tr>
            <td colspan="3" align="center">暂无数据</td>
        </tr>
        {{/foreach}}

        </tbody>
    </table>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li>
            <button type="button" class="btn-close" data-icon="close">关闭</button>
        </li>
    </ul>
</div>