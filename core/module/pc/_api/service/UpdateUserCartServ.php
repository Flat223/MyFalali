<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class UpdateUserCartServ extends BaseAction{
	
	public function action(){
		$ret = array();
		$user = UserAgent::getUser();
			if($user == null)
			{
				$ret['ret'] = -1;
				$ret['msg'] = "尚未登录，登陆后重试";
				return $ret;
			}
		$mid = $user['mid'];
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
		if(!Common::isInteger($id) || $id <= 0){
		       $id = 0;
	    }
		$num = isset($_REQUEST['num']) ? trim($_REQUEST['num']) : "";
		FileUtil::requireService("UserServ");
		$service = new UserServ();
		$callback = $service->updateShopcart($id,$mid,$num);
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		if($callback == null){
			$ret['ret'] = 0;
			$ret['msg'] = "没有更新内容";
			return $ret;
		}
		$ret['ret'] = 1;
		$ret['msg'] = "修改成功";
		return $ret;		
	}
}