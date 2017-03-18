<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class OrderManager extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		if($user['type'] != 3){
			FileUtil::load404Html();
			exit(0);
		}
		FileUtil::requireService("ShopServ");
		$service=new ShopServ();
		$shop = $service->getShopByMid($user['mid']);
		if($shop === false){
			FileUtil::load404Html();
			exit(0);
		}
		if($shop == null){
			FileUtil::load404Html();
			exit(0);
		} 
		
		$expressArray = $service->getExpress();
		if($expressArray == false){
			FileUtil::load404Html();
			exit(0);
		}
		
        $order_code=isset($_GET['order_code'])?trim($_GET['order_code']):"";
        $product=isset($_GET['product'])?trim($_GET['product']):"";
        $start_time=isset($_GET['start_time'])?trim(strtotime($_GET['start_time'])):"";
        $end_time=isset($_GET['end_time'])?trim(strtotime($_GET['end_time'])):"";
        $state=isset($_GET['state'])?trim($_GET['state']):"0";
        $page = isset($_GET['page'])?trim($_GET['page']):1;
        $sid = $shop['sid'];
        
		if(!Common::isInteger($page) || $page <= 0){
			$page = 1;
		}
		
        $baseUrl = "../myshop/orderManager.html?";
		$condition = "";
		if(!empty($order_code)){
	        $baseUrl .= "order_code=".$order_code;
        } 
        if(!empty($product)){
	        $baseUrl .= "product=".$product;
        }
        if($start_time != ""){	
	        $baseUrl .= "start_time=".$start_time;
	        $condition['start_time'] = $start_time;
        }
        if($end_time != ""){
	        $baseUrl .= "end_time=".$end_time;
	        $condition['end_time'] = $end_time;
        }
		if($state!=0){
	        $baseUrl .= "state=".$state;
	        $condition['state'] = $state;
        }
		
        $pageUtil = "";
        $count = 0;			
        $pagesize = 5;
        $newOrderArray = array();
        
        FileUtil::requireService("ShopOrderServ");
		$service=new ShopOrderServ();
		if(!empty($order_code)){
			$order = $service->getShopOrderByOrderCode($sid,$order_code,$state);
			if($order === false){
				FileUtil::load404Html();
				exit(0);
			}
			if($order != null){
				$orderArray = array();
				$orderArray[] = $order;
			}
		} else if(!empty($product)){
			$count = $service->getShopOrderCountByProduct($sid,$product,$state);
	        if($count === false){
				FileUtil::load404Html();
				exit(0);
			}
			$pageUtil = new PageUtil($pagesize,$count,$page); 
			$index = ($pageUtil->getCurrentPage()-1)*$pagesize;
	        $orderArray=$service->getShopOrderByProduct($sid,$product,$state,$index,$pagesize);
	        if($orderArray === false){
				FileUtil::load404Html();
				exit(0);
			}
		} else {	
	        $count = $service->getShopOrderCount($sid,$condition);
	        if($count === false){
				FileUtil::load404Html();
				exit(0);
			}
			$pageUtil = new PageUtil($pagesize,$count,$page); 
			$index = ($pageUtil->getCurrentPage()-1)*$pagesize;
	        $orderArray=$service->getAllShopOrder($sid,$condition,$index,$pagesize);
	        if($orderArray === false){
				FileUtil::load404Html();
				exit(0);
			}   
        }
        if(!empty($orderArray)){
			$newOrderArray = $this->getNewOrderArray($orderArray);
		}
        
        $params['style'] = 'myshop';
		$params['substyle'] = 'orderManager';
		$params['order'] = $newOrderArray;
		$params['express'] = $expressArray;
		$params['baseurl'] = $baseUrl;
		$params['pager'] = $pageUtil;
		$params['count'] = $count;
		return $params;
	}
	
	function getNewOrderArray($orderArray){
		for($i=0;$i<count($orderArray);$i++){
			FileUtil::requireService("ShopOrderServ");
			$service=new ShopOrderServ();
			
			$order = $orderArray[$i];
			$productArray = $service->getProductByOrdercode($order['order_code']);	
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