$(document).ready(function(){
	var questflag = 0;
			
	$('#save').on('click',function(){
		if(questflag == 1){
			return;
		}
        var name=$("input[name=typeName]").val();
        var level=$("input[name=level]").val();
        
        if(level == 1){
	    	parentid = 0;
        } else if(level == 2){
	        parentid = $("select[name=parentid] option:selected").val();
        } else {
	        parentid=$("input[name=parentid]").val();
        }
        
        if(name.length<1){
            layer.alert('请输入类名');
            return false;
        }
        questflag = 1;
        $.ajax({
            type: "POST",
            url: '/service/AddtypeServ.html',
            data: {"name":name,"parentid":parentid,"level":level},
            dataType: "json",
            success: function (data) {
                var alert = layer.alert(data.msg,function(){
	            	layer.close(alert);
	            	if(data.ret == 1){
		            	window.setTimeout(widreload,1000);
		                parent.location.reload();
	            	}
                });
            },
            error: function (data) {
                layer.alert('服务器错误,请稍后再试',{offset:'200px'});
            },
            complete:function(){
	            questflag = 0;
            }
        });
    });
    function widreload() {
        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
        parent.layer.close(index);
    }
});