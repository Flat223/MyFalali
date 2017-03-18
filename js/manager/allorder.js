$(document).ready(function(){	
	var url = "";
	var order_type = $('input[name=order_type]').val();
	if(order_type == 1){
		url = "../ordermanager/requireOrder.html?";
	} else if(order_type == 2){
		url = "../ordermanager/purchaseOrder.html?";
	} else if(order_type == 3){
		url = "../ordermanager/collegeOrder.html?";
	} else {
		url = "../ordermanager/personalOrder.html?";
	}
	
	$("#goto").on("click",function(){
		var page = $.trim($("#page_num").val());
		if(page == ""){
			return;
		}
		var baseurl = $('input[name=baseurl]').val();
		window.location.href = baseurl+"&page="+page;
	});
	$("#page_num").keypress(function(e){
		if(e.keyCode == 13){
			$("#goto").click();
		}
	});
	
	$('.search').on('click',function(){
	    var condition = setLocationUrl();
	    var state = $('#status_list li.chart-this').val();
		if(order_type == 1){
		    condition += '&agree='+state;
	    } else {
		    condition += '&state='+state;
	    }
	    if(condition == ""){
		    layer.alert('请填写搜索条件',{offset:'200px'});
	    } else {
			window.location.href = url + condition;
	    }
	});
	
	$('#status_list li').click(function(){
	    var condition = setLocationUrl();
	    var state = $(this).val();
	    if(order_type == 1){
		    condition += '&agree='+state;
	    } else {
		    condition += '&state='+state;
	    }
	    window.location.href = url + condition;
	});
	
	
	function setLocationUrl(){
		var order_code=$('input[name=order_code]').val();
	    var applier=$('input[name=applier]').val();
	    var product=$('input[name=product]').val();
	    var start_time=$('input[name=start_time]').val();
	    var end_time=$('input[name=end_time]').val();
	    
	    var condition = "";
	    if(order_code != ""){
	        condition+= 'order_code='+order_code;
	    }
	    if(applier != ""){
	        condition+= '&applier='+applier;
	    }
	    if(product != ""){
	        condition+= '&product='+product;
	    }
	    if(start_time != ""){
	        condition+= '&start_time='+start_time;
	    }
	    if(end_time != ""){
	        condition+= '&end_time='+end_time;
	    }
	    return condition;
	}	
});
