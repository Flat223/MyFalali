<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class LoginServ extends BaseAction{
	
	public function action(){
		$logintype = isset($_REQUEST['logintype'])?trim($_REQUEST['logintype']):"";
		$auto_login = isset($_REQUEST['auto_login'])?trim($_REQUEST['auto_login']):"";
		$mobile = isset($_REQUEST['mobile'])?trim($_REQUEST['mobile']):"";
		$password = isset($_REQUEST['password'])?$_REQUEST['password']:"";
		$validcode = isset($_REQUEST['validcode'])?trim($_REQUEST['validcode']):"";
		
		if($logintype != 1 && $logintype != 2){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		
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
			$ret['msg'] = "抱歉，服务器错误1,请稍后再试";
			return $ret;
		}
		if(empty($user)){
			$ret['ret'] = 0;
			$ret['msg'] = "该手机号尚未注册";
			return $ret;
		}
		if($logintype == 2){
			if(empty($password)){
				$ret['ret'] = 0;
				$ret['msg'] = "请填写密码";
				return $ret;
			}
			if(strlen($password) < 6){
				$ret['ret'] = 0;
				$ret['msg'] = "密码长度不低于6位";
				return $ret;
			}
			
			$user = $service->getMember($mobile,$password);
			if($user === false){
				$ret['ret'] = 0;
				$ret['msg'] = "抱歉，服务器错误2，请稍后再试";
				return $ret;
			}
			if(empty($user)){
				$ret['ret'] = 0;
				$ret['msg'] = "账号或密码错误";
				return $ret;
			}
		} else {
			if($validcode == ""){
				$ret['ret'] = 0;
				$ret['msg'] = "请填写验证码";
				return $ret;
			}
			
			FileUtil::requireService("CheckVerifycodeServ");
	        $check=new CheckVerifycodeServ();
	        $res=$check->checkverify($mobile,$validcode,'login');
	        if($res === false){
		        $ret['ret'] = 0;
				$ret['msg'] = "抱歉，服务器错误3，请稍后再试";
				return $ret;
	        }
			if(empty($res)){
				$ret['ret'] = 0;
				$ret['msg'] = "验证码无法验证";
				return $ret;
			}
			if(!isset($res['ret']) || $res['ret'] != 1){
				$ret['ret'] = 0;
				$ret['msg'] = $res['msg'];
				return $ret;
			}
		}
			
		UserAgent::addUser($user);
		if($auto_login){
			$cookieTime = strtotime("+2 week");//有效时间两周
			$webKey = 'labring';
			$md5Str = md5($user['mobile'].$user['password'].$cookieTime.$webKey);
			$base64Str = base64_encode($user['mobile'].':'.$cookieTime.':'.$md5Str);
			$ret['cookie'] = $base64Str;
		}
		$ret['ret'] = 1;
		$ret['msg'] = "登录成功";
		return $ret;
	}
}