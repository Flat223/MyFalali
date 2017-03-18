<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Publisharticle extends BaseAction{

	public function action(){

		FileUtil::requireService("ArticleServ");
		$service=new ArticleServ();
		$category=$service->getArticleCategory();
        $child = $service->getSecondArticleTypeById(1);
		$params = array();
		$params['style'] = 'user';
		$params['substyle'] = 'publisharticle';
		$params['category'] = $category; 
        $params['child'] = $child;
		return $params;
	}
}