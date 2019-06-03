<!DOCTYPE html>
<!-- saved from url=(0045)http://www.xundanwang.com/partner/form/1.html -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title></title>
    <link rel="stylesheet" type="text/css" href="/static/myjs/cityPicker.css">
    <link rel="stylesheet" type="text/css" href="/static/myjs/mobiscroll.css">
    <style>
        input,button{
            outline:none;
        }
        .box{
            width: 850px;
            margin: 20px auto;
            border: 1px solid #f2f2f2;
            padding: 30px;
            background: #f1f3f7;
        }
        .scr-box{
            padding: 0 6px;
        }
        .scr-box-small{
            margin-bottom:10px ;
            overflow: hidden
        }
        .scr-info{
            display:inline-flex;
            width: 90%;
            flex-direction:row;
            float: right;
        }
        .scr-info div{
            width: 100%;
            height: 36px;
            line-height: 36px;
            margin-left: 5px;
            margin-right: 5px;
            background-color:#fff ;
            text-align: center;
            padding: 3px;
            font-size: 16px;
            border-radius: 5px;
            border:1px solid rgb(201, 201, 201);
            cursor:pointer;


        }
        .scr-info input{
            width: 100%;
            height: 36px;
            line-height: 36px;
            margin-left: 5px;
            margin-right: 5px;
            background-color:#fff ;
            padding: 3px;
            font-size: 16px;
            border-radius: 5px;
            border:1px solid rgb(201, 201, 201) ;
            text-align: center;
        }
        .scr-box-title{
            margin: 5px auto;
            padding: 5px;
            float: left;
            width: 8%;
            font-weight: bold;

        }
        .scr-info-active{
            background-color: #007AFF !important;
            color: #fff !important;
        }
        .btn{
            width: 90%;
            background-color: #007AFF;
            float: right;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border:1px solid rgb(201, 201, 201) ;
            text-align: center;
            color: #fff

        }
    </style>
</head>

