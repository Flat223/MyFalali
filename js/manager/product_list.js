$(function () {
    var ch = GetQueryString('ch');
    $(".chart-wrapp ul li").each(function () {
        if(ch == null){
            ch = 1;
        }
        if ($(this).attr('value') == ch) {
            $(this).addClass("chart-this");
        }
    });

    $(".ptype").on('click',function () {
        var id = $(this).attr('value');
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/service/RecommendProductServ.html",
            data: {"lv":id,"flag":2},
            success: function (product) {
                console.log(product.length);
                $(".pinfo").empty();
                $("#page").hide();
                if(product != null){
                    $("#num").text(product.length);
                    for(var i = 0;i<product.length;i++){
                        var str = "<tr class='pinfo'>";
                        if(product[i].images != ""){
                            var image = product[i].images.split(",");
                            str += "<td><img style='width:139px;height:105px'src='"+image[0]+"' /></td>";
                        }else{
                            str += "<td><img style='width:139px;height:105px'src='' /></td>";
                        }
                        str += "<td>"+product[i].name+"</td>";
                        str += "<td style='text-align: center'>"+product[i].price+"</td>";
                        if(product[i].bname != null){
                            str += "<td>"+product[i].bname+"</td>";
                        }else{
                            str += "<td>"+'无'+"</td>";
                        }
                        str += "<td>"+product[i].sale_num+"</td>";
                        str += "<td>"+product[i].tname+"</td>";
                        var time = product[i].time;
                        var date = new Date();
                        date.setTime(time * 1000);
                        str += "<td>"+date.toLocaleString()+"</td>";
                        str += "<td style='text-align: center'>";
                        str	+= "<span class='look' value='"+product[i].pid+"'style='text-align:center;cursor: pointer;color: white;background: #00bfb8;width: 100px;height: 30px;line-height: 30px;display: inline-block;'>查看</span>";
                        str	+= "<span class='rmdNo' value='"+product[i].pid+"'style='text-align:center;cursor: pointer;color: white;background: #f23e47;width: 100px;height: 30px;line-height: 30px;display: inline-block;'>取消推荐</span>";
                        str += "</td>";
                        str += "<br>";
                        str += "</tr>";
                        $("#tab").append(str);
                    }
                }
            },
            error:function (msg) {
                layer.msg('操作失败！', {icon: 2});
            }
        });
    });

    $(document).on('click','.look',function(){
        var id = $(this).attr('value');
        window.location.href="http://d27.ichuk.com/goods/detail.html?pid="+hex_md5(id);
    });

    $(document).on('click',".rmdNo",function(){
        var id = $(this).attr('value');
        layer.confirm('确定取消推荐？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/service/RecommendProductServ.html",
                data: {"id":id,"flag":1},
                success: function (data) {
                    if(data.ret == 1){
                        layer.msg(data.msg, {icon: 1});
                        window.setTimeout(widreload,1000);
                    }else if(data.ret == -1){
                        layer.msg(data.msg, {icon: 2});
                    }
                },
                error:function (msg) {
                    layer.msg('操作失败！', {icon: 2});
                }
            });
        }, function(){
            layer.msg('', {
                time: 100
            });
        });
    });

    $(".rmd").on('click',function(){
        var id = $(this).attr('value');
        /*var ptid = $(this).attr('ptid');*/

        layer.confirm('确定推荐？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/service/RecommendProductServ.html",
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
                    layer.msg('操作失败！', {icon: 2});
                }
            });
        }, function(){
            layer.msg('', {
                time: 100
            });
        });
    });

    $(".down").on('click',function(){
        var id = $(this).attr('value');
        layer.prompt({title: '下架理由', formType: 2}, function(text, index) {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '/service/DownProductServ.html',
                data: {"id":id,'text':text},
                success: function (data) {
                    if(data.ret == 1){
                        layer.msg(data.msg,{"icon":1});
                        window.location.reload();
                    }else{
                        layer.msg(data.msg,{"icon":0});
                    }
                },
                error: function (data) {
                    layer.alert("error");
                }
            });
        })
    });

    $(".del").on('click',function(){
        var id = $(this).attr('value');
        layer.confirm('确定删除？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/service/DeleteProductServ.html",
                data: {"id":id},
                success: function (data) {
                    if(data.ret == 1){
                        layer.msg(data.msg, {icon: 1});
                        window.setTimeout(widreload,500);
                    }else if(data.ret == -1){
                        layer.msg(data.msg, {icon: 2});
                    }
                },
                error:function (msg) {
                    layer.msg('操作失败！', {icon: 2});
                }
            });
        }, function(){
            layer.msg('', {
                time: 100
            });
        });
    });

    $("span[name=search]").on('click',function(){
        var info=$("input[name=info]").val();
        var ch = $(this).attr('value');
        window.location.href="/goodsmanager/productList.html?ch="+ch+"&info="+info;
    });

    function GetQueryString(name) {
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if(r!=null)return  unescape(r[2]); return null;
    }

    function widreload() {
        window.location.reload();
    }
});
