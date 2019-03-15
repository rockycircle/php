var Xwindow = function () {   //公共界面类
    var kindEditor = null;
    var XWIN = {
        kindEditor:null,
        upload: function (url, url2) {   //上传组件
            var me = this;
            me.__init = function () { //初始化界面
                //写入js组件
                $('#xwindow-dialog-upload').dialog({
                    title: '文件上传',
                    href: Think.PUBLIC + '/app/plugins/xwindow-sorce.html',
                    width: 400,
                    height: 220,
                    closed: true,
                    resizable: true, //是否允许调整大小
                    border: false, //取消边框
                    modal: true, //遮罩层
                    buttons: [{
                            text: '下一步',
                            handler: function () {
                                //开启
                                Xwindow.loading().show();
                                //上传文件
                                $("#window-upload-file-form").form(
                                        {url: url,
                                            dataType: "json",
                                            success: function (data) {
                                                var data = $.parseJSON(data);
                                                Xwindow.loading().close();
                                                if (data.success == true) {
                                                    me.showProperty();
                                                    //加载数据

                                                    $("#xwindow-dialog-upload-grid").datagrid('loadData', data);
                                                    $('#xwindow-dialog-upload').dialog("close");
                                                } else {
                                                    $.messager.alert('提示', data.message);
                                                }
                                            }
                                        });
                                $("#window-upload-file-form").submit();
                            }
                        }],
                });
                $('#xwindow-dialog-upload').dialog("open"); //打开窗口
            },
                    me.showProperty = function () {
                        $('#xwindow-dialog-upload-grid-dialog').dialog({
                            title: '属性匹配',
                            width: 300,
                            height: 500,
                            closed: true,
                            resizable: true, //是否允许调整大小
                            border: false, //取消边框
                            modal: true, //遮罩层
                            buttons: [{
                                    text: '取　消',
                                    handler: function () {
                                        $('#xwindow-dialog-upload-grid-dialog').dialog("close"); //打开窗口
                                    }}, {
                                    text: '下一步',
                                    handler: function () {
                                        Xwindow.loading().show();
                                        //获取grid中的数据
                                        var data = $("#xwindow-dialog-upload-grid").datagrid("getRows");
                                        //json转字符串
                                        data = JSON.stringify(data);
                                        $.ajax({
                                            url: url2,
                                            jsonType: "json",
                                            type: "POST",
                                            data: {"field": data},
                                            success: function (data) {
                                                //导入数据
                                                Xwindow.loading().close();
                                                $('#xwindow-dialog-upload-grid-dialog').dialog("close");
                                                $.messager.alert('提示', data.message);
                                                $('#manager-productinfo').datagrid("reload");  //重新加载数据
                                            }
                                        });
                                    }
                                }],
                        });
                        $('#xwindow-dialog-upload-grid-dialog').dialog("open"); //打开窗口
                        $("#xwindow-dialog-upload-grid").datagrid({
                            fit: true, //自适应
                            striped: true,
                            fitColumns: true,
                            onClickRow: function (rowIndex, rowData) {//单击事件
                                $('#xwindow-dialog-upload-grid').datagrid("clearChecked"); //取消所有行的选中
                            },
                            columns: [[
                                    {field: 'id', title: '序号', width: 100, align: 'center'},
                                    {field: 'field', title: '系统字段', width: 100, align: 'center'},
                                    {field: 'excelField', title: 'Excel字段', width: 100, align: 'center'},
                                ]]
                        });
                    }
            me.__init();
            return me;
        },
        //加载动画插件
        loading: function () {
            var me = this;
            me.show = function () {
                $("<div class=\"datagrid-mask\"></div>").css({display: "block", width: "100%", 'z-index': '9998', height: $(window).height()}).appendTo("body");
                $("<div class=\"datagrid-mask-msg\"></div>").html("loading。。。").appendTo("body").css({display: "block", 'z-index': '9999', left: ($(document.body).outerWidth(true) - 190) / 2, top: ($(window).height() - 45) / 2});
            },
                    me.close = function () {
                        $(".datagrid-mask").remove();
                        $(".datagrid-mask-msg").remove();
                    }
            return me;
        },
    };

    XWIN.getKindEditor = function () {
       return editor = KindEditor.create('textarea[name=category_desc]', {
            resizeType: 1,
            allowPreviewEmoticons: false,
            cssPath: './Public/app/plugins/kindeditor-4.1.10/plugins/code/prettify.css',
            uploadJson: Think.APP + 'File/upload',
            fileManagerJson: Think.APP + 'File/fileManager',
            allowFileManager: true,
            items: [
                'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                'insertunorderedlist', '|', 'emoticons', 'image', 'link'],
            afterBlur: function () {
                //console.log(111);
                this.sync();
            }  //失去焦点的时候获取值
        });
    },
            XWIN._baseDialog = function () {
                var storeClass = arguments[0] ? arguments[0] : null;
                var className = arguments[1] ? arguments[1] : "";
                var title = arguments[2] ? arguments[2] : "";
                var isSearch = arguments[3] ? arguments[3] : false;

                //显示搜索框
                $(className).dialog({
                    closed: true,
                    modal: true, //遮罩层
                    onOpen: function () {   //加KindEditor插件
                        if(typeof(KindEditor) != "undefined"){
                            kindEditor = Xwindow.getKindEditor();
                        }
                       
                    },
                    onBeforeClose: function () {  //销毁
                        if(typeof(KindEditor) != "undefined"){
                            kindEditor.html('').remove();//解决二次内容加载的问题
                        }
                    },
                    buttons: [{
                            text: "确 定",
                            handler: function () {
                                if (isSearch) {
                                    Xwindow._baseSearch(className, storeClass);
                                    return;
                                }
                                //获取数据
                                $(className).form({
                                    url: $(className).attr("url"),
                                    dataType: "json",
                                    onSubmit: function () {
                                    },
                                    success: function (data) {
                                        var data = eval('(' + data + ')');
                                        if (data.success === 1) {
                                            //刷新
                                            console.log(data);
                                            $(className).dialog("close").form("reset");  //关闭窗体
                                            $(storeClass).datagrid("reload");  //重新加载数据
                                            if (data.msg) {
                                                $.messager.alert('提示', data.msg);
                                            }
                                        } else if (data.total >= 0 && data.total !== undefined) {
                                            $(storeClass).datagrid("reload");  //重新加载数据
                                        } else
                                        {
                                            //添加失败 
                                            $.messager.alert('提示', data.msg, 'error');
                                        }
                                    }
                                });
                                //提交表单
                                $(className).submit();
                            }
                        }, {
                            text: "取 消",
                            handler: function () {
                                $(className).dialog("close").form('reset');
                            }
                        }]
                });
                $(className).form('clear');
                $(className).dialog({"title": title});
                //设置commbox值
                //$(".project_info_commbox").combobox("setValue",selectData[0]["project_id"]);

            };
    XWIN._baseSearch = function (className, storeClass) {
        //根据form获取数据
        var inputList = $(className).find("input");
        var data = {};
        $.each(inputList, function (n, v) {
            var key = $(this).attr("name");
            var value = $(this).val();
            if (key !== undefined && value != null && value != "") {
                data[key] = value;
            }
        });
        $(storeClass).datagrid("load", data);
    };
    //公共删除接口
    XWIN.baseDelete = function (storeClass, url, key) {
        var selectData = $(storeClass).datagrid("getSelections");

        if (selectData == "") {
            $.messager.alert('提示', '请选择数据');
        } else {
            $.messager.confirm('提示', '确定删除该记录?', function (r) {
                if (r) {
                    var temp = Array();
                    for (var i = 0; i < selectData.length; i++) {
                        temp[i] = selectData[i][key];
                    }
                    temp = JSON.stringify(temp);
                    var param = {};
                    param[key] = temp;
                    $.ajax({
                        url: url,
                        dataType: "json",
                        data: param,
                        type: 'post',
                        success: function (data) {
                            if (data.success == true) {
                                $(storeClass).datagrid("reload");  //重新加载数据
                            } else {
                                $.messager.alert('提示', '删除失败');
                            }
                        }
                    })
                }
            });
        }
    };

    //定义公共弹出窗
    XWIN.baseDialogInit = function () {
        var storeClass = arguments[0] ? arguments[0] : null;
        var className = arguments[1] ? arguments[1] : "";
        var title = arguments[2] ? arguments[2] : "";
        var url = arguments[3] ? arguments[3] : "";
        var loaddata = arguments[4] ? arguments[4] : null;
        var isSelect = arguments[5] ? arguments[5] : 0;
        var isShowCf = arguments[6] ? arguments[6] : false;

        if (isSelect == 2) {
            var selectData = $(storeClass).datagrid("getSelections");

            if (selectData == "") {
                $.messager.alert('提示', '请选择数据');
                return;
            }
            if (isSelect == 1) {
                if (selectData.length > 1) {
                    $.messager.alert('提示', '只允许选择一条数据操作');
                    return;
                }
            }
        }
        if (isShowCf) {
            $.messager.confirm('提示', "确定进行" + title, function (r) {
                if (!r) {
                    return;
                }
                Xwindow._baseDialog(storeClass, className, title);
                if (isSelect >= 2) {
                    if (loaddata == null) {
                        loaddata = selectData[0];
                    }
                    $(className).form("load", loaddata);
                }
                $(className).attr("url", url);
                $(className).dialog("open");//开启
            });
        } else {
            if (url == 'search') {
                Xwindow._baseDialog(storeClass, className, title, true);
            } else {
                Xwindow._baseDialog(storeClass, className, title);
                if (isSelect >= 2) {
                    if (loaddata == null) {
                        loaddata = selectData[0];
                    }
                    $(className).form("load", loaddata);
                }
            }

            $(className).attr("url", url);
            $(className).dialog("open");//开启
        }
        return Xwindow;
    };
    return XWIN;
}();



