/**
 * Created by Thinkpad on 2017/2/9.
 */
$(function () {
    function is_weixin(){
        var ua = navigator.userAgent.toLowerCase();
        if(ua.match(/MicroMessenger/i)=="micromessenger") {
            $(".title").hide();
        }
    }
    is_weixin();
});