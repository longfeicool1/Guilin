/**
 * Created by tyleryang on 16/3/23.
 */

function fdbx_clear_form() {
    $.each(['200', '600', '500', '701', '702', '231', '310', '210', '240', '290'], function (idx, kindCode) {
        // 主险,附加险
        $('#FDBX\\.dutys\\.' + kindCode + '\\.preminumRate').val('');
        $('#FDBX\\.dutys\\.' + kindCode + '\\.totalStandardPremium').val('');
        $('#FDBX\\.dutys\\.' + kindCode + '\\.totalAgreePremium').val('');
    });
    $.each(['911', '912', '920', '921', '928', '929', '970', '971', '972'], function (idx, kindCode) {
        // 不计免赔
        $('#FDBX\\.dutys\\.' + kindCode + '\\.totalActualPremium').val('');
    });
}

function fdbx_enable_form() {
    $.each(['200', '600', '500', '701', '702'], function (idx, kindCode) {
        // 主险
        $('#FDBX\\.dutys\\.' + kindCode + '\\.isChecked').iCheck('enable');
    });
}

function fdbx_disable_form() {
    $.each(['200', '600', '500', '701', '702'], function (idx, kindCode) {
        // 主险
        $('#FDBX\\.dutys\\.' + kindCode + '\\.isChecked').iCheck('uncheck');
        $('#FDBX\\.dutys\\.' + kindCode + '\\.isChecked').iCheck('disable');
    });
}

var fdbx_dutys_200_isChecked = $('#FDBX\\.dutys\\.200\\.isChecked');
fdbx_dutys_200_isChecked.on('ifChecked', function (e) {
    $('#FDBX\\.dutys\\.231').attr('class', '');
    $('#FDBX\\.dutys\\.310').attr('class', '');
    $('#FDBX\\.dutys\\.210').attr('class', '');
    $('#FDBX\\.dutys\\.240').attr('class', '');
    $('#FDBX\\.dutys\\.290').attr('class', '');

    $('#FDBX\\.dutys\\.200\\.insuredAmount').attr('readonly', false);

    $('#FDBX\\.dutys\\.911\\.isChecked').iCheck('enable');
    $('#FDBX\\.dutys\\.911\\.isChecked').iCheck('check');
});
fdbx_dutys_200_isChecked.on('ifUnchecked', function (e) {
    $('#FDBX\\.dutys\\.231').attr('class', 'hidden');
    $('#FDBX\\.dutys\\.310').attr('class', 'hidden');
    $('#FDBX\\.dutys\\.210').attr('class', 'hidden');
    $('#FDBX\\.dutys\\.240').attr('class', 'hidden');
    $('#FDBX\\.dutys\\.290').attr('class', 'hidden');

    $('#FDBX\\.dutys\\.231\\.isChecked').iCheck('uncheck');
    $('#FDBX\\.dutys\\.310\\.isChecked').iCheck('uncheck');
    $('#FDBX\\.dutys\\.210\\.isChecked').iCheck('uncheck');
    $('#FDBX\\.dutys\\.240\\.isChecked').iCheck('uncheck');
    $('#FDBX\\.dutys\\.290\\.isChecked').iCheck('uncheck');

    $('#FDBX\\.dutys\\.200\\.insuredAmount').attr('readonly', true);

    $('#FDBX\\.dutys\\.911\\.isChecked').iCheck('uncheck');
    $('#FDBX\\.dutys\\.911\\.isChecked').iCheck('disable');
});

var fdbx_dutys_600_isChecked = $('#FDBX\\.dutys\\.600\\.isChecked');
fdbx_dutys_600_isChecked.on('ifChecked', function (e) {
    $('#FDBX\\.dutys\\.600\\.insuredAmount').attr('disabled', false);
    $('#FDBX\\.dutys\\.600\\.insuredAmount').selectpicker('refresh');

    $('#FDBX\\.dutys\\.912\\.isChecked').iCheck('enable');
    $('#FDBX\\.dutys\\.912\\.isChecked').iCheck('check');
});
fdbx_dutys_600_isChecked.on('ifUnchecked', function (e) {
    $('#FDBX\\.dutys\\.600\\.insuredAmount').attr('disabled', true);
    $('#FDBX\\.dutys\\.600\\.insuredAmount').selectpicker('refresh');

    $('#FDBX\\.dutys\\.912\\.isChecked').iCheck('uncheck');
    $('#FDBX\\.dutys\\.912\\.isChecked').iCheck('disable');
});

var fdbx_dutys_500_isChecked = $('#FDBX\\.dutys\\.500\\.isChecked');
fdbx_dutys_500_isChecked.on('ifChecked', function (e) {
    $('#FDBX\\.dutys\\.500\\.insuredAmount').attr('readonly', false);

    $('#FDBX\\.dutys\\.921\\.isChecked').iCheck('enable');
    $('#FDBX\\.dutys\\.921\\.isChecked').iCheck('check');
});
fdbx_dutys_500_isChecked.on('ifUnchecked', function (e) {
    $('#FDBX\\.dutys\\.500\\.insuredAmount').attr('readonly', true);

    $('#FDBX\\.dutys\\.921\\.isChecked').iCheck('uncheck');
    $('#FDBX\\.dutys\\.921\\.isChecked').iCheck('disable');
});

