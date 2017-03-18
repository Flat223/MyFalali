<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class ArticleDetail extends BaseAction{

    public function action(){
        $id=isset($_REQUEST['id'])?$_REQUEST['id']:"";
        FileUtil::requireService("NewsServ");
        FileUtil::requireService("ArticleServ");
        $Article=new ArticleServ();
        $News=new NewsServ();
        $newsarticle=$News->GetNewsById($id);
        if($newsarticle == null || $newsarticle == false){
            $Article->Addviewnum($id);
            $newsarticle = $Article->GetAriticle_Writer($id);
        }
        else
        {
	        $News->Addviewnum($id);
        }
        $num = $Article->getLikesNumById($id);
        $params['new'] = $newsarticle;
        $params['num'] = $num;
        return $params;
    }

}