<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Addvideo extends BaseAction{

    public function action(){
        FileUtil::requireService("ArticleServ");
        $Article=new ArticleServ();
       /* FileUtil::requireService('VideoServ');
        $serv = new VideoServ();
        $type = $serv->getVideoType();*/
        $params['category']=$Article->getArticleByView(3,'id desc',$type=4);
        $params['style'] = 'media';
       /* $params['category'] = $type;Z*/
       	$params['substyle'] = 'video';
        return $params;
    }

}