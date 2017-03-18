<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class ShopCart extends BaseAction{
	
	public function action(){
		$ret=array();
		$user=UserAgent::getUser();
		if($user==null){
			$url="/member/login.html";
			Header("Location:$url");
		}
		$mid=$user['mid'];
		FileUtil::requireService("UserServ");
		$service=new UserServ();
		$list=$service->getCartList($mid);
		FileUtil::requireService("ProductServ");
		$service1=new ProductServ();
		FileUtil::requireService('ShopServ');
		$service2=new ShopServ();
		$sidlist=array();
		for($a=0;$a<count($list);$a++){
			$sidlist[]=$list[$a]['sid'];
		}
		$sidlistnew=array_unique($sidlist);
		sort($sidlistnew);
		$productlist=array();
		for($i=0;$i<count($sidlistnew);$i++){
			$shop=$service2->getShopDetail($sidlistnew[$i]);
			$productlist[]=$shop;
		}
		foreach($list as $key=>$single){
			$pid=$single['pid']; 
			$skuid=$single['skuid'];
			$sid=$single['sid'];
			$pid=md5($pid);
			$product=$service1->getCartProduct($pid,$skuid);
			$property=$service1->getCartProperties($skuid);
			$product['property']=$property;
			$product['num']=$single['num'];
			$product['cid']=$single['id'];
			$product['testing']=$single['testing'];
			$product['guarantee']=$single['guarantee'];
			foreach($productlist as $key=>$p){
				if($p['sid']==$sid){ 	
					$productlist[$key]['product'][]=$product;
				}
			}
		}

		$params = array();
		$params['productlist']=$productlist;
		
		return $params;
	}
	
}