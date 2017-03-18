<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetProductTypeServ extends BaseAction{
	
	public function action(){
// 		$level = isset($_REQUEST['level'])?trim($_REQUEST['level']):"";
		
		FileUtil::requireService("ProductServ");
		$service=new ProductServ();

		$secondType = $service->getProductTypeByLev(2);
		if($secondType === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		
		$thirdType = $service->getProductTypeByLev(3);	
		$forthType = $service->getProductTypeByLev(4);	
		$fifthType = $service->getProductTypeByLev(5);		
		 
		$ret['ret'] = 1;
		$ret['msg'] = "获取成功"; 
		$ret['second'] = $secondType;
		$ret['third'] = $thirdType;
		$ret['forth'] = $forthType;
		$ret['fifth'] = $fifthType;
		return $ret;
	}
}

?>