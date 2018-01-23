/**
 * Created by tyleryang on 16/3/23.
 */

function clear_form() {
    // $('#dutys\\.01\\.isChecked').iCheck('uncheck');
    $('#dutys\\.totalAgreePremium').val();
}

$(document).ready(function () {
    var carcard = $('#offer-carcard').val();
    var voucherNo = $('#voucherNo').val();
    var qid = $('#qid').val();
    // console.log(voucherNo);
    if (carcard || voucherNo || qid) {
        // console.log(voucherNo);
        $('#queryForm').submit();
        $('#voucherNo').val('');
        $('#qid').val('');

        // TODO:删除旧表单?
        // var data = {
        //     'voucherNo': voucherNo,
        //     'vehicleLicenceCode': carcard
        // };
        // var xhr = $.ajax({
        //     type: 'POST',
        //     url: '/offer/offer/delete?s=' + $('#offer-area').val(),
        //     data: JSON.stringify(data),
        //     contentType: "application/json"
        // });
        //
        // xhr.done(function (data) {
        //     console.log(data);
        // });
        // xhr.fail(function (jqXHR, textStatus, errorThrown) {
        //     console.log(textStatus);
        //     showMsg('error', textStatus);
        // });
    }
});

function query(json) {
    var s = $('#offer-area').val();
    if (json.result == 0) {
        console.log(json.data);
        var main = json.data.main;
        var relateShips = json.data.relateShips;
        var vechile = json.data.vechile;
        var risks = json.data.risks;
        var coverages = json.data.coverages;
        var carShip = json.data.carShip;
        var delivery = json.data.delivery;
        var summary = json.data.summary;

        var pingan = json.data.pingan;
        var cpic = json.data.cpic;
        var custom = json.data.custom;
        clear_form();

        if (!isEmpty(pingan)) {
            var ownerDriver = pingan.voucher.ownerDriver;
            var autoModelType = pingan.autoModelType;
            var vehicleTarget = pingan.voucher.vehicleTarget;

            if (vehicleTarget.usageAttributeCode != "02") {
                showMsg('error', '营运车辆,请联系@金志东!');
                return;
            }

            if (vehicleTarget.ownershipAttributeCode != "03") {
                showMsg('error', '非个人车主,请联系@金志东!');
                return;
            }

            // console.log(ownerDriver);
            $('#personnelName').val(ownerDriver.personnelName);
            $('#certificateTypeNo').val(ownerDriver.certificateTypeNo);
            $('#certificateTypeCode').selectpicker('val', ownerDriver.certificateTypeCode);
            $('#sexCode' + ownerDriver.sexCode).iCheck('check');
            if (ownerDriver.birthday && ownerDriver.birthday.length >= 10) {
                $('#birthday').val(ownerDriver.birthday.substr(0, 10));
            }
            if (ownerDriver.mobileTelephone) {
                $('#linkmodeNum').val(ownerDriver.mobileTelephone);
            }

            // console.log(autoModelType);
            $('#vehicleLicenceCode').val(vehicleTarget.vehicleLicenceCode);
            $('#engineNo').val(vehicleTarget.engineNo);
            $('#vehicleFrameNo').val(vehicleTarget.vehicleFrameNo);

            $('#autoModelCode').empty();
            var xhr = $.post('/offer/offer/queryName?s=' + s + '&name=' + vehicleTarget.autoModelCode);
            xhr.done(function (data) {
                // console.log(data);
                if (data.result == 0) {
                    $.each(data.data || [], function (index, obj) {
                        // console.log(obj.optionDisplay);
                        $('#autoModelCode').append($(
                            '<option>', {
                                value: obj.autoModelCode,
                                text: obj.optionDisplay
                            }
                        ))
                    });
                    $('#autoModelCode').selectpicker('refresh');
                }
                // else {
                //     showMsg('error', data.message);
                // }
            });
            xhr.fail(function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                // showMsg('error', textStatus);
            });

            // console.log(vehicleTarget);
            $('#brandName').val(vehicleTarget.circVehicleChineseBrand);
            $('#brand').val(vehicleTarget.modifyAutoModelName);

            if (vehicleTarget.vehicleTypeCode == 'A012') {
                $('#vehicleTypeCode').val('0');
            }
            else if (vehicleTarget.vehicleTypeCode == 'A022') {
                $('#vehicleTypeCode').val('1');
            }

            $('#usageAttributeCode').val(vehicleTarget.usageAttributeCode);
            if (vehicleTarget.usageAttributeCode == "02") {
                $('#usageAttributeCodeText').text('非营业');
            }
            else if (vehicleTarget.usageAttributeCode == "01") {
                $('#usageAttributeCodeText').text('营业');
            }

            $('#ownershipAttributeCode').val(vehicleTarget.ownershipAttributeCode);
            if (vehicleTarget.ownershipAttributeCode == "03") {
                $('#ownershipAttributeCodeText').text('私人');
            }
            else if (vehicleTarget.ownershipAttributeCode == "02") {
                $('#ownershipAttributeCodeText').text('企业');
            }
            else if (vehicleTarget.ownershipAttributeCode == "01") {
                $('#ownershipAttributeCodeText').text('机关');
            }

            $('#vehicleSeats').val(vehicleTarget.vehicleSeats);
            $('#vehicleSeats').trigger('input');
            $('#vehicleTonnages').val(vehicleTarget.vehicleTonnages);
            $('#vehWholeWeight').val(vehicleTarget.wholeWeight);
            $('#vehExhaustCapability').val(vehicleTarget.exhaustCapability);
            $('#licenceTypeCode').val(vehicleTarget.licenceTypeCode);
            if (vehicleTarget.firstRegisterDate && vehicleTarget.firstRegisterDate.length >= 10) {
                $('#firstRegisterDate').val(vehicleTarget.firstRegisterDate.substr(0, 10));

                // var now = moment();
                // var thisYear = moment(vehicleTarget.firstRegisterDate.substr(0, 10)).add(1, 'day').year(now.year());
                // var nextYear = moment(vehicleTarget.firstRegisterDate.substr(0, 10)).add(1, 'day').year(now.year() + 1);
                //
                // console.log(thisYear.format('YYYY-MM-DD'), nextYear.format('YYYY-MM-DD'));
                //
                // $('#c01BeginTime').val(thisYear.format('YYYY-MM-DD'));
                // $('#c01EndTime').val(nextYear.format('YYYY-MM-DD'));
                //
                // $('#c51BeginTime').val(thisYear.format('YYYY-MM-DD'));
                // $('#c51EndTime').val(nextYear.format('YYYY-MM-DD'));
            }

            var specialCarFlag = vehicleTarget.specialCarFlag;
            $('#specialCarFlag').selectpicker('val', specialCarFlag);
            if (specialCarFlag == "1") {
                if (vehicleTarget.transferDate && vehicleTarget.transferDate.length >= 10) {
                    $('#transferDate').val(vehicleTarget.transferDate.substr(0, 10));
                }
            }

            query_pingan(pingan);

            var vehicleTaxInfo = pingan.voucher.vehicleTaxInfo;
            if (vehicleTaxInfo) {
                $('#taxType' + vehicleTaxInfo.taxType).iCheck('check');

                $('#payTaxNo').val(vehicleTaxInfo.payTaxNo);
                $('#taxOrg').val(vehicleTaxInfo.taxOrg);
                console.log(vehicleTaxInfo);
            }

            var insurantInfo = pingan.voucher.insurantInfo;
            var applicantInfo = pingan.voucher.applicantInfo;
            // console.log(insurantInfo, applicantInfo);

            if (insurantInfo.personnelFlag == "1") {
                // 个人
                $('#insureInfo\\.personnelFlag1').iCheck('check');
                $('#insureInfo\\.personnelName').val(insurantInfo.personnelName);
                $('#insureInfo\\.certificateTypeNo').val(insurantInfo.certificateTypeNo);

                $('#insureInfo\\.linkmodeNum').val(insurantInfo.linkmodeNum || '13530380999');
                $('#insureInfo\\.certificateTypeCode').selectpicker('val', insurantInfo.certificateTypeCode);
                $('#insureInfo\\.sexCode' + insurantInfo.sexCode).iCheck('check');
                if (insurantInfo.birthday && insurantInfo.birthday.length >= 10) {
                    $('#insureInfo\\.birthday').val(insurantInfo.birthday.substr(0, 10));
                }
            }
            else {
                // 团体
                $('#insureInfo\\.personnelFlag0').iCheck('check');
                $('#insureInfo\\.organization\\.personnelName').val(insurantInfo.personnelName);
                $('#insureInfo\\.organization\\.certificateTypeNo').val(insurantInfo.certificateTypeNo);

                $('#insureInfo\\.organization\\.certificateTypeCode').selectpicker('val', insurantInfo.certificateTypeCode);

                $('#insureInfo\\.organization\\.organizationType').selectpicker('val', insurantInfo.personnelType);

                $('#insureInfo\\.organization\\.linkManName').val(insurantInfo.linkManName);
                $('#insureInfo\\.organization\\.linkmodeNum').val(insurantInfo.linkmodeNum);
            }

            if (applicantInfo.personnelFlag == "1") {
                // 个人
                $('#applicantInfo\\.personnelFlag1').iCheck('check');
                $('#applicantInfo\\.personnelName').val(applicantInfo.personnelName);
                $('#applicantInfo\\.certificateTypeNo').val(applicantInfo.certificateTypeNo);

                $('#applicantInfo\\.linkmodeNum').val(applicantInfo.linkmodeNum || '13530380999');
                $('#applicantInfo\\.certificateTypeCode').selectpicker('val', applicantInfo.certificateTypeCode);
                $('#applicantInfo\\.sexCode' + applicantInfo.sexCode).iCheck('check');
                if (applicantInfo.birthday && applicantInfo.birthday.length >= 10) {
                    $('#applicantInfo\\.birthday').val(applicantInfo.birthday.substr(0, 10));
                }
            }
            else {
                // 团体
                $('#applicantInfo\\.personnelFlag0').iCheck('check');
                $('#applicantInfo\\.organization\\.personnelName').val(applicantInfo.personnelName);
                $('#applicantInfo\\.organization\\.certificateTypeNo').val(applicantInfo.certificateTypeNo);

                $('#applicantInfo\\.organization\\.certificateTypeCode').selectpicker('val', applicantInfo.certificateTypeCode);

                $('#applicantInfo\\.organization\\.organizationType').selectpicker('val', applicantInfo.personnelType);

                $('#applicantInfo\\.organization\\.linkManName').val(applicantInfo.linkManName);
                $('#applicantInfo\\.organization\\.linkmodeNum').val(applicantInfo.linkmodeNum);
            }
        }
        else if (!isEmpty(cpic)) {
            if (!isEmpty(custom)) {
                $('#personnelName').val(custom[0].personnelName);
                $('#certificateTypeCode').selectpicker('val', '01');
                $('#certificateTypeNo').val(custom[0].certificateTypeNo);
                $('#certificateTypeNo').trigger('input');

                // $('#vehicleLicenceCode').val(custom[0].vehicleLicenceCode);
                // $('#engineNo').val(custom[0].engineNo);
                // $('#vehicleFrameNo').val(custom[0].vehicleFrameNo);
                //
                // $('#firstRegisterDate').val(custom[0].firstRegisterDate.substr(0, 10));
            }

            var autoModelType = cpic[0].autoModelType;
            var engineNo = cpic[0].engineNo;
            var firstRegisterDate = cpic[0].firstRegisterDate;
            var vehicleFrameNo = cpic[0].vehicleFrameNo;
            var vehicleLicenceCode = dealCarcard(cpic[0].vehicleLicenceCode);

            var vehicleTypeCode = 'A012';
            var usageAttributeCode = '02';
            var ownershipAttributeCode = '03';
            var licenceTypeCode = '02';

            $('#vehicleLicenceCode').val(vehicleLicenceCode);
            $('#engineNo').val(engineNo);
            $('#vehicleFrameNo').val(vehicleFrameNo);

            $('#autoModelCode').empty();
            var xhr = $.post('/offer/offer/queryName?s=' + s + '&name=' + autoModelType.autoModelCode);
            xhr.done(function (data) {
                // console.log(data);
                if (data.result == 0) {
                    $.each(data.data || [], function (index, obj) {
                        // console.log(obj.optionDisplay);
                        $('#autoModelCode').append($(
                            '<option>', {
                                value: obj.autoModelCode,
                                text: obj.optionDisplay
                            }
                        ))
                    });
                    $('#autoModelCode').selectpicker('refresh');
                    $('#autoModelCode').change();
                }
                // else {
                //     showMsg('error', data.message);
                // }
            });
            xhr.fail(function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                // showMsg('error', textStatus);
            });

            $('#brandName').val(autoModelType.brandName);
            $('#brand').val(autoModelType.autoModelName);

            if (vehicleTypeCode == 'A012') {
                $('#vehicleTypeCode').val('0');
            }
            else if (vehicleTypeCode == 'A022') {
                $('#vehicleTypeCode').val('1');
            }

            $('#usageAttributeCode').val(usageAttributeCode);
            if (usageAttributeCode == "02") {
                $('#usageAttributeCodeText').text('非营业');
            }
            else if (usageAttributeCode == "01") {
                $('#usageAttributeCodeText').text('营业');
            }

            $('#ownershipAttributeCode').val(ownershipAttributeCode);
            if (ownershipAttributeCode == "03") {
                $('#ownershipAttributeCodeText').text('私人');
            }
            else if (ownershipAttributeCode == "02") {
                $('#ownershipAttributeCodeText').text('企业');
            }
            else if (ownershipAttributeCode == "01") {
                $('#ownershipAttributeCodeText').text('机关');
            }

            $('#vehicleSeats').val(autoModelType.seats);
            $('#vehicleSeats').trigger('input');
            $('#vehicleTonnages').val(autoModelType.tons);
            $('#vehWholeWeight').val('0');
            $('#vehExhaustCapability').val(autoModelType.exhaustMeasure);
            $('#licenceTypeCode').val(licenceTypeCode);
            if (firstRegisterDate && firstRegisterDate.length >= 10) {
                $('#firstRegisterDate').val(firstRegisterDate.substr(0, 10));
            }
        }
        else if (!isEmpty(custom)) {
            $('#personnelName').val(custom[0].personnelName);
            $('#certificateTypeCode').selectpicker('val', '01');
            $('#certificateTypeNo').val(custom[0].certificateTypeNo);
            $('#certificateTypeNo').trigger('input');

            $('#vehicleLicenceCode').val(custom[0].vehicleLicenceCode);
            $('#engineNo').val(custom[0].engineNo);
            $('#vehicleFrameNo').val(custom[0].vehicleFrameNo);

            $('#firstRegisterDate').val(custom[0].firstRegisterDate.substr(0, 10));

            var now = moment();
            var thisYear = moment(custom[0].firstRegisterDate.substr(0, 10)).add(1, 'day').year(now.year());
            var nextYear = moment(custom[0].firstRegisterDate.substr(0, 10)).add(0, 'day').year(now.year() + 1);

            // console.log(thisYear.format('YYYY-MM-DD'), nextYear.format('YYYY-MM-DD'),111);

            $('#c01BeginTime').val(thisYear.format('YYYY-MM-DD'));
            $('#c01EndTime').val(nextYear.format('YYYY-MM-DD'));

            $('#c51BeginTime').val(thisYear.format('YYYY-MM-DD'));
            $('#c51EndTime').val(nextYear.format('YYYY-MM-DD'));
        }
        else if (!isEmpty(main)) {

            var insuraceCompany = main.insuraceCompany;
            if (insuraceCompany == 'FDBX') {
                $('#initkind\\.fdbx').trigger('click');
                $('#initkind\\.fdbx').on('onloaded', function (e) {
                    query_fdbx(main, relateShips, vechile, risks, coverages, carShip, delivery, summary);
                });
            }
            else if (insuraceCompany == 'MACN') {
                $('#initkind\\.macn').trigger('click');
                $('#initkind\\.macn').on('onloaded', function (e) {
                    query_macn(main, relateShips, vechile, risks, coverages, carShip, delivery, summary);
                });
            }
            else if (insuraceCompany == 'TAIC') {
                $('#initkind\\.taic').trigger('click');
                $('#initkind\\.taic').on('onloaded', function (e) {
                    query_taic(main, relateShips, vechile, risks, coverages, carShip, delivery, summary);
                });
            }

            $('#personnelName').val(vechile.vehicleOwnerName);
            $('#certificateTypeNo').val(vechile.identifyNumber);
            $('#certificateTypeNo').trigger('input');
            $('#certificateTypeCode').selectpicker('val', vechile.identifyType);

            // console.log(autoModelType);
            $('#vehicleLicenceCode').val(vechile.licensePlateNo);
            $('#engineNo').val(vechile.engineNo);
            $('#vehicleFrameNo').val(vechile.VIN);

            $('#autoModelCode').empty();
            var xhr = $.post('/offer/offer/queryName?s=' + s + '&name=' + vechile.modelCode);
            xhr.done(function (data) {
                // console.log(data);
                if (data.result == 0) {
                    $.each(data.data || [], function (index, obj) {
                        // console.log(obj.optionDisplay);
                        $('#autoModelCode').append($(
                            '<option>', {
                                value: obj.autoModelCode,
                                text: obj.optionDisplay
                            }
                        ))
                    });
                    $('#autoModelCode').selectpicker('refresh');
                }
                // else {
                //     showMsg('error', data.message);
                // }
            });
            xhr.fail(function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                // showMsg('error', textStatus);
            });

            // console.log(vehicleTarget);
            // $('#brandName').val(vechile.circVehicleChineseBrand);
            $('#brand').val(vechile.modelName);

            $('#vehicleSeats').val(vechile.seatCount);
            $('#vehicleSeats').trigger('input');
            $('#vehicleTonnages').val(vechile.tonnage || 0);
            $('#vehWholeWeight').val(vechile.wholeWeight);
            $('#vehExhaustCapability').val(parseInt(vechile.displacement) / 1000);
            // $('#licenceTypeCode').val(vechile.licenceTypeCode);
            $('#firstRegisterDate').val(vechile.firstRegisterDate.substr(0, 10));

            $.each(relateShips || [], function (idx, p) {
                if (p.relateType == "1") {
                    $('#insureInfo\\.personnelFlag1').iCheck('check');
                    $('#insureInfo\\.personnelName').val(p.applyName);
                    $('#insureInfo\\.certificateTypeNo').val(p.credentialNo);

                    $('#insureInfo\\.linkmodeNum').val(p.phone || '13530380999');
                    $('#insureInfo\\.certificateTypeCode').selectpicker('val', p.credentialCode);
                    if (p.sex == '1') {
                        $('#insureInfo\\.sexCodeM').iCheck('check');
                    } else {
                        $('#insureInfo\\.sexCodeF').iCheck('check');
                    }
                    if (p.birthDate && p.birthDate.length >= 10) {
                        $('#insureInfo\\.birthday').val(p.birthDate.substr(0, 10));
                    }
                }
                else if (p.relateType == "2") {
                    $('#applicantInfo\\.personnelFlag1').iCheck('check');
                    $('#applicantInfo\\.personnelName').val(p.applyName);
                    $('#applicantInfo\\.certificateTypeNo').val(p.credentialNo);

                    $('#applicantInfo\\.linkmodeNum').val(p.phone || '13530380999');
                    $('#applicantInfo\\.certificateTypeCode').selectpicker('val', p.credentialCode);
                    if (p.sex == '1') {
                        $('#applicantInfo\\.sexCodeM').iCheck('check');
                    } else {
                        $('#applicantInfo\\.sexCodeF').iCheck('check');
                    }
                    if (p.birthDate && p.birthDate.length >= 10) {
                        $('#applicantInfo\\.birthday').val(p.birthDate.substr(0, 10));
                    }
                }
            });

            $('#isCheckC01').iCheck('uncheck');
            $('#isCheckC51').iCheck('uncheck');
            $.each(risks || [], function (idx, p) {
                if (p && p.riskCode == "0101") {
                    $('#isCheckC01').iCheck('check');
                    $('#c01BeginTime').val(p.startDate);
                    $('#c01EndTime').val(p.endDate);
                }
                else if (p && p.riskCode == "0102") {
                    $('#isCheckC51').iCheck('check');
                    $('#c51BeginTime').val(p.startDate);
                    $('#c51EndTime').val(p.endDate);
                }
            });
        }

        if (!isEmpty(custom)) {
            $('#linkmodeNum').val(custom[0].linkmodeNum);
        }
    }
    else {
        showMsg('error', json.message);
    }
}

