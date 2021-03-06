<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class MallDetail extends BaseAction{

    public function action(){
		FileUtil::requireService("ProductServ");
        FileUtil::requireService("ShopServ");
        $myshop=new ShopServ();
        $service=new ProductServ();
		$params=array();
		$pid=isset($_REQUEST['pid'])?trim($_REQUEST['pid']):0;
		$pskuid=isset($_REQUEST['skuid'])?trim($_REQUEST['skuid']):0;
		if(!Common::isInteger($pskuid)||$pskuid<=0){
				
		}else{
			$psku=$service->getSkuDetailById($pskuid);
			if($psku===false){
				FileUtil::load404Html();
				exit(0);
			}
			$params['sku']=$psku;
		}
		/*if(!Common::isInteger($pid)||$pid<=0){
			FileUtil::load404Html();
			exit(0);
		}*/
		
		$product=$service->getProductDetail($pid);
		if($product===false){
			FileUtil::load404Html();
			exit(0);
		}
		if($product==null){
			FileUtil::load404Html();
			exit(0);
		}
		$ptid=$product['ptid'];
		$property=$service->getProductProperty($ptid);
/*
		echo(json_encode($property));
		exit(0);
*/
		$vallist=$service->getProductProperties($pid);

		$a=array();
		for($i=0;$i<count($vallist);$i++){
			$properties=$vallist[$i]['properties'];
			$temp=explode(',', $properties);
			for($b=0;$b<count($temp);$b++){
				if(in_array($temp[$b], $a)===false){
					$a[]=$temp[$b];
				}
			}
		}
		sort($a);
		$tempval=array();
		for($c=0;$c<count($a);$c++){
			$tempval[]=$service->getPropertyVal($a[$c]);
		}
		foreach($tempval as $value){
			foreach($property as $key=>$value2){
				if($value['propertyid']==$value2['id']){
					$property[$key]['propertyval'][]=$value;
				}
			}
		}		
		foreach($property as $key=>$ppp){
			if(!isset($ppp['propertyval'])){
				unset($property[$key]);
			}
		}
		$product['property']=$property;		
		$comment=$service->getProductComment($pid);
		if($comment===false){
			FileUtil::load404Html();
			exit(0);
		}
		$hotsell=$service->getHotSellProduct();
		if($hotsell===false){
			FileUtil::load404Html();
			exit(0);
		}
		$id=$service->getProductBySkuId(2);
		$prolist=array();
		$sku=$service->getProductGroup($pid);
		foreach($sku as $key=>$group){
			$skuids=$group['skuids'];
			$prolist[$key]['total_money']=$group['price'];
			$prolist[$key]['id']=$group['id'];
			$skuidarr=explode(',', $skuids);
			for($i=0;$i<count($skuidarr);$i++){
				$id=$service->getProductBySkuId($skuidarr[$i]);
				$product1=$service->getProductDetail($id);
				$product1['skuid']=$skuidarr[$i];
				$prolist[$key]['product'][]=$product1;
			}
		};
/*
		echo(json_encode($comment));
		exit(0);
*/
//        $params['shop']=$myshop->getShopByMid($product['sid']);

        $params['skulist']=$vallist;
		$params['product']=$product;
		$params['comment']=$comment;
		$params['hotsell']=$hotsell;
		$params['prolist']=$prolist;
		return $params;
    }

}