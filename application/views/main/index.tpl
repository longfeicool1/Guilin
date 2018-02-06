<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>鼎然科技</title>
    <meta name="Keywords" content="UBI后台管理"/>
    <meta name="Description" content="UBI后台管理"/>
    <!-- bootstrap - css -->
    <link href="/static/BJUI/themes/css/bootstrap.css" rel="stylesheet">
    <!-- core - css -->
    <link href="/static/BJUI/themes/css/style.css" rel="stylesheet">
    <link href="/static/BJUI/themes/blue/core.css" id="bjui-link-theme" rel="stylesheet">
    <!-- plug - css -->
    <link href="/static/BJUI/plugins/kindeditor_4.1.10/themes/default/default.css" rel="stylesheet">
    <link href="/static/BJUI/plugins/kindeditor_4.1.10/plugins/code/prettify.css" rel="stylesheet">
    <link href="/static/BJUI/plugins/colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <link href="/static/BJUI/plugins/niceValidator/jquery.validator.css" rel="stylesheet">
    <link href="/static/BJUI/plugins/bootstrapSelect/bootstrap-select.css" rel="stylesheet">
    <link href="/static/BJUI/themes/css/FA/css/font-awesome.min.css" rel="stylesheet">
    <!-- datatables -->
    <!--<link rel="stylesheet" type="text/css" href="/static/datatables/Bootstrap-3.3.6/css/bootstrap.min.css"/>*}}-->
    <link rel="stylesheet" type="text/css" href="/static/datatables/DataTables-1.10.11/css/dataTables.bootstrap.min.css"/>
    <!--<link rel="stylesheet" type="text/css" href="/static/datatables/AutoFill-2.1.1/css/autoFill.bootstrap.css"/>
   <link rel="stylesheet" type="text/css" href="/static/datatables/Buttons-1.1.2/css/buttons.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="/static/datatables/ColReorder-1.3.1/css/colReorder.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="/static/datatables/FixedColumns-3.2.1/css/fixedColumns.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="/static/datatables/FixedHeader-3.1.1/css/fixedHeader.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="/static/datatables/KeyTable-2.1.1/css/keyTable.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="/static/datatables/Responsive-2.0.2/css/responsive.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="/static/datatables/RowReorder-1.1.1/css/rowReorder.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="/static/datatables/Scroller-1.4.1/css/scroller.bootstrap.min.css"/>-->
    <link rel="stylesheet" type="text/css" href="/static/datatables/Select-1.1.2/css/select.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="/static/js/select2/select2.min.css"/>

    <!-- vue -->
    <!--<script src="/static/js/vue.min.js"></script>
    <script src="/static/js/vue-resource.js"></script>-->
    <script src='//cdn.bootcss.com/socket.io/1.3.7/socket.io.js'></script>

    <!--[if lte IE 7]>
    <link href="/static/BJUI/themes/css/ie7.css" rel="stylesheet">
    <![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lte IE 9]>
    <script src="/static/BJUI/other/html5shiv.min.js"></script>
    <script src="/static/BJUI/other/respond.min.js"></script>
    <![endif]-->
    <!-- jquery -->
    <script src="/static/BJUI/js/jquery-1.11.3.min.js"></script>
    <script src="/static/js/jquery.qrcode.min.js"></script>
    <script src="/static/BJUI/js/jquery.cookie.js"></script>
    <script src="/static/js/moment.min.js"></script>
    <script src="/static/jplayer/jquery.jplayer.min.js"></script>
    <!--[if lte IE 9]>
    <script src="/static/BJUI/other/jquery.iframe-transport.js"></script>
    <![endif]-->
    <!-- BJUI.all 分模块压缩版 -->
    <script src="/static/BJUI/js/bjui-all.min.js"></script>
    <!-- plugins -->
    <!-- swfupload for uploadify && kindeditor -->
    <script src="/static/BJUI/plugins/swfupload/swfupload.js"></script>
    <!-- kindeditor -->
    <script src="/static/BJUI/plugins/kindeditor_4.1.10/kindeditor-all.min.js"></script>
    <script src="/static/BJUI/plugins/kindeditor_4.1.10/lang/zh_CN.js"></script>
    <script src="/static/BJUI/plugins/kindeditor_4.1.10/plugins/code/prettify.js"></script>
    <!-- colorpicker -->
    <script src="/static/BJUI/plugins/colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <!-- ztree -->
    <script src="/static/BJUI/plugins/ztree/jquery.ztree.all-3.5.js"></script>
    <!-- nice validate -->
    <script src="/static/BJUI/plugins/niceValidator/jquery.validator.js"></script>
    <script src="/static/BJUI/plugins/niceValidator/jquery.validator.themes.js"></script>
    <!-- bootstrap plugins -->
    <script src="/static/BJUI/plugins/bootstrap.min.js"></script>
    <script src="/static/BJUI/plugins/bootstrapSelect/bootstrap-select.min.js"></script>
    <script src="/static/BJUI/plugins/bootstrapSelect/defaults-zh_CN.min.js"></script>

    <script src="/static/js/select2/select2.min.js"></script>
    <script src="/static/BJUI/plugins/bootstrapSelect/defaults-zh_CN.min.js"></script>
    <!-- datatables -->
    <!--<script type="text/javascript" src="/static/datatables/jQuery-2.2.0/jquery-2.2.0.min.js"></script>
    <script type="text/javascript" src="/static/datatables/Bootstrap-3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/static/datatables/JSZip-2.5.0/jszip.min.js"></script>
    <script type="text/javascript" src="/static/datatables/pdfmake-0.1.18/build/pdfmake.min.js"></script>
    <script type="text/javascript" src="/static/datatables/pdfmake-0.1.18/build/vfs_fonts.js"></script>-->
    <script type="text/javascript" src="/static/datatables/DataTables-1.10.11/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="/static/datatables/DataTables-1.10.11/js/dataTables.bootstrap.min.js"></script>
    <!--<script type="text/javascript" src="/static/datatables/AutoFill-2.1.1/js/dataTables.autoFill.min.js"></script>
    <script type="text/javascript" src="/static/datatables/AutoFill-2.1.1/js/autoFill.bootstrap.min.js"></script>
    <script type="text/javascript" src="/static/datatables/Buttons-1.1.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="/static/datatables/Buttons-1.1.2/js/buttons.bootstrap.min.js"></script>
    <script type="text/javascript" src="/static/datatables/Buttons-1.1.2/js/buttons.colVis.min.js"></script>
    <script type="text/javascript" src="/static/datatables/Buttons-1.1.2/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="/static/datatables/Buttons-1.1.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="/static/datatables/Buttons-1.1.2/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="/static/datatables/ColReorder-1.3.1/js/dataTables.colReorder.min.js"></script>
    <script type="text/javascript" src="/static/datatables/FixedColumns-3.2.1/js/dataTables.fixedColumns.min.js"></script>
    <script type="text/javascript" src="/static/datatables/FixedHeader-3.1.1/js/dataTables.fixedHeader.min.js"></script>
    <script type="text/javascript" src="/static/datatables/KeyTable-2.1.1/js/dataTables.keyTable.min.js"></script>
    <script type="text/javascript" src="/static/datatables/Responsive-2.0.2/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="/static/datatables/Responsive-2.0.2/js/responsive.bootstrap.min.js"></script>
    <script type="text/javascript" src="/static/datatables/RowReorder-1.1.1/js/dataTables.rowReorder.min.js"></script>
    <script type="text/javascript" src="/static/datatables/Scroller-1.4.1/js/dataTables.scroller.min.js"></script>
    -->
    <script type="text/javascript" src="/static/datatables/Select-1.1.2/js/dataTables.select.min.js"></script>
    <!-- icheck -->
    <script src="/static/BJUI/plugins/icheck/icheck.min.js"></script>
    <!-- dragsort -->
    <script src="/static/BJUI/plugins/dragsort/jquery.dragsort-0.5.1.min.js"></script>
    <!-- HighCharts -->
    <!--     <script src="/static/BJUI/plugins/highcharts/highcharts.js"></script>
        <script src="/static/BJUI/plugins/highcharts/highcharts-3d.js"></script>
        <script src="/static/BJUI/plugins/highcharts/themes/gray.js"></script> -->

    <!--<script src="http://cdn.hcharts.cn/highcharts/highcharts.js"></script>
    <script src="http://cdn.hcharts.cn/highcharts/highcharts-3d.js"></script>-->


    <!-- ECharts -->
    <!-- <script src="/static/BJUI/plugins/echarts/echarts.js"></script>-->
      <script src="/static/js/echarts/echarts.common.min.js"></script>
      <script src="/static/js/echarts/myecharts.js"></script>
    <!-- other plugins -->
    <script src="/static/BJUI/plugins/other/jquery.autosize.js"></script>
    <link href="/static/BJUI/plugins/uploadify/css/uploadify.css" rel="stylesheet">
    <script src="/static/BJUI/plugins/uploadify/scripts/jquery.uploadify.min.js"></script>
    <script src="/static/BJUI/plugins/download/jquery.fileDownload.js"></script>
    <script src="/static/js/phone/mine.js"></script>


    <!-- init -->
    <script type="text/javascript">


        $(function () {
            BJUI.init({
                JSPATH: '/static/BJUI/',         //[可选]框架路径
                PLUGINPATH: '/static/BJUI/plugins/', //[可选]插件路径
                loginInfo: {url: 'login_timeout.html', title: '登录', width: 400, height: 200}, // 会话超时后弹出登录对话框
                statusCode: {ok: 200, error: 300, timeout: 301}, //[可选]
                ajaxTimeout: 1000000, //[可选]全局Ajax请求超时时间(毫秒)
                pageInfo: {
                    total: 'total',
                    pageCurrent: 'pageCurrent',
                    pageSize: 'pageSize',
                    orderField: 'orderField',
                    orderDirection: 'orderDirection'
                }, //[可选]分页参数
                alertMsg: {displayPosition: 'topcenter', displayMode: 'slide', alertTimeout: 3000}, //[可选]信息提示的显示位置，显隐方式，及[info/correct]方式时自动关闭延时(毫秒)
                keys: {statusCode: 'statusCode', message: 'message'}, //[可选]
                ui: {
                    windowWidth: 0,    //框架可视宽度，0=100%宽，> 600为则居中显示
                    showSlidebar: true, //[可选]左侧导航栏锁定/隐藏
                    clientPaging: true, //[可选]是否在客户端响应分页及排序参数
                    overwriteHomeTab: false //[可选]当打开一个未定义id的navtab时，是否可以覆盖主navtab(我的主页)
                },
                debug: true,    // [可选]调试模式 [true|false，默认false]
                theme: 'sky' // 若有Cookie['bjui_theme'],优先选择Cookie['bjui_theme']。皮肤[五种皮肤:default, orange, purple, blue, red, green]
            })

            // main - menu
            $('#bjui-accordionmenu')
                .collapse()
                .on('hidden.bs.collapse', function (e) {
                    $(this).find('> .panel > .panel-heading').each(function () {
                        var $heading = $(this), $a = $heading.find('> h4 > a')

                        if ($a.hasClass('collapsed')) $heading.removeClass('active')
                    })
                })
                .on('shown.bs.collapse', function (e) {
                    $(this).find('> .panel > .panel-heading').each(function () {
                        var $heading = $(this), $a = $heading.find('> h4 > a')

                        if (!$a.hasClass('collapsed')) $heading.addClass('active')
                    })
                })

            $(document).on('click', 'ul.menu-items > li > a', function (e) {
                var $a = $(this), $li = $a.parent(), options = $a.data('options').toObj()
                var onClose = function () {
                    $li.removeClass('active')
                }
                var onSwitch = function () {
                    $('#bjui-accordionmenu').find('ul.menu-items > li').removeClass('switch')
                    $li.addClass('switch')
                }

                $li.addClass('active')
                if (options) {
                    options.url = $a.attr('href')
                    options.onClose = onClose
                    options.onSwitch = onSwitch
                    if (!options.title) options.title = $a.text()

                    if (!options.target)
                        $a.navtab(options)
                    else
                        $a.dialog(options)
                }

                e.preventDefault()
            })

            //时钟
            var today = new Date(), time = today.getTime()
            $('#bjui-date').html(today.formatDate('yyyy/MM/dd'))
            setInterval(function () {
                today = new Date(today.setSeconds(today.getSeconds() + 1))
                $('#bjui-clock').html(today.formatDate('HH:mm:ss'))
            }, 1000)
        })

        //菜单-事件
        function MainMenuClick(event, treeId, treeNode) {
            event.preventDefault()

            if (treeNode.isParent) {
                var zTree = $.fn.zTree.getZTreeObj(treeId)

                zTree.expandNode(treeNode, !treeNode.open, false, true, true)
                return
            }

            if (treeNode.target && treeNode.target == 'dialog')
                $(event.target).dialog({id: treeNode.tabid, url: treeNode.url, title: treeNode.name})
            else
                $(event.target).navtab({
                    id: treeNode.tabid,
                    url: treeNode.url,
                    title: treeNode.name,
                    fresh: treeNode.fresh,
                    external: treeNode.external
                })
        }

    </script>
    <style type="text/css">
        .bjui-pageContent{padding: 0px}
    </style>
