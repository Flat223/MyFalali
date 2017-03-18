<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Cooperate extends BaseAction{
    public function action(){
        $page=isset($_GET['page'])?$_GET['page']:1;
        FileUtil::requireService("ArticleServ");
//        FileUtil::requireService('PageUtil');
        FileUtil:: requireService("ProductServ");
        $service = new ProductServ();
        $Article=new ArticleServ();
        /*$params['article']=$Article->GetAriticleBynum(10,$page,$categoryid='0',2,'id');*/
        $params['article'] = $Article->getCoopArticle();
        $params['count']=$Article->getAllArticleCount('2');
        FileUtil::requireService('PageUtil');
        $pageUtil = new PageUtil(10,$params['count'],$page);
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/info/cooperate.html?';
        $params['pager'] = $pageUtil;
        return $params;
    }
}