define(function(require){


    var handle = {
        init:function(){

            $(document).on('click','.del',function(){
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
            function deletenews(id){
                var url="/service/DeletenewsServ.html";
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
        }
    };

    $(function(){
        handle.init();
    });
});