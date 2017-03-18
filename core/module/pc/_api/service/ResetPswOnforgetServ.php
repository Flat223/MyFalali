<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class ResetPswOnforgetServ extends BaseAction{
	
	public function action(){
		$ret = array();
		$mobile = isset($_REQUEST['mobile'])?trim($_REQUEST['mobile']):"";
		$validcode = isset($_REQUEST['validcode'])?trim($_REQUEST['validcode']):"";
		$password = isset($_REQUEST['password'])?trim($_REQUEST['password']):"";
		
		if(!Common::isMobile($mobile)){
			$ret['ret'] = 0;
			$ret['msg'] = "请填写正确的手机号码";
			return $ret;
		}
		FileUtil::requireService('UserServ');
		$service = new UserServ();
		$user = $service->getMemberByMobile($mobile);
		
		if($user === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误";
			return $ret;
		}
		if($user == null || $user['status'] != 1){
			$ret['ret'] = 0;
			$ret['msg'] = "该手机号尚未注册";
			return $ret;
		}
		if($validcode == ""){
			$ret['ret'] = 0;
			$ret['msg'] = "请填写验证码";
			return $ret;
		}
		if($password == ""){
			$ret['ret'] = 0;
			$ret['msg'] = "请填写密码";
			return $ret;
		}
		if(strlen($password) < 6){
			$ret['ret'] = 0;
			$ret['msg'] = "密码长度不低于6位";
			return $ret;
		}
		
		FileUtil::requireService("CheckVerifycodeServ");
        $check=new CheckVerifycodeServ();
        $res=$check->checkverify($mobile,$validcode,'forgetpsw');
        
		if($res === null){
			$ret['ret'] = 0;
			$ret['msg'] = "验证码无法验证";
			return $ret;
		}
		if(!isset($res['ret']) || $res['ret'] != 1){
			$ret['ret'] = 0;
			$ret['msg'] = $res['msg'];
			return $ret;
		}
		
		FileUtil::requireService('UserServ');
		$service = new UserServ();
		$result = $service->updateMemberPassword($user['mid'],$mobile,$password);
		if($result === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误";
			return $ret;
		}
		
		$ret['ret'] = 1;
		$ret['msg'] = "密码重置成功";
		return $ret;
	}
}