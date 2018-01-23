/**
 * Created by tyleryang on 16/3/23.
 */

function showWaitDialog(title, msg) {
    $('#query-mask-content').html('<div class="bjui-pageContent"><h1 style="text-align:center;padding-top:60px">' + msg + '</h1></div>');
    $(document).dialog({
        id: 'wait-dialog',
        title: title,
        width: 400,
        height: 300,
        mask: true,
        resizable: false,
        drawable: false,
        maxable: false,
        minable: false,
        target: '#query-mask',
        fresh: true
    });
}

$('#queryButton').on('click', function () {
    var table = $('#query').DataTable();
    if (table) {
        table.destroy();
    }
    var other_table = $('#other-query').DataTable();
    if (other_table) {
        other_table.destroy();
    }
    $('#query').DataTable({
        processing: true,
        serverSide: true,
        iDisplayLength: 20,
        bLengthChange: false,
        bFilter: false,
        bSort: false,
        select: true,
        bAutoWidth: false,
        ajax: {
            url: "/offer/voucherQuery/query",
            data: function (d) {
                d.s = $('#query-area').val();
                d.carcard = dealCarcard($('#query-carcard').val());
                d.personnelAttribute = $('#query-personnelType').val();
                d.applicantName = $('#query-name').val();
                d.voucherType = $('#query-voucherType').val();
                d.voucherNo = $('#query-voucherNo').val();
                d.taskState = $('#query-taskState').val();
            }
        },
        columns: [
            {data: "voucherNo", defaultContent: "", width: "80px"},
            {data: "insuredName", defaultContent: "", width: "20px"},
            {data: "applicantName", defaultContent: "", width: "20px"},
            {data: "vehicleLicenceCode", defaultContent: "", width: "25px"},
            {data: "nameList", defaultContent: "", width: "40px"},
            {data: "dateInput", defaultContent: "", width: "20px"},
            {data: "productName", defaultContent: "", width: "20px"},
            {data: "totalActualPremium", defaultContent: "", width: "40px"},
            {data: "otherPremium", defaultContent: "", width: "40px"},
            {data: "taskStateName", defaultContent: "", width: "20px"},
            {data: "agentFeeRate", defaultContent: "", width: "10px"},
            {data: "agentFee", defaultContent: "", width: "20px"},
            {data: "status", defaultContent: "", width: "50px"},
            {data: "message", defaultContent: "", width: "50px"}
        ]
    });
    $('#other-query').DataTable({
        processing: true,
        serverSide: true,
        iDisplayLength: 20,
        bLengthChange: false,
        bFilter: false,
        bSort: false,
        select: true,
        bAutoWidth: false,
        ajax: {
            url: "/offer/voucherQuery/query?type=other",
            data: function (d) {
                d.carcard = dealCarcard($('#query-carcard').val());
                d.personnelAttribute = $('#query-personnelType').val();
                d.applicantName = $('#query-name').val();
            }
        },
        columns: [
            {
                data: "quotationNo", defaultContent: "", width: "80px",
                render: function (data, type, row) {
                    return '<a data-id="id48" data-title="保险报价" data-toggle="navtab" href="/offer/offer?qid=' + data + '">' + data + '</a>';
                }
            },
            {
                data: "insuraceCompany", defaultContent: "", width: "20px",
                render: function (data, type, row) {
                    if (data == 'FDBX') {
                        return '富德';
                    }
                    else if (data == 'MACN') {
                        return '亚太';
                    }
                    else if (data == 'TAIC') {
                        return '天安';
                    }
                    else {
                        return '未知';
                    }
                }
            },
            {data: "applyName", defaultContent: "", width: "20px"},
            {data: "licensePlateNo", defaultContent: "", width: "20px"},
            {data: "inputDate", defaultContent: "", width: "25px"},
            {data: "sumGrosspremium", defaultContent: "", width: "40px"},
            {data: "backCash", defaultContent: "", width: "20px"},
            {data: "sumPaidpremium", defaultContent: "", width: "20px"},
            {
                data: "uwInd", defaultContent: "", width: "40px",
                render: function (data, type, row) {
                    if (data == 0) {
                        return '初始状态'
                    }
                    else if (data == 1) {
                        return '报价成功';
                    }
                    else if (data == 2) {
                        return '报价撤回';
                    }
                    else if (data == 3) {
                        return '投保失败';
                    }
                    else if (data == 4) {
                        return '待核保';
                    }
                    else if (data == 5) {
                        return '核保不通过';
                    }
                    else if (data == 6) {
                        return '带获取支付链接';
                    }
                    else if (data == 7) {
                        return '等待支付';
                    }
                    else if (data == 8) {
                        return '生成保单';
                    }
                }
            }
        ]
    });
});

