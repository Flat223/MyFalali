<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Order extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		
        //公司成员并且绑定了公司,或者是公司本身
        if($user['type'] != 2 || ($user['sub_type'] != 0 && $user['bind_status'] != 2)){
			FileUtil::load404Html();
			exit(0);
		}
        
        $order_code=isset($_GET['order_code'])?trim($_GET['order_code']):"";
		$applier=isset($_GET['applier'])?trim($_GET['applier']):"";
        $product=isset($_GET['product'])?trim($_GET['product']):"";
        $start_time=isset($_GET['start_time'])?trim(strtotime($_GET['start_time'])):"";
        $end_time=isset($_GET['end_time'])?trim(strtotime($_GET['end_time'])):"";
        $agree=isset($_GET['agree'])?trim($_GET['agree']):"";
        $page = isset($_GET['page'])?trim($_GET['page']):1;
		if(!Common::isInteger($page) || $page <= 0){
			$page = 1;
		}
        			
        $baseUrl = "../user/order.html?";
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
		if(!empty($agree)){
	        $baseUrl .= "agree=".$agree;
	        $condition['agree'] = $agree;
        }
		
		$orderType = 1;//需求订单
        $pageUtil = "";
        $count = 0;			
        $pagesize = 5;
        $newOrderArray = array();
        $cid = $user['sub_type'] == 0 ? $user['mid'] : $user['bind_company'];
        
        FileUtil::requireService("OrderServ");
		$service=new OrderServ();
		
		if(!empty($order_code)){
			$order = $service->searchOrderByOrderCode($order_code,$user['mid'],$cid,$user['sub_type'],$orderType,$agree,'');
			if($order === false){
				FileUtil::load404Html();
				exit(0);
			}
			if($order != null){
				$orderArray = array();
				$orderArray[] = $order;
			}
		} else if(!empty($product)){
			$orderArray = $service->searchOrderByProduct($product,$user['mid'],$cid,$user['sub_type'],$orderType,$condition);
			if($orderArray === false){
				FileUtil::load404Html();
				exit(0);
			}
		} else {	
	        $count = $service->getOrderCount($user['mid'],$cid,$user['sub_type'],$orderType,$condition);
	        if($count === false){
				FileUtil::load404Html();
				exit(0);
			}
			$pageUtil = new PageUtil($pagesize,$count,$page); 
			$index = ($pageUtil->getCurrentPage()-1)*$pagesize;
	        $orderArray=$service->getAllOrder($user['mid'],$cid,$user['sub_type'],$orderType,$condition,$index,$pagesize);
	        if($orderArray === false){
				FileUtil::load404Html();
				exit(0);
			}   
        }
        if(!empty($orderArray)){
			$newOrderArray = $this->getNewOrderArray($orderArray);
		}
        
		$params = array();
		$params['style'] = 'user';
		$params['substyle'] = 'order';
		$params['order'] = $newOrderArray;
		$params['baseurl'] = $baseUrl;
		$params['pager'] = $pageUtil;
		$params['count'] = $count;
		return $params;
	}
	
	function getNewOrderArray($orderArray){
		for($i=0;$i<count($orderArray);$i++){
			FileUtil::requireService("OrderServ");
			$service=new OrderServ();
			
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