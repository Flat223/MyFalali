var orginaldata;
$(function () { 
    iChuk = iChukCore.Inital()
    layui.use(['layer'], function(){
        var layer = layui.layer;
 
    });
    
    iChuk.InitOnOff('#statusonoff');
    
    var post = {}; 
	post.status = 1;  
	post.pid = 0;  
	var url = "../../service/getSystemMenulistServ.html";
	iChuk.RequestData(url,"POST",post,function(data){ 
		console.log(data);
		if(data.ret == 1)
		{
		    var items = data.data;
			var html = '<select name="pid"><option value="0">根目录</option>';
			$.each(items,function(key,value){
			    html += '<option value="'+value.id+'">'+value.name+'</option>';
			});
			html += '</select>';
			$(".menu-tree").html(html);  
			InitEditInfo(id);
		} 
	});
	
	
     
 
});

function InitEditInfo(id)
{
	var url = "../../service/getSystemMenuServ.html";
    var post = {id:id};
	iChuk.RequestData(url,"POST",post,function(data){
		if(data.ret == 1)
		{
			orginaldata = data.data[0];
			$("input[name=menuname]").val(orginaldata.name);
			$("input[name=url]").val(orginaldata.url);
			$("input[name=icon]").val(orginaldata.icon);
			$(".ichuk-icon-chooser").find(".chooser-preview").html("<div class=\'"+orginaldata.icon+"\'></div>");
			$("input[name=status]").val(orginaldata.status);
			iChuk.InitOnOff('#statusonoff',null,orginaldata.status);
			$("input[name=remark]").val(orginaldata.remark);
			$("select[name=pid]").val(orginaldata.pid);
		}
	});
}

$(function(){
	iChuk.iChukIconChooser($(".ichuk-icon-chooser").find(".chooser-select"),function(data){
		$(".ichuk-icon-chooser").find(".chooser-preview").html("<div class=\'"+data+"\'></div>");
		$("input[name=icon]").val(data);
	});
	
	
	
	$("#_submit_tourist").click(function(){
		var post = {};
		var keyvalue = {}; 
		 
		keyvalue.name = $("input[name=menuname]").val();
		keyvalue.pid = $("select[name=pid]").val();
		keyvalue.url = $("input[name=url]").val();
		keyvalue.icon = $("input[name=icon]").val();
		keyvalue.status = $("input[name=status]").val();
		keyvalue.remark = $("input[name=remark]").val();
		
		
		post.keyvalue = keyvalue;
		post.id = orginaldata.id;
		 
		if(keyvalue.name != "")
		{
			if($(this).hasClass("clicked"))
			{
				alert("发送中，请稍后...");
			}
			else{
				
				$(this).addClass("clicked");
				$(this).text("提交中，请稍后...");
				console.log(post);  
			    var url = "../service/editSystemMenuServ.html";
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
		window.location.href = "../../rolemanager/menuList.html";
	}
	else 
	{
		alert(data.msg);
	}
}
