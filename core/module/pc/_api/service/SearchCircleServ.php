<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class SearchCircleServ extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		$key = isset($_REQUEST['key'])?trim($_REQUEST['key']):'';
			
		FileUtil::requireService("CircleServ");
		$service=new CircleServ();
		$circleArray = $service->searchCircleByKey($key);
		
		$callback = $circleArray;	
		$ret = array();
		if($callback === false){
			$ret['ret'] = -1;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		if($callback == null) {
			$ret['ret'] = 0;
			$ret['msg'] = "没有数据了";
			return $ret;
		}
		 
		$ret['ret'] = 1;
		$ret['msg'] = "搜索成功"; 
		$ret['data'] = $callback;
		return $ret;
	}
}

?>