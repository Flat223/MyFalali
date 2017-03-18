<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Freights extends BaseAction{
	
	public function action(){
		$ret = array();
        $user = UserAgent::getUser();
		if($user == null){
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}
		if($user['type'] != 3){
			$ret['ret'] = 0;
			$ret['msg'] = "无权访问";
			return $ret;
		}
		FileUtil::requireService("ShopServ");
		$shopService = new ShopServ();
		$shop = $shopService->getShopById($user['mid']);
		if($shop === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，发生错误";
			return $ret;
		}
		if(!$shop){
			$ret['ret'] = 0;
			$ret['msg'] = "没有店铺";
			return $ret;
		}
		FileUtil::requireService("FreightServ");
		$service = new FreightServ();
		$freights = $service->getFreights($shop['sid']);
		if($freights === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，发生错误";
			return $ret;
		}
		$ret['ret'] = 1;
		$ret['msg'] = "操作成功";
		$ret['freights'] = $freights;
		return $ret;
	}
	
	
	
}