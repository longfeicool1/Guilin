$(".called").click(function(){

    var bindid = $(this).attr('bindid');
    $.ajax({
        async: false,
        data: {bindid:bindid},
        url: '/user/OffLine/callStatus',
        type: 'post',
        dataType:'json',
        error: function (request) {
            alert("连接异常");
        },
        success: function (data) {


        }
    });
})