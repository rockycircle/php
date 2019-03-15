/**
 *用户管理模块
 **/
$(function() {
    //用户数据列表
    $('#manager-user').datagrid({
        url: Think.APP + "User/index", //数据源
        fit: true, //自适应
        striped: true,
        fitColumns: true,
        toolbar: '#user-toolbar',
        border: false, //取消边框
        pagination: true, //分页开启
        pageSize: 50,
        rownumbers: true,
        columns: [[
                {title: 'ID', field: 'id', checkbox: true},
                {field: 'account', title: '账号', width: 100, align: 'center'},
                {field: 'nickname', title: '昵称', width: 100, align: 'center'},
                {field: 'email', title: '邮箱', width: 100, align: 'center'},
                {field: 'last_login_ip', title: '最后登录IP', width: 100, align: 'center'},
                {field: 'action', title: '操作', width: 100, align: 'center', formatter: function(value, rec) {
                        var btn = '<a class="user_action" onclick="user_toolbar.edit(\'' + rec.id + '\')" href="javascript:void(0)">编辑</a>|'+
                                  '<a class="user_passwd_action" onclick="user_toolbar.updateuserpasswd(\'' + rec.id + '\')" href="javascript:void(0)">编辑</a>';
                        return btn;
                    }}
            ]],
        onClickRow: function(rowIndex, rowData) {//单击事件
            $('#manager-user').datagrid("clearChecked"); //取消所有行的选中
            $('#manager-user').datagrid("selectRow", rowIndex);  //选中当前行
        },
        onLoadSuccess: function(data) {
            $('.user_action').linkbutton({text: '修改信息', plain: true, iconCls: 'icon-edit'});
            $('.user_passwd_action').linkbutton({text: '修改密码', plain: true, iconCls: 'icon-edit'});
        },
    });

    //获取所有用户组
    $(".user-group").combobox({
        url: Think.APP + 'Public/groupList',
        valueField: 'id',
        textField: 'name',
        panelHeight: "auto",
        editable: false, //不允许手动输入
    });

    //验证输入框
    function validata() {

        $("input[name='password']").validatebox({required: true});
        $("input[name='newpassword']").validatebox({required: true, validType: ["equals['input[name=password]']"]});
        if (!$("input[name='password']").validatebox("isValid")) {
            return false;
        }
        if (!$("input[name='newpassword']").validatebox("isValid")) {
            return false;
        }

        $("input[name='account']").validatebox({required: true});
        $("input[name='nickname']").validatebox({required: true});
        if (!$("input[name='account']").validatebox("isValid")) {
            return false;
        }
        if (!$("input[name='nickname']").validatebox("isValid")) {
            return false;
        }
        return true;
    }

    //显示添加框
    $('#user-add-form').dialog({
        title: '添加用户',
        width: 350,
        closed: true,
        modal: true, //遮罩层
        buttons: [{
                text: "确 定",
                handler: function() {
                    //获取数据
                    $('#user-add-form').form({
                        url: $("#user-add-form").attr("url"),
                        dataType: "json",
                        onSubmit: function() {
                            //进行表单验证
                            return validata(); //如果返回false阻止提交
                        },
                        success: function(data) {
                            var data = eval('(' + data + ')');
                            if (data.success === 1) {
                                //刷新
                                $('#user-add-form').dialog("close").form("reset");  //关闭窗体
                                $('#manager-user').datagrid("reload");  //重新加载数据

                            } else
                            {
                                //添加失败 
                                $.messager.alert('提示', data.msg, 'error');
                            }
                        }
                    });
                    //提交表单
                    $('#user-add-form').submit();
                }
            }, {
                text: "取 消",
                handler: function() {
                    //关闭
                    //$('#user-add-form').clear();
                    $('#user-add-form').dialog("close").form('reset');
                }
            }]
    });


    //显示修改框
    $('#user-edit-form').dialog({
        title: '修改用户',
        width: 350,
        closed: true,
        modal: true, //遮罩层
        buttons: [{
                text: "确 定",
                handler: function() {
                    //获取数据
                    $('#user-edit-form').form({
                        url: $("#user-edit-form").attr("url"),
                        dataType: "json",
                        onSubmit: function() {
                        
                        },
                        success: function(data) {
                            var data = eval('(' + data + ')');
                            if (data.success === 1) {
                                //刷新
                                $('#user-edit-form').dialog("close").form("reset");  //关闭窗体
                                $('#manager-user').datagrid("reload");  //重新加载数据
                            } else
                            {
                                //添加失败 
                                $.messager.alert('提示', data.msg, 'error');
                            }
                        }
                    });
                    //提交表单
                    $('#user-edit-form').submit();
                }
            }, {
                text: "取 消",
                handler: function() {
                    $('#user-edit-form').dialog("close").form('reset');
                }
            }]
    });
    
    
    //显示密码更新框
    $('#user-updatepasswd-form').dialog({
        title: '修改用户密码',
        width: 350,
        closed: true,
        modal: true, //遮罩层
        buttons: [{
                text: "确 定",
                handler: function() {
                    //获取数据
                    $('#user-updatepasswd-form').form({
                        url: $("#user-updatepasswd-form").attr("url"),
                        dataType: "json",
                        onSubmit: function() {
                        
                        },
                        success: function(data) {
                            var data = eval('(' + data + ')');
                            if (data.success === 1) {
                                //刷新
                                $('#user-updatepasswd-form').dialog("close").form("reset");  //关闭窗体
                                $('#manager-user').datagrid("reload");  //重新加载数据
                            } else
                            {
                                //添加失败 
                                $.messager.alert('提示', data.msg, 'error');
                            }
                        }
                    });
                    //提交表单
                    $('#user-updatepasswd-form').submit();
                }
            }, {
                text: "取 消",
                handler: function() {
                    $('#user-updatepasswd-form').dialog("close").form('reset');
                }
            }]
    });

    //添加方法
    user_toolbar = {
        add: function() {  //添加
            //弹出dialog
            $("#user-add-form").attr("url", Think.APP + "User/insert");
            $('#user-add-form').dialog("open");//开启
        },
        edit: function(id) {
            //先加载数据
            $.ajax({
                url: Think.APP + "User/edit&id=" + id,
                dataType: 'json',
                type: 'get',
                success: function(data) {
                    if (data.success == true) {
                        $(".edit_account").val(data.user.account);
                        $(".edit_nickname").val(data.user.nickname);
                        $(".edit_group").combobox('setValue', data.user.role_id);
                        //弹出dialog
                        $("#user-edit-form").attr("url", Think.APP + "User/edit&id=" + id+"&role="+data.user.role_id);
                        $('#user-edit-form').dialog("open");//开启
                    }
                }
            });
        },
        updateuserpasswd:function(id){
            //弹出dialog
            $("#user-updatepasswd-form").attr("url", Think.APP + "User/updateuserpasswd&id=" + id);
            $('#user-updatepasswd-form').dialog("open");//开启
        },
        //删除数据
        delete: function() {
            var selectData = $('#manager-user').datagrid("getSelections");
            if (selectData == "") {
                $.messager.alert('提示', '请选择数据');
            } else {
                $.messager.confirm('提示', '确定删除该用户?', function(r) {
                    if (r) {
                        var temp = Array();
                        for (var i = 0; i < selectData.length; i++) {
                            temp[i] = selectData[i].id;
                        }
                        temp = JSON.stringify(temp);
                        console.log(temp);
                        $.ajax({
                            url: Think.APP + "User/delete",
                            dataType: "json",
                            data: {"ids": temp},
                            type: 'post',
                            success: function(data) {
                                if (data.success == 1) {
                                    $('#manager-user').datagrid("reload");  //重新加载数据
                                } else {
                                    $.messager.alert('提示', '删除失败');
                                }
                            }
                        })
                    }
                });
            }
        },
        //信息维护修改密码
        updatepasswd:function(){
            //获取数据
            var orignPasswd = $(".orign-passwd").val();
            var newPasswd = $(".new-password").val();
            var newPasswd1 = $(".new-password1").val();
            if(orignPasswd==""|| newPasswd==""||newPasswd1==""){
                $.messager.alert('提示',"数据不准为空", 'error');
                return;
            }
            if(newPasswd!==newPasswd1){
                $.messager.alert('提示',"两次密码输入不一致", 'error');
                return;
            }
            $.ajax({
                url:Think.APP + "User/updatePassword",
                type:"post",
                dataType:"json",
                data:{"old_password":orignPasswd,"new_password":newPasswd},
                success:function(data){
                    if(data.success==true){
                        $.messager.alert('提示',data.message);
                        window.location.href = Think.APP + "Public/loginout";
                    }else{
                        $.messager.alert('提示',data.message);
                    }
                }
            })
            //发送请求
        }
    };

});