var isCheckC51 = $('#isCheckC51');
isCheckC51.on('ifChecked', function (event) {
    $('#c51').attr('class', '');
});
isCheckC51.on('ifUnchecked', function (event) {
    $('#c51').attr('class', 'hidden');
});

var isCheckC01 = $('#isCheckC01');
isCheckC01.on('ifChecked', function (event) {
    // $('#c01').attr('class', '');
    if (typeof window['pingan_enable_form'] != 'undefined') {
        pingan_enable_form();
    }

    if (typeof window['fdbx_enable_form'] != 'undefined') {
        fdbx_enable_form();
    }

    if (typeof window['macn_enable_form'] != 'undefined') {
        macn_enable_form();
    }

    if (typeof window['taic_enable_form'] != 'undefined') {
        taic_enable_form();
    }
});
isCheckC01.on('ifUnchecked', function (event) {
    // $('#c01').attr('class', 'hidden');
    if (typeof window['pingan_disable_form'] != 'undefined') {
        pingan_disable_form();
    }

    if (typeof window['fdbx_disable_form'] != 'undefined') {
        fdbx_disable_form();
    }

    if (typeof window['macn_disable_form'] != 'undefined') {
        macn_disable_form();
    }

    if (typeof window['taic_disable_form'] != 'undefined') {
        taic_disable_form();
    }
});

