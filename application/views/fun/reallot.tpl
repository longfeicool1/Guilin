<div class="bjui-pageContent">
    <form method="post" action="/fun/fun/startReallot" class="pageForm" data-toggle="validate">
        <input type="hidden" name="ids" value="{{$ids}}" />
        <table>
            <tr>
                <td>
                    <label>重设预约时间：</label>
                    <input data-toggle="datepicker" data-pattern="yyyy-MM-dd HH:mm:ss" type="text"
                    value="" name="meetTime" autocomplete="off" data-min-date="%y-%M-%d"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label>重分配业务员：</label>
                    <select name="firstOwer" id="firstOwer" data-toggle="selectpicker" data-rule="required;">
                        <option value="">--请选择--</option>
                        {{foreach $saleman as $v}}
                        <option value="{{$v['uid']}}">{{$v['name']}}</option>
                        {{/foreach}}
                    </select>
                </td>
            </tr>
        </table>
    </form>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close">关闭</button></li>
        <li><button type="submit" class="btn-default">修改</a></li>
    </ul>
</div>
