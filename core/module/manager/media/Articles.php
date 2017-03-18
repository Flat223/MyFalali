<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Articles extends BaseAction{

    public function action(){
        $page=isset($_GET['page'])?$_GET['page']:1;
        $info=isset($_GET['info'])?$_GET['info']:"";
        $ob=isset($_GET['ob'])?$_GET['ob']:0;
        FileUtil::requireService("ArticleServ");
        $serv = new ArticleServ();

        $pagesize = 10;
        $data = $serv->getManagerArticle($info);
        $count = sizeof($data);
        $data = array();
        $pageUtil = new PageUtil($pagesize,$count,$page);
        $index = ($pageUtil->getCurrentPage()-1)*$pagesize;
        $data = $serv->getPageManagerArticle($info,$index,$pagesize,$ob);
        /*echo json_encode($data);*/
        $params['count'] = $count;
        $params['article'] = $data;
        $params['style'] = 'media';
		$params['substyle'] = 'articles';
        $params['pager'] = $pageUtil;
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/media/articles.html?ob='.$ob."&info=".$info;
        return $params;
    }

}