var c01BeginTime = $('#c01BeginTime');
var c01EndTime = $('#c01EndTime');
var c51BeginTime = $('#c51BeginTime');
var c51EndTime = $('#c51EndTime');
c01BeginTime.on('focusout afterchange.bjui.datepicker', function (e) {
    var date = c01BeginTime.val();
    var d = new Date(date);
    d.setFullYear(d.getFullYear() + 1);
    d.setDate(d.getDate() - 1);

    var next = d.formatDate('yyyy-MM-dd');

    c01EndTime.val(next);

    $('#veh\\.vehicleLossInsuredValue').trigger('input');
});
c51BeginTime.on('focusout afterchange.bjui.datepicker', function (e) {
    var date = c51BeginTime.val();
    var d = new Date(date);
    d.setFullYear(d.getFullYear() + 1);
    d.setDate(d.getDate() - 1);

    var next = d.formatDate('yyyy-MM-dd');

    c51EndTime.val(next);
});

$('#certificateTypeNo').on('input', function () {
    var certificateTypeNo = $('#certificateTypeNo').val();
    if (certificateTypeNo && (certificateTypeNo.length == 15 || certificateTypeNo.length == 18)) {
        var cert = CertificateNoParse(certificateTypeNo);
        if (cert) {
            if (cert.idxSex == '2') {
                $('#sexCodeF').iCheck('check');
            }
            else {
                $('#sexCodeM').iCheck('check');
            }
            $('#birthday').val(cert.birthday);
        }
    }
});

