define(function(require){
    var $ = require('jquery');
    require('swiper.min.js');
    // $(".menu-wrapp li").on("click",function() {
    //     $(this).find("ul").slideToggle("slow");
    // });
    /*require('manager/cyupload.js');*/

    var handle = {
        init:function(){
            $("button[name=update]").on("click",function(){
                if(confirm("是否保存")){
                    Updatevideo();
                }
            })

            $.cyupload({
                elem:'#brand_upimg',
                btnName:"请选择",		//按键名称
                infoElementId:"",	//上传状态信息包装元素id
                maxFilesize:10485760,
                uploadUrl:'/service/uploadimgServ.html',
                fileFilter:'',
                upfileParam:'upload_file_input',
                success:function(url){
                    $('#images').attr('src',url['file_url']);
                }
            });

            //更新
            function Updatevideo(){
                var data={};
                var title=$('input[name=title]').val();
                var category=$('select[name=category]').val();
                var intro=$('textarea[name=intro]').val();
                var content=ue.getContent();
                var url=$('input[name=videourl]').val();
                var images=$("#images")[0].src;
                if(title.length<1){
                    alert('请添加标题');
                    return false;
                }
                if(category.length<1){
                    alert('请选择分类');
                    return false;
                }
                if(url.length<1){
                    alert('请填写视频地址');
                    return false;
                }
                data.title=title;
                data.url=url;
                data.category=category;
                data.intro=intro;
                data.url=url;
                data.content=content;
                data.images=images
                console.log(data);
                var url="/service/addvideoServ.html";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    dataType: "json",
                    success: function (data) {
                        if(data.ret=='1'){
                            window.location.reload(-1);
                        }else{
                            alert(data.msg);
                        }
                    },
                    error: function (data) {
                        alert("error");
                    }
                });
            }


        }
    };

    $(function(){
        handle.init();
    });
});