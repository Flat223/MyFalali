<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class StepIndustry extends BaseAction{
	
	public function action(){
		$tid = isset($_GET['tid'])?trim($_GET['tid']):'';
		$subtid = isset($_GET['subtid'])?trim($_GET['subtid']):'';
		$mid = isset($_GET['mid'])?trim($_GET['mid']):'';
		
		if(empty($mid)){
			FileUtil::load404Html();
			exit(0);
		}
		if(!Common::isInteger($tid) || $tid < 0){
			$tid = 4;
		}
		if(!Common::isInteger($subtid) || $subtid < 0){
			$subtid = 0;
		}
		
		$check = 0;
		if(($tid == 2 && $subtid == 0) || $tid == 3){
			$check = $tid;
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
		$industryTypes = $service->getIndustryTypes();
		if($industryTypes === false){
			FileUtil::load404Html();
			exit(0);
		}
		$params['tid'] = $tid;
		$params['subtid'] = $subtid;
		$params['industryTypes'] = $industryTypes;
		$params['check'] = $check;
		$params['mid'] = $mid;
		return $params;
	}
	
}