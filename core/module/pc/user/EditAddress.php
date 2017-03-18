<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class EditAddress extends BaseAction{
	
	public function action(){
		$aid = isset($_REQUEST['aid'])?trim($_REQUEST['aid']):"";		
		$user = UserAgent::getUser();
		FileUtil::requireService("AddressServ");
		$service=new AddressServ();
		$address = $service->getMyAddressList($user['mid']);
		if($address === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		$editAddress = $service->getAddressById($user['mid'],$aid);
		if($editAddress === false){
			FileUtil::load404Html();
			exit(0);
		}

		$params = array();
		$params['style'] = 'user';
		$params['substyle'] = 'eidtAddress';
		$params['address'] = $address;
		$params['editAddress'] = $editAddress;
		return $params;
	}
}