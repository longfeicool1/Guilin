<div class="bjui-pageFooter">
    <div class="pages">
        <span>每页&nbsp;</span>
        <div class="selectPagesize">
            <select data-toggle="selectpicker" data-toggle-change="changepagesize">
                <option value="30">30</option>
                <option value="60">60</option>
                <option value="120">120</option>
                <option value="150">150</option>
            </select>
        </div>
        <span>&nbsp;条，共 {{$count}} 条</span>
    </div>
    <div class="pagination-box"
        data-toggle="pagination"
        data-total="{{$count}}"
        data-page-size="{{if !empty($limit)}}{{$limit}}{{else}}30{{/if}}"
        data-page-current="{{if !empty($search['pageCurrent'])}}{{$search['pageCurrent']}}{{else}}1{{/if}}">
    </div>
</div>