<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddProNext extends BaseAction{
	
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

		$pid=isset($_GET['pid'])?trim($_GET['pid']):"";
		if(empty($pid) || !strstr($pid,'LR-')){
			FileUtil::load404Html();
			exit(0);
		}
		$pid = substr($pid,3);//将产品编号转为pid;
		FileUtil::requireService("ProductServ");
		$service=new ProductServ();
		
		$product = $service->getMyProduct($pid);
		if($product === false || empty($product)){//该产品不存在
// 			echo '1';
			FileUtil::load404Html();
			exit(0);
		}
		
		$product_type = $service->getSingleType($product['ptid']);
		if($product_type === false || empty($product_type)){//产品分类不存在
			FileUtil::load404Html();
			exit(0);
		}
		
		$property = $service->getProductProperty($product['ptid']);
		if($property === false){
// 			echo '3';
			FileUtil::load404Html();
			exit(0);
		}
		if(empty($property)){
			$proptype = 2;
		} else {
			$proptype = 1;
		}
		
/*
		$result = $service->clearProductValues1($pid);//清除该产品已添加过的属性值
		if($result === false){
			FileUtil::load404Html();
			exit(0);
		}
		$result = $service->clearProductSku($pid);//清除该产品已添加过的库存信息
		if($result === false){
			FileUtil::load404Html();
			exit(0);
		}
*/
		
		$params['style'] = 'myshop';
		$params['substyle'] = 'addProNext';	
		$params['product'] = $product;	
		$params['property'] = $property;	
		$params['proptype'] = $proptype;	
		$params['product_type'] = $product_type;	
		return $params;
	}
	
}