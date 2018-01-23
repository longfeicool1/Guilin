
function assigned()
{
    var str="";

    $('input[name="ids"]:checked').each(function(){
        str += $(this).val()+",";
    });
    if (str == "")
    {
        alert('请勾选内容!!!');
        return;
    }
    $(document).dialog({
        id: 'id48',
        url:'/user/channel/assigned?ids='+str,
        title: '分配人员'
    });
}