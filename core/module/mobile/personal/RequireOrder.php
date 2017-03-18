<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class RequireOrder extends BaseAction{

    public function action(){
/*
	    FileUtil::load404Html();
		exit(0);
*/
	    $user = UserAgent::getUser();
	    //公司成员并且绑定了公司,或者是公司本身
        if($user['type'] != 2 || ($user['sub_type'] != 0 && $user['bind_status'] != 2)){
			FileUtil::load404Html();
			exit(0);
		}
		
   	    $params = array();
        $agree=isset($_GET['agree'])?trim($_GET['agree']):"0";
        
        $mid = $user['mid'];
        $cid = $user['bind_company'];
        $subType = $user['sub_type'];
        
        FileUtil::requireService("MobileOrderServ");	
		$service=new MobileOrderServ();		
		$orderArray = $service->getOrder($mid,$cid,1,$subType,$agree,'0',0,10);
		if($orderArray === false){
			FileUtil::load404Html();
			exit(0);
		}
		$newOrderArray = array();
		if(!empty($orderArray)){
			$newOrderArray = $this->getNewOrderArray($orderArray);
		} 
		$params['style'] = 'personal';
		$params['substyle'] = 'requireOrder';
		$params['order'] = $newOrderArray;
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