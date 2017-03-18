<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Activity extends BaseAction{
    public function action(){
        $page=isset($_GET['page'])?$_GET['page']:1;
        FileUtil::requireService("ArticleServ");
        FileUtil::requireService('PageUtil');
        $Article=new ArticleServ();
        /*$params['article']=$Article->GetAriticleBynum(10,$page,$categoryid='0',5,'id');*/
        $params['article'] = $Article->getActArticle();
        $params['count']=$Article->getAllArticleCount('5');
        $pageUtil = new PageUtil(10,$params['count'],$page);
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/info/morearticle.html?';
        $params['pager'] = $pageUtil;
        return $params;
    }
}