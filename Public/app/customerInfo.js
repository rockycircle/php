$(function () {
    //用户数据列表
    $('#manager-customerinfo').datagrid({
        url: Think.APP + "CustomerInfo/index&cmd=Customer", //数据源
        fit: true, //自适应
        striped: true,
        fitColumns: true,
        toolbar: '#customerinfo-toolbar',
        border: false, //取消边框
        pagination: true, //分页开启
        pageSize: 50,
        rownumbers: true,
        columns: [[
            {field: 'customer_id', title: 'ID', width: 100, align: 'center', checkbox: true},
            {field: 'customer_title', title: '客户名称', width: 100, align: 'center'},
            {field: 'wx_code', title: '微信CODE', width: 100, align: 'center'},
            {field: 'customer_credit_grade', title: '信用分', width: 100, align: 'center'},
            {field: 'customer_balance', title: '可用余额', width: 100, align: 'center'},
            {field: 'customer_no_balance', title: '冻结余额', width: 100, align: 'center'},
            {field: 'seller_orders_num', title: '出售单数', width: 100, align: 'center'},
            {field: 'buy_orders_num', title: '购买单数', width: 100, align: 'center'},
            {field: 'customer_create_date', title: '创建日期', width: 100, align: 'center'}
            ]],
        onClickRow: function (rowIndex, rowData) {//单击事件
            $('#manager-customerinfo').datagrid("clearChecked"); //取消所有行的选中
            $('#manager-customerinfo').datagrid("selectRow", rowIndex);  //选中当前行
        },
        onDblClickRow: function (field, row) {
            var url = Think.APP + "CustomerInfo/edita&cmd=Customer&customer_id=" + row.customer_id;
            //打开弹出窗口
            Xwindow.baseDialogInit("#manager-customerinfo","#detail-customerinfo-form","客户详细信息",url,row,3);
            var rowss = $("#manager-customerinfo").datagrid("getSelections");
            var imm= rowss[0].wx_img;
            document.getElementById("tou").src = rowss[0].wx_img;
        }

    });



    //添加方法
    customerinfo_toolbar = {

        delete: function () {
            var url = Think.APP + "CustomerInfo/del&cmd=Customer";
            Xwindow.baseDelete("#manager-customerinfo", url, "customer_id");
        },
        search:function(value, name){
            $('#manager-customerinfo').datagrid("load", {
                "customer_title": value
            });
        },
      release:function (){
          var url = Think.APP + "CustomerInfo/rel&cmd=Customer";
          //打开弹出窗口
          var rows = $("#manager-customerinfo").datagrid("getSelections");
          Xwindow.baseDialogInit("#manager-customerinfo","#release-form","解冻金额",url,rows[0],2,true);

      },
        openSearchCustomerInfo:function(){
            //打开弹出窗口
            Xwindow.baseDialogInit("#manager-customerinfo", "#search-customer-form", "高级搜索",'search');
        },

    };



});


