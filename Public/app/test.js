
var url;//声明全局变量
function savePro(){
    $('#am').form({//这个地方不能像easyUI文档里加submit,不然会有错误
        url: url,
        onSubmit: function(){
            return $(this).form('validate');
        },//验证
        dataType:"json",
        success: function(data) {
            var data = eval('(' + data + ')');//将JSON的字符串解析成JSON数据格式
            console.log(data.success);
            if (data.success){
                $('#dl').dialog('close');
                $('#dg').datagrid('reload');
            }else{
                $.messager.alert('info',data.msg,'info')
            }
        }
    });
}


$('#dg').datagrid({
    url:Think.APP+"Test/select",
})


//设置状态下拉框
$(".node-status").combobox({
    data: [
        {id: 1, name: "启用"},
        {id: 2, name: "禁用"}
    ],
    valueField: 'id',
    textField: 'name',
    panelHeight: "auto",
    editable: false, //不允许手动输入
});

//设置类目下拉框
$(".node-category").combobox({
    data: [
        {id: 1, name: "类目一"},
        {id: 2, name: "类目二"}
    ],
    valueField: 'id',
    textField: 'name',
    panelHeight: "auto",
    editable: false, //不允许手动输入
});

function newPro(){
    $('#dl').dialog('open').dialog('setTitle','New Information');
    $('#am').form('clear');
    url=Think.APP + "Test/save";
}

function editPro(){
    var row = $("#dg").datagrid("getSelected");//取得选中行

    if(row){
        $("#dl").dialog("open").dialog("setTitle","Change Information");
        $("#am").form("load",row);
        url = Think.APP+'Test/update/id/'+row.product_id;//为update方法准备访问url，注意是全局变量
    }

}

function destroyPro(){
    var row = $('#dg').datagrid('getSelected');
    if (row){
        $.messager.confirm('Confirm','确定删除这条记录吗?',function(r){
            if (r){
                $.post(Think.APP+'Test/delete',{id:row.product_id},function(result){
                    if (result.success){
                        console.log(result.success)
                        $('#dg').datagrid('reload');	// reload the user data
                    } else {
                        $.messager.alert('error','删除失败','info');

                    }
                },'json');
            }
        });
    }
}


