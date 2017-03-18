<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class RebindCompanyServ extends BaseAction{
	public function action(){
		$ret = array();
		$user = UserAgent::getUser();
		FileUtil::requireService('CompanyServ');
		$service = new CompanyServ();
		$callback = $service->ReBindCompany($user['mid']);
		
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		
		if($callback){
			FileUtil::requireService('UserServ');
			$service = new UserServ();
			$user2 = $service->getMemberByMid($user['mid']);
			$_SESSION['user'] = $user2;
			
			$ret['ret'] = 1;
			$ret['msg'] = "请求成功";
			return $ret;
		}
	}
}