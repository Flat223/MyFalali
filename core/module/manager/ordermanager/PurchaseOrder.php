<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class PurchaseOrder extends BaseAction{

	public function action(){
        $order_code=isset($_GET['order_code'])?trim($_GET['order_code']):"";
		$applier=isset($_GET['applier'])?trim($_GET['applier']):"";
        $product=isset($_GET['product'])?trim($_GET['product']):"";
        $start_time=isset($_GET['start_time'])?trim(strtotime($_GET['start_time'])):"";
        $end_time=isset($_GET['end_time'])?trim(strtotime($_GET['end_time'])):"";
        $state=isset($_GET['state'])?trim($_GET['state']):"";
        $page = isset($_GET['page'])?trim($_GET['page']):1;
		if(!Common::isInteger($page) || $page <= 0){
			$page = 1;
		}
        		
        $baseUrl = "../ordermanager/purchaseOrder.html?";
		$condition = "";
		if(!empty($order_code)){
	        $baseUrl .= "order_code=".$order_code;
        } 
        if(!empty($applier)){
	        $baseUrl .= "applier=".$applier;
	        $condition['applier'] = $applier;
        }
        if(!empty($product)){
	        $baseUrl .= "product=".$product;
        }
        if(!empty($start_time)){
	        $baseUrl .= "start_time=".$start_time;
	        $condition['start_time'] = $start_time;
        }
        if(!empty($end_time)){
	        $baseUrl .= "end_time=".$end_time;
	        $condition['end_time'] = $end_time;
        }
		if(!empty($state)){
	        $baseUrl .= "state=".$state;
	        $condition['state'] = $state;
        }
		
		$orderType = 2;//采购订单
        $pageUtil = "";
        $count = 0;			
        $pagesize = 5;
        $newOrderArray = array();
        
        FileUtil::requireService("AdminOrderServ");
		$service=new AdminOrderServ();
		
		if(!empty($order_code)){
			$order = $service->getOrderByOrderCode($order_code,"",$state,$orderType);
			if($order === false){
				FileUtil::load404Html();
				exit(0);
			}
			if($order != null){
				$orderArray = array();
				$orderArray[] = $order;
			}
		} else if(!empty($product)){
			$allOrder = $service->getOrderByProduct($product,$orderType,$condition);
			if($allOrder === false){
				FileUtil::load404Html();
				exit(0);
			}
			if($allOrder != null){
				$orderArray = array();
				foreach ($allOrder as $order){
					$result = $service->getOrderByOrderCode($order['order_code'],"","",$orderType);
					if($result != null){
						$orderArray[] = $result;	
					}
				}
			}
		} else {	
	        $count = $service->getAllOrderCount($orderType,$condition);
	        if($count === false){
				FileUtil::load404Html();
				exit(0);
			}
			$pageUtil = new PageUtil($pagesize,$count,$page); 
			$index = ($pageUtil->getCurrentPage()-1)*$pagesize;
	        $orderArray=$service->getAllOrder($orderType,$condition,$index,$pagesize);
	        if($orderArray === false){
				FileUtil::load404Html();
				exit(0);
			}   
        }
        if(!empty($orderArray)){
			$newOrderArray = $this->getNewOrderArray($orderArray);
		}
		
		$params = array();
		$params['style'] = 'ordermanager';
		$params['substyle'] = 'purchaseOrder';
		$params['order'] = $newOrderArray;
		$params['baseurl'] = $baseUrl;
		$params['pager'] = $pageUtil;
		$params['count'] = $count;
		return $params;
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