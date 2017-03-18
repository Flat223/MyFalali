$(function () {
    $("#search").on('click',function(){
        var info=$('.home_search').val();
        var url='../info/search.html?info=';
        if(info.length<1){
            alert('请输入内容');
            return;
        }
        window.location.href=url+info;
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
                url: "/service/GetScrollArticleServ.html",
                data: {"start":start},
                success: function (data) {
                    if(data.length == 0){
                        layer.msg("没有数据啦~", {icon: 6});
                        return;
                    }
                    var process = 0;
                    var index  = layer.load(0, {shade: false});
                    for(var i = 0;i<data.length;i++){
                        var str = "<li class='home_div41' name='detail'>";
                        var id = data[i]['id'];
                        str += "<a href='/news/articleDetail.html?id="+id+"' class='clearfix'>";
                        if(data[i].images == ""){
                            str += "<div class='news left' style='background-image: url('/images/temp_pc/0.jpg')'>";
                        }else {
                            str += "<div class='news left' style='background-image: url("+data[i].images+")'>";
                        }
                        str += "</div>";
                        str += "<div class='right home_context'>";
                        str += "<div class='new-title-wrap'>";
                        str += "<span style='color:#333333'>"+data[i].title+"</span>";
                        str += "</div>";
                        str += "<div class='news_type'>"+data[i].cname+"</div>";
                        str += "<span class='view'>"+data[i].view_num+"人看过</span>";
                        str += "</div></a></li>";
                        $("#article").append(str);
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

});

