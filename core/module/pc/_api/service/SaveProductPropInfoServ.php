<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class SaveProductPropInfoServ extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		if($user == null){
			$ret['ret'] = -1;
			$ret['msg'] = "请登录";
			return $ret;
		}
		if($user['type'] != 3){
			$ret['ret'] = -1;
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
		$propType = isset($_REQUEST['proptype'])?trim($_REQUEST['proptype']):'';
		if(empty($propType)){
			$ret['ret'] = 0;
			$ret['msg'] = "缺少参数";
			return $ret;
		}
		if($propType != 1 && $propType != 2){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误1";
			return $ret;
		}
		
		if($propType == 1){
			return $this->handleRelativeProps($sid);
		}else{
			return $this->handleNoProp($sid);
		}
	}
	
	function handleRelativeProps($sid){
		$user = UserAgent::getUser();
		$skus = isset($_REQUEST['skus'])?trim($_REQUEST['skus']):"";
		$pid = isset($_REQUEST['pid'])?trim($_REQUEST['pid']):0;
		$ptid = isset($_REQUEST['ptid'])?trim($_REQUEST['ptid']):0;
		
		if(!Common::isInteger($pid) || $pid <= 0){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误2";
			return $ret;
		}
		
		if(empty($skus)){
			$ret['ret'] = 0;
			$ret['msg'] = "请添加属性关联";
			return $ret;
		}
		$skus = json_decode($skus,true);
		if($skus === null || !is_array($skus)){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误3";
			return $ret;
		}
		if(count($skus) === 0){
			$ret['ret'] = 0;
			$ret['msg'] = "请添加属性关联";
			return $ret;
		}
		foreach($skus as $sku){
			$skuid = isset($sku['skuid'])?$sku['skuid']:"";
			$price = isset($sku['price'])?$sku['price']:0;
			$inventory = isset($sku['inventory'])?$sku['inventory']:0;
			$inventoryWarn = isset($sku['inventoryWarn'])?$sku['inventoryWarn']:0;
			if($skuid == ""){
				$ret['ret'] = 0;
				$ret['msg'] = "参数错误4";
				return $ret;
			}
			if(!is_numeric($price) || $price <= 0){
				$ret['ret'] = 0;
				$ret['msg'] = "属性关联价格必须为大于0的数字";
				return $ret;
			}
			if($price >= 1000000000){
				$ret['ret'] = 0;
				$ret['msg'] = "请填写合适的属性关联价格";
				return $ret;
			}
			if(!Common::isInteger($inventory) || $inventory <= 0){
				$ret['ret'] = 0;
				$ret['msg'] = "产品库存必须为大于0的整数";
				return $ret;
			}
			
			if(!Common::isInteger($inventoryWarn) || $inventoryWarn <= 0){
				$ret['ret'] = 0;
				$ret['msg'] = "产品库存预警必须为大于0的整数";
				return $ret;
			}
		}
		
		FileUtil::requireService("ProductServ");
		$service=new ProductServ();
		$product = $service->getProductByPidAndSid($pid,$sid);
		if($product === false){
			$ret['ret'] = 0;
			$ret['msg'] = "服务器错误1";
			return $ret;
		}
		if(empty($product)){
			$ret['ret'] = 0;
			$ret['msg'] = "产品不存在";
			return $ret;
		}
		
		$properties = $service->getProductProperty($ptid);
		if($properties === false){
			$ret['ret'] = 0;
			$ret['msg'] = "服务器错误2";
			return $ret;
		}
		
		for($i = 0; $i < count($properties); $i++){
			$prop = $properties[$i];
			$prop['vals'] = $service->getPropertyValByProId($prop['id'],$pid);
			if($prop['vals'] === false){
				$ret['ret'] = 0;
				$ret['msg'] = "服务器错误3";
				return $ret;
			}
			$properties[$i] = $prop;
		}
				
		$skuidArr = $service->getSkuidArr($properties);
		$skuids = array();
		foreach($skuidArr as $arr){
			sort($arr);
			$skuid = join(",",$arr);
			$skuids[] = $skuid;
		}
		
		$upSkus = array();
		$addSkus = array();
		$retainSkuids = array();	//保留的sku
		$minPrice = 0;
		$unrepeatSkuids = array();
		foreach($skus as $sku){
			if(in_array($sku['skuid'], $unrepeatSkuids)){
				continue;
			}
			$unrepeatSkuids[] = $sku['skuid'];
			if(!in_array($sku['skuid'], $skuids)){
				$ret['ret'] = 0;
				$ret['msg'] = "参数错误3";
				return $ret;
			}
			if($minPrice === 0 || $minPrice > floatval($sku['price'])){
				$minPrice = $sku['price'];
			}
			
			$skuData = $service->getSkuByProperty($sku['skuid'],$pid);
			if($skuData === false){
				$ret['ret'] = 0;
				$ret['msg'] = "服务器错误4";
				return $ret;
			}
			if($skuData == null){
				$addSkus[] = $sku;
			}else{
				$upSkus[] = $sku;
			}
			$retainSkuids[] = $sku['skuid'];
		}
		$productInfo['price'] = $minPrice;

		$callback = $service->updateProduct($pid,$upSkus,$addSkus,$productInfo,$retainSkuids,1);
		if(!$callback){
			$ret['ret'] = 0;
			$ret['msg'] = "服务器错误5";
			return $ret;
		}
		$ret['ret'] = 1;
		$ret['msg'] = "操作成功";
// 		$_SESSION['pro_upload_suc'] = "suc";
		return $ret;
	}
	
	function handleNoProp($sid){
		$user = UserAgent::getUser();
		$price = isset($_REQUEST['price'])?trim($_REQUEST['price']):0;
		$inventory = isset($_REQUEST['inventory'])?trim($_REQUEST['inventory']):0;
		$inventoryWarn = isset($_REQUEST['inventoryWarn'])?trim($_REQUEST['inventoryWarn']):0;
		$pid = isset($_REQUEST['pid'])?trim($_REQUEST['pid']):0;
		
		if(!Common::isInteger($pid) || $pid <= 0){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误1";
			return $ret;
		}
	
		if(!is_numeric($price) || $price <= 0){
			$ret['ret'] = 0;
			$ret['msg'] = "产品价格必须为大于0的数字";
			return $ret;
		}
		if($price >= 1000000000){
			$ret['ret'] = 0;
			$ret['msg'] = "请填写合适的产品价格";
			return $ret;
		}
		if(!Common::isInteger($inventory) || $inventory <= 0){
			$ret['ret'] = 0;
			$ret['msg'] = "产品库存必须为大于0的整数";
			return $ret;
		}
		
		if(!Common::isInteger($inventoryWarn) || $inventoryWarn <= 0){
			$ret['ret'] = 0;
			$ret['msg'] = "产品库存预警必须为大于0的整数";
			return $ret;
		}
		FileUtil::requireService("ProductServ");
		$service=new ProductServ();
		$product = $service->getProductByPidAndSid($pid,$sid);
		if($product === false){
			$ret['ret'] = 0;
			$ret['msg'] = "服务器错误6";
			return $ret;
		}
		if(empty($product)){
			$ret['ret'] = 0;
			$ret['msg'] = "产品不存在";
			return $ret;
		}
		
		$upSkus = array();
		$addSkus = array();
		$sku = array();
		$sku['skuid'] = '';
		$sku['price'] = $price;
		$sku['inventory'] = $inventory;
		$sku['inventoryWarn'] = $inventoryWarn;
		$sku2 = $service->getSkuByProperty("",$pid);
		if($sku2 === false){
			$ret['ret'] = 0;
			$ret['msg'] = "服务器错误7";
			return $ret;
		}
		if(empty($sku2)){
			$addSkus[] = $sku;
		}else{
			$upSkus[] = $sku;
		}
		$productInfo['price'] = $price;
		$skuids = array();
		$skuids[] = '';
	
		$callback = $service->updateProduct($pid,$upSkus,$addSkus,$productInfo,$skuids,2);
		if(!$callback){
			$ret['ret'] = 0;
			$ret['msg'] = "服务器错误8";
			return $ret;
		}
		$ret['ret'] = 1;
		$ret['msg'] = "操作成功";
// 		$_SESSION['pro_upload_suc'] = "suc";
		return $ret;
	}
}

?>