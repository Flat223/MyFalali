<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetProductSkuPriceServ extends BaseAction{
	
	public function action(){
		$ret = array();
		$property=isset($_REQUEST['ids'])?trim($_REQUEST['ids']):"";
		if(empty($property)){
			$ret['ret']=0;
			$ret['msg']="参数错误";
			return $ret;
		}
		FileUtil::requireService("ProductServ");
		$service=new ProductServ();
		$sku=$service->getSkuDetail($property);
		if($sku===false){
			$ret['ret']=0;
			$ret['msg']="抱歉，服务器错误，请稍后再试";
			return $ret;
		}
/*
		if($sku==null){
			$ret['msg']=0;
			$ret['msg']="无数据";
			return $ret;
		}
*/
		$ret['ret']=1;
		$ret['msg']="获取成功";
		$ret['data']=$sku;
		return $ret;
		
	}
}