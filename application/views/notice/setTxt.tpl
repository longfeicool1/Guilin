<form action="notice/notice/setTxt" method='post' id="pagerForm" data-toggle="validate">
    <div class="bjui-pageContent" style="width:50%">
        <div id="one-step">
            <table class="table">
                <tbody>
                <tr>
                    <td>名称：</td>
                    <td><input type="text" id="name" name="name" value="" placeholder="请输入名称"></td>
                </tr>

                <tr>
                    <td>内容：</td>
                    <td>
                        <textarea cols="60" rows="4" id="txt" name="txt" placeholder="请输入内容，建议150字符以内~"></textarea>
                    </td>
                </tr>

                <tr>
                    <td align="right"><button type="button" class="btn-close" data-icon="close">取消</button></td>
                    <td><button type="button" class="btn-default" data-icon="next" onclick="nextStep();">下一步</button></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="two-step" style="display:none;">
            {{include file='notice/selectuser.tpl'}}
        </div>
    </div>
</form>

<script>
    $(function(){
        $("#all_user").click(function(){
            var checked = this.checked;
            if (checked) {
                $("#to_user").html('');
                $("#to_user").hide();
            } else {
                $("#to_user").show();
            }
        })
    })

    function nextStep()
    {
        var name = $.trim($("#name").val());
        var txt = $.trim($("#txt").val());

        if (name.length == 0) {
            $(this).alertmsg('warn', '名称不能为空', {});
            return false;
        }

        if (txt.length == 0) {
            $(this).alertmsg('warn', '内容不能为空', {});
            return false;
        }

        $("#two-step").show();
        $("#one-step").hide();
    }

    function prevStep(){
        $("#two-step").hide();
        $("#one-step").show();
    }
</script>