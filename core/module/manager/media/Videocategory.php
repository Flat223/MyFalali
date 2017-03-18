<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Videocategory extends BaseAction{

    public function action()
    {
        FileUtil::requireService("ArticleServ");
        $Article=new ArticleServ();
        $params['article']=$Article->getArticleByView(3,'id desc',$type=4);
        $params['substyle'] = 'video';
        return $params;
    }
}