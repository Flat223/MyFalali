<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddGroupProductList extends BaseAction{
	
	public function action(){
	    $page=isset($_GET['page'])?$_GET['page']:1;
        $sta=isset($_GET['sta'])?$_GET['sta']:null;
		$user = UserAgent::getUser();
		if($user['type'] != 3){
			FileUtil::load404Html();
			exit(0);
		}
		FileUtil::requireService("ShopServ");
		$service=new ShopServ();
		$myshop = $service->getShopByMid($user['mid']);
		if($myshop === false){
			FileUtil::load404Html();
			exit(0);
		}
		if($myshop == null){
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
		$secondType = array();
		if(!empty($firstType)){
			$secondType = $service->getChildType($firstType[0]['ptid']);	
			if($secondType === false){
				FileUtil::load404Html();
				exit(0);
			}
		}
		$thirdType = array();
		if(!empty($secondType)){
			$thirdType = $service->getChildType($secondType[0]['ptid']);	
			if($thirdType === false){
				FileUtil::load404Html();
				exit(0);
			}
		}
		
		FileUtil::requireService("BrandServ");
		$service=new BrandServ();
		$brandArray = $service->getBrandList();	
		if($brandArray === false){
			FileUtil::load404Html();
			exit(0);
		}
        FileUtil::requireService("ShopServ");
        $shop = new ShopServ();
        $num=10;
        $product = $shop->userProductBymid($myshop['sid'],$num,$page,1,"");
        FileUtil::requireService("ProductServ");
        $Pro=new ProductServ();
        foreach ($product as $key=>$v){
            $product[$key]['val']=$Pro->getLowPricebyId($v['pid']);

        }

        FileUtil::requireService('PageUtil');

        $params = array();
        $params['myshop']=$myshop;
		$params['style'] = 'myshop';
		$params['substyle'] = 'addGroupProductList';	
		$params['first_type'] = $firstType;		
		$params['second_type'] = $secondType;		
		$params['third_type'] = $thirdType;		
		$params['brand'] = $brandArray;
        $params['product'] = $product;
        $params['count']=$shop->userProductCount($myshop['sid'],1);
        $pageUtil = new PageUtil(10,$params['count'],$page);
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/myshop/addGroupProductList.html?sta=1';
        $params['pager'] = $pageUtil;
		return $params;
	}
	
}