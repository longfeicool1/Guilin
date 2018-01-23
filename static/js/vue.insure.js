// var APIServer = 'http://linux-api.ubi001.com:10088/insure/';
// var WebSocketServer = 'http://192.168.64.2:2111';
var APIServer = 'http://dev-api.ubi001.com/insure/';
// var APIServer = 'http://dev.api.ubi001.com/insure/';
var WebSocketServer = 'http://112.74.95.105:2111';
var AID = 1;
var ACCESSTOKEN = '123456';
// {uid: 26774, token: "0LWWq0jNhCRk4XmyVWnKAAFxNiNbjpLV892oTRgwtQsw9atXVf9kntYs6Pu0b0j7",…}
var U = '26774';
var T = 'zrO2ircGv3FhKKRgG9ZUovX7MJ89tde4j6Br0cY8n7xMzqkB7TR7FqyROLtjHDim';
function ARG(data) {
    return $.extend(data, {'aid': AID, 'accessToken': ACCESSTOKEN});
}

// function ARG(data) {
//     return $.extend(data, {'u': U, 't': T});
// }

renderjson.set_icons('+', '-');
renderjson.set_show_to_level(2);

$(document).ready(function() {
    var modal = Vue.extend({
        template: '#modal',
        props: {
            show: {
              type: Boolean,
              required: true,
              twoWay: true
            },
            type: {
              type: Number,
              default: 1
            },
            timeout: {
              type: Number,
              default: 0
            },
            lock: {
              type: Boolean,
              default: false
            },
            style: {
              type: String,
              default: ""
            }
        },
        data: function() {
            return {
              t: null
            }
        },

        created: function() {
            // console.log("modal created")
        },

        methods: {

            // 倒计时
            countdown: function() {
              this.t = setTimeout(this.stopCountDown, this.timeout)
            },

            // 停止倒计时
            stopCountDown: function() {
              clearTimeout(this.t)
              this.show = false
            },

            // 背景点击
            bgClick: function() {
              if (!this.lock) {
                this.show = false
              }
            },

            // 阻止事件传播
            stopEvent: function(event) {
              event.preventDefault()
              event.stopPropagation()
            },
          },

        watch: {
            show: function(oldVal, newVal) {
              // console.log(newVal, this.timeout)
              if (newVal == false && this.timeout != 0) {
                this.countdown()
            }

            if (newVal == true) {
            if (this.t != null) {
              clearTimeout(this.t)
            }
            }
        }
    }});

    new Vue({
      el: '#vueapp',
      ready: function() {
        // console.log(1)
        
        if (this.area)
        {
            this.provider();
            // setTimeout(this.provider, 1000);
        }

        try {
            $('#cityselect').aaarea();
        } catch (e) {

        }
        
        var id = $('#qid').val();
        if (id > 0) {
            this.id = id;
            this.getOrderData();
        }

      },
      components: {
          modal: modal
      },

      data: function() {
        return {
                id: '',
                
                // area: '440300',
                // area_name: '深圳市',
                // carcard: '粤B',
                
                area: '',
                area_name: '',
                carcard: '',

                mobile: '',
                tax_type: true,
                c01_start: '',
                c51_start: '',
                vehicle_newcar: false,
                vehicle_engine: '',
                vehicle_vin: '',
                vehicle_register: '',
                vehicle_model: '',
                vehicle_id: '',
                vehicle_code: '',
                vehicle_transfer: '',
                vehicle_transferDate: '',
                owner_name: '',
                // owner_type: '',
                owner_id: '',
                // owner_birthday: '',
                // owner_sex: '',
                applicant_name: '',
                // applicant_type: '',
                applicant_id: '',
                // applicant_birthday: '',
                // applicant_sex: '',
                insurant_name: '',
                // insurant_type: '',
                insurant_id: '',
                // insurant_birthday: '',
                // insurant_sex: '',
                c01_duty_01: true,
                c01_duty_01_deduction: true,
                c01_duty_02: true,
                c01_duty_02_deduction: true,
                c01_duty_02_amount: 50,
                c01_duty_04: false,
                c01_duty_04_deduction: true,
                c01_duty_04_amount: 10000,
                c01_duty_05: false,
                c01_duty_05_deduction: true,
                c01_duty_05_amount: 10000,
                c01_duty_05_seat: 4,
                c01_duty_08: false,
                c01_duty_08_kind: 0,
                c01_duty_08_deduction: true,
                c01_duty_17: false,
                c01_duty_17_amount: 10000,
                c01_duty_17_deduction: true,
                c01_duty_41: false,
                c01_duty_41_deduction: true,
                c01_duty_03: false,
                c01_duty_03_deduction: true,
                c01_duty_18: false,
                c01_duty_18_deduction: true,
                c01_duty_42: false,
                c01_duty_43: false,
                
                vehicle_brand: '',
                vehicle_price: '',
                // vehicle_wave: '',
                vehicle_key: '',
                vehicle_market_date: '',
                quote_company: '',
                insure_company: '',
                insure_company_name: '',
                delivery_accept_name: '',
                delivery_accept_telephone: '',
                delivery_accept_province: '',
                delivery_accept_city: '',
                delivery_accept_town: '',
                delivery_accept_address: '',
                delivery_appointment_time: '',
                c01_type: 1,

                panel: {
                    quoteShow: false,
                    autoPoi: false,
                    autoAreaName: "",
                    autoAreaNumber: "",
                    autoPlateNum: "",
                    cityListLetter: [],
                    cityList: [],
                    letterCities: [],
                    msgModal: false,

                    search: '',

                    carModelList: [],
                    carModal: false,

                    sockets: {
                        quote: false,
                        insure: false,
                        pay: false,
                    },

                    quoteList: [],
                    insureList: [],
                    pay_url: '',
                    quoteCompanyList: [],
                    imageResult: '',
                    orcType: 0,
                    priceList: [],
                    companyList: [],
                    companys: [],

                    // 全选
                    companyAll: false,

                    applicant_equal: false,
                    insurant_equal: false,
                    delivery_equal: false,

                    agAreaCode: '440000'
                }
        };
      },
      // 方法集
      methods: {
        // 保险公司列表
        provider: function() {
            var resource = this.$resource(APIServer + 'resultSet/getProvider');
            resource.get(ARG({'area': this.area, 'channel': 'all'})).then(function (response) {
                this.panel.companyList = response.data.data;
                
                // for(var i in this.panel.companyList) {
                //     if (typeof this.panel.companyList[i] == 'Object') {
                //         this.panel[this.panel.companyList[i]['company']] = 0;
                //     }
                // }
            }, function (response) {
                this.alert('查询失败');
            });
        },

        // 信息提示
        alert: function(msg) {
            alert(msg);
        },

        // 关闭城市
        closeCity: function() {
            this.panel.msgModal = false;
        },

        // 定义位置
        setPlace: function(areaName, areaNumber, plateNum) {
            this.area = areaNumber;
            this.area_name = areaName;
            if (this.carcard.length <= 2) {
                this.carcard = plateNum;
            }
            // console.log(this.carcard)
            this.closeCity();

            // 重新查询保险提供商
            this.provider();
            // setTimeout(this.provider, 1000);
        },

        // 下一步点击事件
        next: function(eq) {
            this.setOrderData();
            $('#tabkv').find('li').eq(eq).find('a').click();
        },

        // A
        simpleQuery: function () {
            var resource = this.$resource(APIServer + 'result/simpleCreateOrder');
            resource.save(ARG({'area': this.area,'carcard': this.carcard, 'owner_name': this.owner_name})).then(function (response) {
                $('#js-a-result').html(renderjson(response.data.data));
            }, function (response) {
                this.alert('查询失败');
            });
        },

        // 图像预览
        imagePreview: function () {
            console.log('imagePreview')

            var file = document.getElementById("js-image-file").files[0];
            if(!/image\/\w+/.test(file.type)){  
                console.log("FILE TYPE:", file.type);
                this.alert("看清楚，这个需要jpg图片！");
                return false;  
            }

            var dataReader = new FileReader();
            var that = this;
            dataReader.readAsDataURL(file);  
            dataReader.onload = function(e){  
                var result = document.getElementById("result");
                //显示文件  
                $('#js-image-file-show').html('<img src="' + this.result +'" alt="" width="350px;"/>');  
                that.panel.imageContent = this.result
            }
        },

        // orc 识别
        orcQuery: function () {
            // var that = this;
            var file = document.getElementById("js-image-file").files[0];

            if(!/image\/\w+/.test(file.type)){  
                console.log("FILE TYPE:", file.type);
                this.alert("看清楚，这个需要jpg图片！");
                return false;  
            }
            
            // var binaryReader = new FileReader();
            // console.log('开始查询');
            //将文件以二进制形式读入页面  
            // binaryReader.readAsBinaryString(file);
            // binaryReader.onload = function(f){  
            //     var resource = that.$resource(APIServer + 'writer/orc');
            //     resource.save({'imageContent': this.result, 'imageType': that.panel.orcType, 'imageMode': 'jpg'}).then(function (response) {
            //         console.log(response)
            //     }, function (response) {
            //         that.alert('查询失败');
            //     });
            // }
            var start = this.panel.imageContent.indexOf('base64,') + 'base64,'.length;
            var end = this.panel.imageContent.length;
            var imageContent = this.panel.imageContent.slice(start, end);
            // return;
            var resource = this.$resource(APIServer + 'result/orc');
            // var resource = this.$resource('http://dev-dsapi.ubi001.com/v1/upload/recognizeImage');
            resource.save(ARG({'imageContent': imageContent, 'imageType': this.panel.orcType, 'imageMode': 'jpg'})).then(function (response) {
                // console.log(typeof response.body);
                //this.panel.imageResult = renderjson(response.data.data);
                $('#imageResult').html(renderjson(response.data.data));
                // console.log(response.body);
            }, function (response) {
                this.alert('查询失败');
            });
        },

        // 任务查询
        taskQuery: function () {
            if (typeof this.panel.taskId != 'undefined' && this.panel.taskId > 0 && typeof this.panel.prvId != 'undefined' && this.panel.prvId > 0) {
                var resource = this.$resource(APIServer + 'result/queryTask');
                resource.save(ARG({'taskId': this.panel.taskId, 'prvId': this.panel.prvId})).then(function (response) {
                    // console.log(typeof response.body);
                    //this.panel.imageResult = renderjson(response.data.data);
                    $('#js-query-result').html(renderjson(response.data.data));
                    // console.log(response.body);
                }, function (response) {
                    this.alert('查询失败');
                }); 
            } else {
                this.alert('任务ID和供应商ID不能为空');
            }

        },

        // 搜索城市
        searchCity: function (obj) {
            // console.log(this.panel.search);

            if (this.panel.search.length == 0) {
                return;
            }

            var cityListLetter = [];
            var letterCities = [];
            if (this.panel.cityList) {
                for (var i = 0; i < this.panel.cityList.length; i++) {
                  var temp = this.panel.cityList[i]
                  var templetter = temp.shortName.substr(0, 1).toUpperCase()

                  if (temp.areaName.indexOf(this.panel.search) == -1) {
                    continue;
                  }

                  if (typeof(letterCities[templetter]) != "undefined") {
                    letterCities[templetter].push(temp)
                  } else {
                    letterCities[templetter] = [temp]
                  }

                  if(cityListLetter.indexOf(templetter) == -1) {
                    cityListLetter.push(templetter)
                  }
                }
            }
            cityListLetter.sort()
            this.panel.letterCities = letterCities
            this.panel.cityListLetter = cityListLetter
        },

        // 切换城市
        changeCity: function(obj) {
            if (this.panel.cityList.length == 0) {
              var resource = this.$resource(APIServer + 'resultSet/getCityList')
              var cityListLetter = []
              var letterCities = []
              this.panel.loadingModal = true
              resource.get(ARG({})).then(function (response) {
                if (response.data.errcode == 0) {
                  this.panel.loadingModal = false
                  this.panel.cityList = response.data.data
                  if (this.panel.cityList) {
                    for (var i = 0; i < this.panel.cityList.length; i++) {
                      var temp = this.panel.cityList[i]
                      var templetter = temp.shortName.substr(0, 1).toUpperCase()
                      if (typeof(letterCities[templetter]) != "undefined") {
                        letterCities[templetter].push(temp)
                      } else {
                        letterCities[templetter] = [temp]
                      }

                      if(cityListLetter.indexOf(templetter) == -1) {
                        cityListLetter.push(templetter)
                      }
                    }
                    
                  }
                  // console.log(cityListLetter)
                  cityListLetter.sort()
                  this.panel.letterCities = letterCities
                  this.panel.cityListLetter = cityListLetter
                  // $(obj).dialog({id:'mydialog1', target: "#cityChoose", title:'城市选择'});
                  this.panel.msgModal = true;
                }
              }, function(response) {
                this.alert('读取地址失败');
              });
            }
            else {
                this.panel.msgModal = true;
            }
        },

        // 查车牌自动填充
        autoSetCarData: function() {
            var resource = this.$resource(APIServer + 'resultSet/autoFillCarData')
            resource.get(ARG({id: this.id, area: this.area, carcard: this.carcard, owner_name: this.owner_name})).then(function (response) {
            if (response.data.errcode == 0) {
              if (response.data.data) {
                for (var i in response.data.data) {
                    if (typeof this[i] != 'undefined') {
                        this[i] = response.data.data[i];
                    }
                }

                // this.vehicle_code = response.data.data.autoModelCode
                // this.vehicle_engine = response.data.data.engineNo
                // this.vehicle_model = response.data.data.autoModelName
                // this.vehicle_vin = response.data.data.vehicleFrameNo
                // this.vehicle_register = response.data.data.firstRegisterDate
              }
            }
            }, function(response) {
                console.log('填充失败');
            });
        },

        // 查车型选择
        searchCar: function() {
            var resource = this.$resource(APIServer + 'resultSet/search')
            resource.get(ARG({vehicle_model: this.vehicle_model})).then(function (response) {
                if (response.data.errcode == 0 && response.data.data) {
                // console.log(response.data.data)
                    this.panel.carModelList = response.data.data
                    this.panel.carModal = true
                } else{
                    this.alert(response.data.errmsg)
                // console.log('填充失败');
                }
            }, function(response) {
                console.log('填充失败');
            });
        },

        // 选中车型
        changeCar: function(vehicleId) {
            // this.vehicle_code = code
            // this.vehicle_id = vehicleId
            for (var i in this.panel.carModelList) {
                if (this.panel.carModelList[i]['vehicle_id'] == vehicleId) {
                    for (var j in this.panel.carModelList[i]) {
                        this[j] = this.panel.carModelList[i][j];
                    }
                }
            }
            this.panel.carModal = false
        },

        // 创建订单
        createOrder: function() {
            var resource = this.$resource(APIServer + 'writer/createOrder')
            resource.get(ARG({carcard: this.carcard})).then(function (response) {
                if (response.data.errcode == 0 && response.data.data) {
                    // console.log(response.data.data)
                    this.id = response.data.data.order_id
                    // this.panel.carModal = true
                    this.listenStart();
                } else{
                    this.alert('无法创建订单')
                // console.log('填充失败');
                }
            }, function(response) {
                console.log('无法创建订单#2');
            });
        },

        // 设定数据
        setOrderData: function(CB) {
          // console.log(this)
          var data = {}

          for (var i in this._data) {
            // console.log(typeof this._data[i])
            if (i == 'panel') {
                continue;
            }

            if(typeof this._data[i] == 'boolean') {
                data[i] = this._data[i] == true ? 1 : 0;
            } else if (i == 'quote_company' && this._data['panel']['companys'].length > 0) { // 组合选择的保险公司
                data[i] = this._data['panel']['companys'].join(',');
                console.log('保险公司列表', i, this._data['panel']['companys'].join(','))
            }
            else {
                data[i] = this._data[i];
            }
          }
          var resource = this.$resource(APIServer + 'writer/setOrder')
            resource.save(ARG(data)).then(function (response) {
                if (response.data.errcode == 0 && response.data.data) {
                    if(typeof CB == 'object' || typeof CB == 'function') {
                        CB()
                    }
                } else{
                    this.alert('无法保存订单')
                }
            }, function(response) {
                console.log('无法保存订单#2');
            });
        },

        // 查询订单数据
        getOrderData: function() {
            var resource = this.$resource(APIServer + 'resultSet/getDetail');

            if (!this.id) {
                return this.alert('请填写订单ID');
            }
            this.panel.priceList = [];
            this.panel.quoteList = [];
            this.panel.quoteCompanyList = []
            this.panel.insureList = []
            resource.get(ARG({id: this.id})).then(function (response) {
                if (response.data.errcode == 0 && response.data.data) {


                    for (var i in response.data.data) {
                        // console.log(i, response.data.data[i])
                        // console.log(i, typeof response.data.data[i], response.data.data[i])
                        if (typeof this[i] != 'undefined') {
                            if (response.data.data[i] == '0') {

                                this[i] = false;
                            } else if (response.data.data[i] == '1') {
                                this[i] = true;
                            } else {
                                this[i] = response.data.data[i];
                            }
                        }
                    }
                    try {

                        // 给地址选择框默认值 
                        $('#loc_province').val(this.delivery_accept_province);
                        $('#loc_province').change();
                        
                        $('#loc_city').val(this.delivery_accept_city);
                        $('#loc_city').change();
                        $('#loc_city').val(this.delivery_accept_town);
                        $('#loc_town').change();
                    } catch (e) {

                    }

                    this.listenStart();
                    // setTimeout(this.provider, 1000);
                    this.provider();
                    // console.log('地址');
                    this.quoteListQuery();
                    this.insureListQuery();
                    // this.payAddrListQuery();
                } else{
                    this.alert(response.data.errmsg);
                // console.log('填充失败');
                }
            }, function(response) {
                this.alert('无法保存订单#2');
            });
        },

        // 执行询价
        doQuote: function() {
            // console.log(this.id);
            var resource = this.$resource(APIServer + 'writer/doQuote');

            this.quote_company = []
            for(var i in this.panel.companyList)
            {
                this.quote_company.push(this.panel.companyList[i].company);
            }

            this.quote_company = this.quote_company.join(',');

            if (!this.id) {
                return this.alert('请填写订单ID');
            }
            if (this.quote_company.length == 0) {
                return this.alert('请选择保险公司');
            }

            var self = this;
            // {id: this.id, channel: 'sys', company: 'FDBX'}
            resource.save(ARG({id: this.id})).then(function (response) {
                if (response.data.errcode == 0 && response.data.data) {
                    this.alert('报价开始');
                    // this.websocket(this.id, 'quote', function(msg) {
                    //     var data = JSON.parse(JSON.parse(msg));
                    //     // console.log(data);
                    //     if (self.panel.quoteCompanyList.indexOf(data['company']) == -1) {
                    //       self.panel[data['company']] = data['data'].insure_total_price;
                    //       self.panel.quoteList.push(data);
                    //       console.log('quoteList');
                    //       console.log(self.panel.quoteList);
                    //       self.panel.quoteCompanyList.push(data['company']);
                    //     }
                    // });
                } else{
                    this.alert(response.data.errmsg);
                }
            }, function(response) {
                this.alert('无法保存订单#2');
            });
        },

        // 执行投保
        doInsure: function() {
            var resource = this.$resource(APIServer + 'writer/doInsure');

            if (!this.id) {
                return this.alert('请填写订单ID');
            }

            var self = this;
            self.panel.insureList = [];
            resource.save(ARG({id: this.id})).then(function (response) {
                if (response.data.errcode == 0 && response.data.data) {
                    this.alert('投保开始');
                } else{
                    this.alert(response.data.errmsg);
                }
            }, function(response) {
                this.alert('无法保存订单#2');
            });
        },

        // 执行投保
        doPay: function() {
            var resource = this.$resource(APIServer + 'writer/doPay');

            if (!this.id) {
                return this.alert('请填写订单ID');
            }

            var self = this;
            
            resource.save(ARG({id: this.id})).then(function (response) {
                if (response.data.errcode == 0 && response.data.data) {
                    // this.alert('获取支付地址');
                    this.panel.pay_url = response.data.data['pay_url'];
                    console.log('获取支付地址', response.data);
                    if (this.panel.pay_url.length > 0) {
                        $('#qrcode').html('');
                        $('#qrcode').qrcode(this.panel.pay_url);
                    }
                } else{
                    this.alert(response.data.errmsg);
                }
            }, function(response) {
                this.alert('无法保存订单#2');
            });
        },

        // 选择投保公司
        setInsureCompany: function(company_name, company) {
            console.log('选择投保公司', company, company_name);
            this.insure_company = company;
            this.insure_company_name = company_name;
            this.panel.quoteShow = false;
            this.panel.insureList = [];
            // $('#tabkv').find('li').eq(2).find('a').click();
        },

        // 保存当前数据并询价
        saveAndDoQuote: function() {
            // 清空查询数据
            this.panel.quoteList = []
            this.panel.quoteCompanyList = []
            this.setOrderData(this.doQuote);
            this.panel.quoteShow = true;
        },
        
        // 保存当前数据并投保
        saveAndDoInsure: function() {
            // 清空查询数据
            // this.panel.quoteList = []
            // this.panel.quoteCompanyList = []
            this.setOrderData(this.doInsure);
        },

        // 保存当前数据并投保
        saveAndDoPay: function() {
            // 清空查询数据
            // this.panel.quoteList = []
            // this.panel.quoteCompanyList = []
            this.setOrderData(this.doPay);
        },

        listenStart: function() {
            var self = this;
            if (this.id) {
                this.websocket(this.id, function(msg) {
                    console.log('收到数据:', msg);
                    var data = JSON.parse(JSON.parse(msg));
                    console.log('data start');
                    console.log(data);
                    console.log(data['type']);
                    console.log('data end');
                    switch(data['type']) {
                        case 'quote':
                            if (self.panel.quoteCompanyList.indexOf(data['company']) == -1) {
                                self.panel.priceList[data['company']] = data['data'].insure_total_price;
                                self.panel.quoteList.push(data);
                                self.panel.quoteShow = true;
                                self.panel.quoteCompanyList.push(data['company']);
                            }
                        break;

                        case 'checkInsure':
                        case 'insure':
                            self.panel.insureList = data;
                        break;

                        case 'pay':
                            self.payAddrList();
                            $('#js-pay-result').html(renderjson(data));
                        break;

                        case 'check':
                        break;

                        case 'msg':
                        break;
                    }
                });
            }
            
        },
        // websocket: function(id, type, CB) {
        websocket: function(id, CB) {
            if (this.panel.sockets[type] == true) {
                return false;
            }

            // if (['quote', 'insure', 'pay'].indexOf(type) == -1) {
            //     return false;
            // }
            var type = 'order';
            var sockets = $('#sockets-car-'+type).data('socket');
            // var sockets = '';
            if (!sockets) {
                // 连接服务端
                sockets = io(WebSocketServer, {multiplex: false});
                // sockets = io(WebSocketServer);
                sockets.on('connect', function () {
                    sockets.emit('login', type + id);
                    console.log('websocket登录:',  type + id);
                    console.log(type + id);
                });
                // 后端推送来消息时
                sockets.on('new_msg', function (msg) {
                    console.log('websocket收到数据:');
                    if (typeof CB == 'object' || typeof CB == 'function') {
                        CB(msg);
                    }
                });
            } else {
                // console.log('websocket登录#2:', type + id);
                sockets.emit('login', type + id);
            }

            this.panel.sockets[type] = true;
        },

        quoteListQuery: function() {
            this.panel.quoteList = []
            this.panel.quoteCompanyList = []
            var resource = this.$resource(APIServer + 'resultSet/quoteList')
            resource.get(ARG({id: this.id})).then(function (response) {
                if (response.data.errcode == 0 && response.data.data) {
                    // $('#js-quote-query-result').html(renderjson(response.data.data));
                    for (var i in response.data.data) {
                        if (isNaN(i)) {
                            continue;
                        }

                        try { 
                            this.panel[response.data.data[i]['company']] = response.data.data[i]['data']['insure_total_price'] / 100;
                            // console.log(response.data.data[i]['company'], response.data.data[i]['data']['insure_total_price']);
                            this.panel.quoteList.push(response.data.data[i]);
                        } catch(e) {

                        }
                    }
                } else{
                    this.alert('无法创建订单')
                // console.log('填充失败');
                }
            }, function(response) {
                console.log('无法创建订单#2');
            });
        },
        insureListQuery: function() {
            this.panel.insureList = [];
            var resource = this.$resource(APIServer + 'resultSet/insureList')
            resource.get(ARG({id: this.id})).then(function (response) {
                if (response.data.errcode == 0 && response.data.data) {
                    // $('#js-quote-query-result').html(renderjson(response.data.data));

                    this.panel.insureList = response.data.data;
                    
                } else{
                    this.alert('无法创建订单')
                // console.log('填充失败');
                }
            }, function(response) {
                console.log('无法创建订单#2');
            });
        },
        payAddrListQuery: function() {
            this.panel.insureList = [];
            var resource = this.$resource(APIServer + 'resultSet/payAddrList')
            resource.get(ARG({id: this.id})).then(function (response) {
                if (response.data.errcode == 0 && response.data.data) {
                    // $('#js-quote-query-result').html(renderjson(response.data.data));
                    try {
                        this.panel.pay_url = response.data.data['pay_url'];
                        if (this.panel.pay_url.length > 0) {
                            $('#qrcode').qrcode(this.panel.pay_url);
                        }
                    } catch (e) {

                    }
                    // console.log(this.panel.pay_url)
                } else{
                    this.alert('无法创建订单')
                // console.log('填充失败');
                }
            }, function(response) {
                console.log('无法创建订单#2');
            });
        },
        agAreaCodeQuery: function() {
            var resource = this.$resource(APIServer + 'result/getInsureArea')
            resource.get(ARG({areaCode: this.panel.agAreaCode})).then(function (response) {
                if (response.data.errcode == 0 && response.data.data) {
                    $('#js-agAreaCode-query-result').html(renderjson(response.data.data));
                } else{
                    this.alert('无法创建订单')
                // console.log('填充失败');
                }
            }, function(response) {
                console.log('无法创建订单#2');
            });
        },
      },

      
      // 监控事件
        watch: {
           'panel.companyAll': function (val, oldVal) {
              // console.log('new: %s, old: %s', val, oldVal)
              if (val == true)
              {
                for(var i in this.panel.companyList)
                {
                    this.panel.companys.push(this.panel.companyList[i].company);
                }
              }
              else
              {
                this.panel.companys = [];
              }
              // console.log(this.panel.companys);
            },
           'panel.applicant_equal': function (val, oldVal) {
              // console.log('new: %s, old: %s', val, oldVal)
              if (val == true)
              {
                this.applicant_id = this.owner_id;
                this.applicant_name = this.owner_name;
              }
              
              // console.log(this.panel.companys);
            },
            'panel.insurant_equal': function (val, oldVal) {
              // console.log('new: %s, old: %s', val, oldVal)
              if (val == true)
              {
                this.insurant_id = this.owner_id;
                this.insurant_name = this.owner_name;
              }
              
            },

            'panel.delivery_equal': function (val, oldVal) {
              // console.log('new: %s, old: %s', val, oldVal)
              if (val == true)
              {
                
                this.delivery_accept_name = this.owner_name;
                this.delivery_accept_telephone = this.mobile;
              }
              
            },
        }
    });
});
