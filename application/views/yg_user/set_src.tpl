<form action="user/yg/setSrc?bindId={{$bindId}}" method='post' id="pagerForm" data-toggle="validate">
    <div class="bjui-pageContent">
        <table class="table">
            <tbody>
            <tr>
                <td align="center" colspan="2"><h4><b>更换设备</b></h4></td>
            </tr>
            <tr>
                <td>旧设备ID：</td>
                <td>{{if $src}}{{$src}}{{/if}}<input type="hidden" name="old_src" id="old_src" value="{{$src}}"></td>
            </tr>
            <tr>
                <td>新设备ID：</td>
                <td><input name="src" value="" data-rule="required; number;" /></td>

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
