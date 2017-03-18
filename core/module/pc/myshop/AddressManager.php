<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddressManager extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		if($user['type'] != 3){
			FileUtil::load404Html();
			exit(0);
		}
		FileUtil::requireService("ShopServ");
		$service=new ShopServ();
		$shop = $service->getShopByMid($user['mid']);
		if($shop === false){
			FileUtil::load404Html();
			exit(0);
		}
		if(empty($shop)){
			FileUtil::load404Html();
			exit(0);
		} 
		
		FileUtil::requireService("ShopAddressServ");
		$service = new ShopAddressServ();
		$address = $service->getShopAddress($shop['sid']);
		if($address === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		$params['style'] = 'myshop';
		$params['substyle'] = 'addressManager';
		$params['address'] = $address;
		return $params;
	}
}