<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddProduct extends BaseAction{
	
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
		if($shop == null){
			FileUtil::load404Html();
			exit(0);
		} 
		
		FileUtil::requireService("FreightServ");
		$service=new FreightServ();
		$freight = $service->getShopFreight($shop['sid']);
		if($freight === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		FileUtil::requireService("ShopAddressServ");
		$service = new ShopAddressServ();
		$address = $service->getShopAddress($shop['sid']);
		if($address === false){
			FileUtil::load404Html();
			exit(0);
		}
				
		FileUtil::requireService("ProductServ");
		$service=new ProductServ();
		$firstType = $service->getProductTypeByLev(1);	
		if($firstType === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		$allSecond = $service->getProductTypeByLev(2);
		$allThird = $service->getProductTypeByLev(3);
		$allForth = $service->getProductTypeByLev(4);
		$allFifth = $service->getProductTypeByLev(5);
		
		FileUtil::requireService("PropertyServ");
		$service = new PropertyServ();
		$property = $service->getAllProperty();
        if($property === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		FileUtil::requireService("BrandServ");
		$service=new BrandServ();
		$brandArray = $service->getBrandList('');	
		if($brandArray === false){
			FileUtil::load404Html();
			exit(0);
		}

		$params['style'] = 'user';
		$params['substyle'] = 'addProduct';	
		$params['brand'] = $brandArray;		
		$params['freight'] = $freight;
		$params['address'] = $address;
		
		$params['property'] = $property;		
		$params['first_type'] = $firstType;		
		$params['second_type'] = $allSecond;		
		$params['third_type'] = $allThird;		
		$params['forth_type'] = $allForth;		
		$params['fifth_type'] = $allFifth;		
		return $params;
	}
	
}