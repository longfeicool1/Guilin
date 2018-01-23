/**
 * Created by tyleryang on 16/3/23.
 */


function pingan_enable_form() {
    $.each(['01', '02', '03', '04', '05'], function (idx, kindCode) {
        // 主险
        $('#dutys\\.' + kindCode + '\\.isChecked').iCheck('enable');
    });
}

function pingan_disable_form() {
    $.each(['01', '02', '03', '04', '05'], function (idx, kindCode) {
        // 主险
        $('#dutys\\.' + kindCode + '\\.isChecked').iCheck('uncheck');
        $('#dutys\\.' + kindCode + '\\.isChecked').iCheck('disable');
    });
}

function query_pingan(pingan) {
    var autoModelType = pingan.autoModelType;
    var vehicleTarget = pingan.voucher.vehicleTarget;
    var policy = pingan.policy;

    var totalPremium = 0;

    console.log(pingan.voucher);

    var modifyQuote = pingan.voucher.totalPremium;

    if (policy.hasOwnProperty('C01')) {
        var c01 = policy.C01;
        $('#c01BeginTime').val(getThisYear(c01.insuranceBeginTime));
        $('#c01EndTime').val(getNextYear(c01.insuranceEndTime));
        $('#isCheckC01').iCheck('check');

        $('#c01\\.commercialClaimRecord').val(pingan.voucher.c01ExtendInfo.commercialClaimRecord);
        // $('#c01\\.commercialClaimRecordName').text(pingan.voucher.c01ExtendInfo.commercialClaimRecordName);

        // console.log(pingan.voucher.c01ExtendInfo.commercialClaimRecordName);

        var c01dutyList = pingan.voucher.c01DutyList;
        var totalStandardPremium = 0;
        var totalActualPremium = 0;
        $.each(c01dutyList || [], function (index, obj) {
            totalStandardPremium += obj.totalStandardPremium;
            totalActualPremium += obj.totalActualPremium;
            // console.log(obj.dutyCode);
            if (obj.dutyCode == "01") {
                $('#dutys\\.01\\.isChecked').iCheck('check');
                // $('#dutys\\.01\\.insuredAmount').val(obj.insuredAmount);
                $('#dutys\\.01\\.preminumRate').val(obj.premiumRate);
                $('#dutys\\.01\\.totalStandardPremium').val(obj.totalStandardPremium);
                $('#dutys\\.01\\.totalAgreePremium').val(obj.totalAgreePremium);
                $('#dutys\\.01\\.totalActualPremium').val(obj.totalActualPremium);
            }
            else if (obj.dutyCode == "02") {
                $('#dutys\\.02\\.isChecked').iCheck('check');

                $('#dutys\\.02\\.insuredAmount').selectpicker('val', obj.insuredAmount / 10000);
                $('#dutys\\.02\\.preminumRate').val(obj.premiumRate);
                $('#dutys\\.02\\.totalStandardPremium').val(obj.totalStandardPremium);
                $('#dutys\\.02\\.totalAgreePremium').val(obj.totalAgreePremium);
                $('#dutys\\.02\\.totalActualPremium').val(obj.totalActualPremium);
            }
            else if (obj.dutyCode == "03") {
                $('#dutys\\.03\\.isChecked').iCheck('check');

                // $('#dutys\\.03\\.insuredAmountDefaultValue').val(obj.insuredAmountDefaultValue);
                // $('#dutys\\.03\\.insuredAmount').val(obj.insuredAmount);
                $('#dutys\\.03\\.preminumRate').val(obj.premiumRate);
                $('#dutys\\.03\\.totalStandardPremium').val(obj.totalStandardPremium);
                $('#dutys\\.03\\.totalAgreePremium').val(obj.totalAgreePremium);
                $('#dutys\\.03\\.totalActualPremium').val(obj.totalActualPremium);
            }
            else if (obj.dutyCode == "04") {
                $('#dutys\\.04\\.isChecked').iCheck('check');

                $('#dutys\\.04\\.insuredAmount').selectpicker('val', obj.insuredAmount);
                $('#dutys\\.04\\.preminumRate').val(obj.premiumRate);
                $('#dutys\\.04\\.totalStandardPremium').val(obj.totalStandardPremium);
                $('#dutys\\.04\\.totalAgreePremium').val(obj.totalAgreePremium);
                $('#dutys\\.04\\.totalActualPremium').val(obj.totalActualPremium);
            }
            else if (obj.dutyCode == "05") {
                $('#dutys\\.05\\.isChecked').iCheck('check');

                $('#dutys\\.05\\.seats').val(obj.seats);

                $('#dutys\\.05\\.insuredAmount').selectpicker('val', obj.insuredAmount);
                $('#dutys\\.05\\.preminumRate').val(obj.premiumRate);
                $('#dutys\\.05\\.totalStandardPremium').val(obj.totalStandardPremium);
                $('#dutys\\.05\\.totalAgreePremium').val(obj.totalAgreePremium);
                $('#dutys\\.05\\.totalActualPremium').val(obj.totalActualPremium);
            }
            else if (obj.dutyCode == "08") {
                $('#dutys\\.08\\.isChecked').iCheck('check');

                $('#dutys\\.08\\.seats' + obj.seats).iCheck('check');

                $('#dutys\\.08\\.insuredAmount').val(obj.insuredAmount);
                $('#dutys\\.08\\.preminumRate').val(obj.premiumRate);
                $('#dutys\\.08\\.totalStandardPremium').val(obj.totalStandardPremium);
                $('#dutys\\.08\\.totalAgreePremium').val(obj.totalAgreePremium);
                $('#dutys\\.08\\.totalActualPremium').val(obj.totalActualPremium);
            }
            else if (obj.dutyCode == "17") {
                $('#dutys\\.17\\.isChecked').iCheck('check');

                $('#dutys\\.17\\.insuredAmount').val(obj.insuredAmount);
                $('#dutys\\.17\\.preminumRate').val(obj.premiumRate);
                $('#dutys\\.17\\.totalStandardPremium').val(obj.totalStandardPremium);
                $('#dutys\\.17\\.totalAgreePremium').val(obj.totalAgreePremium);
                $('#dutys\\.17\\.totalActualPremium').val(obj.totalActualPremium);
            }
            else if (obj.dutyCode == "18") {
                $('#dutys\\.18\\.isChecked').iCheck('check');

                // $('#dutys\\.18\\.insuredAmount').val(obj.insuredAmount);
                $('#dutys\\.18\\.preminumRate').val(obj.premiumRate);
                $('#dutys\\.18\\.totalStandardPremium').val(obj.totalStandardPremium);
                $('#dutys\\.18\\.totalAgreePremium').val(obj.totalAgreePremium);
                $('#dutys\\.18\\.totalActualPremium').val(obj.totalActualPremium);
            }
            else if (obj.dutyCode == "59") {
                $('#dutys\\.59\\.isChecked').iCheck('check');

                $('#dutys\\.59\\.insuredAmount').val(obj.insuredAmount);
                $('#dutys\\.59\\.preminumRate').val(obj.premiumRate);
                $('#dutys\\.59\\.totalStandardPremium').val(obj.totalStandardPremium);
                $('#dutys\\.59\\.totalAgreePremium').val(obj.totalAgreePremium);
                $('#dutys\\.59\\.totalActualPremium').val(obj.totalActualPremium);
            }
            else if (obj.dutyCode == "41") {
                $('#dutys\\.41\\.isChecked').iCheck('check');

                $('#dutys\\.41\\.insuredAmount').val(obj.insuredAmount);
                $('#dutys\\.41\\.preminumRate').val(obj.premiumRate);
                $('#dutys\\.41\\.totalStandardPremium').val(obj.totalStandardPremium);
                $('#dutys\\.41\\.totalAgreePremium').val(obj.totalAgreePremium);
                $('#dutys\\.41\\.totalActualPremium').val(obj.totalActualPremium);
            }
            else if (obj.dutyCode == "27") {
                $('#dutys\\.27\\.isChecked').iCheck('check');

                $('#dutys\\.27\\.totalActualPremium').val(obj.totalActualPremium);
            }
            else if (obj.dutyCode == "28") {
                $('#dutys\\.28\\.isChecked').iCheck('check');

                $('#dutys\\.28\\.totalActualPremium').val(obj.totalActualPremium);
            }
            else if (obj.dutyCode == "48") {
                $('#dutys\\.48\\.isChecked').iCheck('check');

                $('#dutys\\.48\\.totalActualPremium').val(obj.totalActualPremium);
            }
            else if (obj.dutyCode == "49") {
                $('#dutys\\.49\\.isChecked').iCheck('check');

                $('#dutys\\.49\\.totalActualPremium').val(obj.totalActualPremium);
            }
            else if (obj.dutyCode == "50") {
                $('#dutys\\.50\\.isChecked').iCheck('check');

                $('#dutys\\.50\\.totalActualPremium').val(obj.totalActualPremium);
            }
        });

        if (modifyQuote) {
            $('#dutys\\.c01\\.totalStandardPremium').val(totalStandardPremium.toFixed(2));
            $('#dutys\\.c01\\.totalActualPremium').val(totalActualPremium.toFixed(2));
            $('#dutys\\.c01\\.discount').val((totalActualPremium / totalStandardPremium).toFixed(3));
            totalPremium += totalActualPremium;
        }
    } else {
        $('#isCheckC01').iCheck('uncheck');
        var thisYear = moment().add(1, 'day');
        var nextYear = moment().add(1, 'year');
        $('#c01BeginTime').val(thisYear.format('YYYY-MM-DD'));
        $('#c01EndTime').val(nextYear.format('YYYY-MM-DD'));
    }

    if (modifyQuote) {
        var c51dutyList = pingan.voucher.c51DutyList;
        var totalAgreePremium = 0;
        $.each(c51dutyList || [], function (index, obj) {
            totalAgreePremium += obj.totalActualPremium;
        });

        totalPremium += totalAgreePremium;

        var vehicleTaxInfo = pingan.voucher.vehicleTaxInfo;
        if (vehicleTaxInfo) {
            totalPremium += vehicleTaxInfo.totalTaxMoney;
        }

        $('#c51\\.totalActualPremium').val(totalAgreePremium);
        $('#vehicleTaxInfo\\.totalTaxMoney').val(vehicleTaxInfo.totalTaxMoney);

        $('#dutys\\.c51\\.totalActualPremium').val(totalAgreePremium);
        $('#dutys\\.totalTaxMoney').val(vehicleTaxInfo.totalTaxMoney);

        $('#dutys\\.totalAgreePremium').val(totalPremium.toFixed(2));
    }

    if (policy.hasOwnProperty('C51')) {
        var c51 = policy.C51;
        $('#c51BeginTime').val(getThisYear(c51.insuranceBeginTime));
        $('#c51EndTime').val(getNextYear(c51.insuranceEndTime));
        $('#isCheckC51').iCheck('check');
    }
    else {
        $('#isCheckC51').iCheck('uncheck');
        var thisYear = moment().add(1, 'day');
        var nextYear = moment().add(1, 'year');
        $('#c51BeginTime').val(thisYear.format('YYYY-MM-DD'));
        $('#c51EndTime').val(nextYear.format('YYYY-MM-DD'));
    }

    $('#veh\\.purchasePriceDefaultValue').text(autoModelType.purchasePrice);
    if (s == 'gd' || s == 'fs') {
        $('#veh\\.vehicleLossInsuredValue').val(autoModelType.purchasePrice);
    }
    else {
        $('#veh\\.vehicleLossInsuredValue').val(vehicleTarget.vehicleLossInsuredValue);
    }
    $('#veh\\.vehicleLossInsuredValue').trigger('input');
}

