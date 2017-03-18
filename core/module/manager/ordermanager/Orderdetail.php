<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Orderdetail extends BaseAction{
	
	public function action(){
		$params = array();
		$type=isset($_GET['type'])?trim($_GET['type']):"";
		$substyle = "";
		if($type == 1){
			$substyle = 'requireOrder';
		} else if($type == 2){
			$substyle = 'purchaseOrder';
		} else if($type == 3){
			$substyle = 'collegeOrder';
		} else {
			$substyle = 'personalOrder';
		} 
		$order_code=isset($_GET['order_code'])?trim($_GET['order_code']):"";
		FileUtil::requireService("OrderServ");
		$service=new OrderServ();
		
		if($order_code != ""){
			$order = $service->getOrderDetailByCode($order_code);
			$productArray = $service->getOrderShopDetail($order_code);
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
		}
		
		$params['style'] = 'ordermanager';
		$params['type'] = $type;
		$params['substyle'] = $substyle;
		return $params;
	}
}