$('#isInsureSame').on('ifChecked', function () {
    var personnelName = $('#personnelName').val();
    var certificateTypeCodeText = $('#certificateTypeCode').text();
    var certificateTypeCode = $('#certificateTypeCode').val();
    var certificateTypeNo = $('#certificateTypeNo').val();
    var sexCode = $('#sexCodeM').prop('checked');
    if (sexCode) {
        sexCode = 'M'
    }
    else {
        sexCode = 'F'
    }
    var birthday = $('#birthday').val();

    // 必须是个人
    $('#insureInfo\\.personnelFlag1').iCheck('check');
    $('#insureInfo\\.personnelName').val(personnelName);
    $('#insureInfo\\.certificateTypeNo').val(certificateTypeNo);
    // $('#insureInfo\\.linkmodeNum').val();
    $('#insureInfo\\.certificateTypeCode').selectpicker('val', certificateTypeCode);
    $('#insureInfo\\.sexCode' + sexCode).iCheck('check');
    if (birthday && birthday.length >= 10) {
        $('#insureInfo\\.birthday').val(birthday.substr(0, 10));
    }
});

$('#isApplicantSame').on('ifChecked', function () {
    var personnelFlag = $('#insureInfo\\.personnelFlag1').prop('checked') ? "1" : "0";
    if (personnelFlag == "1") {
        // 个人
        var personnelName = $('#insureInfo\\.personnelName').val();
        var certificateTypeCodeText = $('#insureInfo\\.certificateTypeCode').text();
        var certificateTypeCode = $('#insureInfo\\.certificateTypeCode').val();
        var certificateTypeNo = $('#insureInfo\\.certificateTypeNo').val();
        var linkmodeNum = $('#insureInfo\\.linkmodeNum').val();
        var sexCode = $('#insureInfo\\.sexCodeM').prop('checked');
        if (sexCode) {
            sexCode = 'M'
        }
        else {
            sexCode = 'F'
        }
        var birthday = $('#insureInfo\\.birthday').val();

        $('#applicantInfo\\.personnelFlag1').iCheck('check');
        $('#applicantInfo\\.personnelName').val(personnelName);
        $('#applicantInfo\\.certificateTypeNo').val(certificateTypeNo);
        $('#applicantInfo\\.certificateTypeCode').selectpicker('val', certificateTypeCode);
        $('#applicantInfo\\.linkmodeNum').val(linkmodeNum);
        $('#applicantInfo\\.sexCode' + sexCode).iCheck('check');
        if (birthday && birthday.length >= 10) {
            $('#applicantInfo\\.birthday').val(birthday.substr(0, 10));
        }
    }
    else {
        // 团体
        var personnelName = $('#insureInfo\\.organization\\.personnelName').val();
        var certificateTypeNo = $('#insureInfo\\.organization\\.certificateTypeNo').val();
        var certificateTypeCode = $('#insureInfo\\.organization\\.certificateTypeCode').val();
        var personnelType = $('#insureInfo\\.organization\\.organizationType').val();
        var linkManName = $('#insureInfo\\.organization\\.linkManName').val();
        var linkmodeNum = $('#insureInfo\\.organization\\.linkmodeNum').val();

        $('#applicantInfo\\.personnelFlag0').iCheck('check');
        $('#applicantInfo\\.organization\\.personnelName').val(personnelName);
        $('#applicantInfo\\.organization\\.certificateTypeNo').val(certificateTypeNo);

        $('#applicantInfo\\.organization\\.certificateTypeCode').selectpicker('val', certificateTypeCode);

        $('#applicantInfo\\.organization\\.organizationType').selectpicker('val', personnelType);

        $('#applicantInfo\\.organization\\.linkManName').val(linkManName);
        $('#applicantInfo\\.organization\\.linkmodeNum').val(linkmodeNum);
    }
});

