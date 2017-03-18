<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Freight extends BaseAction{
	
	public function action(){
		$params = array();
		$id = isset($_POST['fid'])?trim($_POST['fid']):0;
		if(!Common::isInteger($id) || $id < 0){
			$id = 0;
		}
		$id = intval($id);
		if($id == 0){
			return null;
		}
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
		$freight = $service->getFreightById($id);
		if($freight === false){
			FileUtil::loadServerErrHtml();
			exit(0);
		}
		if(!$freight){
			FileUtil::load404Html();
			exit(0);
		}
		if($freight['sid'] != $shop['sid']){
			FileUtil::load404Html();
			exit(0);
		}
		$params['fid'] = $id;
		return $params;
	}
	
	
	
}