var dutys_01_isChecked = $('#dutys\\.01\\.isChecked');
dutys_01_isChecked.on('ifChecked', function (event) {
    $('#dutys\\.08').attr('class', '');
    $('#dutys\\.17').attr('class', '');
    $('#dutys\\.18').attr('class', '');
    $('#dutys\\.59').attr('class', '');
    $('#dutys\\.41').attr('class', '');

    $('#veh\\.vehicleLossInsuredValue').attr('readonly', false);
    $('#dutys\\.01\\.insuredAmount').attr('readonly', false);

    $('#dutys\\.27\\.isChecked').iCheck('enable');
    $('#dutys\\.27\\.isChecked').iCheck('check');
});
dutys_01_isChecked.on('ifUnchecked', function (event) {
    $('#dutys\\.08').attr('class', 'hidden');
    $('#dutys\\.17').attr('class', 'hidden');
    $('#dutys\\.18').attr('class', 'hidden');
    $('#dutys\\.59').attr('class', 'hidden');
    $('#dutys\\.41').attr('class', 'hidden');

    $('#dutys\\.08\\.isChecked').iCheck('uncheck');
    $('#dutys\\.17\\.isChecked').iCheck('uncheck');
    $('#dutys\\.18\\.isChecked').iCheck('uncheck');
    $('#dutys\\.59\\.isChecked').iCheck('uncheck');
    $('#dutys\\.41\\.isChecked').iCheck('uncheck');

    $('#veh\\.vehicleLossInsuredValue').attr('readonly', true);
    $('#dutys\\.01\\.insuredAmount').attr('readonly', true);

    $('#dutys\\.27\\.isChecked').iCheck('uncheck');
    $('#dutys\\.27\\.isChecked').iCheck('disable');
});

