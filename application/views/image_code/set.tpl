<form action="common/imageCode/set?id={{if $info}}{{$info['id']}}{{/if}}" method='post' id="pagerForm" data-toggle="validate">
    <div class="bjui-pageContent">
        <table class="table">
            <tbody>
            <tr>
                <td>图片名称：</td>
                <td><input type="text" name="name" value="{{if $info }}{{$info['name']}}{{/if}}" data-rule="required"  placeholder="图片名称"></td>
            </tr>
            <tr>
                <td>图片：</td>
                <td>
                    <sapn class="img" id="showimg1">
                        {{if $info }}<img src="{{$info['url']}}" width="100px;">{{/if}}
                    </sapn>
                    <button id="pickFiles" class="btn btn-blue" type="button">{{if $info}}更新{{/if}}上传</button>
                    <input type="hidden" id="url" name="url" value="{{if $info }}{{$info['url']}}{{/if}}" />
                </td>
            </tr>
            </tbody>
        </table>
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
</form>

<script src="/static/js/qiniujs/moxie.js"></script>
<script src="/static/js/qiniujs/plupload.dev.js"></script>
<script src="/static/js/qiniujs/qiniu.min.js"></script>
<script>
    var uploader = Qiniu.uploader({
        runtimes: 'html5,flash,html4',    //上传模式,依次退化
        browse_button: 'pickFiles',       //上传选择的点选按钮，**必需**
//  uptoken_url: '/token',
        //Ajax请求upToken的Url，**强烈建议设置**（服务端提供）
        uptoken : '{{$token}}',
        //若未指定uptoken_url,则必须指定 uptoken ,uptoken由其他程序生成
//  unique_names: true,
        // 默认 false，key为文件名。若开启该选项，SDK会为每个文件自动生成key（文件名）
//     save_key: true,
        // 默认 false。若在服务端生成uptoken的上传策略中指定了 `sava_key`，则开启，SDK在前端将不对key进行任何处理
        get_new_uptoken: false,             // 设置上传文件的时候是否每次都重新获取新的 uptoken
        domain: '{{$qiniuDomain}}',//bucket 域名，下载资源时用到，**必需**
//  container: 'container',           //上传区域DOM ID，默认是browser_button的父元素，
        max_file_size: '100mb',           //最大文件体积限制
        flash_swf_url: 'js/plupload/Moxie.swf',  //引入flash,相对路径
        max_retries: 3,                   //上传失败最大重试次数
        dragdrop: true,                   //开启可拖曳上传
//  drop_element: 'container',        //拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
        chunk_size: '4mb',                //分块上传时，每片的体积
        auto_start: true,                 //选择文件后自动上传，若关闭需要自己绑定事件触发上传
        init: {
            'FilesAdded': function(up, files) {
                plupload.each(files, function(file) {
                    // 文件添加进队列后,处理相关的事情
                });
            },
            'BeforeUpload': function(up, file) {
                // 每个文件上传前,处理相关的事情
            },
            'UploadProgress': function(up, file) {
                // 每个文件上传时,处理相关的事情
                $('#pickFiles').prop('disabled', true).html('图片上传中...');
            },
            'FileUploaded': function(up, file, info) {
                // 每个文件上传成功后,处理相关的事情
                //var data     =   $.parseJSON(info);
                $('#pickFiles').prop('disabled', false).html('上传图片');
                var res = JSON.parse(info);
                imgUrl = up.getOption('domain') + res.key;
                $('#url').val(imgUrl);
                $('#showimg1').html('<img src="'+ imgUrl +'" width="150px;">');
            },
            'Error': function(up, err, errTip) {
                //上传出错时,处理相关的事情
            },
            'UploadComplete': function() {
                //队列文件处理完毕后,处理相关的事情
            },
            'Key': function(up, file) {
                // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
                // 该配置必须要在 unique_names: false , save_key: false 时才生效
                var key = "";
                // do something with key here
                return file.id;
            }
        }
    });
