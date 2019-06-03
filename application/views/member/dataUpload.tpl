{{if checkAuth(150)}}
<div class="bjui-pageHeader" style="marign:5px;">
    <div class="bottom" style="display: inline-block; vertical-align: middle;">
        <div id="custom_pic_up" class="btn-lg"
             data-toggle="upload"
             data-uploader="/member/member/startUpload"
             data-file-size-limit="10240"
             data-button-text="导入客户"
             data-file-type-exts="*.csv;*.xls;*.xlsx;"
             data-multi="false"
             data-auto="true"
             data-on-upload-success="data_upload_success"
             data-icon="cloud-upload"></div>
    </div>
</div>
{{/if}}
<div class="bjui-pageContent tableContent">
    <table class="table table-bordered table-hover table-striped table-top" data-toggle="tablefixed" data-width="100%" data-nowrap="true">
        <thead>
            <tr>
                <th>序号</th>
                <th>文件名</th>
                <th>大小</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            {{if $list}}
            {{foreach $list as $v}}
            <tr>
                <td>{{$v['xuhao']}}</td>
                <td>{{$v['filename']}}</td>
                <td>{{$v['size']}}</td>
                <td>
                    {{if checkAuth(151)}}
                    <a href="{{$v['url']}}" class="btn btn-blue">下载</a>
                    {{/if}}
                    {{if checkAuth(152)}}
                    <a href="/member/member/delFile?filename={{$v['filename']}}" class="btn btn-red"  data-toggle="doajax" data-confirm-msg="是否删除该文件">删除</a>
                    {{/if}}
                </td>
            </tr>
            {{/foreach}}
            {{else}}
            <tr><td colspan="20" style="text-align: center;">尚未查询到任何相关数据...</td></tr>
            {{/if}}
        </tbody>
    </table>
</div>
<script type="text/javascript">
    function data_upload_success(file, data){
        var json = $.parseJSON(data);
        if(json.statusCode == 200) {
            $(document).alertmsg('ok',json.message);
        } else {
            $(document).alertmsg('error',json.message);
        }
        $(this).navtab('refresh', 'dataUpload');
    }
</script>