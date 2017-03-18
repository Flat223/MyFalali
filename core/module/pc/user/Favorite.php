<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Favorite extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		FileUtil::requireService("UserServ");
		$service=new UserServ();
		$product=$service->getUserCollection(1,$user['mid']);
		$laboratory=$service->getUserCollection(2,$user['mid']);
		foreach($product as $key=>$single){
			$product[$key]['pid']=($single['pid']);
		}
		$params = array();
		$params['style'] = 'user';
		$params['substyle'] = 'favorite';
		$params['product'] = $product;
		$params['laboratory'] = $laboratory;
		return $params;	
	}
}