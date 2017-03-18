define(function(require){
    var $ = require('jquery');
    var layer = require('layer/layer.js');
    layer.config({
        path:'/js/layer/'
    });

    var handle = {
        init:function(){

            $("span[name=sendtopic]").click(function(){
                $("div[name=usertopic]").css("display","block");
            })

            $(document).on("click","span[name=focus]",function(){
                var a=$(this);
                var circle_id=$(this).attr('circle_id');
                var url="/service/FocusServ.html";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {"circle_id":circle_id,"userid":userid},
                    dataType: "json",
                    success: function (data) {
                        if(data.ret=='1') {
                            a.text('已关注').css("background","#DD4F43").attr('name','remove');
                            // window.location.reload(-1);
                        }else{
                            alert(data.msg);
                        }
                    },
                    error: function (data) {
                        alert("error");
                    }
                });
            })

            $(document).on("click","span[name=remove]",function(){
                var a=$(this);
                var circle_id=$(this).attr('circle_id');
//        var url="/service/FocusServ.html";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {"circle_id":circle_id,"userid":userid},
                    dataType: "json",
                    success: function (data) {
                        if(data.ret=='1') {
                            a.text('已关注').css("background","#DD4F43").attr('name','remove');
                            // window.location.reload(1);
                        }else{
                            alert(data.msg);
                        }
                    },
                    error: function (data) {
                        alert("error");
                    }
                });
            })

            $(document).on("click","span[name=notinterest]",function(){
                var circle_id=$(this).attr('circle_id');
                var alert = layer.confirm("对此不感兴趣?", {
                    title:"温馨提示",
                    btn: ['确认','取消'] //按钮
                }, function(){
                    layer.close(alert);
                    handle.notinterest(circle_id,userid);
                }, function(){

                });
            })

            $(document).on("click",".list-one",function(){
                var id=$(this).attr('title');
                if(!id){
                    return false;
                }
                window.location.href="../info/interesting.html?id="+id;
            })

            $(document).on("click","span[name=topic]",function(){
                var circle_id=$("input[name=circle_id]").val();
                var mid=$("input[name=userid]").val();
                var title=$("input[name=topictitle]").val();
                var content=ue.getContent();
                if(title.length<=0){
                    layer.alert('请输入标题');
                    return false;
                }
                var url="/service/AddtopicServ.html";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {"circle_id":circle_id,"title":title,"content":content,"mid":mid},
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
            })

            $(document).on('click',"span[name=joincircle]",function(){
                var a=$(this);
                var circleid=$(this).attr('circle_id');
                var mid=$("input[name=userid]").val();
                var url="/service/FocusServ.html";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {"circle_id":circleid,"userid":mid},
                    dataType: "json",
                    success: function (data) {
                        if(data.ret=='1') {
                            // a.text('已关注').css("background","#DD4F43").attr('name','remove');
                            window.location.reload(-1);
                        }else{
                            layer.alert(data.msg);
                        }
                    },
                    error: function (data) {
                        alert("error");
                    }
                });
            })

            $(document).on('click',"span[name=deletecircle]",function(){
                var a=$(this);
                var circleid=$(this).attr('circle_id');
                var mid=$("input[name=userid]").val();
                var url="/service/DeleteFocusServ.html";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {"circle_id":circleid,"userid":mid},
                    dataType: "json",
                    success: function (data) {
                        if(data.ret=='1') {
                            window.location.reload(-1);
                        }else{
                            layer.alert(data.msg);
                        }
                    },
                    error: function (data) {
                        alert("error");
                    }
                });
            })

        },

        notinterest:function(cid,uid){
        var url="/service/NotInterestServ.html";
        $.ajax({
            type: "POST",
            url: url,
            data: {"circle_id":cid,"userid":uid},
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

    };

    $(function(){
        handle.init();
    });
});