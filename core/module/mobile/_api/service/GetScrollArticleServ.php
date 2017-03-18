<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetScrollArticleServ extends BaseAction{
    public function action(){
        $start = isset($_REQUEST['start'])?trim($_REQUEST['start']):0;
        FileUtil::requireService("ArticleServ");
        $serv = new ArticleServ();
        $data = $serv->getMobileArticle($start,15);
       /* echo json_encode($lab);*/
        return $data;
    }
}

?>