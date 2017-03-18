<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class ArticleType extends BaseAction{

	public function action(){
        FileUtil::requireService('ArticleServ');
        $serv = new ArticleServ();
        $type = $serv->getArticleType();
        $params = array();
        $params['style'] = 'media';
		$params['substyle'] = 'articles';
        $params['type'] = $type;
		return $params;
	}

}