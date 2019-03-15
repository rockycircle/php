$(function () {
    $('#manager-product').datagrid({
        url:Think.APP+"Product/index&cmd=product",
        fit: true, //自适应
        striped: true,
        fitColumns: true,
        toolbar: '#product-toolbar',
        border: false, //取消边框
        pagination: true, //分页开启
        pageSize: 50,
        rownumbers: true,
        columns: [[
            {title: '', field: 'product_id', checkbox: true},
            {field: 'product_title', title: '产品名称', width: 100, align: 'center'},
            {field: 'product_origin_price', title: '市场价', width: 100, align: 'center'},
            {field: 'product_price', title: '售价', width: 100, align: 'center'},
            {field: 'product_from', title: '商品来源', width: 100, align: 'center'},
            {field: 'category_id', title: '类目',class:"node-category",width: 100, align: 'center',formatter:function (value,row,index) {
                if (value <= 0) {
                            return "无";
                    }
                return Store().converter(app_data.info.productcategory("STORE"), value);
            }},
            {field: 'product_status', title: '状态',class:"node-status",width: 100, align: 'center',formatter:function (value,row,index) {
                if (value==1){
                    return "出售中";
                }
                else if(value==2){
                    return "已下架";
                }else if(value==3){
                    return "已出售";
                }else if(value==4){
                    return "纠纷中"
                }else{
                    return "无"
                }
            }},
            {field: 'product_use_address', title: '适用门店', width: 100, align: 'center'},
        ]],
        onClickRow: function (rowIndex, rowData) {//单击事件
            $('#manager-product').datagrid("clearChecked"); //取消所有行的选中
            $('#manager-product').datagrid("selectRow", rowIndex);  //选中当前行
        },
        onDblClickRow:function(field, row){
            var url =  Think.APP + "Product/edit&cmd=product&product_id=" + row.product_id;
            //打开弹出窗口

            Xwindow.baseDialogInit("#manager-product", "#add-product-form", "修改产品",url,row,3);
        }
    })

    //产品列表
    $('#manager-prolist').datagrid({
        url:Think.APP+"Product/index&cmd=product_user",
        fit: true, //自适应
        striped: true,
        fitColumns: true,
        toolbar: '#prolist-toolbar',
        border: false, //取消边框
        pagination: true, //分页开启
        pageSize: 50,
        rownumbers: true,
        columns: [[
            {title: 'ID', field: 'product_customer_id', checkbox: true},
            {field: 'product_id', title: '产品模板', width: 100, align: 'center',formatter:function (value,row,index) {
                if (value <= 0) {
                            return "无";
                    }
                return Store().converter(app_data.info.product("STORE"), value);
            }},
            {field: 'product_title', title: '产品名称', width: 100, align: 'center'},
            {field: 'product_origin_price', title: '市场价', width: 100, align: 'center'},
            {field: 'product_price', title: '售价', width: 100, align: 'center'},
            {field: 'product_from', title: '产品来源', width: 100, align: 'center'},
            {field: 'category_id', title: '类目',width: 100, align: 'center',formatter:function(value,row,index){
               if (value <= 0) {
                            return "无";
                    }
                return Store().converter(app_data.info.productcategory("STORE"), value);
            }},
            {field: 'product_code', title: '兑换码',class:"node-status",width: 100, align: 'center'},
            {field: 'customer_id', title: '负责人ID',class:"node-status",width: 100, align: 'center'},
            {field: 'product_merchant', title: '供应商', width: 100, align: 'center'},
        ]],
        onClickRow: function (rowIndex, rowData) {//单击事件
            $('#manager-prolist').datagrid("clearChecked"); //取消所有行的选中
            $('#manager-prolist').datagrid("selectRow", rowIndex);  //选中当前行
        },
        onDblClickRow:function(field, row){
            var url =  Think.APP + "Product/edit&cmd=product_user&product_customer_id=" + row.product_customer_id;
            //打开弹出窗口

            Xwindow.baseDialogInit("#manager-prolist", "#add-prolist-form", "修改产品",url,row,3);
        }
    })


    //设置状态下拉框
    $(".node-status").combobox({
        data: [
            {id: 1, name: "出售中"},
            {id: 2, name: "已下架"},
            {id: 3, name: "已出售"},
            {id: 4, name: "纠纷中"}
        ],
        valueField: 'id',
        textField: 'name',
        panelHeight: "auto",
        editable: false, //不允许手动输入
    });



    //添加方法
    product_toolbar = {
        addProduct: function () {  //添加
            var url =  Think.APP + "Product/insert&cmd=product";
            //打开弹出窗口
            Xwindow.baseDialogInit("#manager-product", "#add-product-form", "添加基本产品信息",url);
        },
        deleteProduct:function(){
            var url = Think.APP + "Product/del&cmd=product";

            Xwindow.baseDelete( "#manager-product",url,"product_id");
        },


    };
    prolist_toolbar={
        addProlist: function () {  //添加
            var url =  Think.APP + "Product/insert&cmd=product_user";
            //打开弹出窗口
            Xwindow.baseDialogInit("#manager-prolist", "#add-prolist-form", "添加产品",url);
        },

        deleteProlist:function(){
            var url = Think.APP + "Product/del&cmd=product_user";
            Xwindow.baseDelete( "#manager-prolist",url,"product_customer_id");
        }
    }
})