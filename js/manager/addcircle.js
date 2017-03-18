
$(function () {

    // $(".menu-wrapp li").on("click",function() {
    //     $(this).find("ul").slideToggle("slow");
    // });

    $("button[name=checkurl]").on("click",function() {
        var id = $(this).attr('value');
        layer.open({
            type: 2,
            title: '添加标签',
            shadeClose: true,
            shade: 0.8,
            area: ['380px', '30%'],
            content: 'http://d31.ichuk.com/circle/addlabel.html'

        });
    });

    $("button[name=update]").on("click",function(){
        var data={};
        var title=$("input[name=title]").val();
        var label=$("input[type=checkbox]");
        var s='';
        for(var i=0;i<label.length;i++){
            if(label[i].checked) //取到对象数组后，我们来循环检测它是不是被选中
                s+=label[i].value+',';   //如果选中，将value添加到变量s中
        }
        var intro=$("textarea[name=intro]").val();
        var logo=$("#images")[0].src;
        var url="/service/addcircleServ.html";
        data.title=title;
        data.logo=logo;
        data.s=s;
        data.intro=intro;
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "json",
            success: function (data) {
                if(data.ret=='1'){
                    parent.location.reload(-1);
                }else{
                    layer.alert(data.msg);
                }
            },
            error: function (data) {
                layer.alert("error");
            }
        });

    })
});
