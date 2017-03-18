<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetSkuDetailServ extends BaseAction{
	
	public function action(){
		$ret = array();
		$skuid=isset($_REQUEST['skuid'])?trim($_REQUEST['skuid']):0;
		if(!Common::isInteger($skuid)||$skuid<=0){
			$ret['ret']=0;
			$ret['msg']="无数据";
			return $ret;
		}
		FileUtil::requireService("ProductServ");
		$service=new ProductServ();
		$sku=$service->getSkuDetailById($skuid);
		if($sku===false){
			$ret['ret']=0;
			$ret['msg']="数据错误";
			return $ret;
		}
		if($sku==null){
			$ret['ret']=0;
			$ret['msg']="无数据";
			return $ret;
		}
		$ret['ret']=1;
		$ret['msg']="获取成功";
		$ret['data']=$sku;
		return $ret;
		exit(0);
	}
}