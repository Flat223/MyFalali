<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class DeleteLaboratoryServ extends BaseAction{
	
	public function action(){
		$lid = isset($_REQUEST['lid'])?trim($_REQUEST['lid']):"";
		if ($lid == 0) {
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉,没有该实验室";
			return $ret;
		}
		FileUtil::requireService("UserServ");
		$service = new UserServ();
		$callback = $service->deleteLaboratoryById($lid);
		$ret = array();		
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		if($callback){
			$ret['ret'] = 1;
			$ret['msg'] = "删除成功"; 
		}
		return $ret;
	}
}