$('#applyButton').on('click', function () {
    var table = $('#query').DataTable();
    if (table) {
        var count = table.rows({selected: true}).count();
        if (count != 1) {
            showMsg('error', '一次只能申请一张表单!');
            return;
        }

        var row = table.rows({selected: true}).data()[0];
        console.log(row);
        if (row.voucherType != "0") {
            showMsg('error', '只允许报价单申请转投保!');
            return;
        }
        if (row.statusCode != 0) {
            showMsg('error', '只允许系统单申请转投保!');
            return;
        }
        $(this).alertmsg('confirm', '是否确认提交选中保单转投保?', {
            displayMode: 'fade',
            displayPosition: 'middlecenter',
            okName: '确认',
            cancelName: '取消',
            title: '请确认',
            okCall: function () {
                showWaitDialog('加载请求', '请求中,请等待...');

                var xhr = $.ajax({
                    type: 'POST',
                    url: '/offer/voucherQuery/apply?s=' + $('#query-area').val() + '&voucherNo=' + row.voucherNo,
                    // async: false,
                    // data: JSON.stringify(rows[i]),
                    // contentType: "application/json"
                });

                xhr.done(function (data) {
                    $(document).dialog('closeCurrent');
                    console.log(data);

                    if (data.errcode != 0) {
                        showMsg('error', data.errmsg);
                    }

                    table.ajax.reload(null, false);
                });
                xhr.fail(function (err) {
                    $(document).dialog('closeCurrent');
                    console.log(err);
                    showMsg('error', err);
                });
            }
        });
    }
});

$('#modifyButton').on('click', function () {
    var table = $('#query').DataTable();
    if (table) {
        var count = table.rows({selected: true}).count();
        if (count != 1) {
            showMsg('error', '一次只能修改一张表单!');
            return;
        }

        var row = table.rows({selected: true}).data()[0];
        if (row.voucherType != "0") {
            showMsg('error', '只允许修改报价单!');
            return;
        }
        console.log(row);
        $(this).alertmsg('confirm', '是否确认修改选中保单?', {
            displayMode: 'fade',
            displayPosition: 'middlecenter',
            okName: '确认',
            cancelName: '取消',
            title: '请确认',
            okCall: function () {
                var options = {
                    id: 'id48',
                    title: '保险报价',
                    url: '/offer/offer?carcard=' + row.vehicleLicenceCode + '&voucherNo=' + row.voucherNo,
                    loadingmask: true,
                    fresh: true
                };
                $(this).navtab(options);
            }
        });
    }
});

$('#delButton').on('click', function () {
    var table = $('#query').DataTable();
    if (table) {
        var count = table.rows({selected: true}).count();
        if (count > 0) {
            $(this).alertmsg('confirm', '是否确认删除选中保单?', {
                displayMode: 'fade',
                displayPosition: 'middlecenter',
                okName: '确认',
                cancelName: '取消',
                title: '请确认',
                okCall: function () {
                    var rows = table.rows({selected: true}).data();
                    for (var i = 0; i < count; i++) {
                        console.log(rows[i]);
                        if (rows[i].voucherType != "0" &&
                            rows[i].voucherType != "1") {
                            showMsg('error', '只允许删除询价单和投保单!');
                            return;
                        }
                    }

                    showWaitDialog('加载请求', '请求中,请等待...');

                    for (var i = 0; i < count; i++) {
                        console.log(rows[i]);
                        var xhr = $.ajax({
                            type: 'POST',
                            url: '/offer/voucherQuery/delete?s=' + $('#query-area').val(),
                            // async: false,
                            data: JSON.stringify(rows[i]),
                            contentType: "application/json"
                        });

                        xhr.done(function (data) {
                            console.log(data);
                            table.ajax.reload(null, false);
                        });
                        xhr.fail(function (err) {
                            console.log(err);
                            showMsg('error', err);
                        });
                    }

                    $(document).dialog('closeCurrent');
                }
            })
        }
    }
});

$('#payButton').on('click', function () {
    var table = $('#query').DataTable();
    if (table) {
        var count = table.rows({selected: true}).count();
        if (count > 0) {
            $(this).alertmsg('confirm', '是否确认获取选中保单支付链接?请确认为同一张保单!', {
                displayMode: 'fade',
                displayPosition: 'middlecenter',
                okName: '确认',
                cancelName: '取消',
                title: '请确认',
                okCall: function () {
                    var rows = table.rows({selected: true}).data();
                    // console.log(rows);
                    var dataList = [];
                    for (var i = 0; i < count; i++) {
                        // console.log(rows[i]);
                        dataList.push(rows[i]);
                    }

                    showWaitDialog('加载请求', '请求中,请等待...');
                    var xhr = $.ajax({
                        type: 'POST',
                        url: '/offer/voucherQuery/paycheck?s=' + $('#query-area').val() + '&clientName=' + $('#query-clientName').val(),
                        // async: false,
                        data: JSON.stringify(dataList),
                        contentType: "application/json"
                    });

                    xhr.done(function (data) {
                        $(document).dialog('closeCurrent');
                        // console.log(data);
                        if (data.result == 0) {
                            var url = data.data;
                            // console.log(v);
                            var options = {
                                id: 'payTab',
                                title: '支付页面',
                                external: true,
                                url: '/offer/voucherQuery/payback?url=' + url,
                                // type: 'POST',
                                // data: v.param,
                                loadingmask: true,
                                fresh: true
                            };
                            $(this).navtab(options);
                            table.ajax.reload(null, false);
                        }
                        else {
                            showMsg('error', data.message);
                        }
                    });
                    xhr.fail(function (err) {
                        $(document).dialog('closeCurrent');
                        console.log(err);
                        showMsg('error', err);
                    });
                }
            })
        }
    }
});
