<form action="user/yg/comment?id={{$id}}" method='post' id="pagerForm" data-toggle="validate">
    <div class="bjui-pageContent">
        <table class="table">
            <tbody>
            <tr>
                <td align="center"><h4><b>备注</b></h4></td>
            </tr>
            <tr>
                <td align="center">
                    <textarea name="comment" placeholder="请输入内容,建议50个字内" rows="6" cols="40">{{if $comment}}{{$comment}}{{/if}}</textarea>
                </td>
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
