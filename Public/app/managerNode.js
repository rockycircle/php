/**
 *用户管理模块
 **/
$(function() {
    var n = null;  //定义全局node 
    //授权树
    $("#node-tree").tree({//在输入框中显示树
        lines: true,
        url: Think.APP + "Node/tree&",
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
        onClick: function(node) {
            //获取text，action，状态，备注
            $("#node-form .app-form-group input[name='name']").val(node.name);
            $("#node-form .app-form-group input[name='title']").val(node.text);
            $("#node-form .app-form-group .node-status").combobox("setValue", node.status);
            $("#node-form .app-form-group input[name='remark']").val(node.remark);
            $("#node-form .app-form-group input[name='id']").val(node.id);
            n = node;
        }
    });

    /**
     * 验证保存节点时候的表单
     * @returns {undefined}
     */
    function checkSaveNodeForm() {
        $("input[name='name']").validatebox({required: true});
        $("input[name='status']").validatebox({required: true});
        $("input[name='title']").validatebox({required: true});
        if (!$("input[name='name']").validatebox("isValid")) {
            return false;
        }
        if (!$("input[name='status']").validatebox("isValid")) {
            return false;
        }
        if (!$("input[name='title']").validatebox("isValid")) {
            return false;
        }
        return true;
    }

    //显示保存框
    $('#node-form').panel({
        width: 400
    });

    //设置状态
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

    //保存
    $('#save-node-form').click(function() {
        $('#node-form').form({
            url: Think.APP + "Node/save",
            dataType: "json",
            onSubmit: function() {
                //进行表单验证
                return checkSaveNodeForm(); //如果返回false阻止提交
            },
            success: function(data) {
                var data = eval('(' + data + ')');
                if (data.success === 1) {
                    //刷新
                    $('#node-tree').tree("reload");  //重新加载数据
                } else
                {
                    //添加失败 
                    $.messager.alert('提示', data.msg, 'error');
                }
            }
        });
        //提交表单
        $('#node-form').submit();
    });

    //弹出添加框
    $('#node-add-form').dialog({
        width: 350,
        closed: true,
        modal: true, //遮罩层
        buttons: [{
                text: "确 定",
                handler: function() {
                    $('#node-add-form').form({
                        url: Think.APP + "Node/insert",
                        dataType: "json",
                        onSubmit: function() {
                            //进行表单验证
                            return checkSaveNodeForm(); //如果返回false阻止提交
                        },
                        success: function(data) {
                            var data = eval('(' + data + ')');
                            if (data.success === 1) {
                                //刷新
                                $('#node-add-form').dialog("close").form('reset');
                                $("#node-tree").tree("reload");
                            } else
                            {
                                //添加失败 
                                $.messager.alert('提示', data.msg, 'error');
                            }
                        }
                    });
                    $('#node-add-form').submit();
                }
            }, {
                text: "取 消",
                handler: function() {
                    //关闭
                    //$('#user-add-form').clear();
                    $('#node-add-form').dialog("close").form('reset');
                }
            }]
    });

    node_toolbar = {
        add: function() { //添加同级节点
            if (n !== null) {
                $("input[name='pid']").val(n.pid);
                $("input[name='level']").val(n.level);
                //提交表单
                $('#node-add-form').dialog({title: "添加同级节点", "closed": false});
            }
        },
        addChird: function() {
            if (n !== null) {
                //获取到父亲的节点
                $("input[name='pid']").val(n.id);
                $("input[name='level']").val(parseInt(n.level) + 1);
                $('#node-add-form').dialog({title: "添加子节点", "closed": false});
            }
        },
        remove: function() {   //删除节点
            if (n !== null) {
                $.ajax({
                    url: Think.APP+ "Node/remove",
                    dataType:'json',
                    type:'post',
                    data: {id: n.id}, 
                    success: function(data) {
                        if (data.success == 1) {
                           n = null;
                           $('#node-form').form("clear");
                           $("#node-tree").tree("reload");
                        } else {
                            $.messager.alert('提示', '删除失败');
                        }
                    }});
            }
        }
    };

});