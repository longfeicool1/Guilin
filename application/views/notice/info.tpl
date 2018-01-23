<div class="bjui-pageContent">
    <div id="one-step">
        <table class="table">
            <tbody>
                <tr>
                    <td colspan="2" align="center"><h3>详情</h3></td>
                </tr>
                <tr>
                    <td width="120" align="right">名称：</td>
                    <td>{{$info['name']}}</td>
                </tr>
                {{if $info['type'] == 2}}<!--图文详情-->
                    <tr>
                        <td align="right">封面图片：</td>
                        <td><img src="{{$info['image']}}" height="100"></td>
                    </tr>
                    <tr>
                        <td align="right">链接：</td>
                        <td><a href="{{$info['url']}}" target="_blank">{{$info['url']}}</a></td>
                    </tr>
                {{elseif $info['type'] == 3}} <!--文本-->
                    <tr>
                        <td align="right">内容：</td>
                        <td>{{$info['txt']}}</td>
                    </tr>
                {{/if}}

                <tr>
                    <td align="right">推送数：</td>
                    <td>{{$info['to_num']}}</td>
                </tr>
                <tr>
                    <td align="right">阅读数：</td>
                    <td>{{$info['read_num']}}</td>
                </tr>
                <tr>
                    <td align="right">推送时间：</td>
                    <td>{{$info['created']}}</td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li>
            <button type="button" class="btn-close" data-icon="close">关闭</button>
        </li>
    </ul>
</div>