var dutys_02_isChecked = $('#dutys\\.02\\.isChecked');
dutys_02_isChecked.on('ifChecked', function (event) {
    $('#dutys\\.02\\.insuredAmount').attr('disabled', false);
    $('#dutys\\.02\\.insuredAmount').selectpicker('refresh');

    $('#dutys\\.28\\.isChecked').iCheck('enable');
    $('#dutys\\.28\\.isChecked').iCheck('check');
});
dutys_02_isChecked.on('ifUnchecked', function (event) {
    $('#dutys\\.02\\.insuredAmount').attr('disabled', true);
    $('#dutys\\.02\\.insuredAmount').selectpicker('refresh');

    $('#dutys\\.28\\.isChecked').iCheck('uncheck');
    $('#dutys\\.28\\.isChecked').iCheck('disable');
});

var dutys_03_isChecked = $('#dutys\\.03\\.isChecked');
dutys_03_isChecked.on('ifChecked', function (event) {
    $('#dutys\\.03\\.insuredAmount').attr('readonly', false);

    $('#dutys\\.48\\.isChecked').iCheck('enable');
    $('#dutys\\.48\\.isChecked').iCheck('check');
});
dutys_03_isChecked.on('ifUnchecked', function (event) {
    $('#dutys\\.03\\.insuredAmount').attr('readonly', true);

    $('#dutys\\.48\\.isChecked').iCheck('uncheck');
    $('#dutys\\.48\\.isChecked').iCheck('disable');
});

var dutys_04_isChecked = $('#dutys\\.04\\.isChecked');
var dutys_05_isChecked = $('#dutys\\.05\\.isChecked');
dutys_04_isChecked.on('ifChecked', function (event) {
    $('#dutys\\.04\\.insuredAmount').attr('disabled', false);
    $('#dutys\\.04\\.insuredAmount').selectpicker('refresh');

    $('#dutys\\.49\\.isChecked').iCheck('enable');
    $('#dutys\\.49\\.isChecked').iCheck('check');
});
dutys_04_isChecked.on('ifUnchecked', function (event) {
    $('#dutys\\.04\\.insuredAmount').attr('disabled', true);
    $('#dutys\\.04\\.insuredAmount').selectpicker('refresh');

    if (!dutys_05_isChecked.prop('checked')) {
        $('#dutys\\.49\\.isChecked').iCheck('uncheck');
        $('#dutys\\.49\\.isChecked').iCheck('disable');
    }
});
dutys_05_isChecked.on('ifChecked', function (event) {
    $('#dutys\\.05\\.insuredAmount').attr('disabled', false);
    $('#dutys\\.05\\.insuredAmount').selectpicker('refresh');

    $('#dutys\\.49\\.isChecked').iCheck('enable');
    $('#dutys\\.49\\.isChecked').iCheck('check');
});
dutys_05_isChecked.on('ifUnchecked', function (event) {
    $('#dutys\\.05\\.insuredAmount').attr('disabled', true);
    $('#dutys\\.05\\.insuredAmount').selectpicker('refresh');

    if (!dutys_04_isChecked.prop('checked')) {
        $('#dutys\\.49\\.isChecked').iCheck('uncheck');
        $('#dutys\\.49\\.isChecked').iCheck('disable');
    }
});

