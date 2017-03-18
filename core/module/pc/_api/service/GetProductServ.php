<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetProductServ extends BaseAction{
	
	public function action(){
		FileUtil::requireService('ProductServ');
        $service = new ProductServ();
        
        $pid = isset($_REQUEST['pid'])?trim($_REQUEST['pid']):"";
        if(!Common::isInteger($pid) || $pid <= 0){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		$product = $service->getSingleProduct($pid);
        if($product === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		 
		$ret['ret'] = 1;
		$ret['msg'] = "获取成功"; 
		$ret['product'] = $product;
		return $ret;
	}
}