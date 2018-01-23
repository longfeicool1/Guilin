/**
 * 2016-04-07
 * yangchao
 */

$('#queryButton').on('click', function () {
    var table = $('#device-stat-table').DataTable();
    if (table) {
        table.destroy();
    }
    $('#device-stat-table').DataTable({
        "processing": true,
        "serverSide": true,
        "iDisplayLength": 20,
        "bLengthChange": false,
        "bFilter": false,
        "bSort": false,
        "select": true,
        "ajax": {
            "url": "/offer/voucherQuery/query",
            "data": function (d) {
                d.s = $('#query-area').val();
                d.carcard = dealCarcard($('#query-carcard').val());
                d.voucherType = $('#query-voucherType').val();
                d.voucherNo = $('#query-voucherNo').val();
                d.taskState = $('#query-taskState').val();
            }
        },
        "columns": [
            {"data": "voucherNo", "defaultContent": ""},
            {"data": "insuredName", "defaultContent": ""},
            {"data": "applicantName", "defaultContent": ""},
            {"data": "vehicleLicenceCode", "defaultContent": ""},
            {"data": "dateInput", "defaultContent": ""},
            {"data": "productName", "defaultContent": ""},
            {"data": "totalActualPremium", "defaultContent": ""},
            {"data": "otherPremium", "defaultContent": ""},
            {"data": "taskStateName", "defaultContent": ""}
        ]
    });
});
