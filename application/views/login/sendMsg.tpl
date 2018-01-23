<div class="bjui-pageHeader" >
    <form id="pagerForm" data-toggle="ajaxsearch" action="manage/auth/checkSendMsg" method="post">
        <h3>验证手机号</h3>
        <div class="bjui-searchBar">
            手机号：<input type="text" value="{{$mobile}}" name="mobile" id="mobile" class="form-control" >&nbsp;
        </div>
        <div class="bjui-searchBar">
            验证码：<input type="text" value="" name="code" id="code" class="form-control" >&nbsp;
            <button type="button" id="sendCode" class="btn-green" onclick="javascript:sendMsg();">获取验证码</button>&nbsp;
        </div>
        <button type="submit" class="btn-blue" >登录</button>&nbsp;

    </form>
</div>
<script>

    function sendMsg()
    {
        var mobile = $("#mobile").val();

        if ($("#mobile").val() == '') {
            alert("请输入注册手机号码");
            return false;
        }
        var ret = /^1\d{10}$/;
        if (!ret.test($("#mobile").val())) {
            alert("请输入正确的注册手机号码");
            return false;
        }
        $("#sendCode").attr("disabled","disabled");
        $.ajax({
            async: false,
            data: {mobile:mobile},
            url: '/manage/auth/sendMsg',
            type: 'post',
            //dataType:'json',
            error: function (request) {
                alert("连接异常");
            },
            success: function (data) {
                $("#sendCode").removeAttr("disabled");
                if (data == 'ok')
                {
                    alert('发送成功,请查收短信');
                }
                else
                {
                    alert('发送失败，请重新获取');
                }
            }
        });
    }
</script>