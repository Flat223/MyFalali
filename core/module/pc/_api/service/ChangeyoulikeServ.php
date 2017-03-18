<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class ChangeyoulikeServ extends BaseAction{
    public function action(){
        FileUtil::requireService("ProductServ");
        $Product=new ProductServ();
        $result=$Product->getUserRecommendProduct(1,4);
        $html=" ";
        foreach($result as $v){
            $link= md5($v['pid']);
            $images=explode(',',$v['images']);
            $image=$images[0];
            $name=$v['name'];
            $href="/goods/detail.html?pid=$link";
            $html.="<div class='imgbox'><a href=$href><img style='width:105px;height:105px' class='recomImage' src=$image />";
            $html.="</a>";
            $html.="<span class='des'>";
            $html.=$name;
            $html.="</span>";
            $html.="</div>";
        }
        exit(json_encode($html));

    }
}