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
    <form action="/fun/fun/startAllot" method='post' id="pagerForm" class="allotForm" data-toggle="validate">
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
        $(this).text('正在分配...')
        $('.allotForm').submit();
    })
</script>