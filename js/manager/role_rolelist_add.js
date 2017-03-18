
$(function () { 
    iChuk = iChukCore.Inital();
    layui.use(['layer'], function(){
        var layer = layui.layer;
 
    });
     
 
});

$(function(){ 
	
	$("#_submit_tourist").click(function(){
		var post = {};  
		post.name = $("input[name=name]").val(); 
		 
		if(post.name != "")
		{
			if($(this).hasClass("clicked"))
			{
				alert("发送中，请稍后...");
			}
			else{
				
				$(this).addClass("clicked");
				$(this).text("提交中，请稍后...");
				console.log(post);  
			    var url = "../../service/addSystemRoleServ.html";
				iChuk.RequestData(url,"POST",post,AddCallBack);
				
			}
		}
		else{
			alert("请确认内容完整。");
		}
		 
	})
})

function AddCallBack(data)
{
	if(data.ret == 1)
	{
		window.location.href="../../rolemanager/roleList.html";
	}
	else 
	{
		alert(data.msg);
	}
}
