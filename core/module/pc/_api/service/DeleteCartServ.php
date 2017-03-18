<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class DeleteCartServ extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		if($user == null){
			$ret['ret'] = -1;
			$ret['msg'] = "尚未登录，登陆后重试";
			return $ret;
		}
		$mid = $user['mid'];
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
		if(!Common::isInteger($id) || $id <= 0){
	       $id = 0;
	    }
		FileUtil::requireService("UserServ");
		$service = new UserServ();
		$callback = $service->deleteShopcart($mid,$id);	
		$ret = array();
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误";
			return $ret;
		}
		$ret['ret']=1;
		$ret['msg']="删除成功";
		return $ret;

	}
}