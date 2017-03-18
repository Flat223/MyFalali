<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetBuyerInfoServ extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		if($user == null){
			$ret['ret'] = -1;
			$ret['msg'] = "请登录";
			return $ret;
		}
		if($user['type'] != 3){
			$ret['ret'] = -1;
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
		if($shop == null){
			$ret['ret'] = 0;
			$ret['msg'] = "请先申请店铺";
			return $ret;
		}
		
		$mid=isset($_REQUEST['mid'])?trim($_REQUEST['mid']):0;
		$aid=isset($_REQUEST['aid'])?trim($_REQUEST['aid']):0;
		
		if($mid == 0 || $aid == 0){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		
		FileUtil::requireService("AddressServ");
		$service=new AddressServ();
        $address = $service->getAddressById($mid,$aid);
		if($address === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		if(empty($address)) {
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉,未找到该用户地址";
			return $ret;
		}
		$ret = array();
		$ret['ret'] = 1;
		$ret['msg'] = "获取成功"; 
		$ret['userInfo'] = $address;
		return $ret;
	}
}

?>