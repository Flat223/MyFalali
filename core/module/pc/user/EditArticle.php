<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class EditArticle extends BaseAction{

    public function action(){
        $aid = isset($_REQUEST['aid'])?$_REQUEST['aid']:"";
        FileUtil::requireService("ArticleServ");
        $service=new ArticleServ();
        $article=$service->getArticleById(1,$aid);
        $category=$service->getArticleCategory();
        $params = array();
        $params['style'] = 'user';
        $params['substyle'] = 'editArticle';
        $params['article'] = $article;
        $params['category'] = $category;
        return $params;
    }
}