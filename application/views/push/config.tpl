<div class="bjui-pageContent" id="push">
    {{foreach $config as $k=>$v}}
    <form id="{{$v['pushGroup']}}" method="post" action="" class="pageForm" data-toggle="validate">
        <input type="hidden" name="pushId" value="{{$v['pushId']}}"/>
        <fieldset>
            <legend>
                {{$v['pushTitle']}}
                <button type="button" data-pushGroup="{{$v['pushGroup']}}" class="btn-orange">保存</button>
            </legend>
            <table class="table table-condensed table-hover">
                <tr>
                    <td>
                        <label class="control-label x90">开启推送：</label>
                        <input type="radio" name="pushType" value="2" class="form-control" data-toggle="icheck" data-label="开启推送" {{if $v['pushType'] == 2}}checked{{/if}}>
                        <!--<input type="radio" name="pushType" value="1" class="form-control" data-toggle="icheck" data-label="是" {{if $v['pushType'] == 1}}checked{{/if}}>-->
                        <!--<input type="radio" name="pushType" value="2" class="form-control" data-toggle="icheck" data-label="否" {{if $v['pushType'] == 2}}checked{{/if}}>-->
                        <input type="radio" name="pushType" value="3" class="form-control" data-toggle="icheck" data-label="关闭推送" {{if $v['pushType'] == 3}}checked{{/if}}>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="defaultUid" class="control-label x90">指定推送：</label>
                        <select name="defaultUid" data-toggle="selectpicker" multiple>
                            {{if !$v['defaultUid']}}
                            <option value="" selected>--请选择--</option>
                            {{/if}}
                            {{foreach $user as $u}}
                            <option value="{{$u['id']}}" {{if in_array($u['id'],$v['defaultUid'])}}selected{{/if}}>
                                {{if $u['real_name']}}{{$u['real_name']}}{{else}}{{$u['username']}}{{/if}}
                            </option>
                            {{/foreach}}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="defaultMsg" class="control-label x90">默认推送内容：</label>
                        <textarea class="form-control autosize" name="defaultMsg" rows="1" cols="30">{{$v['defaultMsg']}}</textarea>
                    </td>
                </tr>
        </table>
        </fieldset>
    </form>
    {{/foreach}}
</div>
<script>
    $('#push').on('click','.btn-orange',function (){
       var group = $(this).attr('data-pushGroup');
       var data = $('#'+group).serializeJson();
       $.post('/push/push/updateConfig',data,function (d){
            if(d == 'ok'){
                $('#push').alertmsg('ok','修改成功！')
            }
       })
    })
</script>