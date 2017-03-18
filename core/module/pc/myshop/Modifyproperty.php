<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Modifyproperty extends BaseAction{

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
		if(empty($shop)){
			FileUtil::load404Html();
			exit(0);
		} 

		$pid=isset($_GET['pid'])?trim($_GET['pid']):"0";
		if(!Common::isInteger($pid) || $pid <= 0){
			FileUtil::load404Html();
			exit(0);
		}		
		FileUtil::requireService("ProductServ");
		$service=new ProductServ();
		
		$product = $service->getMyProduct($pid);
		if($product === false || empty($product)){//该产品不存在
// 			echo '1';
			FileUtil::load404Html();
			exit(0);
		}
		
		$product_type = $service->getSingleType($product['ptid']);
		if($product_type === false || empty($product_type)){
			FileUtil::load404Html();
			exit(0);
		}
		
		$propertyArray= $service->getProductProperty($product['ptid']);
		if($propertyArray === false){
// 			echo '3';
			FileUtil::load404Html();
			exit(0);
		}
		if(empty($propertyArray)){
			$proptype = 2; 
		} else {
			$proptype = 1; 
		}
		
		$properties = array();
		if(!empty($propertyArray)){
			for($i = 0; $i < count($propertyArray); $i++){
				$prop = $propertyArray[$i];
				$prop['vals'] = $service->getPropertyValByProId($prop['id'],$product['pid']);//获取该产品属性的属性值
				if($prop['vals'] === false){
					FileUtil::load404Html();
					exit(0);
				}
				if(!empty($prop['vals'])){
					$properties[] = $prop;	
				}
			}		
		}
							
		$skuArr = $service->getSkuArr($properties);
		foreach($skuArr as &$arr){
			sort($arr['vals']);
			$skuid = join(",",$arr['vals']);
			$arr['skuid'] = $skuid;
		}
		$skus = $service->getProductSkuByPid($product['pid']);
		if($skus === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		$skuInfo = array();
		if(!empty($skus)){
			if($proptype == 1){
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
							$skuInfo[] = $temp;
							break;
						}
					}
				}
			} else {
				$skuInfo['pid'] = $skus[0]['pid'];
				$skuInfo['skuid'] = $skus[0]['properties'];
				$skuInfo['name'] = array();
				$skuInfo['price'] = $skus[0]['price'];
				$skuInfo['inventory'] = $skus[0]['inventory'];
				$skuInfo['inventory_warn'] = $skus[0]['inventory_warn'];
			}
		}
		
		$params = array();
		$params['style'] = 'myshop';
		$params['substyle'] = 'managerProduct';	
		$params['product'] = $product;	
		$params['property'] = $propertyArray;
		$params['pro_vals'] = $properties;			
		$params['skus'] = $skuInfo;		
		$params['proptype'] = $proptype;
		$params['product_type'] = $product_type;
		return $params;
	}

}