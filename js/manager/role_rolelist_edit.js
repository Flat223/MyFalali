
$(function () { 
    iChuk = iChukCore.Inital();
    layui.use(['layer'], function(){
        var layer = layui.layer;
 
    });
    
    InitEditInfo(roleid);
 
});

function InitEditInfo(id)
{
	var url = "../../service/getSystemRoleServ.html";
    var post = {id:id};
	iChuk.RequestData(url,"POST",post,function(data){
		if(data.ret == 1)
		{
			orginaldata = data.data[0];
			$("input[name=name]").val(orginaldata.name); 
		}
	});
}

$(function(){ 
	
	$("#_submit_tourist").click(function(){ 
		var post = {};
		var keyvalue = {}; 
		keyvalue.name = $("input[name=name]").val();  
		
		post.keyvalue = keyvalue;
		post.id = roleid;
		 
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
			    var url = "../../service/editSystemRoleServ.html";
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
