<div class="bjui-pageContent tableContent">
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true">
        <thead>
        <tr>
            <th width="20">时间</th>
            <th width="20">原设备ID</th>
            <th width="20">新设备ID</th>
        </tr>
        </thead>
        <tbody>
        {{foreach $list as $key => $v }}
        <tr>
            <td>{{$v['created']}}</td>
            <td>{{$v['old_src']}}</td>
            <td>{{$v['new_src']}}</td>
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