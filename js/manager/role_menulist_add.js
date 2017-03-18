
$(function () { 
    iChuk = iChukCore.Inital();
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
		} 
	});
     
 
});

$(function(){
	iChuk.iChukIconChooser($(".ichuk-icon-chooser").find(".chooser-select"),function(data){
		$(".ichuk-icon-chooser").find(".chooser-preview").html("<div class=\'"+data+"\'></div>");
		$("input[name=icon]").val(data);
	});
	
	$("#_submit_tourist").click(function(){
		var post = {};
		post.name = $("input[name=menuname]").val();
		post.pid = $("select[name=pid]").val();
		post.url = $("input[name=url]").val();
		post.icon = $("input[name=icon]").val();
		post.status = $("input[name=status]").val();
		post.remark = $("input[name=remark]").val();
		 
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
			    var url = "../../service/addSystemMenuServ.html";
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
		window.location.reload();
	}
	else 
	{
		alert(data.msg);
	}
}
