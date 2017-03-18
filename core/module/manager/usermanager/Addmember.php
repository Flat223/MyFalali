<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Addmember extends BaseAction{

	public function action(){
		$params = array();
		FileUtil::requireService("AdminServ");
		$service = new AdminServ();
/*
		$collegeArray = $service->getAllCollege();
		$companyArray = $service->getAllCompany();
*/
		
/*
		$params['college'] = $collegeArray;
		$params['company'] = $companyArray;
*/
		$params['style'] = 'usermanager';
		$params['substyle'] = 'memberInfo';
		return $params;
	}
}