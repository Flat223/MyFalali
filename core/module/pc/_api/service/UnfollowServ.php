<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class UnfollowServ extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		$follow_mid = isset($_REQUEST['mid'])?trim($_REQUEST['mid']):"";
		FileUtil::requireService("FriendsServ");
		$service = new FriendsServ();
		$callback = $service->unfollow($user['mid'],$follow_mid);
		
		$ret = array();		
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
		} else if($callback){
			$ret['ret'] = 1;
			$ret['msg'] = "取关成功"; 
		}
		return $ret;
	}
}
?>