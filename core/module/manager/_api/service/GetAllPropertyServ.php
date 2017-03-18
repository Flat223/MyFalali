<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetAllPropertyServ extends BaseAction{
	
	public function action(){
		FileUtil::requireService('PropertyServ');
        $service = new PropertyServ();
        
		$property = $service->getAllProperty();
        if($property === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		 
		$ret['ret'] = 1;
		$ret['msg'] = "获取成功"; 
		$ret['property'] = $property;
		return $ret;
	}
}