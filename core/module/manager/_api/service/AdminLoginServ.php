<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AdminLoginServ extends BaseAction{
	
	public function action(){
		$ret = array();
		$mobile = isset($_REQUEST['mobile'])?trim($_REQUEST['mobile']):"";
		$password = isset($_REQUEST['password'])?$_REQUEST['password']:"";
		if($mobile == ""){
			$ret['ret'] = 0;
			$ret['msg'] = "请填写手机号码";
			return $ret;
		}
		if($password == ""){
			$ret['ret'] = 0;
			$ret['msg'] = "请填写密码";
			return $ret;
		}
		FileUtil::requireService("AdminServ");
		$service = new AdminServ();
		$admin = $service->getAdmin($mobile,$password);
		if($admin === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		if(!$admin){
			$ret['ret'] = 0;
			$ret['msg'] = "账号或密码错误";
			return $ret;
		}
/*
		if($admin['status'] != '1'){
			$ret['ret'] = 0;
			$ret['msg'] = "此账号已不可用，请联系客服人员";
			return $ret;
		}
*/
		
		$_SESSION['admin'] = $admin;
		$ret['ret'] = 1;
		$ret['msg'] = "登录成功";
		$ret['data'] = $admin;
		return $ret;
	}
}