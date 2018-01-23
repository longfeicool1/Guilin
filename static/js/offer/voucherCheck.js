/**
 * Created by tyleryang on 16/3/23.
 */

$('#checkButton').on('click', function () {
    var table = $('#check').DataTable();
    if (table) {
        table.destroy();
    }
    $('#check').DataTable({
        processing: true,
        serverSide: true,
        iDisplayLength: 20,
        bLengthChange: false,
        bFilter: false,
        bSort: false,
        // select: true,
        bAutoWidth: false,
        ajax: {
            url: "/offer/voucherCheck/query",
            data: function (d) {
                d.s = $('#check-area').val();
                d.checkType = $('#checkType').val();
            }
        },
        columns: [
            {data: "voucherNo", defaultContent: "", width: "80px"},
            {data: "insuredName", defaultContent: "", width: "20px"},
            {data: "vehicleLicenceCode", defaultContent: "", width: "25px"},
            {data: "dateInput", defaultContent: "", width: "20px"},
            {data: "productName", defaultContent: "", width: "20px"},
            {data: "totalActualPremium", defaultContent: "", width: "40px"},
            {data: "otherPremium", defaultContent: "", width: "40px"},
            {data: "taskStateName", defaultContent: "", width: "20px"},
            {data: "agentFeeRate", defaultContent: "", width: "10px"},
            {data: "agentFee", defaultContent: "", width: "20px"},
            {data: "status", defaultContent: "", width: "50px"},
            {data: "message", defaultContent: "", width: "50px"},
            {
                data: null,
                className: "center",
                render: function (data, type, row) {
                    // console.log(data);
                    if (data.statusCode == 1 || data.statusCode == 2) {
                        return '<button class="btn btn-primary" data-id="' + data.voucherNo + '" data-status="' + data.statusCode + '">通过</button> <button class="btn btn-danger" data-id="' + data.voucherNo + '">拒绝</button>';
                    }
                    else {
                        return '';
                    }
                }
            }
        ]
    });
    $('#check tbody').on('click', '.btn-primary', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var table = $('#check').DataTable();
        var voucherNo = $(this).data('id');
        var status = $(this).data('status');
        if (status == 2) {
            $('#check-mask-content').html('<table class="table table-condensed table-hover">' +
                '<tbody><tr>' +
                '<td><label for="j_dialog_operation" class="control-label x90">审批返现金额：</label>' +
                '<input type="text" id="j_dialog_fee" size="15">' +
                '</td></tr>' +
                '</tbody></table>');
            $(document).dialog({
                id: 'approve-dialog',
                title: '审批通过金额',
                width: 400,
                height: 240,
                mask: true,
                resizable: false,
                drawable: false,
                maxable: false,
                minable: false,
                target: '#check-mask',
                fresh: true,
                beforeClose: function (dialog) {
                    var fee = $('#j_dialog_fee').val();
                    
                    if (isNaN(fee)) {
                        showMsg('error', '请输入正确的返现金额');
                        return false;
                    }

                    var data = {
                        voucherNo: voucherNo,
                        approveType: 0,
                        approveMsg: '',
                        fee: fee
                    };
                    var xhr = $.ajax({
                        type: 'POST',
                        url: '/offer/voucherCheck/approve?s=' + $('#check-area').val(),
                        data: JSON.stringify(data),
                        contentType: "application/json"
                    });
                    xhr.done(function (data) {
                        if (data.result == 0) {
                            table.ajax.reload(null, false);
                        }
                        else {
                            showMsg('error', data.message);
                        }
                    });
                    xhr.fail(function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus);
                        showMsg('error', textStatus);
                    });

                    return true;
                }
            });
        }
        else {
            var data = {
                voucherNo: voucherNo,
                approveType: 0,
                approveMsg: '',
                fee: '0.0'
            };
            var xhr = $.ajax({
                type: 'POST',
                url: '/offer/voucherCheck/approve?s=' + $('#check-area').val(),
                data: JSON.stringify(data),
                contentType: "application/json"
            });
            xhr.done(function (data) {
                if (data.result == 0) {
                    table.ajax.reload(null, false);
                }
                else {
                    showMsg('error', data.message);
                }
            });
            xhr.fail(function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                showMsg('error', textStatus);
            });
        }
    });
    $('#check tbody').on('click', '.btn-danger', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var table = $('#check').DataTable();
        var voucherNo = $(this).data('id');

        $('#check-mask-content').html('<table class="table table-condensed table-hover">' +
            '<tbody><tr>' +
            '<td><label for="j_dialog_operation" class="control-label x90">所属业务：</label>' +
            '<select name="dialog.operation" id="j_dialog_operation" data-toggle="selectpicker">' +
            '<option value="0" selected>单保交强险业务，公司禁止承保</option>' +
            '<option value="1">货车禁止承保</option>' +
            '<option value="2">行驶证为企业，公司禁止承保</option>' +
            '<option value="3">其他</option>' +
            '</select>' +
            '</td></tr>' +
            '<tr><td><textarea id="j_dialog_message" rows="20" style="width: 100%"></textarea></td>' +
            '</tr></tbody></table>');
        $(document).dialog({
            id: 'approve-dialog',
            title: '审批拒绝原因',
            width: 600,
            height: 400,
            mask: true,
            resizable: false,
            drawable: false,
            maxable: false,
            minable: false,
            target: '#check-mask',
            fresh: true,
            beforeClose: function (dialog) {
                var type = $('#j_dialog_operation').val();
                var message = $('#j_dialog_message').val();

                if (type == 0 && message.length == 0) {
                    message = '单保交强险业务，公司禁止承保';
                }
                else if (type == 1 && message.length == 0) {
                    message = '货车禁止承保';
                }
                else if (type == 2 && message.length == 0) {
                    message = '行驶证为企业，公司禁止承保';
                }

                if (message.length == 0) {
                    showMsg('error', '请填写拒绝原因');
                    return false;
                }

                var data = {
                    voucherNo: voucherNo,
                    approveType: 1,
                    approveMsg: message
                };
                var xhr = $.ajax({
                    type: 'POST',
                    url: '/offer/voucherCheck/approve?s=' + $('#check-area').val(),
                    data: JSON.stringify(data),
                    contentType: "application/json"
                });
                xhr.done(function (data) {
                    if (data.result == 0) {
                        table.ajax.reload(null, false);
                    }
                    else {
                        showMsg('error', data.message);
                    }
                });
                xhr.fail(function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus);
                    showMsg('error', textStatus);
                });

                return true;
            }
        });
    });
});
