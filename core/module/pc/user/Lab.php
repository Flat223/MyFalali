<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Lab extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		FileUtil::requireService("UserServ");
		$service=new UserServ();
		$laboratory=$service->getLaboratoryByMid($user['mid']);
		if($laboratory === false){
			FileUtil::load404Html();
			exit(0);
		}
		$params['style'] = 'user';
		$params['substyle'] = 'lab';
		$params['laboratory'] = $laboratory;
		return $params;
	}
}