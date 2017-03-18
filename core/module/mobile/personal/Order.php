<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Order extends BaseAction{
    public function action(){
	    $params = array();
        $user = UserAgent::getUser();
        $state=isset($_GET['state'])?trim($_GET['state']):"0";
        $orderType=isset($_GET['order_type'])?trim($_GET['order_type']):"0";
        		
        if($orderType == 0){
			FileUtil::load404Html();
			exit(0);
        }
        
        if($user['type'] == 3){
			FileUtil::load404Html();
			exit(0);
		}
        
        $mid = $user['mid'];
        $cid = $user['bind_company'];
        $subType = $user['sub_type'];
        
        FileUtil::requireService("MobileOrderServ");	
		$service=new MobileOrderServ();		
		$orderArray = $service->getOrder($mid,$cid,$orderType,$subType,'0',$state,0,10);
		if($orderArray === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		$newOrderArray = array();
		if(!empty($orderArray)){
			$newOrderArray = $this->getNewOrderArray($orderArray);
		} 

		$params['style'] = 'personal';
		$params['substyle'] = 'order';
		$params['order'] = $newOrderArray;
		$params['order_type'] = $orderType;
        return $params;
    }
    
    function getNewOrderArray($orderArray){
		for($i=0;$i<count($orderArray);$i++){
			FileUtil::requireService("MobileOrderServ");
			$service = new MobileOrderServ();
			
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