var fdbx_dutys_701_isChecked = $('#FDBX\\.dutys\\.701\\.isChecked');
var fdbx_dutys_702_isChecked = $('#FDBX\\.dutys\\.702\\.isChecked');
fdbx_dutys_701_isChecked.on('ifChecked', function (e) {
    $('#FDBX\\.dutys\\.701\\.insuredAmount').attr('readonly', false);

    $('#FDBX\\.dutys\\.928\\.isChecked').iCheck('enable');
    $('#FDBX\\.dutys\\.928\\.isChecked').iCheck('check');
});
fdbx_dutys_701_isChecked.on('ifUnchecked', function (e) {
    $('#FDBX\\.dutys\\.701\\.insuredAmount').attr('readonly', true);

    if ($('#FDBX\\.fgInd').val() == '1' || !fdbx_dutys_702_isChecked.prop('checked')) {
        $('#FDBX\\.dutys\\.928\\.isChecked').iCheck('uncheck');
        $('#FDBX\\.dutys\\.928\\.isChecked').iCheck('disable');
    }
});
fdbx_dutys_702_isChecked.on('ifChecked', function (e) {
    $('#FDBX\\.dutys\\.702\\.insuredAmount').attr('readonly', false);

    if ($('#FDBX\\.fgInd').val() == '0') {
        $('#FDBX\\.dutys\\.928\\.isChecked').iCheck('enable');
        $('#FDBX\\.dutys\\.928\\.isChecked').iCheck('check');
    }

    $('#FDBX\\.dutys\\.929\\.isChecked').iCheck('enable');
    $('#FDBX\\.dutys\\.929\\.isChecked').iCheck('check');
});
fdbx_dutys_702_isChecked.on('ifUnchecked', function (e) {
    $('#FDBX\\.dutys\\.702\\.insuredAmount').attr('readonly', true);

    if ($('#FDBX\\.fgInd').val() == '1' || !fdbx_dutys_701_isChecked.prop('checked')) {
        $('#FDBX\\.dutys\\.928\\.isChecked').iCheck('uncheck');
        $('#FDBX\\.dutys\\.928\\.isChecked').iCheck('disable');
    }

    $('#FDBX\\.dutys\\.929\\.isChecked').iCheck('disable');
    $('#FDBX\\.dutys\\.929\\.isChecked').iCheck('uncheck');
});

var fdbx_dutys_231_isChecked = $('#FDBX\\.dutys\\.231\\.isChecked');
fdbx_dutys_231_isChecked.on('ifChecked', function (e) {
    $('#FDBX\\.dutys\\.231\\.seats0').iCheck('enable');
    $('#FDBX\\.dutys\\.231\\.seats1').iCheck('enable');
});
fdbx_dutys_231_isChecked.on('ifUnchecked', function (e) {
    $('#FDBX\\.dutys\\.231\\.seats0').iCheck('disable');
    $('#FDBX\\.dutys\\.231\\.seats1').iCheck('disable');
});

var fdbx_dutys_310_isChecked = $('#FDBX\\.dutys\\.310\\.isChecked');
var fdbx_dutys_210_isChecked = $('#FDBX\\.dutys\\.210\\.isChecked');
var fdbx_dutys_290_isChecked = $('#FDBX\\.dutys\\.290\\.isChecked');
fdbx_dutys_310_isChecked.on('ifChecked', function (e) {
    $('#FDBX\\.dutys\\.310\\.insuredAmount').attr('readonly', false);

    $('#FDBX\\.dutys\\.920\\.isChecked').iCheck('enable');
    $('#FDBX\\.dutys\\.920\\.isChecked').iCheck('check');

    $('#FDBX\\.dutys\\.970\\.isChecked').iCheck('enable');
    $('#FDBX\\.dutys\\.970\\.isChecked').iCheck('check');
});
fdbx_dutys_310_isChecked.on('ifUnchecked', function (e) {
    $('#FDBX\\.dutys\\.310\\.insuredAmount').attr('readonly', true);

    if ($('#FDBX\\.fgInd').val() == '1' ||
        (!fdbx_dutys_210_isChecked.prop('checked') && !fdbx_dutys_290_isChecked.prop('checked'))) {
        $('#FDBX\\.dutys\\.920\\.isChecked').iCheck('uncheck');
        $('#FDBX\\.dutys\\.920\\.isChecked').iCheck('disable');
    }

    $('#FDBX\\.dutys\\.970\\.isChecked').iCheck('uncheck');
    $('#FDBX\\.dutys\\.970\\.isChecked').iCheck('disable');
});
fdbx_dutys_210_isChecked.on('ifChecked', function (e) {
    $('#FDBX\\.dutys\\.210\\.insuredAmount').attr('disabled', false);
    $('#FDBX\\.dutys\\.210\\.insuredAmount').selectpicker('refresh');

    $('#FDBX\\.dutys\\.920\\.isChecked').iCheck('enable');
    $('#FDBX\\.dutys\\.920\\.isChecked').iCheck('check');

    $('#FDBX\\.dutys\\.971\\.isChecked').iCheck('enable');
    $('#FDBX\\.dutys\\.971\\.isChecked').iCheck('check');
});
fdbx_dutys_210_isChecked.on('ifUnchecked', function (e) {
    $('#FDBX\\.dutys\\.210\\.insuredAmount').attr('disabled', true);
    $('#FDBX\\.dutys\\.210\\.insuredAmount').selectpicker('refresh');

    if ($('#FDBX\\.fgInd').val() == '1' ||
        (!fdbx_dutys_210_isChecked.prop('checked') && !fdbx_dutys_290_isChecked.prop('checked'))) {
        $('#FDBX\\.dutys\\.920\\.isChecked').iCheck('uncheck');
        $('#FDBX\\.dutys\\.920\\.isChecked').iCheck('disable');
    }

    $('#FDBX\\.dutys\\.971\\.isChecked').iCheck('uncheck');
    $('#FDBX\\.dutys\\.971\\.isChecked').iCheck('disable');
});
fdbx_dutys_290_isChecked.on('ifChecked', function (e) {
    $('#FDBX\\.dutys\\.290\\.seats').attr('disabled', false);
    $('#FDBX\\.dutys\\.290\\.seats').selectpicker('refresh');

    $('#FDBX\\.dutys\\.920\\.isChecked').iCheck('enable');
    $('#FDBX\\.dutys\\.920\\.isChecked').iCheck('check');

    $('#FDBX\\.dutys\\.972\\.isChecked').iCheck('enable');
    $('#FDBX\\.dutys\\.972\\.isChecked').iCheck('check');
});
fdbx_dutys_290_isChecked.on('ifUnchecked', function (e) {
    $('#FDBX\\.dutys\\.290\\.seats').attr('disabled', true);
    $('#FDBX\\.dutys\\.290\\.seats').selectpicker('refresh');

    if ($('#FDBX\\.fgInd').val() == '1' ||
        (!fdbx_dutys_310_isChecked.prop('checked') && !fdbx_dutys_210_isChecked.prop('checked'))) {
        $('#FDBX\\.dutys\\.920\\.isChecked').iCheck('uncheck');
        $('#FDBX\\.dutys\\.920\\.isChecked').iCheck('disable');
    }

    $('#FDBX\\.dutys\\.972\\.isChecked').iCheck('uncheck');
    $('#FDBX\\.dutys\\.972\\.isChecked').iCheck('disable');
});

