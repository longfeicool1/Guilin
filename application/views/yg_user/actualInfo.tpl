<style type="text/css">
    .table_width{
        width: 33.33% !important;
        float: left;
    }
    .small_chunk{
        display: block;
        width: 16px;
        height: 18px;
        padding: 3px 0px 0px 2px;
        margin: 1px 1px 0px 0px;
        float: left;
        color: white;
        font-size: 10px
    }

    .small_chunk_acce{
        background: #06bbf1;
    }
    .small_chunk_dece{
        background: #FF6600;
    }
    .small_chunk_coce{
        background: red;
    }
</style>
<fieldset>
    <legend>基本信息</legend>
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true">
        <thead>
        <tr>
            <th>轨迹开始时间</th>
            <th>轨迹结束时间</th>
            <th>急加速(次)</th>
            <th>急减速(次）</th>
            <th>急转弯(次）</th>
            <th>最高行驶速度(Km/h)</th>
            <th>行驶里程(Km)</th>
            <th>行驶时间(s)</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{$data['stime']}}</td>
            <td>{{$data['etime']}}</td>
            <td>{{$data['acce']}}</td>
            <td>{{$data['dece']}}</td>
            <td>{{$data['coce']}}</td>
            <td>{{$data['speedtop']}}</td>
            <td>{{$data['tripmile']}}</td>
            <td>{{$data['triptime']}}</td>
        </tr>
        </tbody>
    </table>
</fieldset>
<fieldset>
    <legend>三急详情</legend>
    {{if !empty($brake)}}
    <table class="table table-bordered table-hover table-striped table-top table_width" data-selected-multi="true">
        <thead>
        <tr>
            <th>急加速</th>
        </tr>
        </thead>
        <tbody>
        {{if !empty($brake['acce'])}}
        {{foreach $brake['acce'] as $v}}
        <tr>
            <td><span class="small_chunk small_chunk_acce">加</span>{{$v['fomatRtc']}}&nbsp;{{$v['address']}}</td>
        </tr>
        {{/foreach}}
        {{/if}}
        </tbody>
    </table>
    <table class="table table-bordered table-hover table-striped table-top table_width" data-selected-multi="true">
        <thead>
        <tr>
            <th>急减速</th>
        </tr>
        </thead>
        <tbody>
        {{if !empty($brake['dece'])}}
        {{foreach $brake['dece'] as $v}}
        <tr>
            <td><span class="small_chunk small_chunk_dece">减</span>{{$v['fomatRtc']}}&nbsp;{{$v['address']}}</td>
        </tr>
        {{/foreach}}
        {{/if}}
        </tbody>
    </table>
    <table class="table table-bordered table-hover table-striped table-top table_width" data-selected-multi="true">
        <thead>
        <tr>
            <th>急转弯</th>
        </tr>
        </thead>
        <tbody>
        {{if !empty($brake['coce'])}}
        {{foreach $brake['coce'] as $v}}
        <tr>
            <td><span class="small_chunk small_chunk_coce">转</span>{{$v['fomatRtc']}}&nbsp;{{$v['address']}}</td>
        </tr>
        {{/foreach}}
        {{/if}}
        </tbody>
    </table>
    {{else}}
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true">
        <tr>
            <td>暂无数据</td>
        </tr>
    </table>
    {{/if}}
</fieldset>