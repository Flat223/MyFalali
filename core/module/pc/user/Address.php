<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Address extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		FileUtil::requireService("AddressServ");
		$service=new AddressServ();
		$address = $service->getMyAddressList($user['mid']);
		if($address === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		$params = array();
		$params['style'] = 'user';
		$params['substyle'] = 'address';
		$params['address'] = $address;
		return $params;
	}
}