$('#FDBX\\.dutys\\.200\\.insuredAmount').on('input', function () {
    var purchasePrice = parseFloat($('#FDBX\\.veh\\.purchasePrice').text());
    var inputPurchasePrice = parseFloat($('#FDBX\\.dutys\\.200\\.insuredAmount').val());

    var stealRobInsuredAmount = parseFloat($('#FDBX\\.dutys\\.500\\.insuredAmountDefaultValue').text());

    if (isNaN(purchasePrice) || isNaN(inputPurchasePrice) || inputPurchasePrice > purchasePrice || isNaN(stealRobInsuredAmount)) {
        return;
    }

    $('#FDBX\\.dutys\\.500\\.insuredAmount').val((stealRobInsuredAmount * (inputPurchasePrice / purchasePrice)).toFixed(2));
});

var acceptProvince = $('#FDBX\\.acceptProvince');
var acceptCity = $('#FDBX\\.acceptCity');
var acceptTown = $('#FDBX\\.acceptTown');

acceptProvince.select2();
acceptCity.select2();
acceptTown.select2();

$.getJSON('/offer/offer/queryCity', function (data) {
    acceptProvince.select2({data: data});
    acceptProvince.val('440000').trigger('change');
});

acceptProvince.on('change', function (e) {
    var p = acceptProvince.val();
    $.getJSON('/offer/offer/queryCity?pid=' + p, function (data) {
        acceptCity.empty();
        if (data.length > 0) {
            acceptCity.select2({data: data});
            if (p == '440000') {
                acceptCity.val('440300').trigger('change');
            }
            else {
                acceptCity.val(data[0].id).trigger('change');
            }
        }
    });
});

acceptCity.on('change', function (e) {
    var c = acceptCity.val();
    $.getJSON('/offer/offer/queryCity?cid=' + c, function (data) {
        acceptTown.empty();
        if (data.length > 0) {
            acceptTown.select2({data: data});
            if (c == '440300') {
                acceptTown.val('440304').trigger('change');
            }
            else {
                acceptTown.val(data[0].id).trigger('change');
            }
        }
    });
});

function query_fdbx(main, relateShips, vechile, risks, coverages, carShip, delivery, summary) {
    // 显示报价结果

    // 险种信息
    $.each(risks || [], function (idx, risk) {
        if (risk.riskCode == '0101') {
            // 商业险
            $('#FDBX\\.dutys\\.c01\\.totalStandardPremium').val(risk.benchmarkPremium);
            $('#FDBX\\.dutys\\.c01\\.totalActualPremium').val(risk.sumGrossPremium);
            $('#FDBX\\.dutys\\.c01\\.fee').val(risk.backCash);
        }
        else {
            // 交强险
            $('#c51\\.totalActualPremium').val(risk.sumGrossPremium);

            $('#FDBX\\.dutys\\.c51\\.totalActualPremium').val(risk.sumGrossPremium);
        }
    });

    // 优惠信息

    // 车船税信息
    $('#vehicleTaxInfo\\.totalTaxMoney').val(carShip.taxDue);

    $('#FDBX\\.dutys\\.totalTaxMoney').val(carShip.taxDue);

    // 特约信息

    // 险别信息
    $.each(coverages || [], function (idx, clause) {
        if ($.inArray(clause.coverageCode, ['01', '02', '03', '041', '044']) > -1) {
            // 主险
            var unitCode = 200;
            if (clause.coverageCode == '01') {
                unitCode = 200;
                $('#FDBX\\.veh\\.purchasePrice').text(vechile.replacementValue);
                $('#FDBX\\.dutys\\.200\\.insuredAmount').val(clause.sumInsured);
            }
            else if (clause.coverageCode == '02') {
                unitCode = 600;
                // $('#FDBX\\.dutys\\.600\\.insuredAmount').selectpicker('val', parseInt(clause.sumInsured) / 10000);
                $('#FDBX\\.dutys\\.600\\.insuredAmount').val(parseInt(clause.sumInsured) / 10000);
                $('#FDBX\\.dutys\\.600\\.insuredAmount').selectpicker('refresh');
            }
            else if (clause.coverageCode == '03') {
                unitCode = 500;
                $('#FDBX\\.dutys\\.500\\.insuredAmountDefaultValue').text(vechile.actualValue);
                $('#FDBX\\.dutys\\.500\\.insuredAmount').val(clause.sumInsured);
            }
            else if (clause.coverageCode == '041') {
                unitCode = 701;
                $('#FDBX\\.dutys\\.701\\.insuredAmount').val(clause.sumInsured);
            }
            else if (clause.coverageCode == '044') {
                unitCode = 702;
                $('#FDBX\\.dutys\\.702\\.seats').val(clause.quantity);
                $('#FDBX\\.dutys\\.702\\.insuredAmount').val(parseInt(clause.sumInsured) / parseInt(clause.quantity));
            }
            $('#FDBX\\.dutys\\.' + unitCode).iCheck('check');
            $('#FDBX\\.dutys\\.' + unitCode + '\\.preminumRate').val(parseFloat(clause.discount) / 100.0);
            $('#FDBX\\.dutys\\.' + unitCode + '\\.totalStandardPremium').val(clause.benchmarkPremium);
            $('#FDBX\\.dutys\\.' + unitCode + '\\.totalAgreePremium').val(clause.grossPremium);
        }
        else if ($.inArray(clause.coverageCode, ['11', '13', '21', '62', 'SW']) > -1) {
            // 附加险
            var unitCode = 231;
            if (clause.coverageCode == '11') {
                unitCode = 231;
            }
            else if (clause.coverageCode == '13') {
                unitCode = 310;
                $('#FDBX\\.dutys\\.310\\.insuredAmountDefaultValue').text(vechile.actualValue);
                $('#FDBX\\.dutys\\.310\\.insuredAmount').val(clause.sumInsured);
            }
            else if (clause.coverageCode == '21') {
                unitCode = 210;
            }
            else if (clause.coverageCode == '62') {
                unitCode = 240
            }
            else if (clause.coverageCode == 'SW') {
                unitCode = 290
            }
            $('#FDBX\\.dutys\\.' + unitCode).iCheck('check');
            $('#FDBX\\.dutys\\.' + unitCode + '\\.preminumRate').val(parseFloat(clause.discount) / 100.0);
            $('#FDBX\\.dutys\\.' + unitCode + '\\.totalStandardPremium').val(clause.benchmarkPremium);
            $('#FDBX\\.dutys\\.' + unitCode + '\\.totalAgreePremium').val(clause.grossPremium);
        }
        else if ($.inArray(clause.coverageCode, ['601', '602', '603', '604', '605', '606', '607', '608', '609', '610', '61']) > -1) {
            // 不计免赔
            var unitCode = 911;
            if (clause.coverageCode == '601') {
                unitCode = 911;
            }
            else if (clause.coverageCode == '602') {
                unitCode = 912
            }
            else if (clause.coverageCode == '603') {
                unitCode = 921
            }
            else if (clause.coverageCode == '604') {
                unitCode = 928
            }
            else if (clause.coverageCode == '605') {
                unitCode = 928
            }
            else if (clause.coverageCode == '606') {
                unitCode = 929
            }
            else if (clause.coverageCode == '607') {
                unitCode = 970
            }
            else if (clause.coverageCode == '608') {
                unitCode = 971//TODO;unknow?
            }
            else if (clause.coverageCode == '609') {
                unitCode = 971
            }
            else if (clause.coverageCode == '610') {
                unitCode = 972
            }
            else if (clause.coverageCode == '61') {
                unitCode = 920
            }
            $('#FDBX\\.dutys\\.' + unitCode).iCheck('check');
            $('#FDBX\\.dutys\\.' + unitCode + '\\.totalActualPremium').val(clause.grossPremium);
        }
    });

    $('#FDBX\\.dutys\\.totalAgreePremium').val(main.sumGrosspremium);

    $('#FDBX\\.quotationNo').val(main.quotationNo);

    $('#FDBX\\.acceptInfo').attr('class', 'table table-condensed table-hover');

    if (!isEmpty(delivery)) {
        //
    }
}

