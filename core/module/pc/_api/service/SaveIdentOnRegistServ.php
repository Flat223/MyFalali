<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class SaveIdentOnRegistServ extends BaseAction{
	
	public function action(){
		$mid=isset($_REQUEST['mid'])?trim($_REQUEST['mid']):"";
		$tid=isset($_REQUEST['tid'])?trim($_REQUEST['tid']):"";
		$subtid=isset($_REQUEST['subtid'])?trim($_REQUEST['subtid']):"";
		
		if(empty($mid)){
			$ret['ret'] = 0;
			$ret['msg'] = "缺少参数";
			return $ret;
		}	
		if(empty($tid)){
			$ret['ret'] = 0;
			$ret['msg'] = "请选择您的身份";
			return $ret;
		}
		if($tid == 3 || $tid == 4){
			$subtid = 0;
		}
		
		FileUtil::requireService("UserServ");
		$service = new UserServ();
		$user = $service->getMemberByMid($mid);
		if(empty($user)){
			$ret['ret'] = 0;
			$ret['msg'] = '参数错误';
			return $ret;
		}
		
 	 	$callback = $service->saveUserIdentity($mid,$tid,$subtid);	
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉,服务器错误,请稍后再试";
			return $ret;
		}
		
		$ret['ret'] = 1;
		$ret['msg'] = "保存成功";
		return $ret;
	}
}

?>