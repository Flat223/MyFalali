<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Activity extends BaseAction{

    public function action(){
        $page=isset($_GET['page'])?$_GET['page']:1;
        $order=isset($_GET['order'])?$_GET['order']:'id';
        $info=isset($_GET['info'])?$_GET['info']:"";
        FileUtil::requireService("ArticleServ");
        $Articles=new ArticleServ();
        $num=10;
        /*$params['article']=$Articles->GetAriticleBynum($num,$page,$categoryid='0',$type=5,$order,$info);*/
        $params['count']=$Articles->getAllArticleCount('5',$info);
        $params['article'] = $Articles->getActArticle();
        $params['style'] = 'media';
		$params['substyle'] = 'activity';
        return $params;
    }

}