var dutys_08_isChecked = $('#dutys\\.08\\.isChecked');
dutys_08_isChecked.on('ifChecked', function (event) {
    $('#dutys\\.08\\.seats0').iCheck('enable');
    $('#dutys\\.08\\.seats1').iCheck('enable');
});
dutys_08_isChecked.on('ifUnchecked', function (event) {
    $('#dutys\\.08\\.seats0').iCheck('disable');
    $('#dutys\\.08\\.seats1').iCheck('disable');
});

var dutys_17_isChecked = $('#dutys\\.17\\.isChecked');
var dutys_18_isChecked = $('#dutys\\.18\\.isChecked');
var dutys_59_isChecked = $('#dutys\\.59\\.isChecked');
var dutys_41_isChecked = $('#dutys\\.41\\.isChecked');
dutys_17_isChecked.on('ifChecked', function (event) {
    $('#dutys\\.17\\.insuredAmount').attr('disabled', false);
    $('#dutys\\.17\\.insuredAmount').selectpicker('refresh');

    $('#dutys\\.50\\.isChecked').iCheck('enable');
    $('#dutys\\.50\\.isChecked').iCheck('check');
});
dutys_17_isChecked.on('ifUnchecked', function (event) {
    $('#dutys\\.17\\.insuredAmount').attr('disabled', true);
    $('#dutys\\.17\\.insuredAmount').selectpicker('refresh');

    if (!dutys_18_isChecked.prop('checked') && !dutys_59_isChecked.prop('checked') && !dutys_41_isChecked.prop('checked')) {
        $('#dutys\\.50\\.isChecked').iCheck('uncheck');
        $('#dutys\\.50\\.isChecked').iCheck('disable');
    }
});
dutys_18_isChecked.on('ifChecked', function (event) {
    $('#dutys\\.18\\.insuredAmount').attr('readonly', false);

    $('#dutys\\.50\\.isChecked').iCheck('enable');
    $('#dutys\\.50\\.isChecked').iCheck('check');
});
dutys_18_isChecked.on('ifUnchecked', function (event) {
    $('#dutys\\.18\\.insuredAmount').attr('readonly', true);

    if (!dutys_17_isChecked.prop('checked') && !dutys_59_isChecked.prop('checked') && !dutys_41_isChecked.prop('checked')) {
        $('#dutys\\.50\\.isChecked').iCheck('uncheck');
        $('#dutys\\.50\\.isChecked').iCheck('disable');
    }
});
dutys_59_isChecked.on('ifChecked', function (event) {
    $('#dutys\\.59\\.seats').attr('disabled', false);
    $('#dutys\\.59\\.seats').selectpicker('refresh');

    $('#dutys\\.50\\.isChecked').iCheck('enable');
    $('#dutys\\.50\\.isChecked').iCheck('check');
});
dutys_59_isChecked.on('ifUnchecked', function (event) {
    $('#dutys\\.59\\.seats').attr('disabled', true);
    $('#dutys\\.59\\.seats').selectpicker('refresh');

    if (!dutys_17_isChecked.prop('checked') && !dutys_18_isChecked.prop('checked') && !dutys_41_isChecked.prop('checked')) {
        $('#dutys\\.50\\.isChecked').iCheck('uncheck');
        $('#dutys\\.50\\.isChecked').iCheck('disable');
    }
});
dutys_41_isChecked.on('ifChecked', function (event) {
    $('#dutys\\.50\\.isChecked').iCheck('enable');
    $('#dutys\\.50\\.isChecked').iCheck('check');
});
dutys_41_isChecked.on('ifUnchecked', function (event) {
    if (!dutys_17_isChecked.prop('checked') && !dutys_18_isChecked.prop('checked') && !dutys_59_isChecked.prop('checked')) {
        $('#dutys\\.50\\.isChecked').iCheck('uncheck');
        $('#dutys\\.50\\.isChecked').iCheck('disable');
    }
});

$('#vehicleSeats').on('input', function () {
    var seats = parseInt($('#vehicleSeats').val(), 10) || 0;
    if (seats > 0) {
        $('#dutys\\.05\\.seats').val(seats - 1);
    }
});

$('#veh\\.vehicleLossInsuredValue').on('input', function () {
    var firstRegisterDate = $('#firstRegisterDate').val();
    var c01BeginTime = $('#c01BeginTime').val();

    if (!firstRegisterDate || !c01BeginTime) {
        return;
    }

    var s = $('#offer-area').val();
    var price = parseInt($('#veh\\.purchasePriceDefaultValue').text(), 10) || 0;
    var val = parseInt($('#veh\\.vehicleLossInsuredValue').val(), 10) || 0;
    if (s == 'gd' || s == 'fs') {
        //
    }
    else {
        var sub = val - price;
        if (sub < 0) {
            if (Math.abs(sub) > price * 0.3) {
                return;
            }
        }
        else {
            if (Math.abs(sub) > price * 0.5) {
                return;
            }
        }

        $('#dutys\\.01\\.insuredAmount').val(val);
    }

    var data = {
        vehicleLossInsuredValue: val,
        firstRegisterDate: $('#firstRegisterDate').val(),
        insuranceBeginTime: $('#c01BeginTime').val(),
        vehicleTypeCode: $('#vehicleTypeCode').val() == '0' ? 'A012' : 'A022',
        usageAttributeCode: $('#usageAttributeCode').val()
    };
    // $('#autoModelCode').empty();
    var xhr = $.post('/offer/offer/defaultCalculate?s=' + s, data);
    xhr.done(function (data) {
        if (data.result == 0) {
            var amount = data.data;

            if (s == 'gd' || s == 'fs') {
                $('#dutys\\.01\\.insuredAmount').val(amount);
            }

            $('#dutys\\.03\\.insuredAmountDefaultValue').text(amount);
            $('#dutys\\.03\\.insuredAmount').val(amount);

            $('#dutys\\.18\\.insuredAmountDefaultValue').text(amount);
            $('#dutys\\.18\\.insuredAmount').val(amount);
        }
        else {
            showMsg('error', data.message);
        }
    });
    xhr.fail(function (jqXHR, textStatus, errorThrown) {
        console.log(textStatus);
        // showMsg('error', textStatus);
    });
});