</head>
<body>
<!--[if lte IE 7]>
<div id="errorie">
    <div>
        您还在使用老掉牙的IE，正常使用系统前请升级您的浏览器到 IE8以上版本
        <a target="_blank" href="http://windows.microsoft.com/zh-cn/internet-explorer/ie-8-worldwide-languages">点击升级</a>&nbsp;&nbsp;
        强烈建议您更改换浏览器：<a href="http://down.tech.sina.com.cn/content/40975.html" target="_blank">谷歌 Chrome</a>
    </div>
</div>
<![endif]-->
<div id="bjui-window">
    <header id="bjui-header">
        <div class="bjui-navbar-header">
            <button type="button" class="bjui-navbar-toggle btn-default" data-toggle="collapse"
                    data-target="#bjui-navbar-collapse">
                <i class="fa fa-bars"></i>
            </button>
            <a class="bjui-navbar-logo" href="#"><img src="/static/images/logo.png"></a>
        </div>
        <nav id="bjui-navbar-collapse">
            <ul class="bjui-navbar-right">
                <li>
                    <label id="s" class="control-label x200"
                           style="padding: 8px; color: #fff;">欢迎您：{{$userinfo['username']}}</label>
                </li>
                <li class="datetime">
                    <div><span id="bjui-date"></span> <span id="bjui-clock"></span></div>
                </li>
                <!-- <li><a href="#">消息 <span class="badge">4</span></a></li> -->
                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">我的账户 <span
                                class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="/manage/account/editPassMe" data-toggle="dialog" data-id="changepwd_page"
                               data-mask="true" data-width="400" data-height="260">&nbsp;<span
                                        class="glyphicon glyphicon-lock"></span> 修改密码&nbsp;</a></li>
                        <li><a href="/main/homePage" data-toggle="dialog">&nbsp;<span
                                        class="glyphicon glyphicon-user"></span> 我的资料</a></li>
                        <li class="divider"></li>
                        <li><a href="/login/loginOut" class="red">&nbsp;<span class="glyphicon glyphicon-off"></span>
                                注销登陆</a></li>
                    </ul>
                </li>
                <!-- <li><a href="index.html" title="切换为列表导航(窄版)" style="background-color:#ff7b61;">列表导航栏(窄版)</a></li> -->
                <li class="dropdown"><a href="#" class="dropdown-toggle theme blue" data-toggle="dropdown" title="切换皮肤"><i
                                class="fa fa-tree"></i></a>
                    <ul class="dropdown-menu" role="menu" id="bjui-themes">
                        <li><a href="javascript:;" class="theme_default" data-toggle="theme" data-theme="default">&nbsp;<i
                                        class="fa fa-tree"></i> 黑白分明&nbsp;&nbsp;</a></li>
                        <li><a href="javascript:;" class="theme_orange" data-toggle="theme" data-theme="orange">&nbsp;<i
                                        class="fa fa-tree"></i> 橘子红了</a></li>
                        <li><a href="javascript:;" class="theme_purple" data-toggle="theme" data-theme="purple">&nbsp;<i
                                        class="fa fa-tree"></i> 紫罗兰</a></li>
                        <li class="active"><a href="javascript:;" class="theme_blue" data-toggle="theme"
                                              data-theme="blue">&nbsp;<i class="fa fa-tree"></i> 天空蓝</a></li>
                        <li><a href="javascript:;" class="theme_green" data-toggle="theme" data-theme="green">&nbsp;<i
                                        class="fa fa-tree"></i> 绿草如茵</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="bjui-hnav">
        <!-- <button type="button" class="bjui-hnav-toggle btn-default" data-toggle="collapse" data-target="#bjui-hnav-navbar">
            <i class="fa fa-bars"></i>
        </button> -->
        <ul id="bjui-hnav-navbar">
            {{foreach $item as $v}}
            <li><a href="javascript:;" data-toggle="slidebar"><i class="fa fa-{{$v['font']}}"></i> {{$v['rule_title']}}</a>
                <div class="items hide" data-noinit="true">
                    <ul id="bjui-hnav-tree{{$v['id']}}" class="ztree ztree_main" data-toggle="ztree" data-on-click="MainMenuClick" data-expand-all="true" data-noinit="true">
                        {{foreach $v['child'] as $vv}}
                            <li data-id="{{$vv['id']}}" data-pid="{{$vv['parent_id']}}" data-url="{{$vv['url']}}" data-faicon="{{if !empty($vv['font'])}}{{$vv['font']}}{{/if}}" data-tabid="{{$vv['action_name']}}">{{$vv['rule_title']}}</li>
                        {{/foreach}}
                    </ul>
                </div>
            </li>
            {{/foreach}}
        </ul>
    </div>
    </header>
    <div id="bjui-container">
        <div id="bjui-leftside">
            <div id="bjui-sidebar-s">
                <div class="collapse"></div>
            </div>
            <div id="bjui-sidebar">
                <div class="toggleCollapse"><h2><i class="fa fa-bars"></i> 导航栏 <i class="fa fa-bars"></i></h2><a
                            href="javascript:;" class="lock"><i class="fa fa-lock"></i></a></div>
                <div class="panel-group panel-main" data-toggle="accordion" id="bjui-accordionmenu"
                     data-heightbox="#bjui-sidebar" data-offsety="26">
                </div>
            </div>
        </div>
        <div id="bjui-navtab" class="tabsPage">
            <div class="tabsPageHeader">
                <div class="tabsPageHeaderContent">
                    <ul class="navtab-tab nav nav-tabs">
                        <li data-url="/main/homePage"><a href="javascript:;"><span><i class="fa fa-home"></i> #maintab#</span></a>
                        </li>
                    </ul>
                </div>
                <div class="tabsLeft"><i class="fa fa-angle-double-left"></i></div>
                <div class="tabsRight"><i class="fa fa-angle-double-right"></i></div>
                <div class="tabsMore"><i class="fa fa-angle-double-down"></i></div>
            </div>
            <ul class="tabsMoreList">
                <li><a href="javascript:;">#maintab#</a></li>
            </ul>
            <div class="navtab-panel tabsPageContent">
                <div class="navtabPage unitBox">
                    <div class="bjui-pageContent" style="background:#FFF;">
                        Loading...
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer id="bjui-footer">Copyright &copy; 2016</a></footer>
</div>
</body>
</html>