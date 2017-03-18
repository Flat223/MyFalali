<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class MyArticle extends BaseAction{

    public function action(){
        $user = UserAgent::getUser();
        if(empty($user)){
            FileUtil::load404Html();
            exit(0);
        }
        FileUtil::requireService("ArticleServ");
        $service=new ArticleServ();

        $article = $service->getMyArticle($user['mid']);
        if($article === false){
            FileUtil::load404Html();
            exit(0);
        }

        $params = array();
        $params['article'] = $article;
        return $params;
    }

}