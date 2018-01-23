<form action="user/check/updateLogin?pid={{$pid}}&act=edit" method='post' id="pagerForm" data-toggle="validate">
    <div class="bjui-pageContent">
        <table class="table">
            <tbody>
            <tr>
                <td align="center" colspan="2"><h4><b>更换登录账户</b></h4></td>
            </tr>
            <tr>
                <td>当前使用账户：</td>
                <td>{{$data['account_login']}}</td>
            </tr>
            <tr>
                <td>新登录账户：</td>
                <td><input name="newLogin" value="" data-rule="required; mobile;" /></td>
            </tr>
            </tbody>
        </table>
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
</form>
