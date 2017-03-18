<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class ManagerProduct extends BaseAction{
	
	public function action(){
        $page=isset($_GET['page'])?trim($_GET['page']):1;
        $sta=isset($_GET['sta'])?trim($_GET['sta']):1;//默认为已上架;
        $name=isset($_GET['name'])?trim($_GET['name']):'';

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
		if(empty($myshop)){
			FileUtil::load404Html();
			exit(0);
		} 
		
		$pagesize = 10;
		$baseUrl = "../myshop/managerProduct.html?";
		if($sta){
			$baseUrl .= "&sta=".$sta;
		}
		if($name){
			$baseUrl .= "&name=".$name;
		}
		
		if(!Common::isInteger($page) || $page <= 0){
			$page = 1;
		}
		
		FileUtil::requireService("ShopServ");
        $shopServ = new ShopServ();
        
        if(strstr($name,"LR-")){
	    	$name = substr($name,3);
        }
        $count = $shopServ->userProductCount($myshop['sid'],$sta,$name);
        if($count === false){
	        FileUtil::load404Html();
	        exit(0);
        }
        
        $pageUtil = new PageUtil($pagesize,$count,$page);
        $index = ($pageUtil->getCurrentPage()-1)*$pagesize;
        $product = $shopServ->userProductBymid($myshop['sid'],$sta,$name,$index,$pagesize);
        if($product === false){
	        FileUtil::load404Htnl();
	        exit(0);
        }
               
        $allPro_count = $shopServ->getAllShopProCount($myshop['sid']);
        if($allPro_count === false){
			FileUtil::load404Html();
			exit(0);
        }
        
        FileUtil::requireService("ProductServ");
		$service=new ProductServ();
        foreach ($product as $key=>$v){
            $product[$key]['val']=$service->getLowPricebyId($v['pid']);
        }
		        		
        $params['style'] = 'myshop';
		$params['substyle'] = 'managerProduct';	
		
		$params['pro_status']=$sta;
		$params['allPro_count'] = $allPro_count;
        $params['product'] = $product;
        $params['count'] = $count;
        $params['baseurl'] = $baseUrl;
        $params['pager'] = $pageUtil;
		return $params;
	}
	
}