$(function(){

	//tab栏
    $("#tabs").tabs({
      border:false,  //不显示边框
      fit:true,
    });

    //导航栏
    $("#nav").tree({
        url:Think.APP+"NavTree/tree",
        lines:true,
        onLoadSuccess:function(node,data){
        	if(data){
        		$.each(data,function(index,value){
        			if(this.state=='closed'){  //将关闭的展开
        				$("#nav").tree('expandAll');  //全部展开
        			}
        		});
        	}
        },
        onClick:function(node){
            //判断是否存在，如果存在，选中它
            //改变hash值
            window.location.hash="cmd/xmain>"+node.name;
            if($("#tabs").tabs("exists",node.text)){
                $("#tabs").tabs("select",node.text);
            }else{
                if(node.level==2){
                     //添加tab
                    $("#tabs").tabs("add",{
                        'title':node.text,
                        'iconCls':node.iconCls,
                        'closable':true,
                        'href':Think.APP+node.name
                    });
                }

            }
        }
    });
});