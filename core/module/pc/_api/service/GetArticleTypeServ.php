<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetArticleTypeServ extends BaseAction{
    public function action(){
        $id = isset($_REQUEST['id'])?$_REQUEST['id']:0;
        FileUtil::requireService("ArticleServ");
        $serv = new ArticleServ();
        $data = $serv->getSecondArticleTypeById($id);
        return $data;
    }
}