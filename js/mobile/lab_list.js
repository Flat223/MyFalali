$(function () {
    $(document).on("click",".lab_li",function () {
        var id = $(this).attr('value');
        window.location.href="/lab/labDetail.html?id="+id;
    });

    var start = 15;
    $(window).scroll(function () {
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
        if(scrollTop + windowHeight >= scrollHeight){
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/service/GetScrollLabServ.html",
                data: {"start":start},
                success: function (data) {
                    if(data.length == 0){
                        layer.msg("没有数据啦~", {icon: 6});
                        return;
                    }
                    var process = 0;
                    var index  = layer.load(0, {shade: false});
                    for(var i = 0;i<data.length;i++){
                        var str = "<li class='lab_li' value='"+data[i].lab_id+"'>";
                        str += "<div class='coop_div'>";
                        str += "<div class='coop_div_1'>";
                        if(data[i].logo == ""){
                            str += "<img width='80%'  src='/images/temp_mobile/coop.png'><br>";
                        }else {
                            var logo = data[i].logo.split(",");
                            str += "<img width='80%'  src='"+logo[0]+"'><br>";
                        }
                        str += "<a>"+data[i].name+"</a>";
                        str += "</div>";
                        str += "<div class='coop_div_2'>";
                        var sa = (data[i].service_area).split(",");
                        str += "<div>服务领域:<a>"+sa[0]+"</a></div>";
                        str += "<div>评分:";
                        var starNum = data[i].stars;
                        if(starNum % 2 == 0){
                            for(var j = 0; j < starNum/2; j++){
                                str+= "<img width='8%' src='/images/pc/1y.png'>";
                            }
                        }else{
                            for(var k = 0; k < Math.floor(starNum/2); k++){
                                str+= "<img width='8%' src='/images/pc/1y.png'>";
                            }
                            str+= "<img width='8%' src='/images/pc/1yb.png'>";
                        }
                        str += "</div>";
                        str += "<div>电话:<a>"+data[i].manager_phone+"</a></div>";
                        str += "<div>地址:<a>"+data[i].address+"</a></div>";
                        str += "</li>";
                        $(".lab_ul").append(str);
                    }
                    process = 1;
                    if(process == 1){
                        layer.close(index);
                    }
                    layer.msg("为您列出"+data.length+"条信息");
                    start = start+15;
                },
                error:function (msg) {
                    layer.msg("加载失败", {icon: 5});
                }
            });
        }
    });
})