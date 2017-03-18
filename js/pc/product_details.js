$(function(){
	
	$("div.curs").on("click",function(){
		var pid=$(this).attr("value");
		window.location.href="../../goods/detail.html?pid="+hex_md5(pid);
	});
	$(".littleimg").on("mouseover",function(){
		var imgsrc=$(this).attr("src");
		$("#bigimg").attr('src',imgsrc);
		$("#bigimg").attr('jqimg',imgsrc);
	});
    //放大镜
    $(".jqzoom").jqueryzoom({
        xzoom:400,  //弹出框宽度
        yzoom:400,	//弹出框高度
        offset:10,
        position:"right",
        preload:1,
        lens:1
    });
    $("#spec-list").jdmarquee({
        deriction:"left",
        width:420,
        height:64,
        step:2,
        speed:4,
        delay:10,
        control:true,
        _front:"#spec-right",
        _back:"#spec-left"
    });
    $("#spec-list img").bind("mouseover",function(){
        var src=$(this).attr("src");
        $("#spec-n1 img").eq(0).attr({
            src:src.replace("\/n5\/","\/n1\/"),
            jqimg:src.replace("\/n5\/","\/n0\/")
        });
        $(this).css({
            "border":"1px solid #ccc",
            "padding":"2px"
        });
    }).bind("mouseout",function(){
        $(this).css({
            "border":"1px solid #fff",
            "padding":"2px"
        });
    });
    //省市区
    // _init_area();
    //选择规格
    var pp_number = $(".pp_number ul li");
    pp_number.on("click",function () {
        $(this).css("border-color","#e9201f").siblings().css("border-color","#ddd")
        $(this).find("i").css("display","block");
        $(this).siblings().find("i").css("display","none")
    });
	
    //购买数量
    var set_num = document.getElementById("set_num");
    var set_a = set_num.getElementsByTagName("a");
    var up = set_a[0];
    var down = set_a[1];
    var input = set_num.getElementsByTagName("input")[0];
    up.onclick = add;
    down.onclick = minus;
    function add() {
        input.value++;
    };
    function minus() {
        input.value==1||input.value--;
    };
    var thisval = null;
    input.onfocus = function () {
        thisval=input.value;
    };
   
    //商品详情选项卡
    var tab_nav = document.getElementById("tab_nav");
    var tab_p = tab_nav.getElementsByTagName("p");
    var tab_box = document.querySelectorAll(".tab_box");
    for(var i =0; i<tab_p.length; i++){
        tab_p[i].index = i;
        tab_p[i].onclick = function () {
            for(var n=0; n<tab_box.length; n++){
                tab_p[n].className = "";
                tab_box[n].style.display = "none";
            };
            this.className = "act";
            tab_box[this.index].style.display = "block";
        };
    };




})