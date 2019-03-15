/**
 * 类目模块
 */
$(function() {
    var lastIndex;
    $('#manager-category').treegrid({
        url: Think.APP + "Category/index&prefix=cms", //数据源    
        fit: true, //自适应
        method: 'get',
        striped: true,
        fitColumns: true,
        toolbar: '#category-toolbar',
        border: false, //取消边框
        pagination: false, //分页开启
        rownumbers: true,
        idField: 'category_id',
        treeField: 'category_title',
        columns: [[
                {field: 'category_title', title: '栏目', width: 100,
                    editor: {
                        type: "text"
                    }},
                {field: 'category_status', title: '状态', width: 100}
            ]],
        onDblClickCell: function(field, row) {
            var rowIndex = row.category_id;
            if (lastIndex != rowIndex) {
                $('#manager-category').treegrid('endEdit', lastIndex);
                $('#manager-category').treegrid('beginEdit', rowIndex);
                lastIndex = rowIndex;
            }
        },
        onClickRow: function(row) {//运用单击事件实现一行的编辑结束，在该事件触发前会先执行onAfterEdit事件  
            var rowIndex = row.category_id;
            if (lastIndex != rowIndex) {
                $('#manager-category').treegrid('endEdit', lastIndex);
            }
        },
        onAfterEdit: function(row, changes) {
            var rowId = row.id;
            $.ajax({
                url: Think.APP + "Category/update", //数据源    
                data: row,
                success: function(text) {
                    $.messager.alert('提示信息', text, 'info');
                }
            });
        }
    });

    //显示栏目添加框
    $("#category-add-form").dialog({
        title: '添加栏目',
        width: 400,
        closed: true,
        modal: true, //遮罩层
        buttons: [{
                text: "确 定",
                handler: function() {
                    $('#category-add-form').form({
                        url: Think.APP + "Category/insert&prefix=cms",
                        dataType: "json",
                        onSubmit: function() {
                            //进行表单验证
                            //return validata(); //如果返回false阻止提交
                            return true;
                        },
                        success: function(data) {
                            var data = eval('(' + data + ')');
                            if (data.success === 1) {
                                //刷新
                                $('#category-add-form').dialog("close").form("reset");  //关闭窗体
                                $('#manager-category').treegrid("reload");  //重新加载数据
                            } else
                            {
                                //添加失败 
                                $.messager.alert('提示', data.msg, 'error');
                            }
                        }
                    });
                    //提交表单
                    $('#category-add-form').submit();
                }
            }, {
                text: "取 消",
                handler: function() {
                    $('#category-add-form').dialog("close").form('reset');
                }
            }]
    });
    //显示所有栏目
    $('#category-pid').combotree({
        url: Think.APP + "Category/lists&prefix=cms", //数据源
        lines: true
    });
    //设置状态
    $("#category-status").combobox({
        data: [
            {id: 1, name: "启用"},
            {id: 2, name: "禁用"}
        ],
        valueField: 'id',
        textField: 'name',
    });

    //添加方法
    category_toolbar = {
        add: function() {  //添加
            //弹出dialog
            $('#category-add-form').dialog("open");//开启
        }}
});


