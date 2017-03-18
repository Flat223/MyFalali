<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class JoinNewCircle extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();        
		FileUtil::requireService("CircleServ");
		$service=new CircleServ();
		
		$circleArray = $service->getRecommendCircleList(10);
		
		$params = array();
		$params['style'] = 'user';
		$params['substyle'] = 'joinNewCircle';
		$params['circle'] = $circleArray;
		return $params;
	}
}