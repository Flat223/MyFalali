<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class OperateShopAddressServ extends BaseAction{
	public function action(){
		$user = UserAgent::getUser();
		if(empty($user)){
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
		$service=new ShopServ();
		$shop = $service->getShopByMid($user['mid']);
		if($shop === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误0，请稍后再试";
			return $ret;
		}
		if(empty($shop)){
			$ret['ret'] = 0;
			$ret['msg'] = "未找到你的店铺";
			return $ret;
		} 	
		
		$aid = isset($_REQUEST['aid'])?trim($_REQUEST['aid']):"";	
		$type = isset($_REQUEST['type'])?trim($_REQUEST['type']):"";//1:删除地址 2:设置默认地址		
		
		if(empty($aid) || empty($type)){
			$ret['ret'] = 0;
			$ret['msg'] = "缺少参数";
			return $ret;
		}

		if($type != 1 && $type != 2){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		
		FileUtil::requireService('ShopAddressServ');
		$service = new ShopAddressServ();
		if($type == 1){
			$callback = $service->deleteShopAddress($shop['sid'],$aid);	
		} else if($type == 2){
			$callback = $service->unsetShopAddressDefault($shop['sid']);	
			if($callback === false){
				$ret['ret'] = 0;
				$ret['msg'] = "抱歉，服务器错误1，请稍后再试";
				return $ret;
			}
			$callback = $service->setShopAddressDefault($shop['sid'],$aid);	
		}
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误2，请稍后再试";
			return $ret;
		}
		
		$ret['ret'] = 1;
		if($type == 1){
			$ret['msg'] = "删除成功";	
		} else if($type == 2){
			$ret['msg'] = "设置成功";	
		}
		return $ret;
	}
}