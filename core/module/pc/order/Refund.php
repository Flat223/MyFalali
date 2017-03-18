<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Refund extends BaseAction{
	
	public function action(){
		$params = array();
		$pid=isset($_GET['pid'])?trim($_GET['pid']):"0";
		$order_code=isset($_GET['order_code'])?trim($_GET['order_code']):"0";
		if($pid == '0' || $order_code == "0"){
			FileUtil::load404Html();
			exit(0);
		}
		FileUtil::requireService("OrderServ");
		$service=new OrderServ();
		$product = $service->getOrderProductByPid($pid,$order_code);
		if($product === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		if($product==null){
			$normal=1;
			$params=array();
			$params['msg']="抱歉,未找到该产品";
			FileUtil::load0000Html($params);
			exit(0);
		}
		
		$params['style'] = 'order';
		$params['substyle'] = 'refund';
		$params['product'] = $product;
		return $params;
	}
}