var Xwindow = function() {   //公共界面类
    var XWIN = {
        upload: function() {   //上传组件
            var me = this;
            me.__init = function() { //初始化界面
                //写入js组件
                $('#xwindow-dialog-upload').dialog({
                    title: '文件上传',
                    width: 500,
                    height: 350,
                    closed: true,
                    resizable: true, //是否允许调整大小
                    border: false, //取消边框
                    modal: true, //遮罩层
                    toolbar: [{
                            text: '添加文件',
                            iconCls: 'icon-add-new',
                            id: 'xwindow-upload-item',
                            handler: function() {
                                $('#xwindow-dialog-upload-grid').datagrid('appendRow', {
                                    file_name: 'data.word',
                                    file_size: "30kb",
                                    file_progress: '80%',
                                    file_status: '成功'
                                });
                                $('.xwindow-grid-action').linkbutton({text: '取消', plain: true, iconCls: 'icon-delete-new'});
                            }
                        }, {
                            text: '上传文件',
                            iconCls: 'icon-help',
                            handler: function() {

                            }
                        }],
                    onClose: function() {
                        //$("#xwindow-dialog-upload-grid").datagrid("close"); 
                    }
                });
                $("#xwindow-dialog-upload-grid").datagrid({
                    fit: true, //自适应
                    striped: true,
                    fitColumns: true,
                    onClickRow: function(rowIndex, rowData) {//单击事件
                        $('#xwindow-dialog-upload-grid').datagrid("clearChecked"); //取消所有行的选中
                    },
                    columns: [[
                            {field: 'file_name', title: '文件名', width: 100, align: 'center'},
                            {field: 'file_size', title: '大小', width: 100, align: 'center'},
                            {field: 'file_progress', title: '上传进度', width: 100, align: 'center', formatter: function(value, row, index) {
                                    //将后台的进度条的value进行拼接  *****
                                    var htmlstr = '<div class="progressbar-text" >' + value + '</div><div class="progressbar-value" style="width:' + parseInt(value) + "px" + ';">&nbsp </div>' +
                                            '<style type="text/css">.progressbar-value{background: #d2fee1;}.progressbar-text{z-index: 2;}</style>';
                                    return htmlstr;
                                }},
                            {field: 'file_status', title: '状态', width: 100, align: 'center'},
                            {field: 'action', title: '操作', width: 100, align: 'center', formatter: function(value, row, index) {
                                    var btn = '<a class="xwindow-grid-action"  onclick="canceUpload(\'' + index + '\')" href="javascript:void(0)">　</a>';
                                    return btn;
                                }},
                        ]]
                });

                $('#xwindow-dialog-upload').dialog("open"); //打开窗口
            },
                    me.__init();
        }
    };
    return XWIN;
}();
function canceUpload(index) {
    $('#xwindow-dialog-upload-grid').datagrid("deleteRow", index);
}