function vehicleQuery_fdbx() {
    var insuraceCompany = 'FDBX';
    var areaCode = $('#offer-areaCode').val();
    var vehicleTarget = {
        'autoModelCode': $('#autoModelCode').val(),
        'autoModelName': $('#brand').val(),
        'brandName': $('#brandName').val(),
        'engineNo': $('#engineNo').val(),
        'firstRegisterDate': $('#firstRegisterDate').val(),
        'licenceTypeCode': $('#licenceTypeCode').val(),
        'ownershipAttributeCode': $('#ownershipAttributeCode').val(),
        'ownerVehicleTypeCode': $('#vehicleTypeCode').val() == '0' ? 'K33' : 'K31',
        'usageAttributeCode': $('#usageAttributeCode').val(),
        'vehicleFrameNo': $('#vehicleFrameNo').val(),
        'vehicleLicenceCode': $('#vehicleLicenceCode').val(),
        'vehicleTypeCode': $('#vehicleTypeCode').val() == '0' ? 'A012' : 'A022',
        'vehicleClassCode': '1',
        'vehicleSeats': $('#vehicleSeats').val(),
        'taxType': '1',
        'taxPayerId': $('#certificateTypeNo').val(),
        'fuelType': '0',
        'purchasePriceDefaultValue': $('#veh\\.purchasePriceDefaultValue').text(),
        'vehicleLossInsuredValue': $('#veh\\.vehicleLossInsuredValue').val(),
        'wholeWeight': $('#vehWholeWeight').val(),
        'exhaustCapability': $('#vehExhaustCapability').val(),
        'specialCarFlag': $('#specialCarFlag').val(),
        'transferDate': $('#transferDate').val()
    };

    var showCar = function (car) {
        // console.log(car);
        // 根据查出来的车辆显示价值
        $('#FDBX\\.veh\\.purchasePrice').text(car.purchasePrice);
        var fgInd = $('#FDBX\\.fgInd').val();
        if (fgInd == '0') {
            $('#FDBX\\.dutys\\.200\\.insuredAmount').val(car.purchasePrice);
        }
        else {
            $('#FDBX\\.dutys\\.200\\.insuredAmount').val(car.actualValue);
        }

        $('#FDBX\\.dutys\\.500\\.insuredAmountDefaultValue').text(car.actualValue);
        $('#FDBX\\.dutys\\.500\\.insuredAmount').val(car.actualValue);

        $('#FDBX\\.dutys\\.702\\.seats').val(car.seatCount - 1);

        $('#FDBX\\.dutys\\.310\\.insuredAmountDefaultValue').text(car.actualValue);
        $('#FDBX\\.dutys\\.310\\.insuredAmount').val(car.actualValue);

        $('#FDBX\\.quotationNo').data('car', car);
    };

    // 查车
    var vehicleQuery_params = {
        comType: insuraceCompany,
        areaCode: areaCode,
        licenseNo: vehicleTarget['vehicleLicenceCode'].replace('-', ''),
        frameNo: vehicleTarget['vehicleFrameNo'],
        engineNo: vehicleTarget['engineNo'],
        modelCName: vehicleTarget['autoModelName'],
        purchaseDate: vehicleTarget['firstRegisterDate'],
        enrollDate: vehicleTarget['firstRegisterDate'],
        startDate: $('#c01BeginTime').val(),
        carKindCode: '100',
        useNatureShow: '02'
    };

    showWaitDialog('加载请求', '请求中,请等待...');

    var xhr = $.ajax({
        type: 'POST',
        url: '/offer/offer/vehicleQuery',
        data: JSON.stringify(vehicleQuery_params),
        contentType: 'application/json'
    });

    xhr.done(function (data) {
        $(document).dialog('closeCurrent');
        console.log(data);

        if (data.result == 0) {
            // 车辆选择?

            var car = null;
            var dataLists = data.data;
            if (dataLists.length > 1) {
                $('#offer-mask-content').html('<table id="select.car.table" class="table table-bordered" cellspacing="0" width="100%">' +
                    '<thead><tr>' +
                    '<th>车辆名称</th>' +
                    '<th>排量</th>' +
                    '<th>年款</th>' +
                    '<th>座位数</th>' +
                    '<th>品牌</th>' +
                    '<th>型号</th>' +
                    '<th>购置价</th>' +
                    '<th>实际价值</th>' +
                    '</tr></thead></table>');
                $(document).dialog({
                    id: 'car-select-dialog',
                    title: '选择车辆',
                    width: 600,
                    height: 400,
                    mask: true,
                    resizable: false,
                    drawable: false,
                    maxable: false,
                    minable: false,
                    target: '#offer-mask',
                    fresh: true,
                    onLoad: function (dialog) {
                        // console.log(dialog);
                        var table = $('#select\\.car\\.table').DataTable();
                        if (table) {
                            table.destroy();
                        }
                        table = $('#select\\.car\\.table').DataTable({
                            "data": dataLists,
                            "iDisplayLength": 20,
                            "bLengthChange": false,
                            "bFilter": false,
                            "bSort": false,
                            "select": true,
                            "columns": [
                                {"data": "brandName", "defaultContent": ""},
                                {"data": "exhaustCapacity", "defaultContent": ""},
                                {"data": "marketDate", "defaultContent": ""},
                                {"data": "seatCount", "defaultContent": ""},
                                {"data": "vehicleBrand", "defaultContent": ""},
                                {"data": "vehicleSeries", "defaultContent": ""},
                                {"data": "purchasePrice", "defaultContent": ""},
                                {"data": "actualValue", "defaultContent": ""}
                            ]
                        });

                        table.on('select', function (e, dt, type, indexes) {
                            if (type == 'row') {
                                var car = dt.data();
                                showCar(car);

                                $(document).dialog('closeCurrent');
                            }
                        });
                    }
                });
            }
            else {
                car = dataLists[0];
                showCar(car);
            }
        }
        else {
            showMsg('error', data.message);
        }
    });
    xhr.fail(function (jqXHR, textStatus, errorThrown) {
        $(document).dialog('closeCurrent');
        console.log(textStatus);
        showMsg('error', textStatus);
    });
}

