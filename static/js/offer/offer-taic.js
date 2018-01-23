/**
 * Created by tyleryang on 16/3/23.
 */

function taic_clear_form() {
    $.each(['200', '600', '500', '701', '702', '231', '310', '210', '240', '252', '290'], function (idx, kindCode) {
        // 主险,附加险
        $('#TAIC\\.dutys\\.' + kindCode + '\\.preminumRate').val('');
        $('#TAIC\\.dutys\\.' + kindCode + '\\.totalStandardPremium').val('');
        $('#TAIC\\.dutys\\.' + kindCode + '\\.totalAgreePremium').val('');
    });
    $.each(['911', '912', '920', '921', '928', '929', '970', '971', '972'], function (idx, kindCode) {
        // 不计免赔
        $('#TAIC\\.dutys\\.' + kindCode + '\\.totalActualPremium').val('');
    });
}

function taic_enable_form() {
    $.each(['200', '600', '500', '701', '702'], function (idx, kindCode) {
        // 主险
        $('#TAIC\\.dutys\\.' + kindCode + '\\.isChecked').iCheck('enable');
    });
}

function taic_disable_form() {
    $.each(['200', '600', '500', '701', '702'], function (idx, kindCode) {
        // 主险
        $('#TAIC\\.dutys\\.' + kindCode + '\\.isChecked').iCheck('uncheck');
        $('#TAIC\\.dutys\\.' + kindCode + '\\.isChecked').iCheck('disable');
    });
}

var taic_dutys_200_isChecked = $('#TAIC\\.dutys\\.200\\.isChecked');
taic_dutys_200_isChecked.on('ifChecked', function (e) {
    $('#TAIC\\.dutys\\.231').attr('class', '');
    $('#TAIC\\.dutys\\.310').attr('class', '');
    $('#TAIC\\.dutys\\.210').attr('class', '');
    $('#TAIC\\.dutys\\.240').attr('class', '');
    $('#TAIC\\.dutys\\.252').attr('class', '');
    $('#TAIC\\.dutys\\.290').attr('class', '');

    $('#TAIC\\.dutys\\.200\\.insuredAmount').attr('readonly', false);

    $('#TAIC\\.dutys\\.911\\.isChecked').iCheck('enable');
    $('#TAIC\\.dutys\\.911\\.isChecked').iCheck('check');
});
taic_dutys_200_isChecked.on('ifUnchecked', function (e) {
    $('#TAIC\\.dutys\\.231').attr('class', 'hidden');
    $('#TAIC\\.dutys\\.310').attr('class', 'hidden');
    $('#TAIC\\.dutys\\.210').attr('class', 'hidden');
    $('#TAIC\\.dutys\\.240').attr('class', 'hidden');
    $('#TAIC\\.dutys\\.252').attr('class', 'hidden');
    $('#TAIC\\.dutys\\.290').attr('class', 'hidden');

    $('#TAIC\\.dutys\\.231\\.isChecked').iCheck('uncheck');
    $('#TAIC\\.dutys\\.310\\.isChecked').iCheck('uncheck');
    $('#TAIC\\.dutys\\.210\\.isChecked').iCheck('uncheck');
    $('#TAIC\\.dutys\\.240\\.isChecked').iCheck('uncheck');
    $('#TAIC\\.dutys\\.252\\.isChecked').iCheck('uncheck');
    $('#TAIC\\.dutys\\.290\\.isChecked').iCheck('uncheck');

    $('#TAIC\\.dutys\\.200\\.insuredAmount').attr('readonly', true);

    $('#TAIC\\.dutys\\.911\\.isChecked').iCheck('uncheck');
    $('#TAIC\\.dutys\\.911\\.isChecked').iCheck('disable');
});