var insureInfo_personnelFlag1 = $('#insureInfo\\.personnelFlag1');
var insureInfo_personnelFlag0 = $('#insureInfo\\.personnelFlag0');
insureInfo_personnelFlag1.on('ifChecked', function (event) {
    $('#insureInfo\\.personnel').attr('class', 'table table-condensed table-hover');
    $('#insureInfo\\.organization').attr('class', 'table table-condensed table-hover hidden');
});
insureInfo_personnelFlag0.on('ifChecked', function (event) {
    $('#insureInfo\\.personnel').attr('class', 'table table-condensed table-hover hidden');
    $('#insureInfo\\.organization').attr('class', 'table table-condensed table-hover');
});

var applicantInfo_personnelFlag1 = $('#applicantInfo\\.personnelFlag1');
var applicantInfo_personnelFlag0 = $('#applicantInfo\\.personnelFlag0');
applicantInfo_personnelFlag1.on('ifChecked', function (event) {
    $('#applicantInfo\\.personnel').attr('class', 'table table-condensed table-hover');
    $('#applicantInfo\\.organization').attr('class', 'table table-condensed table-hover hidden');
});
applicantInfo_personnelFlag0.on('ifChecked', function (event) {
    $('#applicantInfo\\.personnel').attr('class', 'table table-condensed table-hover hidden');
    $('#applicantInfo\\.organization').attr('class', 'table table-condensed table-hover');
});

