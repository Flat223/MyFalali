<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetCompareListServ extends BaseAction{
	
	public function action(){
		$ret=array();
		$idstring=isset($_REQUEST['ids'])?trim($_REQUEST['ids']):"";
		$ids=explode(',', $idstring);
		FileUtil::requireService('ProductServ');
		$service=new ProductServ();
		$products=array();
		for($i=0;$i<count($ids);$i++){
			$product=$service->getSingleProductMd5($ids[$i]);
			if($product!=null&&$product!==false){
				$products[]=$product;
			}
		}
		$ret['ret']=1;
		$ret['msg']="获取成功";
		$ret['data']=$products;
		return $ret;	
	}
}