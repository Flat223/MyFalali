<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class HandleRefundServ extends BaseAction{
	public function action(){
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
		
		$order_code = isset($_REQUEST['order_code'])?trim($_REQUEST['order_code']):"";
		$pid = isset($_REQUEST['pid'])?trim($_REQUEST['pid']):"";
		$type = isset($_REQUEST['type'])?trim($_REQUEST['type']):"";
		$reason = isset($_REQUEST['reason'])?trim($_REQUEST['reason']):"";
				
		if(empty($order_code) || empty($pid) || empty($type)){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		
		if($type == 2 && empty($order_code)){
			$ret['ret'] = 0;
			$ret['msg'] = "请填写拒绝理由";
			return $ret;
		} 
		
		FileUtil::requireService("ShopOrderServ");
		$service=new ShopOrderServ();
		$reply = $type == 1 ? '' : $reason;
		$callback = $service->refuseRefund($order_code,$pid,$type,$reply);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误1，请稍后再试";
			return $ret;
		}
		
		FileUtil::requireService("OrderServ");
		$service=new OrderServ();
		$state = $type == 1 ? 10 : 11;
		$callback = $service->updateOrderState($order_code,$state);	
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误2，请稍后再试";
			return $ret;
		}
		
		$callback = $service->updateOrderProductState($order_code,$pid,5);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误3，请稍后再试";
			return $ret;
		}
		
		$ret['ret'] = 1;
		$ret['msg'] = "处理成功";
		return $ret;
	}
}