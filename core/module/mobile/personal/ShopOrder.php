<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class ShopOrder extends BaseAction{

    public function action(){
	    $params = array();
        $user = UserAgent::getUser();
        $state=isset($_GET['state'])?trim($_GET['state']):"0";
        
        if($user['type'] == 3 && $user['sub_type'] == 0){
	        $orderType = 5;//店铺订单
        }else if ($user['type'] == 2 && $user['sub_type'] == 0){
            $orderType = 2;//采购订单
        } else if ($user['type'] == 1 && $user['sub_type'] == 0){
            $orderType = 3;//高校订单
        } else {
            $orderType = 4;//个人订单
        }
        
        $mid = $user['mid'];
        $cid = $user['bind_company'];
        
        FileUtil::requireService("MobileOrderServ");	
		$service=new MobileOrderServ();		
		$orderArray = $service->getPurchaseOrder($mid,$cid,$orderType,0);
		if($orderArray === false){
			exit(0);
		}
		$newOrderArray = array();
		if(!empty($orderArray)){
			$newOrderArray = $this->getNewOrderArray($orderArray);
		} 
		$params['style'] = 'personal';
		$params['substyle'] = 'shopOrder';
		$params['order_type'] = $orderType;
		$params['order'] = $orderArray;
        return $params;
    }
    
    function getNewOrderArray($orderArray){
		for($i=0;$i<count($orderArray);$i++){
			FileUtil::requireService("MobileOrderServ");
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