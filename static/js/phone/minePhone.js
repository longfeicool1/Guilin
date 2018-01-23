var htcore = new HTCORE("/static/js/phone/htcore.swf");
var connection = null;
var hidenumber = 0; //是否隐藏号码
var serverIP = '192.168.18.100'; //呼叫中心服务器IP
function go() {
    appendData('----正在连接服务器...');
    connection = new HTCORE.Connection(htcore, serverIP, "onConnectEvent", "onDataEvent", "onCloseEvent");
}

//连接服务器事件
function onConnectEvent(val) {
    if (val) {
        appendData('连接服务器成功！');
        logincheck(nick, exten, queue, hidenumber);
    } else {
        appendData('不能够连接到服务器！');
        go();
    }
}

//事件返回()
function onDataEvent(str) {
    if (str != null) {
        var json = JSON.parse(str);
        var type = json['type'];
        if (type != 'queuelist' || type != 'extenlist') {
            appendData(json);
        }
        var info_arr = json['info'];
        if (type == "status") { //分机状态，
            switch (info_arr['status']) {
                case '-1':
                    showMsg('warn', '服务器链接错误,请稍后重试！')
                    break;
                default:
            }
        } else if ("queuelist" == type) { //队列列表信息
            statusMsg(info_arr);
        } else if ("extenlist" == type) { //队列列表信息
            statusMsg(info_arr);
        } else if ("logout" == type) { //系统用户在其他机器登录，将退出登录！
            var htcore = null;
            var connection = null;
            showMsg('warn', '您的账号已在其它机器登入！');
            location.href = '/login/loginOut';
        } else if ("pop" == type && info_arr['poptype'] == 'Ringing') { //来/去电弹窗
            //先判断该客户是否为当前用户名单
            if($('#notruble').is(':checked') !== true){
                $.get('/phone/findCustomInfo',{mobile:info_arr['number']},function(d){
                    if(d != 'null'){
                        d = $.parseJSON(d);
                        if(d.who_service == uid){
                            $(document).navtab({
                                id:'id48',
                                title:'报价-'+d.car_code,
                                url:'/offer/offer?carcard='+d.car_code+'&customid='+d.id,
                            });
                        }else{
                            transfer(d.exten);
                        }
                    }else{
                        $(document).navtab({
                            id:'dialog-addphone',
                            title:'添加新用户',
                            url:'/phone/content',
                        });
                    }
                })
            }
        }
    }
}
//服务器连接关闭事件
function onCloseEvent() {
    appendData("Connection closed.");
    go();
}

function appendData(str) {
    console.log(str);
}
htcore.Init();
//座席登录
function logincheck(nick, exten, queue, hidenumber) {
    connection.send('{"type":"login","info":{"nick":"' + nick + '","exten":"' + exten + '","hidenumber":"' + hidenumber + '","queue":"' + queue + '","key":"","UID":""}}\0');
}

//签入队列
function checkin(obj, queue) {
    connection.send('{"type":"checkin","info":{"queue":"' + queue + '"}}\0');
}

//签出队列
function checkout(queue) {
    appendData('checkout');
    connection.send('{"type":"checkout","info":{"queue":"' + queue + '"}}\0');
}

//拨电话
function tocall(obj) {
    var tel = $(obj).attr('data-tel');
    var carcode = $(obj).attr('data-carcode');
    var costomId = $(obj).attr('data-costomid');
    if (connection) {
        callout(tel);
    } else {
        showMsg('warn', '服务器已断开，请刷新页面！');
    }
}

//电话外呼接口
function callout(tel){
    var url = "http://"+serverIP+"/htcall/index.php";
    var pars = "module=action&action=HuChu&fromid="+exten+"&toid="+tel;
    $.get(url+'?'+pars,{},function(d){
        return;
    });
    return;
}

//自定义拨电话
function freeCall(tel,type) {
    if (!tel) {
        showMsg('warn', '手机号码不能为空！');
        return;
    }
    if (connection) {
        callout(tel);
    } else {
        showMsg('warn', '服务器已断开，请刷新页面！');
    }
}

//挂电话
function hangup() {
    if (connection) {
        connection.send('{"type":"hangup","info":{}}\0');
    }
}

//转接
function transfer(tel) {
    $(document).alertmsg('confirm', '确定将电话进行转接到' + tel + '分机吗？', {
        'okCall': function() {
            connection.send('{"type":"transfer","info":{"number":"' + tel + '"}}\0');
        },
    });
}

//通话过程中，通话保持开始

function pauseon() {
    appendData('通话保持开始');
    connection.send('{"type":"pauseon","info":{}}\0');
}

//通话过程中，通话保持结束
function pauseoff() {
    appendData('通话保持结束');
    connection.send('{"type":"pauseoff","info":{}}\0');
}

//通知后台去获取队列信息
function getqueuelist() {
    connection.send('{"type":"extenlist","info":{}}\0');
    connection.send('{"type":"queuelist","info":{}}\0');

}

//对状态数据发布
function statusMsg(info_arr) {
    var id = '';
    $.each(info_arr, function(k, v) {
        id = '#exten_' + v.exten;
        if (v.calltime)
            $(id + ' .time').html(v.calltime);
        else
            $(id + ' .time').html('暂无');
        if (v.paused == 1)
            v.status = 3;
        switch (v.status) {
            case -1:
                $(id + ' .status').html('<span style="color:darkred">离线</span>');
                $(id + ' .transfer').html('<a href="javascript:;" class="btn btn-default" data-toggle="alertmsg" data-msg="该分机已离线，无法转接！" data-type="warn">转接</a>')
                break;
            case 0:
                $(id + ' .status').html('<span style="color:green">空闲</span>');
                $(id + ' .transfer').html('<a href="javascript:;" class="btn btn-green" onclick="transfer(' + v.exten + ');">转接</a>');
                break;
            case 2:
                $(id + ' .status').html('<span style="color:#269ABC">通话</span>');
                $(id + ' .transfer').html('<a href="javascript:;" class="btn btn-default" data-toggle="alertmsg" data-msg="该分机正在通话中，无法转接！" data-type="warn">转接</a>')
                break;
            case 3:
                $(id + ' .status').html('<span style="color:orangered">繁忙</span>');
                $(id + ' .transfer').html('<a href="javascript:;" class="btn btn-default" data-toggle="alertmsg" data-msg="该分机正处于繁忙中，无法转接！" data-type="warn">转接</a>')
                break;
            default:
                $(id + ' .status').html('<span style="color:darkred">离线</span>');
        }
    });
}


//我的座机状态改变
$('#status').change(function() {
    var obj = $(this);
    var status = obj.val();
    if (status == 'leave') {
        if (connection) {
            connection.send('{"type":"setbusy","info":{}}\0');
        }
    } else if (status == 'online') {
        if (connection) {
            connection.send('{"type":"setidle","info":{}}\0');
        }
    }
})

//打开用户信息
function openCustomInfo(carcard,customid) {
    $(document).navtab({
        id: 'id48',
        url:'/offer/offer?carcard='+carcard+'&customid='+customid,
        title: '报价 - ' + carcard,
    });
}
//