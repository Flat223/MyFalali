<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddArticle extends BaseAction{

	public function action(){

		FileUtil::requireService("ArticleServ");
		$service=new ArticleServ();
		$category=$service->getArticleCategory();
        $child = $service->getSecondArticleTypeById(1);
		$params['substyle'] = 'articles';
		$params['category'] = $category;
        $params['child'] = $child;
        return $params;
	}
}