$('#smsButton').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var ret = $('#quotationNo').data('voucher');
    // console.log(ret);

    if (!ret) {
        showMsg('error', '请先获得报价!');
        return;
    }

    var linkmodeNum = $('#linkmodeNum').val();
    if (!linkmodeNum || linkmodeNum.length != 11) {
        showMsg('error', '请填写短信接收方号码!');
        return;
    }

    var voucherNo = ret.voucherNo;
    var c01 = ret.c01;
    var c51 = ret.c51;

    if (voucherNo != $('#quotationNo').val()) {
        showMsg('error', '报价结果不一致?请联系技术人员!');
        return;
    }

    var smsContent = '鼎然UBI互联网车险中心给您爱车';

    var vehicleLicenceCode = $('#vehicleLicenceCode').val();
    smsContent += vehicleLicenceCode;

    smsContent += '报价：【平安价格】';

    var totalPremium = 0;

    if (!isEmpty(c01)) {
        if (c01.resultFlag == 'true') {
            var totalStandardPremium = 0;
            var totalActualPremium = 0;

            var dutys_01_insuredAmount = 0;
            var dutys_02_insuredAmount = 0;
            var dutys_03_insuredAmount = 0;
            var dutys_04_insuredAmount = 0;
            var dutys_05_insuredAmount = 0;
            var dutys_05_seats = 0;
            var dutys_08_insuredAmount = 0;
            var dutys_17_insuredAmount = 0;
            var dutys_18_insuredAmount = 0;
            var dutys_59_insuredAmount = 0;
            var dutys_41_insuredAmount = 0;
            var dutys_27_insuredAmount = 0;
            var dutys_28_insuredAmount = 0;
            var dutys_48_insuredAmount = 0;
            var dutys_49_insuredAmount = 0;
            var dutys_50_insuredAmount = 0;

            var dutyList = c01.resultDTO.dutyList;
            $.each(dutyList || [], function (index, obj) {
                totalStandardPremium += obj.totalStandardPremium;
                totalActualPremium += obj.totalActualPremium;
                if (obj.dutyCode == "01") {
                    // 车损
                    dutys_01_insuredAmount = obj.insuredAmount;
                }
                else if (obj.dutyCode == "02") {
                    // 三者
                    dutys_02_insuredAmount = obj.insuredAmount;
                }
                else if (obj.dutyCode == "03") {
                    // 盗抢
                    dutys_03_insuredAmount = obj.insuredAmount;
                }
                else if (obj.dutyCode == "04") {
                    // 司机
                    dutys_04_insuredAmount = obj.insuredAmount;
                }
                else if (obj.dutyCode == "05") {
                    // 乘客
                    dutys_05_insuredAmount = obj.insuredAmount;
                    dutys_05_seats = obj.seats;
                }
                else if (obj.dutyCode == "08") {
                    // 玻璃单独破碎
                    dutys_08_insuredAmount = obj.seats == 0 ? 1 : 2;
                }
                else if (obj.dutyCode == "17") {
                    // 划痕
                    dutys_17_insuredAmount = obj.insuredAmount;
                }
                else if (obj.dutyCode == "18") {
                    // 自燃
                    dutys_18_insuredAmount = obj.insuredAmount;
                }
                else if (obj.dutyCode == "59") {
                    // 倒车镜
                    dutys_59_insuredAmount = 1;
                }
                else if (obj.dutyCode == "41") {
                    dutys_41_insuredAmount = 1;
                }
                else if (obj.dutyCode == "27") {
                    dutys_27_insuredAmount = 1;
                }
                else if (obj.dutyCode == "28") {
                    dutys_28_insuredAmount = 1;
                }
                else if (obj.dutyCode == "48") {
                    dutys_48_insuredAmount = 1;
                }
                else if (obj.dutyCode == "49") {
                    dutys_49_insuredAmount = 1;
                }
                else if (obj.dutyCode == "50") {
                    dutys_50_insuredAmount = 1;
                }
            });

            if (dutys_01_insuredAmount != 0) {
                smsContent += '车损险' + (dutys_01_insuredAmount / 10000).toFixed(2) + '万，';
            }

            if (dutys_02_insuredAmount != 0) {
                smsContent += '三者险' + dutys_02_insuredAmount / 10000 + '万，';
            }

            if (dutys_03_insuredAmount != 0) {
                smsContent += '盗抢险' + (dutys_03_insuredAmount / 10000).toFixed(2) + '万，';
            }

            if (dutys_04_insuredAmount != 0 ||
                dutys_05_insuredAmount != 0) {
                smsContent += '座位险';

                if (dutys_04_insuredAmount != 0) {
                    smsContent += '司机' + dutys_04_insuredAmount / 10000 + '万，';
                }

                if (dutys_05_insuredAmount) {
                    smsContent += '乘客' + dutys_05_insuredAmount / 10000 + '万，';
                }

                smsContent += '共' + (dutys_04_insuredAmount + (dutys_05_insuredAmount * dutys_05_seats)) / 10000 + '万，';
            }

            if (dutys_08_insuredAmount != 0) {
                if (dutys_08_insuredAmount == 1) {
                    smsContent += '国产'
                }
                else {
                    smsContent += '进口'
                }
                smsContent += '玻璃险，'
            }

            if ($('#dutys\\.41\\.isChecked').prop('checked')) {
                smsContent += '涉水险，'
            }

            smsContent += '各险种不计免赔。';

            smsContent += '商业险' + totalStandardPremium.toFixed(2) + '元优惠后' + totalActualPremium.toFixed(2) + '元，';

            totalPremium += totalActualPremium;
        }
    }

    if (!isEmpty(c51)) {
        if (c51.resultFlag == 'true') {
            // var errorCode = c51.resultDTO.errorCode;
            // var errorMessage = c51.resultDTO.errorMessage;
            var totalAgreePremium = c51.resultDTO.totalAgreePremium;
            // var dutyList = c51.resultDTO.dutyList;
            var vehicleTaxInfo = c51.vehicleTaxInfo;

            smsContent += '交强险' + totalAgreePremium + '元，车船税' + vehicleTaxInfo.totalTaxMoney + '元，';

            totalPremium += totalAgreePremium;
            totalPremium += vehicleTaxInfo.totalTaxMoney;
        }
    }

    smsContent += '车险保费、车船税合计' + totalPremium.toFixed(2) + '元。';
    smsContent += '联系人：' + account_name + '，工号' + account_ext + '，投保热线: 0755-84357001。期待您的来电！';

    $(this).dialog({
        url: '/phone/sendMsg?mobile=' + linkmodeNum + '&sms_type=1',
        title: '发送短信至' + linkmodeNum,
        id: 'dialog-sendMsg',
        type: 'POST',
        data: {content: smsContent},
        width: 600,
        height: 600,
    })
