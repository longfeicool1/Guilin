<style type="text/css">
    .changeRed{color:red}
</style>
<div class="bjui-pageContent">
    <div style="width: 49%;display: inline-block;">
        <fieldset>
            <legend>
            <a href="javascript:;" onclick="getNoAuth()" target="_blank" class="btn btn-blue">获取未认证名单</a>
            </legend>
            <table class="table table-condensed table-hover">
                <thead>
                    <tr>
                    <th>序号</th>
                    <th>临/正常牌</th>
                    <th>账户</th>
                    <th>车牌</th>
                    <th>设备号</th>
                    <th>商业保单号</th>
                    </tr>
                </thead>
                <tbody id="getNoAuth">

                </tbody>
            </table>
        </fieldset>
    </div>
    <div style="width: 49%;display: inline-block;float: right;">
        <fieldset>
            <legend>
                <div class="bottom" style="display: inline-block; vertical-align: middle;">
                    <div id="list_upload_up"
                        data-toggle="upload"
                        data-uploader="/user/check/ygupload"
                        data-file-size-limit="10240"
                        data-button-text="阳光认证数据"
                        data-file-type-exts="*.csv;*.xlsx;*.xls;"
                        data-multi="false"
                        data-auto="true"
                        data-on-upload-success="list_ygupload_success"
                        data-icon="cloud-upload">
                    </div>
                </div>
            </legend>
            <table class="table table-condensed table-hover">
                <thead>
                    <tr>
                    <th>序号</th>
                    <th>车牌</th>
                    <th>设备号</th>
                    <th>商业保单号</th>
                    <th>保单止期</th>
                    </tr>
                </thead>
                <tbody id="getCheckAuth">

                </tbody>
            </table>
        </fieldset>
    </div>
</div>
<div class="bjui-pageFooter" style="height: 50px;">
    <ul style="margin-right: 45%;">
        <li><button type="button" class="btn-close btn-lg" style="height: 40px;">关闭</button></li>
        <li><button type="button" onclick="openMeregWatch(this);" class="btn-default btn-lg" style="height: 40px;">匹配预览</a></li>
    </ul>
</div>
<script type="text/javascript">
    var noAuth    = '';
    var checkAuth = '';
    var success   = new Array();
    function list_ygupload_success(file, data){
        var json = $.parseJSON(data);
        console.log(data);
        if (json) {
            checkAuth = json;
            $('#getCheckAuth').empty();
            $.each(json,function(k,v) {
                var html = '<tr>';
                html += '<td>'+(k+1)+'</td>';
                html += '<td>'+v.carcard+'</td>';
                html += '<td>'+v.src+'</td>';
                html += '<td>'+v.insure_code+'</td>';
                html += '<td>'+v.insure_end+'</td>';
                html += '</tr>';
                $('#getCheckAuth').append(html);
            });
        } else {
            $('#getCheckAuth').append('<tr><td colspan="5"></td></tr>');
        };
    }
    function getNoAuth()
    {
        $.post('/user/check/getNoAuth',{},function (reponse){
            if (reponse) {
                noAuth = reponse;
                $('#getNoAuth').empty();
                $.each(reponse,function (k,v){
                    var html = '<tr>';
                    html += '<td>'+v.xuhao+'</td>';
                    html += '<td>'+v.cardtype+'</td>';
                    html += '<td>'+v.account_login+'</td>';
                    html += '<td>'+v.carcard+'</td>';
                    html += '<td>'+v.src+'</td>';
                    html += '<td>'+v.insure_code+'</td>';
                    html += '</tr>';
                    $('#getNoAuth').append(html);
                });
            } else {
                $('#getNoAuth').append('<tr><td colspan="5"></td></tr>');
            }
        });
    }

    function openMeregWatch(obj)
    {
        if (!noAuth || !checkAuth) {
            showMsg('warn','两边都不为空才能匹配');return;
        };
        $(obj).dialog({
            id:'meregWatch',
            url:'user/check/meregWatch',
            title:'匹配结果',
            type:'post',
            width:800,
            height:500,
            data:{
                'soure':JSON.stringify(noAuth),
                'check':JSON.stringify(checkAuth)
            }
        });
        var i = $('.bjui-dialog').index();
        if (i > 1) {
            $('.bjui-dialog').css('z-index','99');
        }
    }


</script>