<body>
    <div class="box">
        <form action="/api/collect/tosave" id="addForm">
            <input type="hidden" name="sex" id="sex" value="">  <!--性別-->
            <input type="hidden" name="channel" id="channel" value="{{$channel}}">  <!--性別-->
            <input type="hidden" name="career" id="profession" value="">  <!--用户职业-->
            <input type="hidden" name="work_time" id="worklong" value="">  <!--工作年限-->
            <input type="hidden" name="month_income" id="month_income" value="">  <!--月收入-->
            <input type="hidden" name="house" id="is_house" value="">  <!--是否有房-->
            <input type="hidden" name="car" id="car" value="">  <!--是否有车-->
            <input type="hidden" name="is_atom" id="is_atom" value="">  <!--是否用过微粒贷-->
            <input type="hidden" name="salary_modal" id="salary_modal" value="">  <!--工资发放方式-->
            <input type="hidden" name="policy" id="policy" value="">  <!--寿险保单-->
            <input type="hidden" name="social_security" id="social_security" value="">  <!--社保情况-->
            <input type="hidden" name="accumulation_fund" id="accumulation_fund" value="">  <!--公积金情况-->
            <input type="hidden" name="credit_card" id="credit_card" value="">  <!--信用卡情况-->

        <div class="scr-box">
            <div class="scr-box-small">
                <p class="scr-box-title">称呼</p>
                <div class="scr-info">
                    <input type="text" name="name" id="name" placeholder="请输入您的中文姓名" maxlength="20" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAAXNSR0IArs4c6QAAAfBJREFUWAntVk1OwkAUZkoDKza4Utm61iP0AqyIDXahN2BjwiHYGU+gizap4QDuegWN7lyCbMSlCQjU7yO0TOlAi6GwgJc0fT/fzPfmzet0crmD7HsFBAvQbrcrw+Gw5fu+AfOYvgylJ4TwCoVCs1ardYTruqfj8fgV5OUMSVVT93VdP9dAzpVvm5wJHZFbg2LQ2pEYOlZ/oiDvwNcsFoseY4PBwMCrhaeCJyKWZU37KOJcYdi27QdhcuuBIb073BvTNL8ln4NeeR6NRi/wxZKQcGurQs5oNhqLshzVTMBewW/LMU3TTNlO0ieTiStjYhUIyi6DAp0xbEdgTt+LE0aCKQw24U4llsCs4ZRJrYopB6RwqnpA1YQ5NGFZ1YQ41Z5S8IQQdP5laEBRJcD4Vj5DEsW2gE6s6g3d/YP/g+BDnT7GNi2qCjTwGd6riBzHaaCEd3Js01vwCPIbmWBRx1nwAN/1ov+/drgFWIlfKpVukyYihtgkXNp4mABK+1GtVr+SBhJDbBIubVw+Cd/TDgKO2DPiN3YUo6y/nDCNEIsqTKH1en2tcwA9FKEItyDi3aIh8Gl1sRrVnSDzNFDJT1bAy5xpOYGn5fP5JuL95ZjMIn1ya7j5dPGfv0A5eAnpZUY3n5jXcoec5J67D9q+VuAPM47D3XaSeL4AAAAASUVORK5CYII=&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
                </div>
            </div>
            <div class="scr-box-small">
                <p class="scr-box-title">贷款金额</p>
                <div class="scr-info" style="position:relative">
                    <input type="number" name="amount" id="loan_amount" oninput="if(value&gt;9999)value=9999;if(value.length&gt;4)value=value.slice(0,4);if(value&lt;0)value=1" onkeyup="this.value=this.value.replace(/\D|^0/g,&#39;&#39;)" onafterpaste="this.value=this.value.replace(/\D|^0/g,&#39;&#39;)" placeholder="请输入您的贷款金额 (万)"><span style="position:absolute;right:15px;top:10px;font-size:18px;font-weight:700;color:#007AFF">万</span>
                </div>
            </div>
            <div class="scr-box-small">
                <p class="scr-box-title">联系方式</p>
                <div class="scr-info">
                    <input type="text" oninput="if(value.length&gt;11)value=value.slice(0,11);if(value&lt;0)value=1" onkeyup="this.value=this.value.replace(/\D/g,&#39;&#39;)" onafterpaste="this.value=this.value.replace(/\D/g,&#39;&#39;)" name="phone" id="phone" placeholder="请输入您的电话号码">
                </div>
            </div>
            <div class="scr-box-small">
                <p class="scr-box-title">城市</p>
                <div class="scr-info">
                    <input type="text" id="cityChoice" name="city" placeholder="请选择贷款城市" readonly="readonly" unselectable="on" onfocus="this.blur()" style="cursor:pointer">
                </div>
            </div>
            <div class="scr-box-small">
                <p class="scr-box-title">性别</p>
                <div class="scr-info">
                    <div data-type="sex" data-value="1">男</div>
                    <div data-type="sex" data-value="2">女</div>
                </div>
            </div>
            <div class="scr-box-small">
                <p class="scr-box-title">您的生日</p>
                <div class="scr-info">
                    <input readonly="" id="birthday" name="birthday" class="arrow birth input" type="text" placeholder="您的生日" style="width:100%;" value="">
                </div>
            </div>
            <div class="scr-box-small">
                <p class="scr-box-title">用户职业</p>
                <div class="scr-info">
                    <div data-type="profession" data-value="白领">白领</div>
                    <div data-type="profession" data-value="教师">教师</div>
                    <div data-type="profession" data-value="公务员">公务员</div>
                    <div data-type="profession" data-value="私企业主">私企业主</div>
                </div>
            </div>

            <div class="scr-box-small">
                <p class="scr-box-title">工作时间</p>
                <div class="scr-info">
                    <div data-type="worklong" data-value="一年以上">一年以上</div>
                    <div data-type="worklong" data-value="两年以上">两年以上</div>
                    <div data-type="worklong" data-value="三年以上">三年以上</div>
                    <div data-type="worklong" data-value="五年以上">五年以上</div>
                    <div data-type="worklong" data-value="八年以上">八年以上</div>
                </div>
            </div>
            <div class="scr-box-small">
                <p class="scr-box-title">月收入</p>
                <div class="scr-info">
                    <div data-type="month_income" data-value="3000以下">3000以下</div>
                    <div data-type="month_income" data-value="3000-5000">3000-5000</div>
                    <div data-type="month_income" data-value="5001-8000">5001-8000</div>
                    <div data-type="month_income" data-value="8001-10000">8001-10000</div>
                    <div data-type="month_income" data-value="1万以上">1万以上</div>
                </div>
            </div>
            <div class="scr-box-small">
                <p class="scr-box-title">信用卡情况</p>
                <div class="scr-info">
                    <div data-type="credit_card" data-value="2">有信用卡</div>
                    <div data-type="credit_card" data-value="1">无信用卡</div>
                    <!--<div data-type="credit_card" data-value='有信用卡或有贷款'>有信用卡或有贷款</div>-->
                    <!--<div data-type="credit_card" data-value='有信用卡或无贷款'>有信用卡或无贷款</div>-->
                </div>
            </div>
            <div class="scr-box-small">
                <p class="scr-box-title">是否有房</p>
                <div class="scr-info">
                    <div data-type="is_house" data-value="1">无房</div>
                    <div data-type="is_house" data-value="2">有房</div>
                    <div data-type="is_house" data-value="3">有房无贷</div>
                </div>
            </div>
            <div class="scr-box-small">
                <p class="scr-box-title">是否有车</p>
                <div class="scr-info">
                    <div data-type="car" data-value="1">无车</div>
                    <div data-type="car" data-value="2">有车贷</div>
                    <div data-type="car" data-value="3">有车无贷</div>
                </div>
            </div>
            <div class="scr-box-small">
                <p class="scr-box-title">是否用过微粒贷</p>
                <div class="scr-info">
                    <div data-type="is_atom" data-value="1">有</div>
                    <div data-type="is_atom" data-value="2">无</div>
                </div>
            </div>
            <div class="scr-box-small">
                <p class="scr-box-title">工资发放</p>
                <div class="scr-info">
                    <div data-type="salary_modal" data-value="2">现金发放</div>
                    <div data-type="salary_modal" data-value="3">银行转账</div>
                </div>
            </div>
            <div class="scr-box-small">
                <p class="scr-box-title">寿险保单</p>
                <div class="scr-info">
                    <div data-type="policy" data-value="1">无</div>
                    <div data-type="policy" data-value="2">有，年缴2400以下</div>
                    <div data-type="policy" data-value="2">有，年缴2400以上</div>
                </div>
            </div>
            <div class="scr-box-small">
                <p class="scr-box-title">社保情况</p>
                <div class="scr-info">
                    <div data-type="social_security" data-value="1">无社保</div>
                    <div data-type="social_security" data-value="2">1年以下</div>
                    <div data-type="social_security" data-value="3">1年以上</div>
                </div>
            </div>
            <div class="scr-box-small">
                <p class="scr-box-title">公积金情况</p>
                <div class="scr-info">
                    <div data-type="accumulation_fund" data-value="1">无公积金</div>
                    <div data-type="accumulation_fund" data-value="2">1年以下</div>
                    <div data-type="accumulation_fund" data-value="3">1年以上</div>
                </div>
            </div>
            <div class="scr-box-small">
            <p class="scr-box-title"></p>
                <button class="btn">立即提交</button>
            </div>
        </div>
        </form>
    </div>

