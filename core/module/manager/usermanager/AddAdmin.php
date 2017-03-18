<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddAdmin extends BaseAction{

	public function action(){
		$admin = UserAgent::getAdmin();
		if($admin['rid'] != 1){
			FileUtil::load404Html();
			exit(0);
		}
		
		FileUtil::requireService('AdminServ');
		$service = new AdminServ();
		$roleArray = $service->getAllRore();
		if($role === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		$params['substyle'] = 'adminInfo';
		$params['role'] = $roleArray;
		return $params;
	}
}