function offer_fdbx(params) {
    // console.log(params);

    fdbx_clear_form();

    var insuraceCompany = 'FDBX';
    var areaCode = $('#offer-areaCode').val();

    var car = $('#FDBX\\.quotationNo').data('car');
    console.log(car);

    if (!car) {
        showMsg('error', '请先执行车辆查询!');
        return;
    }

    console.log(car);

    var serialNo = 1;
    var today = new Date();
    var taxType = '02';
    if ($('#taxType1').prop('checked')) {
        taxType = '02';
    }
    else if ($('#taxType2').prop('checked')) {
        taxType = '01';
    }
    else if ($('#taxType3').prop('checked')) {
        taxType = '02';
    }
    else if ($('#taxType4').prop('checked')) {
        taxType = '03';
    }
    else if ($('#taxType5').prop('checked')) {
        taxType = '05';
    }
    var fgInd = $('#FDBX\\.fgInd').val();

    var quote_params = {
        main: {
            insuranceCompany: insuraceCompany,
            insurerArea: areaCode,
            businessSource: '0001',
            businessChannel: '1',
            salesCode: 'admin',
            inputDate: today.formatDate('yyyy-MM-dd'),
            fgInd: fgInd
        },
        vehicle: {
            licensePlateNo: params['vehicleTarget']['vehicleLicenceCode'].replace('-', ''),
            VIN: params['vehicleTarget']['vehicleFrameNo'],
            engineNo: params['vehicleTarget']['engineNo'],
            modelName: params['vehicleTarget']['autoModelName'],
            firstRegisterDate: params['vehicleTarget']['firstRegisterDate'],
            seatCount: car.seatCount,
            displacement: parseFloat(params['vehicleTarget']['exhaustCapability']) * 1000,
            carKindCode: '100',//TODO:车辆种类,100客车,200货车
            useNatureCode: params['vehicleTarget']['usageAttributeCode'],//使用性质,01营运,02非营运
            carUseType: '01',//TODO:车辆用途,01家庭自用,02机关自用,03企业自用,04出租客运,05租赁客运,06城市公交,07公路客运,08货物运输,09特殊用途
            attachNature: '01', //TODO:所属性质,01个人,02机关/团体,03企业
            modelCode: car.modelCode,
            replacementValue: car.purchasePrice,
            actualValue: car.actualValue,
            purchaseDate: params['vehicleTarget']['firstRegisterDate'],//TODO:购置日期
            vehicleType: params['vehicleTarget']['vehicleFrameNo'].substring(0, 1).toUpperCase() == 'L' ? '1' : '2', // 国产1,进口2
            importFlag: params['vehicleTarget']['vehicleFrameNo'].substring(0, 1).toUpperCase() == 'L' ? 'A' : 'B', // 国产A,进口B,合资C
            platformModelCode: car.platformModelCode,
            carName: car.carName,
            noticeType: car.noticeType,
            chgOwnerFlag: params['vehicleTarget']['specialCarFlag'] == '1' ? '1' : '0',
            transferDate: params['vehicleTarget']['transferDate']
        },
        risks: [],
        kindList: [],
        carOwner: {
            ownerName: params['ownerDriver']['personnelName'],
            identifyType: params['ownerDriver']['certificateTypeCode'],
            identifyNumber: params['ownerDriver']['certificateTypeNo'],
            gender: params['ownerDriver']['sex'] == 'M' ? 1 : 2,
            birthDate: params['ownerDriver']['birthday']
        },
        carShip: {
            taxConditionCode: taxType,
            taxDocumentNumber: $('#payTaxNo').val(),
            taxDepartment: $('#taxOrg').val()
        },
        relateShips: [{
            applyName: params['applicantInfo']['personnelName'],
            serialNo: '1',
            credentialCode: params['applicantInfo']['certificateTypeCode'],
            credentialNo: params['applicantInfo']['certificateTypeNo'],
            gender: params['applicantInfo']['sex'] == 'M' ? 1 : 2,
            birthDate: params['applicantInfo']['birthday'],
            phone: '13530380999',
            relateType: '1'
        }, {
            applyName: params['insurantInfo']['personnelName'],
            serialNo: '2',
            credentialCode: params['insurantInfo']['certificateTypeCode'],
            credentialNo: params['insurantInfo']['certificateTypeNo'],
            gender: params['insurantInfo']['sex'] == 'M' ? 1 : 2,
            birthDate: params['insurantInfo']['birthday'],
            phone: '13530380999',
            relateType: '2'
        }]
    };

    if ($('#isCheckC01').prop('checked')) {
        quote_params['risks'].push({
            startDate: $('#c01BeginTime').val(),
            endDate: $('#c01EndTime').val(),
            riskCode: '0101',
            unionInd: '1'
        });

        if ($('#FDBX\\.dutys\\.200\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '01',
                kindName: '车辆损失险',
                kindInd: '1',
                sumInsured: $('#FDBX\\.dutys\\.200\\.insuredAmount').val(),
                checked: '1',
                deductibleInd: '0',
                deductibleCode: '601',
                unitCode: '200',
                unitInsured: '0'
            });
        }

        if ($('#FDBX\\.dutys\\.911\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '601',
                kindName: '车损不计免',
                kindInd: '2',
                sumInsured: '0',
                checked: '1',
                deductibleInd: '1',
                deductibleCode: '',
                unitCode: '911',
                unitInsured: '0'
            });
        }

        if ($('#FDBX\\.dutys\\.600\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '02',
                kindName: '商业第三者责任险',
                kindInd: '1',
                sumInsured: parseInt($('#FDBX\\.dutys\\.600\\.insuredAmount').val(), 10) * 10000,
                checked: '1',
                deductibleInd: '0',
                deductibleCode: '602',
                unitCode: '600',
                unitInsured: '0'
            });
        }

        if ($('#FDBX\\.dutys\\.912\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '602',
                kindName: '商三不计免',
                kindInd: '2',
                sumInsured: '0',
                checked: '1',
                deductibleInd: '1',
                deductibleCode: '',
                unitCode: '912',
                unitInsured: '0'
            });
        }

        if ($('#FDBX\\.dutys\\.500\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '03',
                kindName: '全车盗抢险',
                kindInd: '1',
                sumInsured: $('#FDBX\\.dutys\\.500\\.insuredAmount').val(),
                checked: '1',
                deductibleInd: '0',
                deductibleCode: '603',
                unitCode: '500',
                unitInsured: '0'
            });
        }

        if ($('#FDBX\\.dutys\\.921\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '603',
                kindName: '盗抢不计免',
                kindInd: '2',
                sumInsured: '0',
                checked: '1',
                deductibleInd: '1',
                deductibleCode: '',
                unitCode: '921',
                unitInsured: '0'
            });
        }

        if ($('#FDBX\\.dutys\\.701\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '041',
                kindName: '车上人员责任险（司机）',
                kindInd: '1',
                sumInsured: $('#FDBX\\.dutys\\.701\\.insuredAmount').val(),
                checked: '1',
                deductibleInd: '0',
                deductibleCode: $('#FDBX\\.fgInd').val() == '0' ? '604' : '605',
                unitCode: '701',
                unitInsured: $('#FDBX\\.dutys\\.701\\.insuredAmount').val(),
                quantity: 1
            });
        }

        if ($('#FDBX\\.dutys\\.928\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: $('#FDBX\\.fgInd').val() == '0' ? '604' : '605',
                kindName: $('#FDBX\\.fgInd').val() == '0' ? '人员不计免' : '司机不计免赔',
                kindInd: '2',
                sumInsured: '0',
                checked: '1',
                deductibleInd: '1',
                deductibleCode: '',
                unitCode: '928',
                unitInsured: '0'
            });
        }

        if ($('#FDBX\\.dutys\\.702\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '044',
                kindName: '车上人员责任险（乘客）',
                kindInd: '1',
                sumInsured: parseInt($('#FDBX\\.dutys\\.702\\.insuredAmount').val(), 10) * parseInt($('#FDBX\\.dutys\\.702\\.seats').val(), 10),
                checked: '1',
                deductibleInd: '0',
                deductibleCode: $('#FDBX\\.fgInd').val() == '0' ? '604' : '606',
                unitCode: '702',
                unitInsured: $('#FDBX\\.dutys\\.702\\.insuredAmount').val(),
                quantity: $('#FDBX\\.dutys\\.702\\.seats').val()
            });
        }

        if ($('#FDBX\\.dutys\\.929\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '606',
                kindName: '乘客不计免赔',
                kindInd: '2',
                sumInsured: '0',
                checked: '1',
                deductibleInd: '1',
                deductibleCode: '',
                unitCode: '929',
                unitInsured: '0'
            });
        }

        if ($('#FDBX\\.dutys\\.231\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '11',
                kindName: '玻璃单独破碎险',
                kindInd: '2',
                sumInsured: '0',
                checked: '1',
                deductibleInd: '0',
                deductibleCode: '',
                unitCode: '231',
                unitInsured: '0',
                valueType: $('#FDBX\\.dutys\\.231\\.seats0').prop('checked') ? '1' : '2'//国产1,进口2;
            });
        }

        if ($('#FDBX\\.dutys\\.310\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '13',
                kindName: '自燃损失险',
                kindInd: '2',
                sumInsured: $('#FDBX\\.dutys\\.310\\.insuredAmount').val(),
                checked: '1',
                deductibleInd: '0',
                deductibleCode: $('#FDBX\\.fgInd').val() == '0' ? '61' : '607',
                unitCode: '310',
                unitInsured: '0'
            });
        }

        if ($('#FDBX\\.dutys\\.970\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '607',
                kindName: '自燃损失不计免',
                kindInd: '2',
                sumInsured: '0',
                checked: '1',
                deductibleInd: '1',
                deductibleCode: '',
                unitCode: '970',
                unitInsured: '0'
            });
        }

        if ($('#FDBX\\.dutys\\.210\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '21',
                kindName: '车身划痕损失险',
                kindInd: '2',
                sumInsured: $('#FDBX\\.dutys\\.210\\.insuredAmount').val(),
                checked: '1',
                deductibleInd: '0',
                deductibleCode: $('#FDBX\\.fgInd').val() == '0' ? '61' : '609',
                unitCode: '210',
                unitInsured: '0'
            });
        }

        if ($('#FDBX\\.dutys\\.971\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '609',
                kindName: '车身划痕不计免',
                kindInd: '2',
                sumInsured: '0',
                checked: '1',
                deductibleInd: '1',
                deductibleCode: '',
                unitCode: '971',
                unitInsured: '0'
            });
        }

        if ($('#FDBX\\.dutys\\.240\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '62',
                kindName: '多次事故免赔特约险',
                kindInd: '2',
                sumInsured: '0',
                checked: '1',
                deductibleInd: '0',
                deductibleCode: '',
                unitCode: '240',
                unitInsured: '0'
            });
        }

        if ($('#FDBX\\.dutys\\.290\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: 'SW',
                kindName: '涉水行驶损失险',
                kindInd: '2',
                sumInsured: 0,
                checked: '1',
                deductibleInd: '0',
                deductibleCode: $('#FDBX\\.fgInd').val() == '0' ? '61' : '610',
                unitCode: '290',
                unitInsured: '0'
            });
        }

        if ($('#FDBX\\.dutys\\.972\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '610',
                kindName: '涉水不计免',
                kindInd: '2',
                sumInsured: '0',
                checked: '1',
                deductibleInd: '1',
                deductibleCode: '',
                unitCode: '972',
                unitInsured: '0'
            });
        }

        if ($('#FDBX\\.dutys\\.920\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo,
                kindCode: '61',
                kindName: '附加险不计免赔',
                kindInd: '2',
                sumInsured: '0',
                checked: '1',
                deductibleInd: '1',
                deductibleCode: '',
                unitCode: '920',
                unitInsured: '0'
            });
        }
    }

    if ($('#isCheckC51').prop('checked')) {
        quote_params['risks'].push({
            startDate: $('#c51BeginTime').val(),
            endDate: $('#c51EndTime').val(),
            riskCode: '0102',
            unionInd: '1'
        });
    }

    showWaitDialog('加载请求', '请求中,请等待...');
    console.log(quote_params);

    var xhr = $.ajax({
        type: 'POST',
        url: '/offer/offer/quote?insuraceCompany=FDBX&areaCode=' + areaCode,
        data: JSON.stringify(quote_params),
        contentType: "application/json"
    });

    xhr.done(function (data) {
        $(document).dialog('closeCurrent');
        if (data.result == 0) {
            var result = data.data;
            console.log(result);
            if (result.errorCode == '0000') {
                // 显示报价结果
                var totalPremium = 0;
                var totalStandardPremium = 0;
                var totalActualPremium = 0;

                // 基本信息
                var mainDto = result.mainDto;

                // 险种信息
                var riskDtoList = result.riskDtoList;
                $.each(riskDtoList || [], function (idx, risk) {
                    if (risk.riskInd == '1') {
                        // 商业险
                        $('#FDBX\\.dutys\\.totalAgreePremium').val(risk.sumGrossPremium);
                    }
                    else {
                        // 交强险
                        $('#c51\\.totalActualPremium').val(risk.sumGrossPremium);

                        $('#FDBX\\.dutys\\.c51\\.totalActualPremium').val(risk.sumGrossPremium);
                        totalPremium += risk.sumGrossPremium;
                    }
                });

                // 优惠信息
                var profitList = result.profitList;

                // 车船税信息
                var carShipTaxDto = result.carShipTaxDto;
                $('#vehicleTaxInfo\\.totalTaxMoney').val(carShipTaxDto.taxDueActual);

                $('#FDBX\\.dutys\\.totalTaxMoney').val(carShipTaxDto.taxDueActual);
                totalPremium += carShipTaxDto.taxDueActual;

                // 特约信息
                var specialList = result.specialList;

                // 险别信息
                var clauseList = result.clauseList;
                $.each(clauseList || [], function (idx, clause) {
                    if ($.inArray(clause.kindCode, ['01', '02', '03', '041', '044']) > -1) {
                        // 主险
                        var unitCode = 200;
                        if (clause.kindCode == '01') {
                            unitCode = 200;
                        }
                        else if (clause.kindCode == '02') {
                            unitCode = 600
                        }
                        else if (clause.kindCode == '03') {
                            unitCode = 500
                        }
                        else if (clause.kindCode == '041') {
                            unitCode = 701
                        }
                        else if (clause.kindCode == '044') {
                            unitCode = 702
                        }
                        $('#FDBX\\.dutys\\.' + unitCode + '\\.preminumRate').val(parseFloat(clause.discount) / 100.0);
                        $('#FDBX\\.dutys\\.' + unitCode + '\\.totalStandardPremium').val(clause.benchmarkPremium);
                        $('#FDBX\\.dutys\\.' + unitCode + '\\.totalAgreePremium').val(clause.premium);

                        totalStandardPremium += clause.benchmarkPremium;
                        totalActualPremium += clause.premium;
                    }
                    else if ($.inArray(clause.kindCode, ['11', '13', '21', '62', 'SW']) > -1) {
                        // 附加险
                        var unitCode = 231;
                        if (clause.kindCode == '11') {
                            unitCode = 231;
                        }
                        else if (clause.kindCode == '13') {
                            unitCode = 310
                        }
                        else if (clause.kindCode == '21') {
                            unitCode = 210
                        }
                        else if (clause.kindCode == '62') {
                            unitCode = 240
                        }
                        else if (clause.kindCode == 'SW') {
                            unitCode = 290
                        }
                        $('#FDBX\\.dutys\\.' + unitCode + '\\.preminumRate').val(parseFloat(clause.discount) / 100.0);
                        $('#FDBX\\.dutys\\.' + unitCode + '\\.totalStandardPremium').val(clause.benchmarkPremium);
                        $('#FDBX\\.dutys\\.' + unitCode + '\\.totalAgreePremium').val(clause.premium);

                        totalStandardPremium += clause.benchmarkPremium;
                        totalActualPremium += clause.premium;
                    }
                    else if ($.inArray(clause.kindCode, ['601', '602', '603', '604', '605', '606', '607', '608', '609', '610', '61']) > -1) {
                        // 不计免赔
                        var unitCode = 911;
                        if (clause.kindCode == '601') {
                            unitCode = 911;
                        }
                        else if (clause.kindCode == '602') {
                            unitCode = 912
                        }
                        else if (clause.kindCode == '603') {
                            unitCode = 921
                        }
                        else if (clause.kindCode == '604') {
                            unitCode = 928
                        }
                        else if (clause.kindCode == '605') {
                            unitCode = 928
                        }
                        else if (clause.kindCode == '606') {
                            unitCode = 929
                        }
                        else if (clause.kindCode == '607') {
                            unitCode = 970
                        }
                        else if (clause.kindCode == '608') {
                            unitCode = 971//TODO;unknow?
                        }
                        else if (clause.kindCode == '609') {
                            unitCode = 971
                        }
                        else if (clause.kindCode == '610') {
                            unitCode = 972
                        }
                        else if (clause.kindCode == '61') {
                            unitCode = 920
                        }
                        $('#FDBX\\.dutys\\.' + unitCode + '\\.totalActualPremium').val(clause.premium);

                        totalStandardPremium += clause.premium;
                        totalActualPremium += clause.premium;
                    }
                });

                $('#FDBX\\.dutys\\.c01\\.totalStandardPremium').val(totalStandardPremium.toFixed(2));
                $('#FDBX\\.dutys\\.c01\\.totalActualPremium').val(totalActualPremium.toFixed(2));
                $('#FDBX\\.dutys\\.c01\\.fee').val((totalActualPremium * 0.15).toFixed(2));
                totalPremium += totalActualPremium;

                $('#FDBX\\.dutys\\.totalAgreePremium').val(totalPremium.toFixed(2));

                $('#FDBX\\.quotationNo').val(mainDto.quotationNo);

                $('#FDBX\\.acceptInfo').attr('class', 'table table-condensed table-hover');

                showMsg('ok', '报价成功,报价单号:' + mainDto.quotationNo);
            }
            else {
                showMsg('error', result.errorMessage);
            }
        }
        else {
            showMsg('error', data.message);
        }
    });
    xhr.fail(function (jqXHR, textStatus, errorThrown) {
        $(document).dialog('closeCurrent');
        console.log(textStatus);
        showMsg('error', textStatus);
    });
}

