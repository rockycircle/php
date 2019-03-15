$(function(){
    //自定规则
    $.extend($.fn.validatebox.defaults.rules, {
        equals: {
        validator: function(value, param){
            return value == $(param[0]).val();
        },
        message: '两次输入的密码不一致'}
    });
});