var taic_dutys_600_isChecked = $('#TAIC\\.dutys\\.600\\.isChecked');
taic_dutys_600_isChecked.on('ifChecked', function (e) {
    $('#TAIC\\.dutys\\.600\\.insuredAmount').attr('disabled', false);
    $('#TAIC\\.dutys\\.600\\.insuredAmount').selectpicker('refresh');

    $('#TAIC\\.dutys\\.912\\.isChecked').iCheck('enable');
    $('#TAIC\\.dutys\\.912\\.isChecked').iCheck('check');
});
taic_dutys_600_isChecked.on('ifUnchecked', function (e) {
    $('#TAIC\\.dutys\\.600\\.insuredAmount').attr('disabled', true);
    $('#TAIC\\.dutys\\.600\\.insuredAmount').selectpicker('refresh');

    $('#TAIC\\.dutys\\.912\\.isChecked').iCheck('uncheck');
    $('#TAIC\\.dutys\\.912\\.isChecked').iCheck('disable');
});

var taic_dutys_500_isChecked = $('#TAIC\\.dutys\\.500\\.isChecked');
taic_dutys_500_isChecked.on('ifChecked', function (e) {
    $('#TAIC\\.dutys\\.500\\.insuredAmount').attr('readonly', false);

    $('#TAIC\\.dutys\\.921\\.isChecked').iCheck('enable');
    $('#TAIC\\.dutys\\.921\\.isChecked').iCheck('check');
});
taic_dutys_500_isChecked.on('ifUnchecked', function (e) {
    $('#TAIC\\.dutys\\.500\\.insuredAmount').attr('readonly', true);

    $('#TAIC\\.dutys\\.921\\.isChecked').iCheck('uncheck');
    $('#TAIC\\.dutys\\.921\\.isChecked').iCheck('disable');
});

var taic_dutys_701_isChecked = $('#TAIC\\.dutys\\.701\\.isChecked');
var taic_dutys_702_isChecked = $('#TAIC\\.dutys\\.702\\.isChecked');
taic_dutys_701_isChecked.on('ifChecked', function (e) {
    $('#TAIC\\.dutys\\.701\\.insuredAmount').attr('readonly', false);

    $('#TAIC\\.dutys\\.928\\.isChecked').iCheck('enable');
    $('#TAIC\\.dutys\\.928\\.isChecked').iCheck('check');
});
taic_dutys_701_isChecked.on('ifUnchecked', function (e) {
    $('#TAIC\\.dutys\\.701\\.insuredAmount').attr('readonly', true);

    if ($('#TAIC\\.fgInd').val() == '1' || !taic_dutys_702_isChecked.prop('checked')) {
        $('#TAIC\\.dutys\\.928\\.isChecked').iCheck('uncheck');
        $('#TAIC\\.dutys\\.928\\.isChecked').iCheck('disable');
    }
});
taic_dutys_702_isChecked.on('ifChecked', function (e) {
    $('#TAIC\\.dutys\\.702\\.insuredAmount').attr('readonly', false);

    if ($('#TAIC\\.fgInd').val() == '0') {
        $('#TAIC\\.dutys\\.928\\.isChecked').iCheck('enable');
        $('#TAIC\\.dutys\\.928\\.isChecked').iCheck('check');
    }

    $('#TAIC\\.dutys\\.929\\.isChecked').iCheck('enable');
    $('#TAIC\\.dutys\\.929\\.isChecked').iCheck('check');
});
taic_dutys_702_isChecked.on('ifUnchecked', function (e) {
    $('#TAIC\\.dutys\\.702\\.insuredAmount').attr('readonly', true);

    if ($('#TAIC\\.fgInd').val() == '1' || !taic_dutys_701_isChecked.prop('checked')) {
        $('#TAIC\\.dutys\\.928\\.isChecked').iCheck('uncheck');
        $('#TAIC\\.dutys\\.928\\.isChecked').iCheck('disable');
    }

    $('#TAIC\\.dutys\\.929\\.isChecked').iCheck('disable');
    $('#TAIC\\.dutys\\.929\\.isChecked').iCheck('uncheck');
});

var taic_dutys_231_isChecked = $('#TAIC\\.dutys\\.231\\.isChecked');
taic_dutys_231_isChecked.on('ifChecked', function (e) {
    $('#TAIC\\.dutys\\.231\\.seats0').iCheck('enable');
    $('#TAIC\\.dutys\\.231\\.seats1').iCheck('enable');
});
taic_dutys_231_isChecked.on('ifUnchecked', function (e) {
    $('#TAIC\\.dutys\\.231\\.seats0').iCheck('disable');
    $('#TAIC\\.dutys\\.231\\.seats1').iCheck('disable');
});

