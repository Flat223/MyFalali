<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetOrderRefundInfoServ extends BaseAction{
	
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
		
		$pid=isset($_REQUEST['pid'])?trim($_REQUEST['pid']):'';
		$order_code=isset($_REQUEST['order_code'])?trim($_REQUEST['order_code']):'';
		
		if(empty($pid) || empty($order_code)){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		
		FileUtil::requireService("OrderServ");
		$service=new OrderServ();
        $result = $service->getRefundInfo($order_code,$pid);
        
		if($result === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		if($result == null) {
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉,未找到该订单信息";
			return $ret;
		}
		$ret = array();
		$ret['ret'] = 1;
		$ret['msg'] = "获取成功"; 
		$ret['refund'] = $result;
		return $ret;
	}
}

?>