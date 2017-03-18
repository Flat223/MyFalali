<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Freights extends BaseAction{
	
	public function action(){
		$params = array();
		$user = UserAgent::getUser();
		FileUtil::requireService("ShopServ");
		$shopService = new ShopServ();
		$shop = $shopService->getShopById($user['mid']);
		if($shop === false){
			FileUtil::loadServerErrHtml();
			exit(0);
		}
		if(!$shop){
			FileUtil::load404Html();
			exit(0);
		}
		FileUtil::requireService("FreightServ");
		$service = new FreightServ();
		$freights = $service->getFreights($shop['sid']);
		if($freights === false){
			FileUtil::loadServerErrHtml();
			exit(0);
		}
		$params['freights'] = $freights;
		return $params;
	}
	
	
	
}