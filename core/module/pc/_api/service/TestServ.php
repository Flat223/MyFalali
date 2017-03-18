<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class TestServ extends BaseAction{
	
	public function action(){
		FileUtil::requireService("UserServ");
		$service=new UserServ();
		$callback = $service->getMemberByMidMd5('7776f20cbd4775fe15cae89eb23912b6'); 
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "出错";
			return $ret;
		}
		if(empty($callback)) {
			$ret['ret'] = 0;
			$ret['msg'] = "没有数据了";
			return $ret;
		}
		 
		$ret['ret'] = 1;
		$ret['msg'] = "获取成功"; 
		$ret['data'] = $callback;
		return $ret;
	}
}

?>