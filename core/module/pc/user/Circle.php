<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Circle extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		$page = isset($_GET['page'])?trim($_GET['page']):1;
		if(!Common::isInteger($page) || $page <= 0){
			$page = 1;
		}
				
        $pagesize = 5;
        $baseUrl = "/user/circle.html?";
        
		FileUtil::requireService("CircleServ");
		$service=new CircleServ();
		
		$count = $service->getUserCircleCount($user['mid']);
        if($count === false){
			FileUtil::load404Html();
			exit(0);
		}
		$pageUtil = new PageUtil($pagesize,$count,$page); 
		$index = ($pageUtil->getCurrentPage()-1)*$pagesize;
        $circleArray=$service->getUserCircleList($user['mid'],$index,$pagesize);
        if($circleArray === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		for ($i = 0; $i < count($circleArray); $i++){
			$circle = $circleArray[$i];
			$dynamic=$service->getUserCircleDynamic($circle['circle_id'],4);	
			if($dynamic === false){
				FileUtil::load404Html();
				exit(0);
			}
			$circle['dynamic'] = $dynamic;
			$circleArray[$i] = $circle;
		}

		$params = array();
		$params['style'] = 'user';
		$params['substyle'] = 'circle';
		$params['circle'] = $circleArray;
		$params['baseurl'] = $baseUrl;
		$params['pager'] = $pageUtil;
		$params['count'] = $count;
		return $params;
	}
	
}