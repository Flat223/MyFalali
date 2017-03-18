<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class TestServ extends BaseAction{
	
	public function action(){
		FileUtil::requireService("ProductServ");
		$service=new ProductServ();
        $propertyArray= $service->getProductProperty('95');
		if($propertyArray === false){
// 			echo '3';
			FileUtil::load404Html();
			exit(0);
		}
		$properties = array();
		for($i = 0; $i < count($propertyArray); $i++){
			$prop = $propertyArray[$i];
			$prop['vals'] = $service->getPropertyValByProId($prop['id'],'190');//获取该产品属性的属性值
			if($prop['vals'] === false){
				FileUtil::load404Html();
				exit(0);
			}
			if($prop['vals'] != null){
				$properties[] = $prop;	
			}
		}
		
		$skus = $service->getProductSkuByPid('190');
		if($skus === false){
// 			echo '4';
			FileUtil::load404Html();
			exit(0);
		}
				
		$skuArr = $service->getSkuArr($properties);
		foreach($skuArr as &$arr){
			sort($arr['vals']);
			$skuid = join(",",$arr['vals']);
			$arr['skuid'] = $skuid;
		}
		
		$outArr = array();
		if(count($skus) == 1 && $skus[0]['properties'] === ""){
			$temp = array();
			$temp['pid'] = $skus[0]['pid'];
			$temp['skuid'] = $skus[0]['properties'];
			$temp['name'] = array();
			$temp['price'] = $skus[0]['price'];
			$temp['inventory'] = $skus[0]['inventory'];
			$temp['inventory_warn'] = $skus[0]['inventory_warn'];
			$outArr[] = $temp;
		}else{
			for($i=0;$i<count($skus);$i++){
				for($j=0;$j<count($skuArr);$j++){
					if($skus[$i]['properties'] === $skuArr[$j]['skuid']){
						$temp = array();
						$temp['pid'] = $skus[$i]['pid'];
						$temp['skuid'] = $skus[$i]['properties'];
						$temp['name'] = $skuArr[$j]['name'];
						$temp['price'] = $skus[$i]['price'];
						$temp['inventory'] = $skus[$i]['inventory'];
						$temp['inventory_warn'] = $skus[$i]['inventory_warn'];
						$outArr[] = $temp;
						break;
					}
				}
			}
		}
				 
		$callback = $outArr;
		$ret = array();
		if($callback === false){	
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		if($callback == null) {
			$ret['ret'] = 0;
			$ret['msg'] = "没有数据了";
			return $ret;
		}
		 
		$ret['ret'] = 1;
		$ret['msg'] = "成功"; 
		$ret['data'] = $callback;
		return $ret;
	}
	
	function getNewOrderArray($orderArray){
		for($i=0;$i<count($orderArray);$i++){
			FileUtil::requireService("AdminOrderServ");
			$service=new AdminOrderServ();
			
			$order = $orderArray[$i];
			$productArray = $service->getProductArrayByOrder($order['order_code']);	
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
			$orderArray[$i] = $order;
		}
		return $orderArray;
	}
}