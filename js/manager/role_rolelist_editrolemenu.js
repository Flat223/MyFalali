var seletedids = "";
var defaultSelectedID = Array();
$(function () { 
    iChuk = iChukCore.Inital();
    layui.use(['layer'], function(){
        var layer = layui.layer;
 
    });
    
    InitEditInfo(roleid);
    
    $("#_submit_tourist").click(function(){				
		var post = {};  
		post.roleid = roleid;
		post.seletedids = seletedids;
		console.log(post);
		if(post.seletedids != "")
		{
			if($(this).hasClass("clicked"))
			{
				alert("发送中，请稍后...");
			}
			else{
				
				$(this).addClass("clicked");
				$(this).text("提交中，请稍后..."); 
			    var url = "../../service/setSystemRoleMenuServ.html";
				iChuk.RequestData(url,"POST",post,AddCallBack);
				
			}
		}
		else
		{
			alert("没有操作任何权限");
		}
		  
		 
	})
});

function InitEditInfo(id)
{
	var url = "../../service/getSystemRoleMenuServ.html";
    var post = {id:id};
	iChuk.RequestData(url,"POST",post,function(data){
		console.log(data);
		InitPowerList(data);
	});
}

function InitPowerList(data)
{ 
	var powerlist = $("#power-list");
    var html = "";
	if(data.ret == 1)
	{ 
		
		var menulistarray = Array();
		var firstmenuarray = SplitMenuList(0,data.data);
		
		for(var x = 0; x < firstmenuarray.length;x++)
		{
			var tempitem = firstmenuarray[x];
			var secondmenulist = SplitMenuList(firstmenuarray[x].id,data.data); 
			tempitem.lists = secondmenulist;
			menulistarray.push(tempitem);
		}
		var list = data.data;
        html += "<div class=\'power-section\'>\
		             <ul class=\'section-title\'>版本号：</ul>\
					 <ul class=\'section-content\'>Dynamic</ul>\
			     </div>"; 
        for(var i = 0;i < menulistarray.length ;i++)
		{
			var selected =  Number(menulistarray[i].selected);
			var selectedclass = (selected)?"on":""; 
			var checked = (selected)?"checked=\'checked\'":"";
			if(selected)
			{
				defaultSelectedID.push(menulistarray[i].id);
			}
			html += "<div class=\'power-section "+selectedclass+"\'>\
							<ul class=\'section-title\'>\
							    <li><input type=\'checkbox\' name=\'handle\' data-type=\'first\' data-menuid=\'"+menulistarray[i].id+"\' "+checked+"/></li>\
							    <li>"+menulistarray[i].name+"</li>\
						    </ul>\
							<ul class=\'section-content\'>";
							for(var x =0 ;x < menulistarray[i].lists.length;x ++)
							{ 
							    var iselected =  Number(menulistarray[i].lists[x].selected);
								var iselectedclass = (iselected)?"on":"";
			                    var ichecked = (iselected)?"checked=\'checked\'":"";
								if(iselected)
								{
									defaultSelectedID.push(menulistarray[i].lists[x].id);
								}
								html += "<div class=\'section-power-section "+iselectedclass+"\'>\
											<ul class=\'section-section-title\'>\
												<li><input type=\'checkbox\' name=\'handle\' data-type=\'second\' data-menuid=\'"+menulistarray[i].lists[x].id+"\' "+ichecked+"/></li>\
												<li>"+menulistarray[i].lists[x].name+"</li>\
											</ul>\
										</div>";
							}
			html +=       "</ul>\
						</div>";
	    } 
	}
	else
	{
		html += data.msg;
	}
	powerlist.html(html);
	InitCheck();
}

function InitCheck(){
    seletedids = defaultSelectedID.join(",");
    $("input[name=handle]").change(function(){
		var menuid = $(this).attr("data-menuid"); 
		var type = $(this).attr("data-type");
		var checked = $(this).is(':checked');
		if(type == "first")
		{
			$(this).parent().parent().parent().find(".section-content").find("input[name=handle]").trigger("click");
		}
		var selectedArray;
		selectedArray = seletedids.split(",");
		if($.inArray(menuid,selectedArray) == -1)
        {
            selectedArray.push(menuid);
        }
        else
        {
            selectedArray.splice($.inArray(menuid,selectedArray),1); 
        }
        for(var x = 0;x < selectedArray.length;x++)
        {
            if(selectedArray[x]=="")
            {
	            selectedArray.splice($.inArray(selectedArray[x],selectedArray),1); 
            }
        }
        seletedids = selectedArray.join(",");  
	})
}

//根据pid 返回菜单数据
function SplitMenuList(pid,data)
{
	var menuobj = Array();
	for(var y = 0; y < data.length;y++)
	{
		if(Number(data[y].pid) == Number(pid))
		{ 
			menuobj.push(data[y]);
		}
	}
	return menuobj;
}
 
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