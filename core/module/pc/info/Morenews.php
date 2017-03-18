<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Morenews extends BaseAction{
    public function action(){
        $page=isset($_GET['page'])?$_GET['page']:1;
        FileUtil::requireService("ArticleServ");
        FileUtil::requireService('PageUtil');
        FileUtil::requireService('NewsServ');
        $Article=new ArticleServ();
        $News=new NewsServ();
        $params['news']=$News->GetNewsBytime(10,$page);
//        $params['article']=$Article->GetAriticleBynum(10,$page,$categoryid='0');
        $params['count']=$News->Getcount();
        $params['hotart']=$Article->getArticleByView(3,'view_num desc');
        $pageUtil = new PageUtil(10,$params['count'],$page);
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/info/morenews.html?';
        $params['pager'] = $pageUtil;
        return $params;
    }
}
