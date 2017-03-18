<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class ShopInfo extends BaseAction{
	
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
		if($shop == null){
			FileUtil::load404Html();
			exit(0);
		} 
		
		$params['style'] = 'myshop';
		$params['substyle'] = 'shopInfo';
		$params['shop'] = $shop;
		return $params;
	}
	
}