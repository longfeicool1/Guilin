//提示
function showMsg(type, msg) {
    if (type == 'confirm') {
        $(document).alertmsg(type, msg, {
            'title': '来电提示',
            'okName': '用户信息',
            'cancelName': '拒接',
            'okCall': function () {
                console.log(111);
            },
            'cancelCall': function () {
                hangup();
            },
        });
    } else {
        $(document).alertmsg(type, msg);
    }
}

/*
 * 通话总的通会员统计
 */
function getTotalRecord(url, serverList){
    // console.log(serverList);
    $.post(url,{},function (d){
        if(!d){
            return;
        }
        // console.log(d);
        var json = $.parseJSON(d);
        var serverListJson = $.parseJSON(serverList);
        // console.log(serverListJson);
        var html = '';
        var n = 0;
        var callsListExportDataJson = {};
        $.each(serverListJson, function(k,v){
            if(typeof(json[k]) == 'undefined'){
                json[k] = {
                    "ext":k,
                    "callnum":0,
                    "calltime":0,
                    "callonnum":0,
                    "callontime":0,
                    "meetConsume":0
                }
            }
            json[k].name = v.name;
            json[k].group = v.group_desc + '(' + serverListJson[v.leader_id].name + ')';
            json[k].accountId = v.account_id;
            if(typeof(json[k].phoneList) == "undefined") {
                json[k].meetConsume = 0;
            } else {
                json[k].meetConsume = json[k].phoneList.length;
            }
            html += '<tr>';
            html += '<td>'+json[k].group+'</td>';
            html += '<td>'+json[k].name+'</td>';
            html += '<td>'+json[k].ext+'</td>';
            // html += '<td>'+json[k].ext+'('+ json[k].last_name+')</td>';
            html += '<td>'+json[k].callnum+'</td>';
            html += '<td>'+json[k].calltime+'</td>';
            if(!json[k].callonnum){
                json[k].callonnum = 0
            }
            if (!json[k].callontime) {
                json[k].callontime = 0
            }
            html += '<td>'+json[k].callonnum+'</td>';
            html += '<td>'+json[k].callontime+'</td>';
            html += '<td>'+json[k].meetConsume+'</td>';
            html += '</tr>';

            n++;
            callsListExportDataJson[n] = {
                "group":json[k].group,
                "server":json[k].name,
                "serverId":json[k].ext,
                "callnum":json[k].callnum,
                "calltime":json[k].calltime,
                "callonnum":json[k].callonnum,
                "callontime":json[k].callontime,
                "meetConsume":json[k].meetConsume,
            };
        })
        $('.cc').append(html);
        var callsListExportData = JSON.stringify(callsListExportDataJson);
        $('#callsListExportData').val(callsListExportData);
    })
}

/*
 * 填充增量列表
 */
