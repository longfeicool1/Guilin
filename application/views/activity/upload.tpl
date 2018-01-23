<form action="operation/activity/upload?type={{$type}}" id="upload_form" enctype="multipart/form-data" method='post' data-toggle="ajaxform">
    <div class="bjui-pageContent">
        <table class="table">
            <tbody>
            <tr>
                <td colspan="2" align="center"><h4>{{if $type == 'm'}}导入手机号码{{else}}导入设备ID{{/if}}</h4></td>
            </tr>
            <tr>
                <td>选择文件：</td>
                <td><input type="file" name="file" value="" data-rule="required"></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="bjui-pageFooter">
        <ul>
            <li>
                <button type="button" class="btn-close upload_close" id="" data-icon="close">取消</button>
            </li>
            <li>
                <button type="button" id="upload" class="btn-default" data-icon="save">确定</button>
            </li>
        </ul>
    </div>
</form>

<script>
    $(function(){
        $("#upload").click(function(){
            $("#to_user").show();

            $.ajax({
                url: 'operation/activity/upload?type={{$type}}',
                type: 'POST',
                cache: false,
                data: new FormData($('#upload_form')[0]),
                processData: false,
                contentType: false,
                success : function(responseStr) {
                    if(responseStr.status == 200){
                        var json = eval(responseStr.data);
                        var len =json.length;
                        var content = "<table class='table table-bordered table-hover table-striped table-top'>";
                            content = content + "<thead><tr><th width='10'>排序</th><th width='70'>账号</th><th width='40'>设备ID</th><th width='50'>车牌</th></tr></thead>";
                            content = content + "<tbody>";

                            for(var i=0; i < len; i++) {
                                content = content + "<tr><td><input type='hidden' name='user_id[]' value='"+json[i]['user_id']+"' />"+(i+1)+"</td><td>"+json[i]['account_login']+"</td><td>"+json[i]['src']+"</td><td>"+json[i]['car_card']+"</td></tr>";
                            }

                            content = content + "</tbody>";
                            content = content + "</table>";
                        $("#to_user").html(content);
                        $(".upload_close").click();
                    }else{
                        $(this).alertmsg('warn', responseStr.msg, {});
                    }
                },
                error : function(responseStr) {
                    console.log("error");
                }
            });
        });
    })

</script>