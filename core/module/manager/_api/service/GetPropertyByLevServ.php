<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetPropertyByLevServ extends BaseAction{
	
	public function action(){
		FileUtil::requireService('PropertyServ');
        $service = new PropertyServ();
        
		$property = $service->getPropertyByLevl(4);
        if($property === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		
		$property2 = $service->getPropertyByLevl(5);
        if($property2 === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		 
		$ret['ret'] = 1;
		$ret['msg'] = "获取成功"; 
		$ret['forth'] = $property;
		$ret['fifth'] = $property2;
		return $ret;
	}
}