<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Compare extends BaseAction{
	
	public function action(){
		$pids=isset($_REQUEST['pids'])?trim($_REQUEST['pids']):"";
		$pidarray=explode(',', $pids);
		FileUtil::requireService("ProductServ");
		$service=new ProductServ();
		$products=array();
		$ptid=0;
		for($i=0;$i<count($pidarray);$i++){
			$product=$service->getProductDetail($pidarray[$i]);
			$percent=$service->getGoodCommentPercent($pidarray[$i]);
			if($product!==false&&$percent!==false){
				$product['percent']=$percent;
				$products[]=$product;
/*
				echo(json_encode($percent));
				exit(0);
*/
			}
			if($i==0){
				$ptid=$product['ptid'];
			}
		}

		$reproducts=$service->getRecommentProducts($ptid);
		$params = array();
		$params['products']=$products;
		$params['reproducts']=$reproducts;
		return $params;
	}
	
}