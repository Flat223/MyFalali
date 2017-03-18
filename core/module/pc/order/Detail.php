<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Detail extends BaseAction{
	
	public function action(){
		$params = array();
		$user = UserAgent::getUser();
		$type=isset($_GET['type'])?trim($_GET['type']):"";
		$order_code=isset($_GET['order_code'])?trim($_GET['order_code']):"";
		$pid=isset($_GET['pid'])?trim($_GET['pid']):"";
		
		if(empty($type) || empty($order_code)){
			FileUtil::load404Html();
			exit(0);
		}
		
		FileUtil::requireService("OrderServ");
		$service=new OrderServ();
		
		if(!empty($order_code)){
			$order = $service->getOrderDetailByCode($order_code);
			if($order === false || empty($order)){
				FileUtil::load404Html();
				exit(0);
			}
			$productArray = $service->getOrderShopDetail($order_code);
			if($productArray === false){
				FileUtil::load404Html();
				exit(0);
			}
			for($j=0;$j<count($productArray);$j++){
				$product = $productArray[$j];
		        if($product['props'] != ""){
					$product['props'] = json_decode($product['props'],true);	
				} else {
					$product['props'] = array();
		        }
				$productArray[$j] = $product;
			}
			$order['product'] = $productArray;
			$params['order'] = $order;
			
			if($order['state'] == 11){
				$refundInfo = $service->getRefundInfo($order_code,$pid);
				if($refundInfo === false){
					FileUtil::load404Html();
					exit(0);
				}
				$params['refund'] = $refundInfo;
			}
		}
		
		$params['style'] = 'order';
		$params['type'] = $type;
		$params['substyle'] = 'detail';
		return $params;
	}
}