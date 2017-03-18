<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class StepSuc extends BaseAction{
	
	public function action(){
		$mid = isset($_GET['mid'])?trim($_GET['mid']):"";
		$check = isset($_GET['check'])?$_GET['check']:'0'; 
		
		if(($check == 2 || $check == 3) && empty($mid)){
			FileUtil::load404Html();
			exit(0);
		}
		
		if(!empty($mid)){	
			FileUtil::requireService("UserServ");
			$service = new UserServ();
			$user = $service->getMemberByMid($mid);
			if(empty($user)){
				FileUtil::load404Html();
				exit(0);
			}
		}
		
		$params['mid'] = $mid;
		$params['check'] = $check;
		return $params;
	}
	
}