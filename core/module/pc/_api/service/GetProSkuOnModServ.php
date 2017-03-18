<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetProSkuOnModServ extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		if(empty($user)){
			$ret['ret'] = 0;
			$ret['msg'] = "请登录";
			return $ret;
		}
		if($user['type'] != 3){
			$ret['ret'] = 0;
			$ret['msg'] = "你的身份不是供应商";
			return $ret;
		}
		
		FileUtil::requireService("ShopServ");
		$service=new ShopServ();
		$shop = $service->getShopByMid($user['mid']);
		$sid = $shop['sid'];
		if($shop === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误0，请稍后再试";
			return $ret;
		}
		if(empty($shop)){
			$ret['ret'] = 0;
			$ret['msg'] = "请先申请店铺";
			return $ret;
		} 
		
		$pid=isset($_REQUEST['pid'])?trim($_REQUEST['pid']):"";
		if(empty($pid)){
			$ret['ret'] = 0;
			$ret['msg'] = "缺少参数";
			return $ret;
		}
		if(!Common::isInteger($pid) || $pid <= 0){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误1";
			return $ret;
		}
	
		FileUtil::requireService("ProductServ");
		$service=new ProductServ();
		
		$product = $service->getMyProduct($pid);
		if($product === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误1，请稍后再试";
			return $ret;
		}
		if(empty($product)){
			$ret['ret'] = 0;
			$ret['msg'] = "未找到该产品产品";
			return $ret;
		}
		
		$propertyArray= $service->getProductProperty($product['ptid']);
		if($propertyArray === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误2，请稍后再试";
			return $ret;
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
					$ret['ret'] = 0;
					$ret['msg'] = "抱歉，服务器错误3，请稍后再试";
					return $ret;
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
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误4，请稍后再试";
			return $ret;
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
		
		$ret['ret'] = 1;
		$ret['msg'] = "操作成功";
		$ret['product'] = $product;	
		$ret['property'] = $propertyArray;
		$ret['pro_vals'] = $properties;			
		$ret['skus'] = $skuInfo;		
		$ret['proptype'] = $proptype;
		return $ret;
	}
}

?>