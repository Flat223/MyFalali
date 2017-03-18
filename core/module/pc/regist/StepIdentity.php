<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class StepIdentity extends BaseAction{
	
	public function action(){
		$mid = isset($_GET['mid'])?trim($_GET['mid']):'';
		if(empty($mid)){
			FileUtil::load404Html();
			exit(0);
		}
		
		FileUtil::requireService("UserServ");
		$service = new UserServ();
		$user = $service->getMemberByMid($mid);
		if(empty($user)){
			FileUtil::load404Html();
			exit(0);
		}
		
		FileUtil::requireService("RegistServ");
		$service = new RegistServ();
		$collegeTypes = $service->getCollegeTypes();
		if($collegeTypes === false){
			FileUtil::load404Html();
			exit(0);
		}
		$params['mid'] = $mid;
		$params['collegeTypes'] = $collegeTypes;
		return $params;
	}
	
}