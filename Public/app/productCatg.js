$(function () {
    //用户数据列表
    $('#manager-productcatg').datagrid({
        url: Think.APP + "ProductCatg/index&cmd=ProductCategory", //数据源
        fit: true, //自适应
        striped: true,
        fitColumns: true,
        toolbar: '#productcatg-toolbar',
        border: false, //取消边框
        pagination: true, //分页开启
        pageSize: 50,
        rownumbers: true,
        columns: [[
                {field: 'category_id', title: 'ID', width: 100, align: 'center', checkbox: true},
                {field: 'category_title', title: '类目名称', width: 100, align: 'center'},
                {field: 'category_img', title: '类目图片URL', width: 100, align: 'center'},
                {field: 'category_status', title: '类目状态', width: 100, align: 'center',formatter: function (value, row, index) {
                      return   value == 1 ? '启用':'禁用';
                }},
            ]],
        onClickRow: function (rowIndex, rowData) {//单击事件
            $('#manager-productcatg').datagrid("clearChecked"); //取消所有行的选中
            $('#manager-productcatg').datagrid("selectRow", rowIndex);  //选中当前行
        },
        onDblClickRow: function (field, row) {
            var url = Think.APP + "ProductCatg/edit&cmd=ProductCategory&category_id=" + row.category_id;
            //打开弹出窗口
            Xwindow.baseDialogInit("#manager-productcatg", "#add-productcatg-form", "修改产品类目", url, row, 3);
        }
    });

    /**
     * 点击图片上传方法
     */
    $("#category-img").click(function () {
        var editor = Xwindow.getKindEditor();
        editor.loadPlugin('image', function () {
            editor.plugin.imageDialog({
                imageUrl: $('.category-img').val(),
                clickFn: function (url, title, width, height, border, align) {
                    $('.category-img').val(url);
                    editor.hideDialog();
                }
            });
        });
    });

    //添加方法
    productcatg_toolbar = {
        add: function () {  //添加
            var url = Think.APP + "ProductCatg/insert&cmd=ProductCategory";
            //打开弹出窗口
            Xwindow.baseDialogInit("#manager-productcatg", "#add-productcatg-form", "添加产品类目", url);
        },
        delete: function () {
            var url = Think.APP + "ProductCatg/del&cmd=ProductCategory";
            Xwindow.baseDelete("#manager-productcatg", url, "category_id");
        },
    };


});
