<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class CompanyInfo extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		if($user['type'] != 2 || $user['sub_type'] != 0){
			FileUtil::load404Html();
			exit(0);
		}
		
		FileUtil::requireService("UserServ");
		$service = new UserServ();
	
		$user2 = $service->getMemberByMid($user['mid']);
		if($user2 === false){
			FileUtil::load404Html();
			exit(0);
		}
		$_SESSION['user'] = $user2;
		
		$params['style'] = 'user';
		$params['substyle'] = 'companyInfo';
		return $params;
	}
}