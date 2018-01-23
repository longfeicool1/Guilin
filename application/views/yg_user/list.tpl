<div class="bjui-pageHeader">
    <a class="btn btn-default" href="user/yg/export?{{$query}}" target="_blank">导出</a>
    <form id="pagerForm" class="frm_ujbinsure" data-toggle="ajaxsearch" action="user/yg/lists" method="post">
        <input type="hidden" name="pageSize" value="${model.pageSize}">
        <input type="hidden" name="pageCurrent" value="${model.pageCurrent}">
        <div class="bjui-searchBar">
            <select name="is_check" id="is_check" data-toggle="selectpicker">
                <option value="">--是否认证--</option>
                <option value="4" {{if !empty($search['is_check']) && $search['is_check'] == 4}}selected{{/if}}>无法认证</option>
                <option value="1" {{if !empty($search['is_check']) && $search['is_check'] == 1}}selected{{/if}}>未认证</option>
                <option value="2" {{if !empty($search['is_check']) && $search['is_check'] == 2}}selected{{/if}}>认证成功</option>
                <option value="3" {{if !empty($search['is_check']) && $search['is_check'] == 3}}selected{{/if}}>认证失败</option>
            </select>
            <input type="text" value="{{if isset($search['account_login'])}}{{$search['account_login']}}{{/if}}" size="12"  name="account_login" class="form-control" placeholder="账户">&nbsp;
            <input type="text" value="{{if isset($search['username'])}}{{$search['username']}}{{/if}}" size="12" name="username" class="form-control" placeholder="姓名">&nbsp;
            <input type="text" value="{{if isset($search['car_card'])}}{{$search['car_card']}}{{/if}}" size="12" name="car_card" class="form-control" placeholder="车牌">
            <input type="text" value="{{if isset($search['src'])}}{{$search['src']}}{{/if}}" size="14" name="src" class="form-control" placeholder="设备ID">&nbsp;&nbsp;
            <button type="submit" class="btn-green" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo">清空查询</a>
            <!-- <a href="javascript:;" onclick="downloadNoAuth()" target="_blank" class="btn btn-blue">未认证名单</a>
            <a href="/static/checkExplame.csv" onclick="downloadCheckExplame(this); return false;" class="btn btn-default" >导入认证名单模板</a>
            <div class="bottom" style="display: inline-block; vertical-align: middle;">
                <div id="list_upload_up"
                    data-toggle="upload"
                    data-uploader="/user/check/upload"
                    data-file-size-limit="10240"
                    data-button-text="批量认证"
                    data-file-type-exts="*.csv;*.xlsx;*.xls;"
                    data-multi="false"
                    data-auto="true"
                    data-on-upload-success="list_upload_success"
                    data-icon="cloud-upload"></div>
            </div> -->
            <a href="user/check/mulitCheck" class="btn btn-blue" data-id="mulitCheck" data-title="批量认证" data-toggle="dialog" data-width="1200" data-height="600">批量认证</a>
        </div>
</div>

</form>
</div>
<div class="bjui-pageContent tableContent">
    <table class="table table-bordered table-hover table-striped table-top">
        <thead>
        <tr>
            <th>序号</th>
            <th>账户</th>
            <th>姓名</th>
            <th>性别</th>
            <th>生日</th>
            <th>车牌</th>
            <th>临/正牌</th>
            <th>车型</th>
            <th>设备ID</th>
            <th>设备状态</th>
            <th>激活日期</th>
            <th>安装日期</th>
            <th>账户余额</th>
            <th>注册日期</th>
            <th>认证状态</th>
            <th>设备城市</th>
            <th>是否行驶</th>
            <th>使用情况</th>
            <th>保单号</th>
            <th>保单止期</th>
            <th>备注</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {{foreach $list as $key => $v }}
        <tr data-id="{{$key}}">
            <td>{{$v['row_id']}}</td>
            <td>{{$v['account_login']}}</td>
            <td>{{$v['username']}}</td>
            <td>{{$v['sex']}}</td>
            <td>{{$v['birthday']}}</td>
            <td>{{$v['car_card']}}</td>
            <td>{{$v['cardtype']}}</td>
            <td>{{$v['factory']}}{{$v['demio']}}{{$v['version']}}</td>
            <td>{{$v['src']}}</td>
            <td>{{$v['src_status']|default:'离线'}}</td>
            <td>{{$v['src_created']}}</td>
            <td>{{$v['src_install_time']}}</td>
            <td>{{$v['money']}}</td>
            <td>{{$v['created']}}</td>
            <td>
            {{if $v['is_check'] == 1}}
                未认证
            {{elseif $v['is_check'] == 2}}
                <span style="color: green">已认证</span>
            {{elseif $v['is_check'] == 3}}
                <span style="color: red">认证失败</span>
            {{else}}
                未认证
            {{/if}}
            </td>
            <td>{{$v['city']}}</td>
            <td>
            {{if $v['tot'] > 0}}已行驶{{else}}未行驶{{/if}}
            </td>
            <td>{{$v['use_status']}}</td>
            <td>{{$v['insure_code']}}</td>
            <td>{{$v['insure_end']}}</td>
            <td>{{$v['comment']}}</td>
            <td>
                <a href="user/yg/info?pid={{$v['pid']}}" class="btn btn-default" data-id="yg_user_navtab" data-title="[{{$v['account_login']}}] 详情" data-toggle="navtab" data-width="600" data-height="500">详情</a>
                <a href="user/check/updateLogin?pid={{$v['pid']}}" class="btn btn-blue" data-id="updateLogin" data-toggle="dialog" data-id="form" >更换账号</a>
                {{if $v['bindid']}}
                    <a href="user/check/updateCarcard?bindid={{$v['bindid']}}" class="btn btn-blue" data-id="updateCarcard" data-title="更换/认证" data-toggle="dialog" data-width="800" data-height="300">更换/认证</a>
                    <!-- <a href="user/check/authentication?bindid={{$v['bindid']}}" class="btn btn-blue" data-id="authentication" data-title="认证" data-toggle="dialog" data-width="800" data-height="300">更换车牌/保单</a> -->
                {{/if}}
                {{if $v['src']}}
                    <a href="user/yg/setSrc?bindId={{$v['bindid']}}&src={{$v['src']}}" class="btn btn-blue" data-id="setSrc" data-toggle="dialog" data-id="form" >更换设备</a>
                {{/if}}
                <a href="user/yg/comment?id={{$v['id']}}" class="btn btn-green" data-toggle="dialog" data-id="form" >备注</a>
            </td>
        </tr>
        {{/foreach}}
        </tbody>
    </table>
</div>
{{include file='public/page.tpl'}}
<script type="text/javascript">
    function downloadNoAuth()
    {
        window.open("/user/check/download");
    }

    function list_upload_success(file, data){
        var json = $.parseJSON(data);
        showMsg(json.statusCode,json.message);
        $(this).navtab('refresh', 'id273');
    }

    function downloadCheckExplame(a) {
        $.fileDownload($(a).attr('href'), {
            failCallback: function(responseHtml, url) {
                if (responseHtml.trim().startsWith('{')) responseHtml = responseHtml.toObj()
                $(a).bjuiajax('ajaxDone', responseHtml)
            }
        })
    }

</script>