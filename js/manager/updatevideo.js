
// $(".menu-wrapp li").on("click",function() {
//     $(this).find("ul").slideToggle("slow");
// })
$("button[name=checkurl]").on("click",function(){
    var url=$("input[name=videourl]").val();
    window.open(url);
})
$("button[name=update]").on("click",function(){
    var alert = layer.confirm("是否保存？", {
        title:"温馨提示",
        btn: ['确认','取消'] //按钮
    }, function(){
        layer.close(alert);
        Updatevideo();
    }, function(){

    });
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
    var id=$('input[name=videoid]').val();
    var title=$('input[name=title]').val();
    var category=$('select[name=category]').val();
    var intro=$('textarea[name=intro]').val();
    var content=ue.getContent();
    var url=$('input[name=videourl]').val();
    var images=$("#images")[0].src;
    data.id=id;
    data.title=title;
    data.url=url;
    data.category=category;
    data.intro=intro;
    data.url=url;
    data.content=content;
    data.images=images;
    // console.log(data);
    var url="/service/UpdatevideoServ.html";
    $.ajax({
        type: "POST",
        url: url,
        data: data,
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

