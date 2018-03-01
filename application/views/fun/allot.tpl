<style type="text/css">
    .tt th,.tt td{font-size: 16px;text-align: center;}
</style>
<div class="bjui-pageContent" id="fenpei">
    <fieldset>
        <legend>数据聚合</legend>
        <table class="table table-bordered table-hover table-striped table-top tt">
            <tr>
                <th>未分配数据总数</th>
                <th>A类数据</th>
                <th>B类数据</th>
                <th>C类数据</th>
            </tr>
            <tr>
                <td>{{$data['A']+$data['B']+$data['C']}}</td>
                <td>{{$data['A']}}</td>
                <td>{{$data['B']}}</td>
                <td>{{$data['C']}}</td>
            </tr>
        </table>
    </fieldset>
    <form action="/fun/fun/startAllot" method='post' id="pagerForm" class="allotForm" data-toggle="ajaxform">
        <fieldset>
            <legend>分配数据</legend>
            <table class="table table-bordered table-hover table-striped table-top tt" data-selected-multi="true">
                <tr>
                    <th>业务员</th>
                    <th>A类数据</th>
                    <th>B类数据</th>
                    <th>C类数据</th>
                </tr>
                {{foreach $man as $parentName => $team}}
                <tr><td colspan="4">团队长：{{$parentName}}</td></tr>
                    {{foreach $team as $v}}
                    <tr>
                        <td>{{$v['name']}}</td>
                        <td><input type="number" name="list[{{$v['uid']}}][A]" value="0" class="form-control" min="0"></td>
                        <td><input type="number" name="list[{{$v['uid']}}][B]" value="0" class="form-control"></td>
                        <td><input type="number" name="list[{{$v['uid']}}][C]" value="0" class="form-control"></td>
                    </tr>
                    {{/foreach}}
                {{/foreach}}
            </table>
        </fieldset>
        <div style="margin: 0 10px">
            <button type="button" id="startAllot" class="btn-primary btn-lg" data-icon="save">开始分配</button>
        </div>
    </form>


</div>
<div class="bjui-pageFooter" style="text-align: center">
    <ul >
        <!-- <li>
            <button type="submit" class="btn-default" data-icon="save">开始分配</button>
        </li> -->
    </ul>
</div>
<script type="text/javascript">
    $('#startAllot').click(function (){
        // $(this).text('正在分配...')
        // $('.allotForm').submit();
        $('.allotForm').bjuiajax('ajaxForm', {
            "title":"分配结果通知",
            "confirmMsg":"是否开始分配",
            "callback":function (res){
                if (res.statusCode != 200) {
                    $(document).alertmsg('error',res.message);
                    return;
                };
                var html = '';
                html    += '<table class="table table-bordered table-hover table-striped table-top">';
                html    += '<tr><th colspan="4">剩余未分配数</th><tr>';
                html    += '<tr><th>姓名</th><th>A</th><th>B</th><th>C</th><tr>';
                $.each(res.result,function (k,v){
                    html += '<tr>';
                    html += '<td>'+v.name+'</td>';
                    html += '<td>'+ (v.A == undefined ? 0 : v.A)+'</td>';
                    html += '<td>'+ (v.B == undefined ? 0 : v.B)+'</td>';
                    html += '<td>'+ (v.C == undefined ? 0 : v.C)+'</td>';
                    html += '</tr>';
                })
                html += '</table>';
                console.log(html);
                $(document).alertmsg('ok',html,{"autoClose":false});
            }
        });
    })
</script>