var taic_dutys_310_isChecked = $('#TAIC\\.dutys\\.310\\.isChecked');
var taic_dutys_210_isChecked = $('#TAIC\\.dutys\\.210\\.isChecked');
var taic_dutys_290_isChecked = $('#TAIC\\.dutys\\.290\\.isChecked');
taic_dutys_310_isChecked.on('ifChecked', function (e) {
    $('#TAIC\\.dutys\\.310\\.insuredAmount').attr('readonly', false);

    $('#TAIC\\.dutys\\.920\\.isChecked').iCheck('enable');
    $('#TAIC\\.dutys\\.920\\.isChecked').iCheck('check');

    $('#TAIC\\.dutys\\.970\\.isChecked').iCheck('enable');
    $('#TAIC\\.dutys\\.970\\.isChecked').iCheck('check');
});
taic_dutys_310_isChecked.on('ifUnchecked', function (e) {
    $('#TAIC\\.dutys\\.310\\.insuredAmount').attr('readonly', true);

    if ($('#TAIC\\.fgInd').val() == '1' ||
        (!taic_dutys_210_isChecked.prop('checked') && !taic_dutys_290_isChecked.prop('checked'))) {
        $('#TAIC\\.dutys\\.920\\.isChecked').iCheck('uncheck');
        $('#TAIC\\.dutys\\.920\\.isChecked').iCheck('disable');
    }

    $('#TAIC\\.dutys\\.970\\.isChecked').iCheck('uncheck');
    $('#TAIC\\.dutys\\.970\\.isChecked').iCheck('disable');
});
taic_dutys_210_isChecked.on('ifChecked', function (e) {
    $('#TAIC\\.dutys\\.210\\.insuredAmount').attr('disabled', false);
    $('#TAIC\\.dutys\\.210\\.insuredAmount').selectpicker('refresh');

    $('#TAIC\\.dutys\\.920\\.isChecked').iCheck('enable');
    $('#TAIC\\.dutys\\.920\\.isChecked').iCheck('check');

    $('#TAIC\\.dutys\\.971\\.isChecked').iCheck('enable');
    $('#TAIC\\.dutys\\.971\\.isChecked').iCheck('check');
});
taic_dutys_210_isChecked.on('ifUnchecked', function (e) {
    $('#TAIC\\.dutys\\.210\\.insuredAmount').attr('disabled', true);
    $('#TAIC\\.dutys\\.210\\.insuredAmount').selectpicker('refresh');

    if ($('#TAIC\\.fgInd').val() == '1' ||
        (!taic_dutys_210_isChecked.prop('checked') && !taic_dutys_290_isChecked.prop('checked'))) {
        $('#TAIC\\.dutys\\.920\\.isChecked').iCheck('uncheck');
        $('#TAIC\\.dutys\\.920\\.isChecked').iCheck('disable');
    }

    $('#TAIC\\.dutys\\.971\\.isChecked').iCheck('uncheck');
    $('#TAIC\\.dutys\\.971\\.isChecked').iCheck('disable');
});
taic_dutys_290_isChecked.on('ifChecked', function (e) {
    $('#TAIC\\.dutys\\.290\\.seats').attr('disabled', false);
    $('#TAIC\\.dutys\\.290\\.seats').selectpicker('refresh');

    $('#TAIC\\.dutys\\.920\\.isChecked').iCheck('enable');
    $('#TAIC\\.dutys\\.920\\.isChecked').iCheck('check');

    $('#TAIC\\.dutys\\.972\\.isChecked').iCheck('enable');
    $('#TAIC\\.dutys\\.972\\.isChecked').iCheck('check');
});
taic_dutys_290_isChecked.on('ifUnchecked', function (e) {
    $('#TAIC\\.dutys\\.290\\.seats').attr('disabled', true);
    $('#TAIC\\.dutys\\.290\\.seats').selectpicker('refresh');

    if ($('#TAIC\\.fgInd').val() == '1' ||
        (!taic_dutys_310_isChecked.prop('checked') && !taic_dutys_210_isChecked.prop('checked'))) {
        $('#TAIC\\.dutys\\.920\\.isChecked').iCheck('uncheck');
        $('#TAIC\\.dutys\\.920\\.isChecked').iCheck('disable');
    }

    $('#TAIC\\.dutys\\.972\\.isChecked').iCheck('uncheck');
    $('#TAIC\\.dutys\\.972\\.isChecked').iCheck('disable');
});

