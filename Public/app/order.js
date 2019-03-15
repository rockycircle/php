$(function(){

    $('#manager-orders').datagrid({

        url:Think.APP+"Order/index",//用Extend控制器的index将数据取出来

        fit: true, //自适应
        striped: true,
        fitColumns: true,
        toolbar: '#orders-toolbar',
        border: false, //取消边框
        pagination: true, //分页开启
        pageSize: 50,
        rownumbers: true,
        columns: [[
            // {title: '', field: 'orders_id', checkbox: true},
            {field: 'orders_id', title: '订单编号', width: 100, align: 'center'},
            {field: 'orders_num', title: '数量', width: 100, align: 'center'},
            {field: 'orders_price', title: '总价', width: 100, align: 'center'},
            {field: 'orders_status', title: '状态', width: 100, align: 'center',class:"add-class-status",formatter: function (value, row, index) {
                if (value == 1) {
                    return "出售中";
                }else if(value==2){
                    return "已下架";
                }else if(value==3){
                    return "已出售"
                }else if(value==4){
                    return "纠纷中"
                }else{
                    return "无"
                }
            }
                },
            {field: 'orders_create', title: '创建日期',width: 100, align: 'center'},
            {field: 'customer_id',title: '下单客户',width: 100, align: 'center',formatter: function (value, row, index) {
                if (value <= 0) {
                    return "无";
                }
                return Store().converter(app_data.info.customer("STORE"), value);
            }},
        ]],
        onClickRow: function (rowIndex, rowData) {//单击事件
            $('#manager-orders').datagrid("clearChecked"); //取消所有行的选中
            $('#manager-orders').datagrid("selectRow", rowIndex);  //选中当前行
            $('#orders-product').datagrid({ url: Think.APP + "Order/ordersProduct&orders_id=" + rowData.orders_id });  //重新加载数据
        }
    })

    $('#orders-product').datagrid({
        fit: true, //自适应
        striped: true,
        fitColumns: true,
        toolbar: '#orders-product-toolbar',
        border: false, //取消边框
        pagination: true, //分页开启
        pageSize: 50,
        rownumbers: true,
        columns: [[
            { title: '', field: 'orders_product_id', checkbox: true },
            { field: 'product_id', title: '产品编号', width: 100, align: 'center' },
            { field: 'product_title', title: '名称', width: 100, align: 'center' },
            { field: 'category_id', title: '类目', width: 100, align: 'center' },
            { field: 'product_from', title: '来源', width: 100, align: 'center' },
            { field: 'product_price', title: '价格', width: 100, align: 'center' },
            { field: 'product_code', title: '优惠码', width: 100, align: 'center' },
        ]],
        onClickRow: function (rowIndex, rowData) {//单击事件
            $('#orders-product').datagrid("clearChecked"); //取消所有行的选中
            $('#orders-product').datagrid("selectRow", rowIndex);  //选中当前行

        }
    });

    orders_toolbar={
        deleteOrder:function(){
          var url=Think.APP+"Order/del&cmd=orders";
            Xwindow.baseDelete("#manager-orders", url, "orders_id");

        },
        deleteProduct:function(){
            var url=Think.APP+"Order/del&cmd=orders_product";
            Xwindow.baseDelete("#orders-product", url, "orders_product_id");
        },
        searchOrder:function (value, name) {

            $('#manager-orders').datagrid("load", {
                "orders_id": value,
            });
        },
        openSearchOrders:function(){
            //打开弹出窗口

            Xwindow.baseDialogInit("#manager-orders", "#search-form", "高级搜索",'search');
        },
        //获取二维码URL
        selectQrCode:function () {
            var selectData = $("#orders-product").datagrid("getSelections");
            if (selectData == "") {
                $.messager.alert('提示', '请选择数据');
            } else {
                var temp = selectData[0]['product_qr_code'];
                $.messager.alert("查看二维码",temp);
            }

        }
    }
})