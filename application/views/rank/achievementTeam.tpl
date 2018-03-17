<div class="bjui-pageHeader">
    <form id="pagerForm" class="frm_personal" data-toggle="ajaxsearch" action="/rank/data/achievementTeam" method="post">
        <input type="hidden" name="pageSize" value="${model.pageSize}">
        <input type="hidden" name="pageCurrent" value="${model.pageCurrent}">
        <div class="bjui-searchBar">

            <table class="table table-bordered table-hover table-striped" data-toggle="tablefixed" data-width="100%" data-nowrap="true">
                <thead>
                    <th>本月额度</th>
                    <th>本月创收</th>
                    <th>本月创收单量</th>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$info['monthOutMoney']}}</td>
                        <td>{{$info['monthInMoney']}}</td>
                        <td>{{$info['monthDetail']}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>
<div class="bjui-pageContent tableContent" id="customListTable">
    <ul class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#team" role="tab" data-toggle="tab" data-target="#team">团队</a></li>
        <li><a href="/rank/data/achievementArea" data-target="#area" role="tab" data-toggle="ajaxtab">区域</a></li>
        <li><a href="/rank/data/achievementCity" data-target="#city" role="tab" data-toggle="ajaxtab">城市</a></li>
        <li><a href="/rank/data/achievementTotal" data-target="#total" role="tab" data-toggle="ajaxtab">汇总</a></li>
    </ul>
        <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane fade active in" id="team">
            <table class="table table-bordered table-hover table-striped table-top" data-toggle="tablefixed" data-width="100%" data-nowrap="true">
                <thead>
                    <tr>
                        <th>截止日期</th>
                        <th>姓名</th>
                        <th>额度</th>
                        <th>创收</th>
                        <th>创收单数</th>
                    </tr>
                </thead>
                <tbody>
                    {{if $list}}
                    <tr>
                        <td rowspan="500">{{$list[0]['collectDate']}}</td>
                    </tr>
                    {{foreach $list as $v}}

                    <tr>
                        <td>{{$v['name']}}</td>
                        <td>{{$v['monthOutMoney']}}</td>
                        <td>{{$v['monthInMoney']}}</td>
                        <td>{{$v['monthDetail']}}</td>
                    </tr>
                    {{/foreach}}
                    {{else}}
                    <tr><td colspan="20" style="text-align: center;">尚未查询到任何相关数据...</td></tr>
                    {{/if}}
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade active in" id="area">
            <!-- <div id="threeonlineTable" style="width: 100%;height:400px;"></div> -->
        </div>
        <div class="tab-pane fade" id="city">
        </div>

        <div class="tab-pane fade active in" id="total">
        </div>
    </div>
</div>
</div>
<script>
    $('.frm_personal select').change(function (){
        $('.frm_personal').submit();
    })

</script>