$('#queryCarButton').on('click', function (e) {
    var s = $('#offer-area').val();
    var vehicleFrameNo = $('#vehicleFrameNo').val();
    var autoModelCode = $('#autoModelCode');

    if (!vehicleFrameNo) {
        showMsg('error', '请输入车架号!');
        return;
    }

    autoModelCode.empty();
    var xhr = $.post('/offer/offer/queryModel?s=' + s + '&vin=' + vehicleFrameNo);
    xhr.done(function (data) {
        if (data.result == 0) {
            var carLists = data.data;
            // console.log(carLists);
            if (carLists.length > 0) {
                var defCar = carLists[0];
                $.each(carLists || [], function (index, obj) {
                    // console.log(obj.optionDisplay);
                    autoModelCode.append($(
                        '<option>', {
                            value: obj.autoModelCode,
                            text: obj.optionDisplay
                        }
                    ))
                });
                autoModelCode.selectpicker('refresh');
                autoModelCode.change();
            }
        }
        // else {
        //     showMsg('error', data.message);
        // }
    });
    xhr.fail(function (jqXHR, textStatus, errorThrown) {
        console.log(textStatus);
        // showMsg('error', textStatus);
    });

    e.preventDefault();
    e.stopPropagation();
});

$("#brand").on("keydown", function (e) {
    if (e.keyCode == 13) {
        var s = $('#offer-area').val();
        var brandName = $(this).val();
        var autoModelCode = $('#autoModelCode');

        if (brandName.length > 2) {
            var xhr = $.post('/offer/offer/queryBrand?s=' + s + '&name=' + brandName);
            xhr.done(function (data) {
                if (data.result == 0) {
                    var carLists = data.data;
                    // console.log(carLists);
                    if (carLists.length > 0) {
                        $.each(carLists || [], function (index, obj) {
                            // console.log(obj.optionDisplay);
                            autoModelCode.append($(
                                '<option>', {
                                    value: obj.autoModelCode,
                                    text: obj.optionDisplay
                                }
                            ))
                        });
                        autoModelCode.selectpicker('refresh');
                        autoModelCode.change();
                    }
                }
                // else {
                //     showMsg('error', data.message);
                // }
            });
            xhr.fail(function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                // showMsg('error', textStatus);
            });
        }

        e.preventDefault();
        e.stopPropagation();
    }
});

var provinceCode = $('#offer-provinceCode');
var areaCode = $('#offer-areaCode');

provinceCode.select2();
areaCode.select2();

$.getJSON('/offer/offer/queryArea', function (data) {
    provinceCode.select2({data: data});
    provinceCode.val('440000').trigger('change');
});

provinceCode.on('change', function (e) {
    var p = provinceCode.val();
    $.getJSON('/offer/offer/queryArea?id=' + p, function (data) {
        areaCode.empty();
        areaCode.select2({data: data});
        if (p == '440000') {
            areaCode.val('440300').trigger('change');
        }
        else {
            areaCode.val(data[0].id).trigger('change');
        }
    });
});

areaCode.on('change', function (e) {
    var c = areaCode.val();
    console.log(c);
    $('#initkind\\.fdbx').attr('href', '/offer/offer/initkind?insuraceCompany=FDBX&areaCode=' + c);
    $('#initkind\\.macn').attr('href', '/offer/offer/initkind?insuraceCompany=MACN&areaCode=' + c);
    $('#initkind\\.taic').attr('href', '/offer/offer/initkind?insuraceCompany=TAIC&areaCode=' + c);
});

$('#autoModelCode').change(function (e) {
    // do something...
    var s = $('#offer-area').val();
    var autoModelCode = $(this).val();
    // console.log(autoModelCode);

    var xhr = $.post('/offer/offer/queryName?s=' + s + '&name=' + autoModelCode);
    xhr.done(function (data) {
        console.log(data);
        if (data.result == 0) {
            var carLists = data.data;
            if (carLists.length > 0) {
                var vehicleTarget = carLists[0];

                $('#brand').val(vehicleTarget.autoModelName);

                if (vehicleTarget.vehicleTypeNew == 'A012') {
                    $('#vehicleTypeCode').val('0');
                }
                else if (vehicleTarget.vehicleTypeNew == 'A022') {
                    $('#vehicleTypeCode').val('1');
                }

                $('#brandName').val(vehicleTarget.brandName);

                $('#vehicleSeats').val(vehicleTarget.seats);
                $('#vehicleSeats').trigger('input');
                $('#vehicleTonnages').val(vehicleTarget.tons);
                // $('#vehWholeWeight').val(vehicleTarget.wholeWeight);
                $('#vehExhaustCapability').val(vehicleTarget.exhaustMeasure);

                $('#veh\\.purchasePriceDefaultValue').text(vehicleTarget.purchasePrice);
                $('#veh\\.vehicleLossInsuredValue').val(vehicleTarget.purchasePrice);
                $('#veh\\.vehicleLossInsuredValue').trigger('input');
            }
        }
        // else {
        //     showMsg('error', data.message);
        // }
    });
    xhr.fail(function (jqXHR, textStatus, errorThrown) {
        console.log(textStatus);
        // showMsg('error', textStatus);
    });
});

