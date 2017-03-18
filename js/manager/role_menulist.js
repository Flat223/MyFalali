$(function () {
    iChuk = iChukCore.Inital();
    layui.use(['layer'], function(){
        var layer = layui.layer;
 
    });
     
    
    $(".user-handle").on("click",function(){
        var type = $(this).attr("data-type");
        var brandid = $(this).attr("data-id");
        if(type == "delete")
        {
            var post = {};
			var keyvalue = {}; 
			keyvalue.status = 0;
			
			post.keyvalue = keyvalue;
			post.id = brandid;  
		    var url = "../service/editSystemMenuServ.html";
			iChuk.RequestData(url,"POST",post,DeleteCallBack);
        }
        else if(type == "reuse")
        {
            var post = {};
			var keyvalue = {}; 
			keyvalue.status = 1;
			
			post.keyvalue = keyvalue;
			post.id = brandid;  
		    var url = "../service/editSystemMenuServ.html";
			iChuk.RequestData(url,"POST",post,DeleteCallBack);
        }
		
    })
 
});

   
function DeleteCallBack(data)
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