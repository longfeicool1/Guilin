<div class="bjui-pageContent">
<form action="/config/config/toadd?id={{$id}}" method='post' id="pagerForm" data-toggle="validate">
<!-- <fieldset> -->
    <!-- <legend>基本信息</legend> -->
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="true">
        <tr>
            <td>
                <label for="comName" class="control-label x110">合作商名称：</label>
                <input type="text" name="comName" data-rule="required;" value="{{if !empty($data['comName'])}}{{$data['comName']}}{{/if}}" class="form-control">
            </td>
        </tr>
        <tr>
            <td>
                <label for="appid" class="control-label x110">合作商标识：</label>
                <input type="text" name="appid" data-rule="required;" value="{{if !empty($data['appid'])}}{{$data['appid']}}{{/if}}" class="form-control">
            </td>
        </tr>
        <tr>
            <td>
                <label for="appSecrect" class="control-label x110">秘钥：</label>
                <input type="text" name="appSecrect" data-rule="required;" value="{{if !empty($data['appSecrect'])}}{{$data['appSecrect']}}{{/if}}" class="form-control" readonly>
                <a href="javascript:;" onclick="createKey();" class="btn btn-blue">生成秘钥</a>
            </td>
        </tr>
        <tr>
            <td>
                <label for="dataLevel" class="control-label x110">数据等级：</label>
                <select name="dataLevel" data-toggle="selectpicker">
                    <option value="A" {{if !empty($data['dataLevel']) && $data['dataLevel'] == 'A'}}selected{{/if}}>A级</option>
                    <option value="B" {{if !empty($data['dataLevel']) && $data['dataLevel'] == 'B'}}selected{{/if}}>B级</option>
                    <option value="C" {{if !empty($data['dataLevel']) && $data['dataLevel'] == 'C'}}selected{{/if}}>C级</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="status" class="control-label x110">状态：</label>
                <select name="status" data-toggle="selectpicker">
                    <option value="1" {{if !empty($data['status']) && $data['status'] == 1}}selected{{/if}}>调试</option>
                    {{if !empty($id)}}
                    <option value="2" {{if !empty($data['status']) && $data['status'] == 2}}selected{{/if}}>上线</option>
                    <option value="3" {{if !empty($data['status']) && $data['status'] == 3}}selected{{/if}}>终止</option>
                    {{/if}}
                </select>
            </td>
        </tr>
    </table>
<!-- </fieldset> -->
</form>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li>
            <button type="button" class="btn-close" data-icon="close">取消</button>
        </li>
        <li>
            <button type="submit" class="btn-default" data-icon="save">保存</button>
        </li>
    </ul>
</div>
<script type="text/javascript">
    function createKey()
    {
        $.get('/config/config/createAppScrect',{},function (key){
            $('input[name=appSecrect]').val(key);
        })
    }
</script>