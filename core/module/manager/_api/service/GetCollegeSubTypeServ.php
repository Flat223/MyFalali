<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetCollegeSubTypeServ extends BaseAction{
	
	public function action(){
		FileUtil::requireService("AdminServ");
		$service = new AdminServ();
		$typeArray = $service->getCollegeSubType();
		$ret = array();
		if($typeArray === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		if($typeArray == null) {
			$ret['ret'] = 0;
			$ret['msg'] = "没有数据了";
			return $ret;
		}
		 
		$ret['ret'] = 1;
		$ret['msg'] = "获取成功"; 
		$ret['data'] = $typeArray;
		return $ret;
	}
}