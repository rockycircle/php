$(function(){
    //显示模态窗口
    $("#login").dialog({
        modal:true,  //是否遮罩
        closable:false  //关闭按钮取消
    });

    //验证账号,不准为空
    $(".textbox").validatebox({
        required:true,
        missingMessage:"请填写账号！"
    });
    
    //验证密码,不准为空
    $(".password").validatebox({
        required:true,
        missingMessage:"请填写密码！"
    });

    if(!$(".textbox").validatebox("isValid")){  //没有验证通过
             $(".textbox").focus();
      }
       else if(!$(".password").validatebox("isValid")){   //密码验证没有通过
       	     $(".password").focus();
    }
    
    //登录
    $("#login-button").click(function(){
       if(!$(".textbox").validatebox("isValid")){  //没有验证通过
             $(".textbox").focus();
       }
       else if(!$(".password").validatebox("isValid")){   //密码验证没有通过
       	     $(".password").focus();
       }
       else{
         $.ajax({
             url:Think.APP+"Public/login",
             type:"post",
             data:{
                 "username":$(".textbox").val(),
                 "password":$(".password").val()
             },
             dataType:"json",
             beforeSend:function(){
                $.messager.progress({
                   "title":"Loading",
                   "msg":"正在登录中..."
                });
             },
             success:function(data){
                $.messager.progress('close');  //关闭进度条  
                if(data.success==1){  //表示登录成功
                   window.location.href = Think.APP+"Index/manager";
                }else{ //表示登录失败
                   $.messager.alert('提示',data.msg,'error');
                }
                
             }
         });

       }
    });



});