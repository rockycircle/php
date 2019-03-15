/**
 *用户权限模块
 **/
$(function() {
    //角色数据列表
    $('#manager-role').datagrid({
        url: Think.APP + "Role/index", //数据源
        fit: true, //自适应
        striped: true,
        fitColumns: true,
        toolbar: '#role-toolbar',
        border: false, //取消边框
        pagination: true, //分页开启
        rownumbers: true,
        columns: [[
                {title: 'ID', field: 'id', checkbox: true},
                {field: 'name', title: '组名', width: 100, align: 'center'},
                //{field: 'status', title: '状态', width: 100, align: 'center',formatter:function(value,row,index){
                //    return Store().converter(app_data.info.product_status("STORE"),value);
                 //}},
                {field: 'remark', title: '描述', width: 100, align: 'center'},
                {field: 'action', title:'操作',  width:100, align:'center',formatter:function(value,rec){  
                var btn = '<a class="auth_action" onclick="role_toolbar.addrole(\''+rec.id+'\')" href="javascript:void(0)">编辑</a>';  
                return btn;
                }}
            ]],
        onClickRow: function(rowIndex, rowData) {//单击事件
            $('#manager-role').datagrid("clearChecked"); //取消所有行的选中
            $('#manager-role').datagrid("selectRow", rowIndex);  //选中当前行
            //获取当前行的数据
            $('#manager-role-user').datagrid({url: Think.APP + "Role/user&id=" + rowData.id});  //重新加载数据
        },  
       onLoadSuccess:function(data){
           $('.auth_action').linkbutton({text:'授权',plain:true,iconCls:'icon-key'});  
       },
       onDblClickRow: function(field, row) {
           $('#role-add-form').dialog({title:"修改用户组"});
           $('#role-add-form').form("load",row);
           $('#role-add-form').attr("url", Think.APP + "Role/edit&cmd=RbacRole&id="+row.id);
           $('#role-add-form').dialog("open");//开启
       }
    });
    
    $('#manager-role-user').datagrid({
        fit: true, //自适应
        striped: true,
        fitColumns: true,
        toolbar: '#role-user-toolbar',
        border: false, //取消边框
        pagination: false, //分页开启
        rownumbers: true,
        onLoadSuccess: function(data) {  //数据加载加载成功后触发
            var userdata = data.rows;
            for (var i = 0; i < userdata.length; i++) {
                if (userdata[i].check == 1) {
                    $('#manager-role-user').datagrid("selectRow", i);  //选中当前行   
                }
            }
        },
        columns: [[
                {title: 'ID', field: 'id', checkbox: true},
                {field: 'account', title: '账号', width: 100, align: 'center'},
                {field: 'nickname', title: '昵称', width: 100, align: 'center'},
                {field: 'status', title: '状态', width: 100, align: 'center'},
            ]]
    });

    //显示授权框
    $("#role-dialog-tree").dialog({
        title: '用户组授权',
        width: 400,
        closed: true,
        height: 500,
        modal: true, //遮罩层
        buttons: [{
                text: "确 定",
                handler: function() {
                    var selectNode = $("#role-dialog-tree").tree('getChecked');  //显示选中的值
                    var temp = Array();
                    for (var i = 0; i < selectNode.length; i++) {
                        temp[i] = selectNode[i].id;
                    }
                    temp = JSON.stringify(temp); //用户ID的值
                    var selectData = $('#manager-role').datagrid("getSelections");  //获取选中的role
                    var data = {"groupId": selectData[0].id, "nodeIds": temp};
                    $.ajax({
                        url: Think.APP + "Role/saveRole",
                        type: "post",
                        data: data,
                        dataType: "json",
                        success: function(data) {
                            if (data.success == 1) {
                                $("#role-dialog-tree").dialog("close");
                                $('#manager-role').datagrid("reload");  //重新加载数据
                            } else {
                                $.messager.alert('提示', data.msg);
                            }
                        }});
                }
            }, {
                text: "取 消",
                handler: function() {
                    $('#role-dialog-tree').dialog("close").tree("reload");
                }
            }]
    });

    //验证输入框
    function validata() {
        $("input[name='name']").validatebox({required: true});
        $("input[name='status']").validatebox({required: true});
        $("input[name='remark']").validatebox({required: true});

        if (!$("input[name='name']").validatebox("isValid")) {
            return false;
        }
        if (!$("input[name='status']").validatebox("isValid")) {
            return false;
        }
        if (!$("input[name='remark']").validatebox("isValid")) {
            return false;
        }
        return true;
    }

    //设置状态
    $("#group-status").combobox({
        data: [
            {id: 1, name: "启用"},
            {id: 2, name: "禁用"}
        ],
        valueField: 'id',
        textField: 'name',
        panelHeight: "auto",
        editable: false, //不允许手动输入
    });

    //显示添加框
    $('#role-add-form').dialog({
        title: '添加角色',
        width: 350,
        closed: true,
        modal: true, //遮罩层
        buttons: [{
                text: "确 定",
                handler: function() {
                    //添加角色
                    $('#role-add-form').form({
                        dataType: "json",
                        url: $("#role-add-form").attr("url"),
                        onSubmit: function() {
                            //进行表单验证
                            return validata(); //如果返回false阻止提交
                        },
                        success: function(data) {
                            var data = eval('(' + data + ')');
                            if (data.success === 1) {
                                //刷新
                                $('#role-add-form').dialog("close").form("reset");  //关闭窗体
                                $('#manager-role').datagrid("reload");  //重新加载数据

                            } else
                            {
                                //添加失败 
                                $.messager.alert('提示', data.msg, 'error');
                            }
                        }
                    });
                    //提交表单
                    $('#role-add-form').submit();
                }
            }, {
                text: "取 消",
                handler: function() {
                    //关闭
                    //$('#user-add-form').clear();
                    $('#role-add-form').dialog("close").form('reset');
                }
            }]
    });

    //授权树
    $("#role-dialog-tree").tree({//在输入框中显示树
        lines: true,
        required: true,
        multiple: true,
        checkbox: true,
        cascadeCheck: false,
        onLoadSuccess: function(node, data) {
            var _this = this;
            if (data) {
                $.each(data, function(index, value) {
                    if (this.state == 'closed') {  //将关闭的展开
                        $(_this).tree('expandAll');  //全部展开
                    }
                });
            }
        },
        onCheck: function(node) {
            //检测父节点
            var parentNode = $("#role-dialog-tree").tree('getParent', node.target);  //获取父节点
            if (parentNode) {
                if (!parentNode.checked) {
                    $.messager.alert('提示', '请先选择父节点');
                }
            }
        }
    });

    //添加方法
    role_toolbar = {
        add: function() {  //添加
            //弹出dialog
            $('#role-add-form').form("clear");
            $('#role-add-form').dialog({title:"添加用户组"});
            $('#role-add-form').attr("url",Think.APP + "Role/insert");
            $('#role-add-form').dialog("open");//开启
        },
        remove: function() {
            var selectData = $('#manager-role').datagrid("getSelections");
            if (selectData == "") {
                $.messager.alert('提示', '请选择数据');
            } else {
                $.messager.confirm('提示', '确定删除该用户组?', function(r) {
                    if (r) {
                        var temp = Array();
                        for (var i = 0; i < selectData.length; i++) {
                            temp[i] = selectData[i].id;
                        }
                        temp = temp.join(',');
                        $.ajax({
                            url: Think.APP + "Role/delete",
                            dataType: "json",
                            data: {"ids": temp},
                            type: 'post',
                            success: function(data) {
                                if (data.success == 1) {
                                    $('#manager-role').datagrid("reload");  //重新加载数据
                                } else {
                                    $.messager.alert('提示', '删除失败');
                                }
                            }
                        });
                    }
                });
            }
        },
        addrole: function(gid) {  //授权操作
            
            //获取选中的用户，只允许选择一个用户
            //var selectData = $('#manager-role').datagrid("getSelections");
            /**if (selectData.length < 1) {
                $.messager.alert('提示', '请选择用户组');
                return;
            }**/
            /**if (selectData.length > 1) {
                $.messager.alert('提示', '只能选择一个用户组');
                return;
            }**/
            $("#role-dialog-tree").tree({url: Think.APP + "Node/tree&gid=" + gid});
            $("#role-dialog-tree").dialog("open");
        }
    };

    //用户列表工具栏
    role_user_toolbar = {
        //保存
        save: function() {
            //获取选中的值，只允许选择一个用户
            var selectData = $('#manager-role').datagrid("getSelections");
            if (selectData.length < 1) {
                $.messager.alert('提示', '请选择用户组');
                return;
            }
            if (selectData.length > 1) {
                $.messager.alert('提示', '只能选择一个用户');
                return;
            }
            //获取用户列表信息
            var selectUser = $('#manager-role-user').datagrid("getSelections");
            var temp = Array();
            for (var i = 0; i < selectUser.length; i++) {
                temp[i] = selectUser[i].id;
            }
            temp = JSON.stringify(temp);   //用户ID的值
            var data = {"groupId": selectData[0].id, "userIds": temp};
            $.ajax({
                url: Think.APP + "Role/saveUser",
                type: "post",
                data: data,
                dataType: "json",
                success: function(data) {
                    if (data.success == 1) {
                        $('#manager-role-user').datagrid("reload");  //重新加载数据
                    } else {
                        $.messager.alert('提示', data.msg);
                    }
                }
            });
        }
    };

});