// $('#TAIC\\.dutys\\.200\\.insuredAmount').on('input', function () {
//     var purchasePrice = parseFloat($('#TAIC\\.veh\\.purchasePrice').text());
//     var inputPurchasePrice = parseFloat($('#TAIC\\.dutys\\.200\\.insuredAmount').val());
//
//     var stealRobInsuredAmount = parseFloat($('#TAIC\\.dutys\\.500\\.insuredAmountDefaultValue').text());
//
//     if (isNaN(purchasePrice) || isNaN(inputPurchasePrice) || inputPurchasePrice > purchasePrice || isNaN(stealRobInsuredAmount)) {
//         return;
//     }
//
//     $('#TAIC\\.dutys\\.500\\.insuredAmount').val((stealRobInsuredAmount * (inputPurchasePrice / purchasePrice)).toFixed(2));
// });

var acceptProvince = $('#TAIC\\.acceptProvince');
var acceptCity = $('#TAIC\\.acceptCity');
var acceptTown = $('#TAIC\\.acceptTown');

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

function query_taic(main, relateShips, vechile, risks, coverages, carShip, delivery, summary) {
    // 显示报价结果
    var totalPremium = 0;
    var totalStandardPremium = 0;
    var totalActualPremium = 0;

    // 险种信息
    $.each(risks || [], function (idx, risk) {
        if (risk.riskCode == '0101') {
            // 商业险
            $('#TAIC\\.dutys\\.totalAgreePremium').val(risk.sumGrossPremium);
        }
        else {
            // 交强险
            $('#c51\\.totalActualPremium').val(risk.sumGrossPremium);

            $('#TAIC\\.dutys\\.c51\\.totalActualPremium').val(risk.sumGrossPremium);
            totalPremium += parseFloat(risk.sumGrossPremium);
        }
    });

    // 优惠信息

    // 车船税信息
    $('#vehicleTaxInfo\\.totalTaxMoney').val(carShip.taxDue);

    $('#TAIC\\.dutys\\.totalTaxMoney').val(carShip.taxDue);
    totalPremium += parseFloat(carShip.taxDue);

    // 特约信息

    // 险别信息
    $.each(coverages || [], function (idx, clause) {
        if ($.inArray(clause.coverageCode, ['63', '68', '74', '73', '89']) > -1) {
            // 主险
            var unitCode = 200;
            if (clause.coverageCode == '63') {
                unitCode = 200;
                $('#TAIC\\.veh\\.purchasePrice').text(vechile.replacementValue);
                $('#TAIC\\.dutys\\.200\\.insuredAmount').val(clause.sumInsured);
            }
            else if (clause.coverageCode == '68') {
                unitCode = 600;
                // $('#TAIC\\.dutys\\.600\\.insuredAmount').selectpicker('val', parseInt(clause.sumInsured) / 10000);
                $('#TAIC\\.dutys\\.600\\.insuredAmount').val(parseInt(clause.sumInsured) / 10000);
                $('#TAIC\\.dutys\\.600\\.insuredAmount').selectpicker('refresh');
            }
            else if (clause.coverageCode == '74') {
                unitCode = 500;
                $('#TAIC\\.dutys\\.500\\.insuredAmountDefaultValue').text(vechile.actualValue);
                $('#TAIC\\.dutys\\.500\\.insuredAmount').val(clause.sumInsured);
            }
            else if (clause.coverageCode == '73') {
                unitCode = 701;
                $('#TAIC\\.dutys\\.701\\.insuredAmount').val(clause.sumInsured);
            }
            else if (clause.coverageCode == '89') {
                unitCode = 702;
                $('#TAIC\\.dutys\\.702\\.seats').val(clause.quantity);
                $('#TAIC\\.dutys\\.702\\.insuredAmount').val(parseInt(clause.sumInsured) / parseInt(clause.quantity));
            }
            $('#TAIC\\.dutys\\.' + unitCode).iCheck('check');
            $('#TAIC\\.dutys\\.' + unitCode + '\\.preminumRate').val(parseFloat(clause.discount) / 100.0);
            $('#TAIC\\.dutys\\.' + unitCode + '\\.totalStandardPremium').val(clause.benchmarkPremium);
            $('#TAIC\\.dutys\\.' + unitCode + '\\.totalAgreePremium').val(clause.grossPremium);

            totalStandardPremium += parseFloat(clause.benchmarkPremium);
            totalActualPremium += parseFloat(clause.grossPremium);
        }
        else if ($.inArray(clause.coverageCode, ['85', '34', '36']) > -1) {
            // 附加险
            var unitCode = 231;
            if (clause.coverageCode == '34') {
                unitCode = 231;
            }
            else if (clause.coverageCode == '36') {
                unitCode = 310;
                $('#TAIC\\.dutys\\.310\\.insuredAmountDefaultValue').text(vechile.actualValue);
                $('#TAIC\\.dutys\\.310\\.insuredAmount').val(clause.sumInsured);
            }
            else if (clause.coverageCode == '85') {
                unitCode = 252;
            }
            $('#TAIC\\.dutys\\.' + unitCode).iCheck('check');
            $('#TAIC\\.dutys\\.' + unitCode + '\\.preminumRate').val(parseFloat(clause.discount) / 100.0);
            $('#TAIC\\.dutys\\.' + unitCode + '\\.totalStandardPremium').val(clause.benchmarkPremium);
            $('#TAIC\\.dutys\\.' + unitCode + '\\.totalAgreePremium').val(clause.grossPremium);

            totalStandardPremium += parseFloat(clause.benchmarkPremium);
            totalActualPremium += parseFloat(clause.grossPremium);
        }
        else if ($.inArray(clause.coverageCode, ['A63', 'A68', 'A74', 'A73', 'A89', 'A7389', 'A36']) > -1) {
            // 不计免赔
            var unitCode = 911;
            if (clause.coverageCode == 'A63') {
                unitCode = 911;
            }
            else if (clause.coverageCode == 'A68') {
                unitCode = 912
            }
            else if (clause.coverageCode == 'A74') {
                unitCode = 921
            }
            else if (clause.coverageCode == 'A7389') {
                unitCode = 928
            }
            else if (clause.coverageCode == 'A73') {
                unitCode = 928
            }
            else if (clause.coverageCode == 'A89') {
                unitCode = 929
            }
            else if (clause.coverageCode == 'A36') {
                unitCode = 970
            }
            $('#TAIC\\.dutys\\.' + unitCode).iCheck('check');
            $('#TAIC\\.dutys\\.' + unitCode + '\\.totalActualPremium').val(clause.grossPremium);

            totalStandardPremium += parseFloat(clause.grossPremium);
            totalActualPremium += parseFloat(clause.grossPremium);
        }
    });

    $('#TAIC\\.dutys\\.c01\\.totalStandardPremium').val(totalStandardPremium.toFixed(2));
    $('#TAIC\\.dutys\\.c01\\.totalActualPremium').val(totalActualPremium.toFixed(2));
    $('#TAIC\\.dutys\\.c01\\.fee').val((totalActualPremium * 0.15).toFixed(2));
    totalPremium += totalActualPremium;

    $('#TAIC\\.dutys\\.totalAgreePremium').val(totalPremium.toFixed(2));

    $('#TAIC\\.quotationNo').val(main.quotationNo);

    $('#TAIC\\.acceptInfo').attr('class', 'table table-condensed table-hover');

    if (!isEmpty(delivery)) {
        //
    }
}

