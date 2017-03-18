<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class ModifyProduct extends BaseAction{

    public function action(){
        $pid=isset($_GET['pid'])?$_GET['pid']:"";
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
        
        FileUtil::requireService("FreightServ");
		$service=new FreightServ();
		$freightArray = $service->getShopFreight($shop['sid']);
		if($freightArray === false){
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
        $product=$service->getSingleProduct($pid);
        $product['tu']=explode(',',$product['images']);
        if($product === false || empty($product)){
	        FileUtil::load404Html();
			exit(0);
        }
        $valuation_unit = 0;
        if($product['fre_id'] != 0){
	        foreach ($freightArray as $freight){
				if($freight['fre_id'] == $product['fre_id']){
					$valuation_unit = $freight['valuation_unit'];
					break;	
				}
			}
        }

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
        $service = new BrandServ();
        $brand = $service->getBrandById($product['brand_id']);
        if($brand === false){
			FileUtil::load404Html();
			exit(0);
		}

        $params['style'] = 'myshop';
		$params['substyle'] = 'managerProduct';
		$params['freight'] = $freightArray;
		$params['valuation_unit'] = $valuation_unit;
		$params['address'] = $address;
        $params['brand'] = $brand;
        $params['product']= $product;
        
        $params['property'] = $property;	
		$params['first_type'] = $firstType;		
		$params['second_type'] = $allSecond;		
		$params['third_type'] = $allThird;		
		$params['forth_type'] = $allForth;		
		$params['fifth_type'] = $allFifth;
        return $params;
    }

}