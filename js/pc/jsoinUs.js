define(function(require) {
    var $ = require('jquery');
    var layer = require('layer/layer.js');
    layer.config({
        path:'/js/layer/'
    });

    $(".acc-2 ").on("click",function() {
        $(".accordion2").fadeToggle("slow");
    });
    $(".acc-1 ").on("click",function() {
        $(".accordion1").fadeToggle("slow");
    });
    $(".acc-3 ").on("click",function() {
        $(".accordion3").fadeToggle("slow");
    });
    $(".acc-4 ").on("click",function() {
        $(".accordion4").fadeToggle("slow");
    });
    $(".acc-5 ").on("click",function() {
        $(".accordion5").fadeToggle("slow");
    });
    $(".acc-6 ").on("click",function() {
        $(".accordion6").fadeToggle("slow");
    });
    $(".acc-7 ").on("click",function() {
        $(".accordion7").fadeToggle("slow");
    });

    $(".layui-unselect").on("click",function () {
            $(this).addClass("layui-form-radioed").siblings().removeClass("layui-form-radioed");
        if ($(this).hasClass("layui-form-radioed")){
            $(this).find("layui-icon").html("&#xe643;");
        }else {
            $(this).find("layui-icon").html("&#xe63f;");
        }
    })
    // layui.use('form', function(){
    //     var form = layui.form();
    //
    //     //监听提交
    //     form.on('submit(formDemo)', function(data){
    //         layer.msg(JSON.stringify(data.field));
    //         return false;
    //     });
    // });
    // layui.use('layer', function(){
    //     var $ = layui.jquery, layer = layui.layer;
    //     //触发事件
    //     var active = {
    //
    //         notice: function(){
    //             示范一个公告层
    //             layer.open({
    //                 type: 1
    //                 ,title: false
    //                 ,area: ['700px;',"600px;"]
    //                 ,shade: 0.8
    //                 ,id: 'LAY_layuipro'
    //                 ,moveType: 1
    //                 ,content: $('#apply-position')
    //                 ,success: function(){
    //
    //                 }
    //             });
    //         }
    //     };
    //     $('.layui-btn').on('click', function(){
    //         var type = $(this).data('type');
    //         active[type] ? active[type].call(this) : '';
    //     });
    // });
    // layui.use('upload', function(){
    //     layui.upload({
    //         url: '' //上传接口
    //         ,success: function(res){
    //             console.log(res)
    //         }
    //     });
    // });
    $(".apply-btn").on("click",function () {
        layer.open({
            type: 1
            ,skin: 'layui-layer-rim'
            ,title: false
            ,area: ['700px;',"800px;"]
            ,content: $('#apply-position')
            ,yes: function(){

            }
        });
    })
 });


