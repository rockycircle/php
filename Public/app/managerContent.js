/**
 *用户管理模块
 **/
$(function() {
    //用户数据列表
    $('#manager-content').datagrid({
        url: Think.APP + "Content/index&prefix=cms", //数据源
        fit: true, //自适应
        striped: true,
        fitColumns: true,
        toolbar: '#content-toolbar',
        border: false, //取消边框
        pagination: true, //分页开启
        rownumbers: true,
        onClickRow: function(rowIndex, rowData) {//单击事件
            $('#manager-content').datagrid("clearChecked"); //取消所有行的选中
            $('#manager-content').datagrid("selectRow", rowIndex);  //选中当前行
        },
        columns: [[
                {title: 'content_id', field: 'id', checkbox: true},
                {field: 'content_id', title: '序号', width: 100, align: 'center'},
                {field: 'content_title', title: '标题', width: 100, align: 'center'},
                {field: 'content_last_date', title: '更新时间', width: 100, align: 'center'},
                {field: 'content_category_id', title: '类目', width: 100, align: 'center'},
                {field: 'content_has_html', title: 'Html', width: 100, align: 'center'},
                {field: 'content_count', title: '点击量', width: 100, align: 'center'},
                {field: 'content_author', title: '发布人', width: 100, align: 'center'},
                {field: 'content_action', title: '操作', width: 100, align: 'center', formatter: function(value, rec) {
                        var btn = '<a class="see_content" onclick="content_toolbar.seeContent(\'' + rec.content_id + '\',\''+rec.content_title+'\',\''+rec.content_last_date+'\' )" href="javascript:void(0)">编辑</a>';
                        return btn;
                    }
                }
            ]],
        onLoadSuccess: function(data) {
            $('.see_content').linkbutton({text: '预览', plain: true, iconCls: 'icon-search'});
        }
    });

   //显示所有栏目
     $('#category-id').combotree({
        url: Think.APP + "Category/lists&prefix=cms", //数据源
        lines:true,
    });
    /**
     * 内容预览区域
     */
    $('#content-detail').dialog({
        width: 700,
        closed: true,
        height:600,
        resizable:true,  //是否允许调整大小
        border: false, //取消边框
    });
    
    //验证输入框
    function validata() {
        $("input[name='account']").validatebox({required: true});
        $("input[name='nickname']").validatebox({required: true});
        $("input[name='password']").validatebox({required: true});
        $("input[name='newpassword']").validatebox({required: true, validType: ["equals['input[name=password]']"]});

        if (!$("input[name='account']").validatebox("isValid")) {
            return false;
        }
        if (!$("input[name='nickname']").validatebox("isValid")) {
            return false;
        }
        if (!$("input[name='password']").validatebox("isValid")) {
            return false;
        }
        if (!$("input[name='newpassword']").validatebox("isValid")) {
            return false;
        }
        return true;
    }
    
    
    //显示添加框
    $('#content-add-form').dialog({
        title: '添加文档',
        width: 700,
        closed: true,
        resizable:true,  //是否允许调整大小
        border: false, //取消边框
        modal: true, //遮罩层
        onOpen: function() {   //加KindEditor插件
            editor = KindEditor.create('textarea[name="content_text"]', {
                resizeType: 1,
                allowPreviewEmoticons: false,
               	cssPath : './kindeditor-4.1/plugins/code/prettify.css',
				uploadJson : Think.APP+'File/upload',
				fileManagerJson : Think.APP+'File/fileManager',
                allowFileManager: true,
                items: [
                    'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                    'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                    'insertunorderedlist', '|', 'emoticons', 'image', 'link'],
                afterBlur: function(){this.sync();}  //失去焦点的时候获取值
            });
        },
        onBeforeClose: function() {  //销毁
            editor.html('');
        },
        buttons: [{
                text: "确 定",
                handler: function() {
                    //获取数据
                    $('#content-add-form').form({
                        url: Think.APP + "Content/insert&prefix=cms",
                        dataType: "json",
                        onSubmit: function() {
                            //进行表单验证
                            //return validata(); //如果返回false阻止提交
                        },
                        success: function(data) {
                            var data = eval('(' + data + ')');
                            if (data.success === 1) {
                                //刷新
                                $('#content-add-form').dialog("close").form('reset');
                                $('#manager-content').datagrid("reload");  //重新加载数据

                            } else
                            {
                                //添加失败 
                                $.messager.alert('提示', data.msg, 'error');
                            }
                        }
                    });
                    //提交表单
                    $('#content-add-form').submit();
                }
            }, {
                text: "取 消",
                handler: function() {
                    $('#content-add-form').form('clear');
                    editor.html('');
                    $('#content-add-form').dialog("close").form('reset');
                }
            }]
    });
    
    var editor = null;  //定义编辑器
    /**
     * 点击图片上传方法
     */
    $("#content-image").click(function() {
        editor.loadPlugin('image', function() {
            editor.plugin.imageDialog({
                imageUrl: $('.content-image').val(),
                clickFn: function(url, title, width, height, border, align) {
                    $('.content-image').val(url);
                    editor.hideDialog();
                }
            });
        });
    });

    /**
     * 工具栏
     */
    content_toolbar = {
        /**
         * 添加内容
         */
        add: function() {  //添加
            //弹出dialog
            $('#content-add-form').dialog("open");//开启
        },
        /**
         * 预览内容
         * @param {type} id
         */
        seeContent: function(id,title,date) {
            $('#content-detail').dialog({"title":title,"href":Think.APP + "Content/seeContent&id="+id+"&date="+date});
            $('#content-detail').dialog("open");//开启
        },
        /**
         * 删除数据
         */
        delete: function() {
            var selectData = $('#manager-content').datagrid("getSelections");
            if (selectData == "") {
                $.messager.alert('提示', '请选择数据');
            } else {
                $.messager.confirm('提示', '确定删除选中的记录?', function(r) {
                    if (r) {
                        var temp = Array();
                        for (var i = 0; i < selectData.length; i++) {
                            temp[i] = selectData[i].content_id;
                        }
                        temp = JSON.stringify(temp);
                        console.log(temp);
                        $.ajax({
                            url: Think.APP + "Content/delete",
                            dataType: "json",
                            data: {"ids": temp},
                            type: 'post',
                            success: function(data) {
                                if (data.success == 1) {
                                    $('#manager-content').datagrid("reload");  //重新加载数据
                                } else {
                                    $.messager.alert('提示', '删除失败');
                                }
                            }
                        });
                    }
                });
            }
        }
    };

});