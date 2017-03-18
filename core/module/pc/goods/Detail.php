<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Detail extends BaseAction{
	
	public function action(){
		FileUtil::requireService("ProductServ");
        FileUtil::requireService("ShopServ");
        $myshop=new ShopServ();
        $service=new ProductServ();
		$params=array();
		$pid=isset($_REQUEST['pid'])?trim($_REQUEST['pid']):0;
		$callback=$service->updateSee($pid);
		$pskuid=isset($_REQUEST['skuid'])?trim($_REQUEST['skuid']):0;
		if(!Common::isInteger($pskuid)||$pskuid<=0){
				
		}else{
			$psku=$service->getSkuDetailByIdmd($pskuid);
			if($psku===false){
				FileUtil::load404Html();
				exit(0);
			}
			$params['sku']=$psku;
		}
		FileUtil::requireService("GoodsServ");
		$gservice=new GoodsServ();
		$province=$gservice->getProvince();
		$count=$service->getProductSku($pid);
		$normal=0;
		if($count<=0){
			$normal=1;
		}
		$product=$service->getProductDetail($pid);
		if($product===false){
			$normal=1;
			$params=array();
			$params['msg']="服务器有错误，请稍后再试";
			FileUtil::load0000Html($params);
			exit(0);
		}
		if($product==null){
			$normal=1;
			$params=array();
			$params['msg']="产品信息有错误，无法查看";
			FileUtil::load0000Html($params);
			exit(0);
		}
		$ptid=$product['ptid'];
		$property=$service->getProductProperty($ptid);
		$typedetail=$gservice->getprotype15($pid);
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
				$id=md5($id);
				$product1=$service->getProductDetail($id);
				$product1['skuid']=$skuidarr[$i];
				$prolist[$key]['product'][]=$product1;
			}
		};
		if($product['first_tid']==93){
			$product['name']='CAS:'.$product['CASnumber'].":".$product['name'].":".$product['purity'].":".$product['size'];
		}
        $params['skulist']=$vallist;
		$params['product']=$product;
		$params['comment']=$comment;
		$params['hotsell']=$hotsell;
		$params['prolist']=$prolist;
		$params['normal']=$normal;
		$params['province']=$province;
		$params['type']=$typedetail;
		return $params;
	}
	
}