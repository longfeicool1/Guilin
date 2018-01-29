//提示
function showMsg(type, msg) {
    if (type == 'confirm') {
        $(document).alertmsg(type, msg, {
            'title': '来电提示',
            'okName': '用户信息',
            'cancelName': '拒接',
            'okCall': function () {
                console.log(111);
            },
            'cancelCall': function () {
                hangup();
            },
        });
    } else {
        $(document).alertmsg(type, msg);
    }
}

/*
 * 树形选中和未选中时的回调函数
 */
function treeCheckAdd(event, treeId, treeNode){
    var html        =   '';
    var treeObj     = $.fn.zTree.getZTreeObj("ztree_add");
    var nodes       =   treeObj.getCheckedNodes(true);
    $.each(nodes, function(k,v) {
        html            += '<input id="r_' + v.id + '" type="hidden" name="rule_id[]" value="' + v.id + '">';
    });
    $('#nodeContent').html(html);
}

function treeCheckEdit(event, treeId, treeNode){
    var html        =   '';
    var treeObj     = $.fn.zTree.getZTreeObj("ztree_edit");
    var nodes       =   treeObj.getCheckedNodes(true);
    $.each(nodes, function(k,v) {
        html            += '<input id="r_' + v.id + '" type="hidden" name="rule_id[]" value="' + v.id + '">';
    });
    $('#editContent').html(html);
}
