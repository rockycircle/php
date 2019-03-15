var Store = function () {   //公共类
    var XStore = {
        converter: function (store, index) {  //转换器方法
            for (var i = 0; store.length; i++) {
                if (store[i] || false) {
                    if (store[i][0] || false) {
                        if (store[i][0] == index) {
                            return store[i][1];
                        }
                    } else {
                        return "";
                    }

                } else {
                    return "";
                }

            }
            return "";
        }
    }
    return XStore;
};
$(function () {
    
    $(".productcategory").combobox({
        valueField: 'category_id',
        textField: 'category_title',
        panelHeight: "auto",
        editable: false, //不允许手动输入
        data: app_data.info.productcategory("LIST")//将category_id对应的category_title显示
    });

    
     //状态
    $(".status").combobox({
        valueField: 'id',
        textField: 'name',
        panelHeight: "auto",
        editable: false, //不允许手动输入
        data: [{id: '1', name: '启用'},
              {id: '9',  name: '禁止'}]
    });
    
    $(".product").combobox({
        valueField: 'product_id',
        textField: 'product_title',
        panelHeight: "auto",
        editable: false, //不允许手动输入
        data: app_data.info.product("LIST")
    });
    
    $(".start_time_box").datetimebox({
            required: false,
            formatter: function (date) {
                var y = date.getFullYear();
                var m = date.getMonth() + 1;
                var d = date.getDate();
                var hh = date.getHours();
                var mm = date.getMinutes();
                var ss = date.getSeconds();
                return y + '-' + (m < 10 ? ('0' + m) : m) + '-' + (d < 10 ? ('0' + d) : d) + ' ' + (hh < 10 ? ('0' + hh) : hh) + ':' + (mm < 10 ? ('0' + mm) : mm) + ':' + (ss < 10 ? ('0' + ss) : ss);
            },
            parser: function (date) { return new Date(Date.parse(date.replace(/-/g, "/"))); },
            onSelect: function (date) { 
                $("#StartDT").val(date);
                var startDate = date;
                var endDate = $('#EndDT').val();
                if ((new Date(startDate)).dateDiff(endDate) > 0) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    var hh = date.getHours();
                    var mm = date.getMinutes();
                    var ss = date.getSeconds();
                    var dateStr =  y + '-' + (m < 10 ? ('0' + m) : m) + '-' + (d < 10 ? ('0' + d) : d) + ' ' + (hh < 10 ? ('0' + hh) : hh) + ':' + (mm < 10 ? ('0' + mm) : mm) + ':' + (ss < 10 ? ('0' + ss) : ss);
                    $("#EndDT").datetimebox('setValue', dateStr);
                }
            }
        });

    //下单客户
    $(".customer").combobox({
        valueField: 'customer_id',
        textField: 'customer_title',
        panelHeight: "auto",
        editable: false, //不允许手动输入
        data: app_data.info.customer("LIST")
    });

    //状态
    $(".select-status").combobox({
        valueField: 'id',
        textField: 'name',
        panelHeight: "auto",
        editable: false, //不允许手动输入
        data: [
            {id: 1,name:'出售中'},
            {id: 2, name: '已下架'},
            {id: 3, name: '已出售'},
            {id: 4, name: '纠纷中'},
        ],

    });
});




