define(function(require){


    var handle = {
        init:function(){

            $(document).on('click','span[name=delete]',function(){
                var id=$(this).attr('newsid');
                var alert = layer.confirm("是否删除？", {
                    title:"温馨提示",
                    btn: ['确认','取消'] //按钮
                }, function(){
                    layer.close(alert);
                    deletenews(id);
                }, function(){

                });
            })
            $("#search").click(function(){
	            var key=$("#keyword").val();
	            window.location.href="/goodsmanager/couponList.html?key="+key;
            });
            $(document).on('click','span[name=sendcash]',function(){
                var id=$(this).attr('newsid');
				layer.open({
		            type: 1
		            ,title: "发送代金券"
		            ,area: '300px;'
		            ,shade: 0.5
		            ,btn: ["确定", "取消"]
		            ,btnAlign: 'c'
		            ,content: '<div style="padding: 20px; line-height: 22px; font-weight: 300;">手机号   <input class="fund" type="number" style="line-height: 25px;border:1px solid #ddd;padding-left:5px;"></div>'
		            ,success: function(layero){
		                var btn = layero.find('.layui-layer-btn');
		                btn.find('.layui-layer-btn0').click(function(){
		                var mobile = $.trim($(".fund").val());
		                sendcash(mobile,id);
		                });
		            }
		        });
            })
            function deletenews(id){
                var url="/service/DeleteCouponServ.html";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {"id":id},
                    dataType: "json",
                    success: function (data) {
                        if(data.ret=='1'){
                            window.location.reload(-1);
                        }else{
                            layer.alert(data.msg);
                        }
                    },
                    error: function (data) {
                        layer.alert("error");
                    }
                });
            }
            function sendcash(mobile,id){
	             var url="/service/getCouponNewServ.html";
				 $.ajax({
                    type: "POST",
                    url: url,
                    data: {'cid':id,'mobile':mobile},
                    dataType: "json",
                    success: function (data) {
                        if(data.ret=='1'){
                           layer.alert(data.msg);
                        }else{
                            layer.alert(data.msg);
                        }
                    },
                    error: function (data) {
                        layer.alert("error");
                    }
                });

            }
        }
    };

    $(function(){
        handle.init();
    });
});