function fillIncrementList(url, serverList, incrementList){
    var serverListJson = $.parseJSON(serverList);
    var incrementListJson = $.parseJSON(incrementList);
    // console.log(serverListJson);
    // console.log(incrementListJson);
    // console.log(serverList);
    // console.log(incrementList);
    // return;
    $.post(url,{p:incrementListJson},function (d){
        if(!d){
            return;
        }
        var dJson = $.parseJSON(d);
        // console.log(dJson);
        // console.log(d);
        // return;
        var html = '';
        var n = 0;
        var incrementListExportDataJson = {};
        $.each(serverListJson,function (k,v){
            if(k){
                if(typeof(dJson[k]) == 'undefined') {
                    incrementListJson[k].p4 = 0;
                    // incrementListJson[k].p5 = 0;
                    // incrementListJson[k].p6 = 0;
                    incrementListJson[k].p10 = 0;
                } else {
                    incrementListJson[k].p4 = typeof(dJson[k].p4) == 'undefined'?0:dJson[k].p4;
                    // incrementListJson[k].p5 = typeof(dJson[k].p5) == 'undefined'?0:dJson[k].p5;
                    // incrementListJson[k].p6 = typeof(dJson[k].p6) == 'undefined'?0:dJson[k].p6;
                    incrementListJson[k].p10 = typeof(dJson[k].p10) == 'undefined'?0:dJson[k].p10;
                }
                v.name = serverListJson[k].name;
                v.group = serverListJson[k].group_desc + '(' + serverListJson[serverListJson[k].leader_id].name + ')';
                html += '<tr>';
                html += '<td>'+v.group+'</td>';
                html += '<td>'+v.name+'</td>';
                html += '<td>'+k+'</td>';
                html += '<td>'+incrementListJson[k].p1+'</td>';
                html += '<td>'+incrementListJson[k].p2+'</td>';
                html += '<td>'+incrementListJson[k].p3+'</td>';
                html += '<td>'+incrementListJson[k].p4+'</td>';
                html += '<td>'+incrementListJson[k].p5+'</td>';
                html += '<td>'+incrementListJson[k].p6+'</td>';
                html += '<td>'+incrementListJson[k].p7+'</td>';
                html += '<td>'+incrementListJson[k].p8+'</td>';
                html += '<td>'+incrementListJson[k].p9+'</td>';
                html += '<td>'+incrementListJson[k].p10+'</td>';
                html += '<td>'+incrementListJson[k].p11+'</td>';
                html += '<td>'+incrementListJson[k].p12+'</td>';
                html += '<td>'+incrementListJson[k].p13+'</td>';
                html += '<td>'+incrementListJson[k].p14+'</td>';
                html += '<td>'+incrementListJson[k].p15+'</td>';
                html += '<td>'+incrementListJson[k].p16+'</td>';
                html += '</tr>';

                incrementListExportDataJson[n] = {
                    "group":v.group,
                    "server":v.name,
                    "serverId":k,
                    "p1":incrementListJson[k].p1,
                    "p2":incrementListJson[k].p2,
                    "p3":incrementListJson[k].p3,
                    "p4":incrementListJson[k].p4,
                    "p5":incrementListJson[k].p5,
                    "p6":incrementListJson[k].p6,
                    "p7":incrementListJson[k].p7,
                    "p8":incrementListJson[k].p8,
                    "p9":incrementListJson[k].p9,
                    "p10":incrementListJson[k].p10,
                    "p11":incrementListJson[k].p11,
                    "p12":incrementListJson[k].p12,
                    "p13":incrementListJson[k].p13,
                    "p14":incrementListJson[k].p14,
                    "p15":incrementListJson[k].p15,
                    "p16":incrementListJson[k].p16,
                };
                n++;
            }
        })
        // console.log(incrementListExportDataJson);
        var incrementListExportData = JSON.stringify(incrementListExportDataJson);
        $('.increment-list').append(html);
        $('#incrementListExportData').val(incrementListExportData);
    })
}

/*
 * 通话今天的聊天记录统计;传exten分机号
 */
function getTodayRecord(exten) {
    $.post('http://192.168.64.2:10086/api/todayRecordData', {exten: exten}, function (d) {
        $('#table5').html(d);
    })
}

/*
 * 通话记录获取聊天记录
 */
