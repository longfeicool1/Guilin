<div class="bjui-pageContent">
    <form method="post" action="/fun/fun/tuanSet" class="pageForm" data-toggle="validate">
        <input type="hidden" name="type" value="update" />
        <fieldset>
            <legend>可分配数据量</legend>
            <table class="table table-bordered table-hover table-striped table-top tt" data-selected-multi="true">

                <!-- <tr><td colspan="4">-1: 不予分配数量;0: 不限制团长分配;>0: 只能分配的上限</td></tr> -->
                <tr>
                    <th>业务员</th>
                    <th>A类数据</th>
                    <th>B类数据</th>
                    <th>C类数据</th>
                    <th>-</th>
                </tr>
                {{foreach $man as $v}}
                    <tr>
                        <input type="hidden" name="list[{{$v['uid']}}][exist]" value="{{$v['exist']}}" />
                        <td style="text-align: left"><span class="mytitle">{{if !empty($v['positionName'])}}{{$v['positionName']}}{{else}}&nbsp;&nbsp;&nbsp;{{/if}}</span>{{$v['name']}}</td>
                        <td>
                            <input type="number" name="list[{{$v['uid']}}][A]" value="{{$v['limit_num_a']}}" class="form-control" min="0" style="width: 100px">
                            <!-- 不限制<input type="checkbox" name="list[{{$v['uid']}}][A]" class="form-control" value="-1" > -->
                        </td>
                        <td>
                            <input type="number" name="list[{{$v['uid']}}][B]" value="{{$v['limit_num_b']}}" class="form-control" min="0" style="width: 100px">
                            <!-- <input type="checkbox" name="list[{{$v['uid']}}][B]" class="form-control" value="-1" >不限制 -->
                        </td>
                        <td>
                            <input type="number" name="list[{{$v['uid']}}][C]" value="{{$v['limit_num_c']}}" class="form-control" min="0" style="width: 100px">
                            <!-- <input type="checkbox" name="list[{{$v['uid']}}][C]" class="form-control" value="-1" >不限制 -->
                        </td>
                    </tr>
                {{/foreach}}
            </table>
        </fieldset>
    </form>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close">关闭</button></li>
        <li><button type="submit" class="btn-default">修改</a></li>
    </ul>
</div>