//  $(this).alertmsg('confirm', smsContent, {
//      displayMode: 'fade',
//      displayPosition: 'middlecenter',
//      okName: '确认',
//      cancelName: '取消',
//      title: '确认发送短信至' + linkmodeNum,
//      okCall: function () {
//          showWaitDialog('加载请求', '请求中,请等待...');
//
//          var params = {
//              mobile: linkmodeNum,
//              msg: smsContent
//          };
//
//          var xhr = $.ajax({
//              type: 'POST',
//              url: '/offer/offer/sendSMS',
//              data: params,
//              contentType: 'application/json'
//          });
//
//          xhr.done(function (data) {
//              $(document).dialog('closeCurrent');
//              if (data.result == 0) {
//                  console.log(data);
//              }
//              else {
//                  showMsg('error', data.message);
//              }
//          });
//          xhr.fail(function (jqXHR, textStatus, errorThrown) {
//              $(document).dialog('closeCurrent');
//              console.log(textStatus);
//              showMsg('error', textStatus);
//          });
//      }
//  });
});

function offer_pingan(params) {
    var s = $('#offer-area').val();
    if (s == 'gd' || s == 'fs') {
        params['vehicleTarget']['vehicleLossInsuredValue'] = parseInt($('#dutys\\.01\\.insuredAmount').val());
    }
    else {
        params['vehicleTarget']['vehicleLossInsuredValue'] = parseInt($('#veh\\.vehicleLossInsuredValue').val());
    }

    if ($('#isCheckC01').prop('checked')) {
        params['c01'] = {
            'beginTime': $('#c01BeginTime').val(),
            'endTime': $('#c01EndTime').val(),
            'commercialClaimRecord': $('#c01\\.commercialClaimRecord').val(),
            'dutyList': []
        };

        if ($('#dutys\\.08\\.isChecked').prop('checked')) {
            var val = 0;
            if ($('#dutys\\.08\\.seats0').prop('checked')) {
                val = 0;
            }
            else {
                val = 1;
            }
            params.c01.dutyList.push({
                dutyCode: '08',
                seats: val
            })
        }

        if ($('#dutys\\.05\\.isChecked').prop('checked')) {
            params.c01.dutyList.push({
                dutyCode: '05',
                insuredAmount: parseInt($('#dutys\\.05\\.insuredAmount').val()),
                seats: parseInt($('#dutys\\.05\\.seats').val())
            })
        }

        if ($('#dutys\\.04\\.isChecked').prop('checked')) {
            params.c01.dutyList.push({
                dutyCode: '04',
                insuredAmount: parseInt($('#dutys\\.04\\.insuredAmount').val())
            })
        }

        if ($('#dutys\\.03\\.isChecked').prop('checked')) {
            params.c01.dutyList.push({
                dutyCode: '03',
                insuredAmount: parseFloat($('#dutys\\.03\\.insuredAmount').val()),
                insuredAmountDefaultValue: parseFloat($('#dutys\\.03\\.insuredAmountDefaultValue').text())
            })
        }

        if ($('#dutys\\.02\\.isChecked').prop('checked')) {
            params.c01.dutyList.push({
                dutyCode: '02',
                insuredAmount: parseInt($('#dutys\\.02\\.insuredAmount').val())
            })
        }

        if ($('#dutys\\.01\\.isChecked').prop('checked')) {
            params.c01.dutyList.push({
                dutyCode: '01',
                insuredAmount: parseInt($('#dutys\\.01\\.insuredAmount').val()),
                riskConfirmType: '01'
            })
        }

        if ($('#dutys\\.59\\.isChecked').prop('checked')) {
            params.c01.dutyList.push({
                dutyCode: '59',
                seats: parseInt($('#dutys\\.59\\.seats').val())
            })
        }

        if ($('#dutys\\.50\\.isChecked').prop('checked')) {
            params.c01.dutyList.push({
                dutyCode: '50'
            })
        }

        if ($('#dutys\\.49\\.isChecked').prop('checked')) {
            params.c01.dutyList.push({
                dutyCode: '49'
            })
        }

        if ($('#dutys\\.48\\.isChecked').prop('checked')) {
            params.c01.dutyList.push({
                dutyCode: '48'
            })
        }

        if ($('#dutys\\.41\\.isChecked').prop('checked')) {
            params.c01.dutyList.push({
                dutyCode: '41'
            })
        }

        if ($('#dutys\\.28\\.isChecked').prop('checked')) {
            params.c01.dutyList.push({
                dutyCode: '28'
            })
        }

        if ($('#dutys\\.27\\.isChecked').prop('checked')) {
            params.c01.dutyList.push({
                dutyCode: '27'
            })
        }

        if ($('#dutys\\.18\\.isChecked').prop('checked')) {
            params.c01.dutyList.push({
                dutyCode: '18',
                insuredAmount: parseFloat($('#dutys\\.18\\.insuredAmount').val()),
                insuredAmountDefaultValue: parseFloat($('#dutys\\.18\\.insuredAmountDefaultValue').text())
            })
        }

        if ($('#dutys\\.17\\.isChecked').prop('checked')) {
            params.c01.dutyList.push({
                dutyCode: '17',
                insuredAmount: parseInt($('#dutys\\.17\\.insuredAmount').val())
            })
        }
    }

    if ($('#isCheckC51').prop('checked')) {
        params['c51'] = {
            'beginTime': $('#c51BeginTime').val(),
            'endTime': $('#c51EndTime').val(),
            'dutyList': [{
                "dutyCode": 47,
                "insuredAmount": "2000"
            }, {
                "dutyCode": 46,
                "insuredAmount": "10000"
            }, {
                "dutyCode": 45,
                "insuredAmount": "110000"
            }]
        }
    }

    showWaitDialog('加载请求', '请求中,请等待...');
    console.log(params);

    var xhr = $.ajax({
        type: 'POST',
        url: '/offer/offer/quote?s=' + s,
        // async: false,
        data: JSON.stringify(params),
        contentType: "application/json"
    });

    xhr.done(function (data) {
        $(document).dialog('closeCurrent');
        if (data.result == 0) {
            var ret = data.data;
            var voucherNo = ret.voucherNo;
            var claimRecordList = ret.claimRecordList;
            var c01 = ret.c01;
            var c51 = ret.c51;

            var totalPremium = 0;

            if (!isEmpty(claimRecordList)) {
                var html = '<tbody>'
                $.each(claimRecordList, function (index, claim) {
                    html += '<tr><td><label>保险公司:' + claim.insuranceCompanyName + ',出险时间:' + claim.createdDate + ',出险金额:' + claim.claimAmount + ',结案时间:' + claim.endCaseDate + '</label></td></tr>';
                });
                $('#claimRecordList').html(html);
            }
            else {
                $('#claimRecordList').html('');
            }

            if (!isEmpty(c01)) {
                if (c01.resultFlag == 'true') {
                    var errorCode = c01.resultDTO.errorCode;
                    var errorMessage = c01.resultDTO.errorMessage;
                    var totalAgreePremium = c01.resultDTO.totalAgreePremium;
                    var dutyList = c01.resultDTO.dutyList;

                    if (errorCode && errorMessage) {
                        showMsg('error', errorMessage);
                    }
                    else {
                        var totalStandardPremium = 0;
                        var totalActualPremium = 0;
                        $.each(dutyList || [], function (index, obj) {
                            totalStandardPremium += obj.totalStandardPremium;
                            totalActualPremium += obj.totalActualPremium;
                            // console.log(obj.dutyCode);
                            if (obj.dutyCode == "01") {
                                $('#dutys\\.01\\.preminumRate').val(obj.premiumRate);
                                $('#dutys\\.01\\.totalStandardPremium').val(obj.totalStandardPremium);
                                $('#dutys\\.01\\.totalAgreePremium').val(obj.totalAgreePremium);
                                $('#dutys\\.01\\.totalActualPremium').val(obj.totalActualPremium);
                            }
                            else if (obj.dutyCode == "02") {
                                $('#dutys\\.02\\.preminumRate').val(obj.premiumRate);
                                $('#dutys\\.02\\.totalStandardPremium').val(obj.totalStandardPremium);
                                $('#dutys\\.02\\.totalAgreePremium').val(obj.totalAgreePremium);
                                $('#dutys\\.02\\.totalActualPremium').val(obj.totalActualPremium);
                            }
                            else if (obj.dutyCode == "03") {
                                $('#dutys\\.03\\.preminumRate').val(obj.premiumRate);
                                $('#dutys\\.03\\.totalStandardPremium').val(obj.totalStandardPremium);
                                $('#dutys\\.03\\.totalAgreePremium').val(obj.totalAgreePremium);
                                $('#dutys\\.03\\.totalActualPremium').val(obj.totalActualPremium);
                            }
                            else if (obj.dutyCode == "04") {
                                $('#dutys\\.04\\.preminumRate').val(obj.premiumRate);
                                $('#dutys\\.04\\.totalStandardPremium').val(obj.totalStandardPremium);
                                $('#dutys\\.04\\.totalAgreePremium').val(obj.totalAgreePremium);
                                $('#dutys\\.04\\.totalActualPremium').val(obj.totalActualPremium);
                            }
                            else if (obj.dutyCode == "05") {
                                $('#dutys\\.05\\.preminumRate').val(obj.premiumRate);
                                $('#dutys\\.05\\.totalStandardPremium').val(obj.totalStandardPremium);
                                $('#dutys\\.05\\.totalAgreePremium').val(obj.totalAgreePremium);
                                $('#dutys\\.05\\.totalActualPremium').val(obj.totalActualPremium);
                            }
                            else if (obj.dutyCode == "08") {
                                $('#dutys\\.08\\.preminumRate').val(obj.premiumRate);
                                $('#dutys\\.08\\.totalStandardPremium').val(obj.totalStandardPremium);
                                $('#dutys\\.08\\.totalAgreePremium').val(obj.totalAgreePremium);
                                $('#dutys\\.08\\.totalActualPremium').val(obj.totalActualPremium);
                            }
                            else if (obj.dutyCode == "17") {
                                $('#dutys\\.17\\.preminumRate').val(obj.premiumRate);
                                $('#dutys\\.17\\.totalStandardPremium').val(obj.totalStandardPremium);
                                $('#dutys\\.17\\.totalAgreePremium').val(obj.totalAgreePremium);
                                $('#dutys\\.17\\.totalActualPremium').val(obj.totalActualPremium);
                            }
                            else if (obj.dutyCode == "18") {
                                $('#dutys\\.18\\.preminumRate').val(obj.premiumRate);
                                $('#dutys\\.18\\.totalStandardPremium').val(obj.totalStandardPremium);
                                $('#dutys\\.18\\.totalAgreePremium').val(obj.totalAgreePremium);
                                $('#dutys\\.18\\.totalActualPremium').val(obj.totalActualPremium);
                            }
                            else if (obj.dutyCode == "59") {
                                $('#dutys\\.59\\.preminumRate').val(obj.premiumRate);
                                $('#dutys\\.59\\.totalStandardPremium').val(obj.totalStandardPremium);
                                $('#dutys\\.59\\.totalAgreePremium').val(obj.totalAgreePremium);
                                $('#dutys\\.59\\.totalActualPremium').val(obj.totalActualPremium);
                            }
                            else if (obj.dutyCode == "41") {
                                $('#dutys\\.41\\.preminumRate').val(obj.premiumRate);
                                $('#dutys\\.41\\.totalStandardPremium').val(obj.totalStandardPremium);
                                $('#dutys\\.41\\.totalAgreePremium').val(obj.totalAgreePremium);
                                $('#dutys\\.41\\.totalActualPremium').val(obj.totalActualPremium);
                            }
                            else if (obj.dutyCode == "27") {
                                $('#dutys\\.27\\.totalActualPremium').val(obj.totalActualPremium);
                            }
                            else if (obj.dutyCode == "28") {
                                $('#dutys\\.28\\.totalActualPremium').val(obj.totalActualPremium);
                            }
                            else if (obj.dutyCode == "48") {
                                $('#dutys\\.48\\.totalActualPremium').val(obj.totalActualPremium);
                            }
                            else if (obj.dutyCode == "49") {
                                $('#dutys\\.49\\.totalActualPremium').val(obj.totalActualPremium);
                            }
                            else if (obj.dutyCode == "50") {
                                $('#dutys\\.50\\.totalActualPremium').val(obj.totalActualPremium);
                            }
                        });

                        $('#dutys\\.c01\\.totalStandardPremium').val(totalStandardPremium.toFixed(2));
                        $('#dutys\\.c01\\.totalActualPremium').val(totalActualPremium.toFixed(2));
                        $('#dutys\\.c01\\.discount').val((totalActualPremium / totalStandardPremium).toFixed(3));
                        totalPremium += totalActualPremium;
                    }
                }
            }

            if (!isEmpty(c51)) {
                if (c51.resultFlag == 'true') {
                    var errorCode = c51.resultDTO.errorCode;
                    var errorMessage = c51.resultDTO.errorMessage;
                    var totalAgreePremium = c51.resultDTO.totalAgreePremium;
                    var dutyList = c51.resultDTO.dutyList;
                    var vehicleTaxInfo = c51.vehicleTaxInfo;

                    if (errorCode && errorMessage) {
                        showMsg('error', errorMessage);
                    }

                    $('#c51\\.totalActualPremium').val(totalAgreePremium);
                    $('#vehicleTaxInfo\\.totalTaxMoney').val(vehicleTaxInfo.totalTaxMoney);

                    $('#dutys\\.c51\\.totalActualPremium').val(totalAgreePremium);
                    $('#dutys\\.totalTaxMoney').val(vehicleTaxInfo.totalTaxMoney);

                    totalPremium += totalAgreePremium;
                    totalPremium += vehicleTaxInfo.totalTaxMoney;
                }
            }

            $('#dutys\\.totalAgreePremium').val(totalPremium.toFixed(2));

            $('#quotationNo').val(voucherNo);
            $('#quotationNo').data('voucher', ret);

            showMsg('ok', '报价成功,报价单号:' + voucherNo);
        }
        else if (data.result == 400) {
            if (typeof data.message === 'string') {
                showMsg('error', data.message);
            }
            else {
                var ret = data.message;
                var voucherNo = ret.voucherNo;
                var c01 = ret.c01;
                var c51 = ret.c51;

                if (!isEmpty(c01)) {
                    if (c01.resultFlag == 'true') {
                        var errorMessage = c01.resultDTO.errorMessage;
                        showMsg('error', errorMessage);
                    } else {
                        var errorMsg = c01.errorMsg;
                        var flowListMap = c01.flowListMap;
                        showFlowList(errorMsg, flowListMap);
                    }
                }

                if (!isEmpty(c51)) {
                    if (c51.resultFlag == 'true') {
                        var errorMessage = c51.resultDTO.errorMessage;
                        showMsg('error', errorMessage);
                    } else {
                        var errorMsg = c51.errorMsg;
                        var flowListMap = c51.flowListMap;
                        showFlowList(errorMsg, flowListMap);
                    }
                }
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
