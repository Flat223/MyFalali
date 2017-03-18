<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class DelFreightServ extends BaseAction{
	
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
			$ret['msg'] = "你的身份不是供应商";
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
			$ret['msg'] = "抱歉，未找到您的店铺";
			return $ret;
		}
		$fid = isset($_REQUEST['fid'])?trim($_REQUEST['fid']):0;
		if(!Common::isInteger($fid) || $fid <= 0){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		FileUtil::requireService("FreightServ");
		$freightService = new FreightServ();
		$freight = $freightService->getFreightById($fid);
		if($freight === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，发生错误";
			return $ret;
		}
		if(!$freight){
			$ret['ret'] = 0;
			$ret['msg'] = "运费模板不存在";
			return $ret;
		}
		if($freight['sid'] != $shop['sid']){
			$ret['ret'] = 0;
			$ret['msg'] = "运费模板不存在";
			return $ret;
		}
		$count = $freightService->getProductCountByFreidAndSid($fid,$freight['sid']);
		if($count === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，发生错误";
			return $ret;
		}
		if($count > 0){
			$ret['ret'] = 0;
			$ret['msg'] = "有".$count."件产品已绑定此模板，请先解除绑定再做删除";
			return $ret;
		}
		$result = $freightService->delFreightByFreid($fid);
		if($result){
			$ret['ret'] = 1;
			$ret['msg'] = "操作成功";
			return $ret;
		}
		$ret['ret'] = 0;
		$ret['msg'] = "抱歉，发生错误";
		return $ret;		
	}
	
	
}


