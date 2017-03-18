define(function(require){
    var $ = require('jquery');
    var layer = require('layer/layer.js');
    require('swiper.min.js');
    // require('pc/user/logout.js');
    layer.config({
        path:'/js/layer/'
    });

    var handle = {
        init:function(){
            $(document).on('click','a[name=button]',function(){
                var aid=$("input[name=aid]").val();
                var mid=$("input[name=mid]").val();
                var comment=$("textarea[name=comment]").val();
                if(comment.length<=0){
                    layer.alert('请输入评论内容');
                    return false;
                }
                $.ajax({
                    type:"post",
                    dataType:"json",
                    url:"/service/NewscommentServ.html",
                    data:{"aid":aid,"mid":mid,"comment":comment},
                    success:function(data){
                        if(data.ret=='1'){
                            var a='';
                            $("textarea[name=comment]").val(a);
                            layer.alert("发布成功");
                            window.setTimeout(reload,500);
                        }else{
                            layer.alert(data.msg);
                        }
                    },
                    error:function(data){
                        layer.alert('服务器错误,请稍后再试',{offset:'200px'});
                    }
                });
            })


        }
    };

    function reload() {
        window.location.reload();
    }

    $(function(){
        handle.init();
    });
});