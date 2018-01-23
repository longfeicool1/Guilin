<table class="table">
    <tbody>
    <tr>
        <td width="120">群发用户：</td>
        <td><input type="checkbox" id="all_user" name="all_user" value="1" /><label for="all_user">全部用户</label></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td><a href="operation/activity/upload?type=m" data-toggle="dialog" class="btn btn-default">导入手机号码</a>&nbsp;&nbsp;&nbsp;<a href="/static/template/mobile.xlsx" target="_blank">模板</a></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td><a href="operation/activity/upload?type=s" data-toggle="dialog" class="btn btn-default">导入设备ID</a>&nbsp;&nbsp;&nbsp;<a href="/static/template/src.xlsx" target="_blank">模板</a></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>
            <button type="button" class="btn-default" data-icon="next"  onclick="prevStep();">上一步</button>&nbsp;&nbsp;&nbsp;&nbsp;
            <button type="submit" class="btn-default" data-icon="save">确定</button>
        </td>
        <td></td>
    </tr>
    </tbody>
</table>
<div id="to_user" style="display:none;">
</div>