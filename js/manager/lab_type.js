$(function () {

    // $(".menu-wrapp li").on("click",function() {
    //     $(this).find("ul").slideToggle("slow");
    // });

    $(".t1 li").mouseover(function () {
        $(this).css('background','#00c0b8');
        $(this).css('color','#ffffff');
    });
    $(".t1 li").mouseleave(function () {
        $(this).css('background','#c9cdc9');
        $(this).css('color','#000');
    });
    $(".t2 li").mouseover(function () {
        $(this).css('background','#00c0b8');
        $(this).css('color','#ffffff');
    });
    $(".t2 li").mouseleave(function () {
        $(this).css('background','#c9cdc9');
        $(this).css('color','#000');
    });

    $("#t1 li").on("click",function () {
        var tid = $(this).attr('value');
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/service/GetTypeServ.html",
            data: {"tid":tid},
            success: function (data) {
                if(data != null){
                    $("#t2").empty();
                    for (var i = 0; i<data.length;i++){
                        var str = "<a href='javascript:void(0)'><li value='"+data[i].lab_tid+"'>"+data[i].name+"</li></a>";
                        $("#t2").append(str);
                    }
                }
            },
            error:function (msg) {
                alert(msg);
            }
        });
    });

    $(".del").on("click",function() {
        var id = $(this).attr('value');
        layer.confirm('确定要删除吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/service/LabListingServ.html",
                data: {"id":id},
                success: function (data) {
                    if(data.ret == 1){
                        layer.msg(data.msg, {icon: 1});
                        window.setTimeout(widreload,1000);
                    }else if(data.ret == -1){
                        layer.msg(data.msg, {icon: 2});
                    }
                },
                error:function (msg) {
                    layer.msg('删除失败!', {icon: 2});
                }
            });
        }, function(){
            layer.msg('', {
                time: 100
            });
        });
    });

    function widreload() {
        window.location.reload();
    }

});