function getRecord(domName, serverList, para, boolen) {
    var html = '';
    $(domName).html('<tr><td colspan="10">正在加载数据...</td></tr>');
    if (para['tel'] == 1) {
        $(domName).html('<tr><td colspan="10">未查询到符合的数据...</td></tr>');
        return;
    }
    $.post('http://192.168.64.2:10086/api/getTelrecord', para, function (d) {
        if (!d) {
            $(domName).html('<tr><td conspan="10">暂无数据</td></tr>');
            return;
        }
        d = $.parseJSON(d);
        var ids = '', mobile = new Array(), i = 0;
        if (d['lists']) {
            $.each(d['lists'], function (k, v) {
                if (!v['exten']) return true;
                ids += v.id + ',';
                if ($.inArray(v['phone'], mobile) == -1) {
                    mobile[i] = v['phone'];
                    i++;
                }
                html += '<tr>';
                html += '<td>' + v.id + '</td>';
                html += '<td>' + v.calldate + '</td>';
                html += '<td>' + v.phone + '</td>';
                if (para['backData'] && para['backData']['mobile_' + v.phone] && para['backData']['mobile_' + v.phone]['custom_name']) {
                    html += '<td class="name_' + v.phone + '">' + para['backData']['mobile_' + v.phone]['custom_name'] + '</td>';
                } else {
                    html += '<td class="name_' + v.phone + '"></td>';
                }
                if (para['backData'] && para['backData']['mobile_' + v.phone] && para['backData']['mobile_' + v.phone]['car_code']) {
                    html += '<td class="card_' + v.phone + '">' + para['backData']['mobile_' + v.phone]['car_code'] + '</td>';
                } else {
                    html += '<td class="card_' + v.phone + '"></td>';
                }
                if (v['exten']) {
                    var tt = '';
                    if (serverList[v['exten']]) {
                        var tt = '(' + serverList[v['exten']]['name'] + ')'
                    }
                    html += '<td>' + v['exten'] + tt + '</td>';
                } else {
                    html += '<td></td>';
                }
                html += '<td>' + v.talktime + '</td>';
                html += '<td>' + v.callstatus + '</td>';
                if (boolen == true) {
                    html += '<td id="id_' + v.id + '"></td>';
                } else {
                    html += '<td id="idd_' + v.id + '"></td>';
                }
                html += '<td><a href="/phone/writeTip?record_id=' + v.id + '" class="btn btn-default" data-toggle="dialog" data-width="400" data-height="300" data-id="dialog-writeTip" data-mask="true">备注</a>';
                html += '&nbsp;<a href="http://192.168.64.2:10086/api/recordPlay?record=' + v.audiopath + '" class="btn btn-default" data-toggle="dialog" data-width="470" data-height="170" data-id="dialog-recordPlay" data-mask="false">试听</a>';
                html += '&nbsp;<a href="http://192.168.18.100/htdocs/include/voice/download.php?type=recorddownload&filename=' + v.audiopath + '" class="btn btn-default" target="_blank">下载</a></td>';
                html += '</td></tr>';
            });
            $(domName).html(html);
            if (boolen == true) {
                $('#total').text(d['total']);
                $('#page1').pagination({
                    'total': d['total'],
                    'pageSize': d['pagerows'],
                    'pageCurrent': d['curpage'],
                });
            } else {
                $('#last_call').val(d['total']);
                $('#t').text(d['total']);
            }
            //当使用的是唯一检索时
            if (para['recordid']) {
                $.post('/phone/findCustomInfo', {'mobile': d['lists'][0]['phone']}, function (dd) {
                    dd = $.parseJSON(dd);
                    $('#name_' + dd.mobile).text(dd.custom_name);
                    $('#card_' + dd.mobile).text(dd.car_code);
                })
            }
            //获取备注信息
            $.post('/phone/getSomeRecord', {'ids': ids}, function (data) {
                if (data != 0) {
                    data = $.parseJSON(data);
                    $.each(data, function (k, v) {
                        if (boolen == true) {
                            $('#id_' + v.record_id).text(v.tip);
                        } else {
                            $('#idd_' + v.record_id).text(v.tip);
                        }
                    });
                }
            });
            //获取用户信息
            if (para['tel'] == '' && mobile) {
                $.post('/phone/getCustomInfo', {mobile: mobile}, function (dd) {
                    if (dd) {
                        dd = $.parseJSON(dd);
                        $.each(dd, function (k, v) {
                            $('.name_' + v['mobile']).text(v['custom_name']);
                            $('.card_' + v['mobile']).text(v['car_code']);
                        });
                    }
                })
            }
        }
    })
}

function doc_filedownload(a) {
    $.fileDownload($(a).attr('href'), {
        failCallback: function (responseHtml, url) {
            if (responseHtml.trim().startsWith('{')) responseHtml = responseHtml.toObj()
            $(a).bjuiajax('ajaxDone', responseHtml)
        }
    })
}

//websoket 实时响应
//var ws,timeid, reconnect = false;
//init();
//function init() {
//  // 创建websocket
//  ws = new WebSocket("ws://" + document.domain + ":2346");
//  // 当socket连接打开时，输入用户名
//  ws.onopen = function() {
//      timeid && window.clearInterval(timeid);
//      if (reconnect == false) {
//          // 登录
//          account.type = 'login';
//          var login_user = JSON.stringify(account);
//          console.log('首次登入发送信息:'+login_user);
//          ws.send(login_user);
//          reconnect = true;
//      } else {
//          // 断线重连
//          account.type = 'relogin';
//          var relogin_data = JSON.stringify(account);
//          console.log("重连发送登录信息:"+relogin_data);
//          ws.send(relogin_data);
//      }
//  };
//  // 当有消息时根据消息类型显示不同信息
//  ws.onmessage = function(e) {
//      console.log(e.data);
//      var data = JSON.parse(e.data);
//      switch (data['type']) {
//          // 服务端ping客户端
//          case 'ping':
//              ws.send(JSON.stringify({"type": "pong"}));
//              break;
//      }
//  };
//  ws.onclose = function() {
//      // 定时重连
//      window.clearInterval(timeid);
//      timeid = window.setInterval(init, 3000);
//  };
//  ws.onerror = function() {
//      console.log("出现错误");
//  };
//}
//$(document).alertmsg('info', 'xxxx上线了。',{displayPosition:'bottomright'});