$('#j_query_form').bind('dr.submit', function () {
    var pingan_class = $('#initkind\\.pingan').parent().attr('class');
    var fdbx_class = $('#initkind\\.fdbx').parent().attr('class');
    var macn_class = $('#initkind\\.macn').parent().attr('class');
    var taic_class = $('#initkind\\.taic').parent().attr('class');

    var params = {
        'ownerDriver': {
            'personnelFlag': '1',
            'personnelName': $('#personnelName').val(),
            'certificateTypeCode': $('#certificateTypeCode').val(),
            'certificateTypeNo': $('#certificateTypeNo').val(),
            'sex': $('#sexCodeM').prop('checked') ? 'M' : 'F',
            'birthday': $('#birthday').val()
        },
        'aplylicantInfo': {
            'personnelFlag': '1',
            'personnelName': $('#personnelName').val(),
            'certificateTypeCode': $('#certificateTypeCode').val(),
            'certificateTypeNo': $('#certificateTypeNo').val(),
            'sex': $('#sexCodeM').prop('checked') ? 'M' : 'F',
            'birthday': $('#birthday').val(),
            'isConfirm': '5'
        },
        'applicantInfo': {
            'isConfirm': '5'
        },
        'insurantInfo': {
            'isConfirm': '5'
        },
        'vehicleTarget': {
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
            'vehicleLicenceCode': dealCarcard($('#vehicleLicenceCode').val()),
            'vehicleTypeCode': $('#vehicleTypeCode').val() == '0' ? 'A012' : 'A022',
            'vehicleClassCode': '1',
            'vehicleSeats': parseInt($('#vehicleSeats').val()),
            'taxType': '1',
            'taxPayerId': $('#certificateTypeNo').val(),
            'fuelType': '0',
            'purchasePriceDefaultValue': parseInt($('#veh\\.purchasePriceDefaultValue').text()),
            'vehicleLossInsuredValue': parseInt($('#veh\\.vehicleLossInsuredValue').val()),
            'wholeWeight': parseFloat($('#vehWholeWeight').val()),
            'exhaustCapability': parseFloat($('#vehExhaustCapability').val()),
            'specialCarFlag': $('#specialCarFlag').val(),
            'transferDate': $('#transferDate').val()
        }
    };

    var insureInfo_personnelFlag = $('#insureInfo\\.personnelFlag1').prop('checked') ? "1" : "0";
    params['insurantInfo']['personnelFlag'] = insureInfo_personnelFlag;
    if (insureInfo_personnelFlag == "1") {
        // 个人
        var personnelName = $('#insureInfo\\.personnelName').val() || '';
        var certificateTypeCodeText = $('#insureInfo\\.certificateTypeCode').text() || '';
        var certificateTypeCode = $('#insureInfo\\.certificateTypeCode').val() || '';
        var certificateTypeNo = $('#insureInfo\\.certificateTypeNo').val() || '';
        var linkmodeNum = $('#insureInfo\\.linkmodeNum').val() || '';
        var sexCode = $('#insureInfo\\.sexCodeM').prop('checked');
        if (sexCode == 'checked') {
            sexCode = 'M'
        }
        else {
            sexCode = 'F'
        }
        var birthday = $('#insureInfo\\.birthday').val() || '';

        if (personnelName.length <= 0) {
            showMsg('error', '被保人姓名必须填写');
            return;
        }

        if (certificateTypeCode.length <= 0) {
            showMsg('error', '被保人证件类型必须填写');
            return;
        }

        // if (certificateTypeCode == '01')
        // {
        //     var res = IdentityCodeValid(certificateTypeNo);
        //     if (res) {
        //         showMsg('error', '被保人身份证号' + res);
        //         return;
        //     }
        // }
        // else {
        if (certificateTypeNo.length <= 0) {
            showMsg('error', '被保人证件号码必须填写');
            return;
        }
        // }

        if (birthday.length != 10) {
            showMsg('error', '被保人出生日期格式错误');
            return;
        }

        // if (linkmodeNum.length <= 0) {
        //     showMsg('error', '被保人联系方式必须填写');
        //     return;
        // }

        params['insurantInfo']['personnelName'] = personnelName;
        params['insurantInfo']['certificateTypeCode'] = certificateTypeCode;
        params['insurantInfo']['certificateTypeNo'] = certificateTypeNo;
        params['insurantInfo']['sex'] = sexCode;
        params['insurantInfo']['birthday'] = birthday;
    }
    else {
        // 团体
        var personnelName = $('#insureInfo\\.organization\\.personnelName').val() || '';
        var certificateTypeNo = $('#insureInfo\\.organization\\.certificateTypeNo').val() || '';
        var certificateTypeCode = $('#insureInfo\\.organization\\.certificateTypeCode').val() || '';
        var personnelType = $('#insureInfo\\.organization\\.organizationType').val() || '';
        var linkManName = $('#insureInfo\\.organization\\.linkManName').val() || '';
        var linkmodeNum = $('#insureInfo\\.organization\\.linkmodeNum').val() || '';

        if (personnelName.length <= 0) {
            showMsg('error', '被保团体名称必须填写');
            return;
        }

        if (certificateTypeCode.length <= 0) {
            showMsg('error', '被保团体证件类型必须填写');
            return;
        }

        if (certificateTypeNo.length <= 0) {
            showMsg('error', '被保团体证件号码必须填写');
            return;
        }

        if (personnelType.length <= 0) {
            showMsg('error', '被保团体组织机构类型必须填写');
            return;
        }

        if (linkManName.length <= 0) {
            showMsg('error', '被保团体联系人必须填写');
            return;
        }

        if (linkmodeNum.length <= 0) {
            showMsg('error', '被保团体联系方式必须填写');
            return;
        }

        params['insurantInfo']['personnelName'] = personnelName;
        params['insurantInfo']['certificateTypeCode'] = certificateTypeCode;
        params['insurantInfo']['certificateTypeNo'] = certificateTypeNo;
        params['insurantInfo']['personnelType'] = personnelType;
    }

    var applicantInfo_personnelFlag = $('#applicantInfo\\.personnelFlag1').prop('checked') ? "1" : "0";
    params['applicantInfo']['personnelFlag'] = applicantInfo_personnelFlag;
    if (applicantInfo_personnelFlag == "1") {
        // 个人
        var personnelName = $('#applicantInfo\\.personnelName').val() || '';
        var certificateTypeCodeText = $('#applicantInfo\\.certificateTypeCode').text() || '';
        var certificateTypeCode = $('#applicantInfo\\.certificateTypeCode').val() || '';
        var certificateTypeNo = $('#applicantInfo\\.certificateTypeNo').val() || '';
        var linkmodeNum = $('#applicantInfo\\.linkmodeNum').val() || '';
        var sexCode = $('#applicantInfo\\.sexCodeM').prop('checked');
        if (sexCode == 'checked') {
            sexCode = 'M'
        }
        else {
            sexCode = 'F'
        }
        var birthday = $('#applicantInfo\\.birthday').val() || '';

        if (personnelName.length <= 0) {
            showMsg('error', '投保人姓名必须填写');
            return;
        }

        if (certificateTypeCode.length <= 0) {
            showMsg('error', '投保人证件类型必须填写');
            return;
        }

        // if (certificateTypeCode == '01')
        // {
        //     var res = IdentityCodeValid(certificateTypeNo);
        //     if (res) {
        //         showMsg('error', '投保人身份证号' + res);
        //         return;
        //     }
        // }
        // else {
        if (certificateTypeNo.length <= 0) {
            showMsg('error', '投保人证件号码必须填写');
            return;
        }
        // }

        if (birthday.length != 10) {
            showMsg('error', '投保人出生日期格式错误');
            return;
        }

        // if (linkmodeNum.length <= 0) {
        //     showMsg('error', '投保人联系方式必须填写');
        //     return;
        // }

        params['applicantInfo']['personnelName'] = personnelName;
        params['applicantInfo']['certificateTypeCode'] = certificateTypeCode;
        params['applicantInfo']['certificateTypeNo'] = certificateTypeNo;
        params['applicantInfo']['sex'] = sexCode;
        params['applicantInfo']['birthday'] = birthday;
    }
    else {
        // 团体
        var personnelName = $('#applicantInfo\\.organization\\.personnelName').val() || '';
        var certificateTypeNo = $('#applicantInfo\\.organization\\.certificateTypeNo').val() || '';
        var certificateTypeCode = $('#applicantInfo\\.organization\\.certificateTypeCode').val() || '';
        var personnelType = $('#applicantInfo\\.organization\\.organizationType').val() || '';
        var linkManName = $('#applicantInfo\\.organization\\.linkManName').val() || '';
        var linkmodeNum = $('#applicantInfo\\.organization\\.linkmodeNum').val() || '';

        if (personnelName.length <= 0) {
            showMsg('error', '投保团体名称必须填写');
            return;
        }

        if (certificateTypeCode.length <= 0) {
            showMsg('error', '投保团体证件类型必须填写');
            return;
        }

        if (certificateTypeNo.length <= 0) {
            showMsg('error', '投保团体证件号码必须填写');
            return;
        }

        if (personnelType.length <= 0) {
            showMsg('error', '投保团体组织机构类型必须填写');
            return;
        }

        if (linkManName.length <= 0) {
            showMsg('error', '投保团体联系人必须填写');
            return;
        }

        if (linkmodeNum.length <= 0) {
            showMsg('error', '投保团体联系方式必须填写');
            return;
        }

        params['applicantInfo']['personnelName'] = personnelName;
        params['applicantInfo']['certificateTypeCode'] = certificateTypeCode;
        params['applicantInfo']['certificateTypeNo'] = certificateTypeNo;
        params['applicantInfo']['personnelType'] = personnelType;
    }

    if (pingan_class == 'active') {
        offer_pingan(params);
    }
    else if (fdbx_class == 'active') {
        offer_fdbx(params);
    }
    else if (macn_class == 'active') {
        offer_macn(params);
    }
    else if (taic_class == 'active') {
        offer_taic(params);
    }
    else {
        showMsg('error', '未知保险公司?请联系管理员!');
    }
});

