<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Cooperate extends BaseAction{

    public function action(){
        $page=isset($_GET['page'])?$_GET['page']:1;
        $order=isset($_GET['order'])?$_GET['order']:'id';
        $info=isset($_GET['info'])?$_GET['info']:"";
        FileUtil::requireService("ArticleServ");
        $Articles=new ArticleServ();
        $num=10;
        /*$params['article']=$Articles->GetAriticleBynum($num,$page,$categoryid='0',$type=2,$order,$info);*/
        $params['article'] = $Articles->getCoopArticle();
        $params['count']=$Articles->getAllArticleCount('2',$info);
       /* echo json_encode($params['article']);exit(0);*/
        $params['style'] = 'media';
		$params['substyle'] = 'cooperate';
        return $params;
    }

}