<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Article extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		$page = isset($_GET['page'])?trim($_GET['page']):1;
		if(!Common::isInteger($page) || $page <= 0){
			$page = 1;
		}
        			
        $baseUrl = "../user/article.html?";		
        $pagesize = 5;
        
        FileUtil::requireService("ArticleServ");
		$service=new ArticleServ();
        $count = $service->getUserArticleCount($user['mid']);
        if($count === false){
			FileUtil::load404Html();
			exit(0);
		}
		$pageUtil = new PageUtil($pagesize,$count,$page); 
		$index = ($pageUtil->getCurrentPage()-1)*$pagesize;
        $articleArray=$service->getUserArticle($user['mid'],$index,$pagesize);
        if($articleArray === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		$params = array();
		$params['style'] = 'user';
		$params['substyle'] = 'article';
		$params['article'] = $articleArray; 
		$params['baseurl'] = $baseUrl;
		$params['pager'] = $pageUtil;
		$params['count'] = $count;
		return $params;
	}
}