$('#vehicleQueryButton').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var pingan_class = $('#initkind\\.pingan').parent().attr('class');
    var fdbx_class = $('#initkind\\.fdbx').parent().attr('class');
    var macn_class = $('#initkind\\.macn').parent().attr('class');
    var taic_class = $('#initkind\\.taic').parent().attr('class');

    if (pingan_class == 'active') {
        // vehicleQuery_pingan();
    }
    else if (fdbx_class == 'active') {
        vehicleQuery_fdbx();
    }
    else if (macn_class == 'active') {
        vehicleQuery_macn();
    }
    else if (taic_class == 'active') {
        vehicleQuery_taic();
    }
    else {
        showMsg('error', '未知保险公司?请联系管理员!');
    }
});

$('#offerButton').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    $('#j_query_form').trigger('dr.submit');
});

$('#quoteButton').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var pingan_class = $('#initkind\\.pingan').parent().attr('class');
    var fdbx_class = $('#initkind\\.fdbx').parent().attr('class');
    var macn_class = $('#initkind\\.macn').parent().attr('class');
    var taic_class = $('#initkind\\.taic').parent().attr('class');

    if (pingan_class == 'active') {
        // quote_pingan();
    }
    else if (fdbx_class == 'active') {
        quote_fdbx();
    }
    else if (macn_class == 'active') {
        quote_macn();
    }
    else if (taic_class == 'active') {
        quote_taic();
    }
    else {
        showMsg('error', '未知保险公司?请联系管理员!');
    }
});

$('#payButton').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var pingan_class = $('#initkind\\.pingan').parent().attr('class');
    var fdbx_class = $('#initkind\\.fdbx').parent().attr('class');
    var macn_class = $('#initkind\\.macn').parent().attr('class');
    var taic_class = $('#initkind\\.taic').parent().attr('class');

    if (pingan_class == 'active') {
        // pay_pingan();
    }
    else if (fdbx_class == 'active') {
        pay_fdbx();
    }
    else if (macn_class == 'active') {
        pay_macn();
    }
    else if (taic_class == 'active') {
        pay_taic();
    }
    else {
        showMsg('error', '未知保险公司?请联系管理员!');
    }
});