function quote_fdbx() {
    var insuraceCompany = 'FDBX';
    var areaCode = $('#offer-areaCode').val();
    var quotationNo = $('#FDBX\\.quotationNo').val();

    if (!quotationNo || quotationNo.length <= 0) {
        showMsg('error', '保单还未生成,请先报价!');
        return;
    }

    var acceptName = $('#FDBX\\.acceptName').val();
    var acceptTelephone = $('#FDBX\\.acceptTelephone').val();
    var acceptProvince = $('#FDBX\\.acceptProvince').select2('data')[0].text;
    var acceptCity = $('#FDBX\\.acceptCity').select2('data')[0].text;
    var acceptTown = $('#FDBX\\.acceptTown').select2('data')[0].text;
    var acceptAddress = $('#FDBX\\.acceptAddress').val();
    var deliveryType = $('#FDBX\\.deliveryType').val();
    var appointmentTime = $('#FDBX\\.appointmentTime').val();

    if (!acceptName) {
        showMsg('error', '请填写配送人姓名!');
        return;
    }

    if (!acceptTelephone) {
        showMsg('error', '请填写配送人联系方式!');
        return;
    }

    if (!acceptProvince || !acceptCity || !acceptTown || !acceptAddress) {
        showMsg('error', '请填写配送地址!');
        return;
    }

    if (!deliveryType || !appointmentTime) {
        showMsg('error', '请填写配送方式和预约时间!');
        return;
    }

    // 转投保
    var quoteToProposal_params = {
        businessNo: quotationNo,
        comType: insuraceCompany,
        delivery: {
            acceptName: acceptName,
            acceptTelephone: acceptTelephone,
            acceptProvince: acceptProvince,
            acceptCity: acceptCity,
            acceptTown: acceptTown,
            acceptAddress: acceptAddress,
            deliveryType: deliveryType,
            appointmentTime: appointmentTime
        }
    };

    console.log(quoteToProposal_params);

    showWaitDialog('加载请求', '请求中,请等待...');

    var xhr = $.ajax({
        type: 'POST',
        url: '/offer/offer/quoteToProposal',
        data: JSON.stringify(quoteToProposal_params),
        contentType: 'application/json'
    });

    xhr.done(function (data) {
        $(document).dialog('closeCurrent');
        console.log(data);

        if (data.result == 0) {
            console.log(data.data);
        }
        else {
            showMsg('error', data.message);
        }
    });
    xhr.fail(function (jqXHR, textStatus, errorThrown) {
        $(document).dialog('closeCurrent');
        console.log(textStatus);
        showMsg('error', textStatus);
    });
}

function pay_fdbx() {
    // var insuraceCompany = 'FDBX';
    // var areaCode = $('#offer-areaCode').val();
    var quotationNo = $('#FDBX\\.quotationNo').val();

    if (!quotationNo || quotationNo.length <= 0) {
        showMsg('error', '保单还未生成,请先报价!');
        return;
    }

    // 支付
    var payButton_params = {
        quotationNo: quotationNo
    };

    showWaitDialog('加载请求', '请求中,请等待...');

    var xhr = $.ajax({
        type: 'POST',
        url: '/offer/offer/pay',
        data: JSON.stringify(payButton_params),
        contentType: 'application/json'
    });

    xhr.done(function (data) {
        $(document).dialog('closeCurrent');
        console.log(data);

        if (data.result == 0) {
            showMsg('ok', data.data, {autoClose: false});
        }
        else {
            showMsg('error', data.message);
        }
    });
    xhr.fail(function (jqXHR, textStatus, errorThrown) {
        $(document).dialog('closeCurrent');
        console.log(textStatus);
        showMsg('error', textStatus);
    });
}

$('#initkind\\.fdbx').trigger('onloaded');
