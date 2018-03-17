<table class="table table-bordered table-hover table-striped table-top" data-toggle="tablefixed" data-width="100%" data-nowrap="true">
    <thead>
        <tr>
            <th>截止日期</th>
            <th>姓名</th>
            <th>额度</th>
            <th>创收</th>
            <th>创收单数</th>
        </tr>
    </thead>
    <tbody>
        {{if $list}}
        <tr>
            <td rowspan="500">{{$list[0]['collectDate']}}</td>
        </tr>
        {{foreach $list as $v}}
        <tr>
            <td>{{$v['name']}}</td>
            <td>{{$v['monthOutMoney']}}</td>
            <td>{{$v['monthInMoney']}}</td>
            <td>{{$v['monthDetail']}}</td>
        </tr>
        {{/foreach}}
        {{else}}
        <tr><td colspan="20" style="text-align: center;">尚未查询到任何相关数据...</td></tr>
        {{/if}}
    </tbody>
</table>