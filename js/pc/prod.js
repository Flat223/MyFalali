$(function(){
    var labp = $(".lab-province");
    var labc = $(".lab-city");
    var labt = $(".lab-town");
    var conp = $(".lab-province-item");
    var conc = $(".lab-city-item");
    var cont = $(".lab-town-item");
  $("#area").click(function () {
      $(".address-con").fadeIn();
      $(".free-con").hide();
  })
    $(".free").click(function () {
        $(".free-con").fadeIn();
        $(".address-con").hide();
    })
    $(".free ul li").click(function () {
        $(".free-con").fadeOut();
    })
    $(".address-con e").click(function () {
        $(".address-con").fadeOut();
    })
    $(".free-con>ul>li").on("click",function () {
        $(this).addClass("d-show").siblings().removeClass("d-show");
        $(".free-con").fadeOut();
        var ttext = $(this).text();
        var ftext = $(".free span").text(ttext);
        var labttext = $(".lab-town").text(ttext);
        console.log(ftext);
    })
    $(".lab-province-item>span").on("click",function () {
        if ($(".lab-tab>ul>li.lab-city").is(":hidden")){
            $(".lab-tab>ul>li.lab-city").css("display","inline-block");
            $(this).parents().removeClass("lab-show");
            $(".lab-city-item").addClass("lab-show");
        }else{
            $(".lab-tab>ul>li.lab-city").css("display","none");
        }
    })
    $(".lab-city-item").on("click", "span" ,function () {
        if ($(".lab-tab>ul>li.lab-town").is(":hidden")){
            $(".lab-tab>ul>li.lab-town").css("display","inline-block");
            $(this).parents().removeClass("lab-show");
            $(".lab-town-item").addClass("lab-show");
        }else{
            $(".lab-tab>ul>li.lab-town").css("display","none");
        }
    })
    $(".lab-province").on("click",function () {
        if (labc.is(":visible") && labt.is(":visible")){
            labc.hide();
            labt.hide();
            conc.hide();
            cont.hide();
            conp.css("animate-delay","2s").addClass("lab-show");
        }else if(labc.is(":visible")){
            labc.hide();
            conc.hide();
            conp.css("animate-delay","2s").addClass("lab-show");
        }

    })
    $(".lab-city").on("click",function () {
        if (labt.is(":visible")){
            labt.removeClass("lab-show").hide();
            cont.removeClass("lab-show").hide();
            conc.css("animate-delay","2s").addClass("lab-show");
        }
    })
    $(".lab-town-item").on("click","span",function () {
        var stext = $(this).text();
        var selecttext = $("#place").text(stext);
        var labttext = $(".lab-town").text(stext);
        $(".address-con").fadeOut("slow");
    })
})