<script type="text/javascript" src="/static/myjs/jquery.min.js"></script>
<script type="text/javascript" src="/static/myjs/cityData.js"></script>
<script type="text/javascript" src="/static/myjs/cityPicker.js"></script>
<script type="text/javascript" src="/static/myjs/mobiscroll.js"></script>
<script>
    $('.scr-info').on('click','div',function(){
        var that=$(this)
        var elem=that.attr('data-type')
        var dataval=that.attr('data-value')
        $('#'+elem).val(dataval)
        that.siblings().removeClass('scr-info-active')
        that.addClass('scr-info-active')
    })
    var cityPicker = new IIInsomniaCityPicker({
        data: cityData,
        target: '#cityChoice',
        valType: 'k-v',
        hideCityInput: '#city',
        hideProvinceInput: '#province',
        callback: function(city_id){
        }
    });
    cityPicker.init();

    //提交表单
    $('.btn').click(function(e){
        e.preventDefault()
        //验证表单
        if(!validate()) {
            return false
        }
        //防止多次提交
        $('.btn').attr('disabled','').html('提交中.....')
        //金额(万)转化为(元)
        // $('#loan_amount').val($('#loan_amount').val()*10000)
        var data=$('#addForm').serializeArray()
        var pushUrl="/api/collect/tosave"
        $.post(pushUrl,data,function(data){
            // console.log(str)
            // var data=JSON.parse(str)
            // console.log(data)
            if (data.errcode==0) {
                alert('申请成功')
                $('.btn').removeAttr('disabled','').html('立即提交')
            }else{
                alert(data.errmsg)
                $('.btn').removeAttr('disabled','').html('立即提交')
            }
        })
    })

    //验证表单
    function validate () {
        var reg = /^1[3456789][0-9]{9}$/;

        if ($('#name').val()=='') {
            alert('请输入你的姓名')
            return false
        }
        if ($('#loan_amount').val()=='') {
            alert('请输入贷款金额')
            return false
        }
        if ($('#phone').val()=='') {
            alert('请输入您的联系方式')
            return false
        }
        if (!reg.test($('#phone').val())) {
            alert('请输入有效的11位手机号码')
            return false
        }
        if ($('#cityChoice').val()=='') {
            alert('请选择城市')
            return false
        }
        if ($('#sex').val()=='') {
            alert('请选择性别')
            return false
        }
        if ($('#profession').val()=='') {
            alert('请选择职业')
            return false
        }
        if ($('#birthday').val()=='') {
            alert('请选择生日')
            return false
        }
        if ($('#worklong').val()=='') {
            alert('请选择工作时长')
            return false
        }
        if ($('#month_income').val()=='') {
            alert('请选择月收入状况')
            return false
        }
        if ($('#credit_card').val()=='') {
            alert('请选择信用情况')
            return false
        }
        if ($('#is_house').val()=='') {
            alert('请选择房产情况')
            return false
        }
        if ($('#car').val()=='') {
            alert('请选择名下汽车情况')
            return false
        }
        if ($('#is_atom').val()=='') {
            alert('请选择是否使用过微粒贷')
            return false
        }
        if ($('#salary_modal').val()=='') {
            alert('请选择工资发放方式')
            return false
        }
        if ($('#policy').val()=='') {
            alert('请选择寿险保单情况')
            return false
        }
        if ($('#social_security').val()=='') {
            alert('请选择社保情况')
            return false
        }
        if ($('#accumulation_fund').val()=='') {
            alert('请选择公积金情况')
            return false
        }
        return true
    }
    //生日插件
    var currYear = new Date().getFullYear();
    $("#birthday").mobiscroll().date({
        theme: 'android-holo-light',
        mode: 'scroller',
        display: 'modal',
        lang: 'zh',
        dateFormat: 'yy-mm-dd',
        defaultValue: new Date(1992, 5, 15),
        startYear: currYear - 60,
        endYear: currYear - 18
    });

</script>

</body></html>