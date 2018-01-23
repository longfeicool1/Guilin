<form action="/src/city/edit?id={{$info['id']}}" method='post' id="pagerForm" data-toggle="validate">
    <div class="bjui-pageContent ">
        <table class="table tableContent">
            <tr>
                <th>设备ID</th>
                <td>
                    <input type="text" name="src" value="{{$info['src']}}" data-rule="required">
                </td>
            </tr>
            <tr>
                <th>是否测试设备</th>
                <td>
                    <input data-toggle="icheck" type="radio" name="is_test" value="2" id="test_n" {{if $info['is_test'] == 2}}checked{{/if}}><label for="test_n">不是</label>
                    <input data-toggle="icheck" type="radio" name="is_test" value="1" id="test_y" {{if $info['is_test'] == 1}}checked{{/if}}><label for="test_y">是</label>
                </td>
            </tr>
            <tr>
                <th>省份</th>
                <td>
                    <input type="text" name="province" value="{{$info['province']}}" data-rule="required">(请保持省份和已有省份一致)
                </td>
            </tr>
            <tr>
                <th>城市</th>
                <td>
                    <input type="text" name="city" value="{{$info['city']}}" data-rule="required">
                </td>
            </tr>
        </table>
    </div>
    <div class="bjui-pageFooter">
        <ul>
            <li>
                <input type="hidden" name="id" value="{{$info['id']}}" />
                <button type="submit" class="btn-default" data-icon="save">保存</button>
            </li>
            <li>
                <button type="button" class="btn-close" data-icon="close">关闭</button>
            </li>


        </ul>
    </div>
</form>