function vehicleQuery_taic() {
    var insuraceCompany = 'TAIC';
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
        $('#TAIC\\.veh\\.purchasePrice').text(car.purchasePrice);
        var fgInd = $('#TAIC\\.fgInd').val();
        if (fgInd == '0') {
            $('#TAIC\\.dutys\\.200\\.insuredAmount').val(car.purchasePrice);
        }
        else {
            $('#TAIC\\.dutys\\.200\\.insuredAmount').val(car.actualValue);
        }

        $('#TAIC\\.dutys\\.500\\.insuredAmountDefaultValue').text(car.actualValue);
        $('#TAIC\\.dutys\\.500\\.insuredAmount').val(car.actualValue);

        $('#TAIC\\.dutys\\.702\\.seats').val(car.seatCount - 1);

        $('#TAIC\\.dutys\\.310\\.insuredAmountDefaultValue').text(car.actualValue);
        $('#TAIC\\.dutys\\.310\\.insuredAmount').val(car.actualValue);

        $('#TAIC\\.quotationNo').data('car', car);
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
        startDate: $('#c01BeginTime').val()
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

function offer_taic(params) {
    // console.log(params);

    taic_clear_form();

    var insuraceCompany = 'TAIC';
    var areaCode = $('#offer-areaCode').val();

    var car = $('#TAIC\\.quotationNo').data('car');
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
    var fgInd = $('#TAIC\\.fgInd').val();

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

        if ($('#TAIC\\.dutys\\.200\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '63',
                kindName: '车辆损失险',
                kindInd: '1',
                sumInsured: $('#TAIC\\.dutys\\.200\\.insuredAmount').val(),
                checked: '1',
                deductibleInd: '0',
                deductibleCode: 'A63',
                unitCode: '200',
                unitInsured: '0'
            });
        }

        if ($('#TAIC\\.dutys\\.911\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: 'A63',
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

        if ($('#TAIC\\.dutys\\.600\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '68',
                kindName: '商业第三者责任险',
                kindInd: '1',
                sumInsured: parseInt($('#TAIC\\.dutys\\.600\\.insuredAmount').val(), 10) * 10000,
                checked: '1',
                deductibleInd: '0',
                deductibleCode: 'A68',
                unitCode: '600',
                unitInsured: '0'
            });
        }

        if ($('#TAIC\\.dutys\\.912\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: 'A68',
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

        if ($('#TAIC\\.dutys\\.500\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '74',
                kindName: '全车盗抢险',
                kindInd: '1',
                sumInsured: $('#TAIC\\.dutys\\.500\\.insuredAmount').val(),
                checked: '1',
                deductibleInd: '0',
                deductibleCode: 'A74',
                unitCode: '500',
                unitInsured: '0'
            });
        }

        if ($('#TAIC\\.dutys\\.921\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: 'A74',
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

        if ($('#TAIC\\.dutys\\.701\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '73',
                kindName: '车上人员责任险（司机）',
                kindInd: '1',
                sumInsured: $('#TAIC\\.dutys\\.701\\.insuredAmount').val(),
                checked: '1',
                deductibleInd: '0',
                deductibleCode: $('#TAIC\\.fgInd').val() == '0' ? 'A7389' : 'A73',
                unitCode: '701',
                unitInsured: $('#TAIC\\.dutys\\.701\\.insuredAmount').val(),
                quantity: 1
            });
        }

        if ($('#TAIC\\.dutys\\.928\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: $('#TAIC\\.fgInd').val() == '0' ? 'A7389' : 'A73',
                kindName: $('#TAIC\\.fgInd').val() == '0' ? '人员不计免' : '司机不计免赔',
                kindInd: '2',
                sumInsured: '0',
                checked: '1',
                deductibleInd: '1',
                deductibleCode: '',
                unitCode: '928',
                unitInsured: '0'
            });
        }

        if ($('#TAIC\\.dutys\\.702\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '89',
                kindName: '车上人员责任险（乘客）',
                kindInd: '1',
                sumInsured: parseInt($('#TAIC\\.dutys\\.702\\.insuredAmount').val(), 10) * parseInt($('#TAIC\\.dutys\\.702\\.seats').val(), 10),
                checked: '1',
                deductibleInd: '0',
                deductibleCode: $('#TAIC\\.fgInd').val() == '0' ? 'A7389' : 'A89',
                unitCode: '702',
                unitInsured: $('#TAIC\\.dutys\\.702\\.insuredAmount').val(),
                quantity: $('#TAIC\\.dutys\\.702\\.seats').val()
            });
        }

        if ($('#TAIC\\.dutys\\.929\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: 'A89',
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

        if ($('#TAIC\\.dutys\\.231\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '34',
                kindName: '玻璃单独破碎险',
                kindInd: '2',
                sumInsured: '0',
                checked: '1',
                deductibleInd: '0',
                deductibleCode: '',
                unitCode: '231',
                unitInsured: '0',
                valueType: $('#TAIC\\.dutys\\.231\\.seats0').prop('checked') ? '1' : '2'//国产1,进口2;
            });
        }

        if ($('#TAIC\\.dutys\\.310\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '36',
                kindName: '自燃损失险',
                kindInd: '2',
                sumInsured: $('#TAIC\\.dutys\\.310\\.insuredAmount').val(),
                checked: '1',
                deductibleInd: '0',
                deductibleCode: 'A36',
                unitCode: '310',
                unitInsured: '0'
            });
        }

        if ($('#TAIC\\.dutys\\.252\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo++,
                kindCode: '85',
                kindName: '指定专修厂特约险',
                kindInd: '2',
                sumInsured: '0',
                checked: '1',
                deductibleInd: '0',
                deductibleCode: '',
                unitCode: '252',
                unitInsured: '0',
                valueType: params['vehicleTarget']['vehicleFrameNo'].substring(0, 1).toUpperCase() == 'L' ? '1' : '2' // 国产1,进口2
            });
        }

        if ($('#TAIC\\.dutys\\.970\\.isChecked').prop('checked')) {
            quote_params['kindList'].push({
                serialNo: serialNo,
                kindCode: 'A36',
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
        url: '/offer/offer/quote?insuraceCompany=TAIC&areaCode=' + areaCode,
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
                        $('#TAIC\\.dutys\\.totalAgreePremium').val(risk.sumGrossPremium);
                    }
                    else {
                        // 交强险
                        $('#c51\\.totalActualPremium').val(risk.sumGrossPremium);

                        $('#TAIC\\.dutys\\.c51\\.totalActualPremium').val(risk.sumGrossPremium);
                        totalPremium += risk.sumGrossPremium;
                    }
                });

                // 优惠信息
                var profitList = result.profitList;

                // 车船税信息
                var carShipTaxDto = result.carShipTaxDto;
                $('#vehicleTaxInfo\\.totalTaxMoney').val(carShipTaxDto.taxDueActual);

                $('#TAIC\\.dutys\\.totalTaxMoney').val(carShipTaxDto.taxDueActual);
                totalPremium += carShipTaxDto.taxDueActual;

                // 特约信息
                var specialList = result.specialList;

                // 险别信息
                var clauseList = result.clauseList;
                $.each(clauseList || [], function (idx, clause) {
                    if ($.inArray(clause.kindCode, ['63', '68', '74', '73', '89']) > -1) {
                        // 主险
                        var unitCode = 200;
                        if (clause.kindCode == '63') {
                            unitCode = 200;
                        }
                        else if (clause.kindCode == '68') {
                            unitCode = 600
                        }
                        else if (clause.kindCode == '74') {
                            unitCode = 500
                        }
                        else if (clause.kindCode == '73') {
                            unitCode = 701
                        }
                        else if (clause.kindCode == '89') {
                            unitCode = 702
                        }
                        $('#TAIC\\.dutys\\.' + unitCode + '\\.preminumRate').val(parseFloat(clause.discount) / 100.0);
                        $('#TAIC\\.dutys\\.' + unitCode + '\\.totalStandardPremium').val(clause.benchmarkPremium);
                        $('#TAIC\\.dutys\\.' + unitCode + '\\.totalAgreePremium').val(clause.premium);

                        totalStandardPremium += clause.benchmarkPremium;
                        totalActualPremium += clause.premium;
                    }
                    else if ($.inArray(clause.kindCode, ['85', '34', '36']) > -1) {
                        // 附加险
                        var unitCode = 231;
                        if (clause.kindCode == '34') {
                            unitCode = 231;
                        }
                        else if (clause.kindCode == '36') {
                            unitCode = 310
                        }
                        else if (clause.kindCode == '85') {
                            unitCode = 252
                        }
                        $('#TAIC\\.dutys\\.' + unitCode + '\\.preminumRate').val(parseFloat(clause.discount) / 100.0);
                        $('#TAIC\\.dutys\\.' + unitCode + '\\.totalStandardPremium').val(clause.benchmarkPremium);
                        $('#TAIC\\.dutys\\.' + unitCode + '\\.totalAgreePremium').val(clause.premium);

                        totalStandardPremium += clause.benchmarkPremium;
                        totalActualPremium += clause.premium;
                    }
                    else if ($.inArray(clause.kindCode, ['A63', 'A68', 'A74', 'A73', 'A89', 'A7389', 'A36']) > -1) {
                        // 不计免赔
                        var unitCode = 911;
                        if (clause.kindCode == 'A63') {
                            unitCode = 911;
                        }
                        else if (clause.kindCode == 'A68') {
                            unitCode = 912
                        }
                        else if (clause.kindCode == 'A74') {
                            unitCode = 921
                        }
                        else if (clause.kindCode == 'A7389') {
                            unitCode = 928
                        }
                        else if (clause.kindCode == 'A73') {
                            unitCode = 928
                        }
                        else if (clause.kindCode == 'A89') {
                            unitCode = 929
                        }
                        else if (clause.kindCode == 'A36') {
                            unitCode = 970
                        }
                        $('#TAIC\\.dutys\\.' + unitCode + '\\.totalActualPremium').val(clause.premium);

                        totalStandardPremium += clause.premium;
                        totalActualPremium += clause.premium;
                    }
                });

                $('#TAIC\\.dutys\\.c01\\.totalStandardPremium').val(totalStandardPremium.toFixed(2));
                $('#TAIC\\.dutys\\.c01\\.totalActualPremium').val(totalActualPremium.toFixed(2));
                $('#TAIC\\.dutys\\.c01\\.fee').val((totalActualPremium * 0.15).toFixed(2));
                totalPremium += totalActualPremium;

                $('#TAIC\\.dutys\\.totalAgreePremium').val(totalPremium.toFixed(2));

                $('#TAIC\\.quotationNo').val(mainDto.quotationNo);

                $('#TAIC\\.acceptInfo').attr('class', 'table table-condensed table-hover');

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

function quote_taic() {
    var insuraceCompany = 'TAIC';
    var areaCode = $('#offer-areaCode').val();
    var quotationNo = $('#TAIC\\.quotationNo').val();

    if (!quotationNo || quotationNo.length <= 0) {
        showMsg('error', '保单还未生成,请先报价!');
        return;
    }

    var acceptName = $('#TAIC\\.acceptName').val();
    var acceptTelephone = $('#TAIC\\.acceptTelephone').val();
    var acceptProvince = $('#TAIC\\.acceptProvince').val();
    var acceptCity = $('#TAIC\\.acceptCity').val();
    var acceptTown = $('#TAIC\\.acceptTown').val();
    var acceptAddress = $('#TAIC\\.acceptAddress').val();
    var deliveryType = $('#TAIC\\.deliveryType').val();
    var appointmentTime = $('#TAIC\\.appointmentTime').val();

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
            acceptProvince: '3' + acceptProvince,
            acceptCity: '3' + acceptCity,
            acceptTown: '3' + acceptTown,
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

function pay_taic() {
    // var insuraceCompany = 'TAIC';
    // var areaCode = $('#offer-areaCode').val();
    var quotationNo = $('#TAIC\\.quotationNo').val();

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

$('#initkind\\.taic').trigger('onloaded');
