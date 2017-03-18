<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class RegistServ extends BaseAction{
	
	public function action(){
		$mobile = isset($_REQUEST['mobile'])?trim($_REQUEST['mobile']):'';
		$password = isset($_REQUEST['password'])?$_REQUEST['password']:'';
		$validcode = isset($_REQUEST['validcode'])?trim($_REQUEST['validcode']):'';
		$ret = array();
		if($mobile == ""){
			$ret['ret'] = 0;
			$ret['msg'] = "请填写手机号码";
			return $ret;
		}
		if(!Common::isMobile($mobile)){
			$ret['ret'] = 0;
			$ret['msg'] = "请填写正确的手机号码";
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
        $res=$check->checkverify($mobile,$validcode,'regist');
        
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
		$user = $service->getMemberByMobile($mobile);
		if($user === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误1";
			return $ret;
		}
		if($user){
			$ret['ret'] = 5;
			$ret['msg'] = "该手机号码已注册";
			return $ret;
		}
		
		$callback = $service->addMember($mobile,$password);
		if(!$callback){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误2，请稍后再试";
			return $ret;
		}
		$user = $service->getMember($mobile,$password);
		if($user === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误3，请稍后再试";
			return $ret;
		}
		if(empty($user)){
			$ret['ret'] = 0;
			$ret['msg'] = "服务器繁忙,请稍后再试";
			return $ret;
		}
		
		$ret['ret'] = 1;
		$ret['mid'] = $user['mid'];
		$ret['msg'] = "操作成功";
		return $ret;	
	}
}