<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/filter/BaseFilter.php');
class loginFilter extends BaseFilter{
	
	public function __construct(){
		
	}
	
	public function doFilter($filter_path){
		$user = UserAgent::getUser();
		if(!empty($user)){
			FileUtil::requireService("UserServ");
			$service = new UserServ();
			$user2 = $service->getMemberByMid($user['mid']);
			if($user2 === false){
				FileUtil::load404Html();
				exit(0);
			}
			if(!empty($user2)){
				$_SESSION['user'] = $user2;
				return true;
			}	
		}
		
		if($this->checkCookie()){//没有存cookie
			$Redirect = 'http://'.$_SERVER['HTTP_HOST'].$filter_path;
			header('Location: ../../member/login.html?redirect='.$Redirect);
			return false;
		}
		return true;
	}
	
	function checkCookie(){
		$cookieValue = isset($_COOKIE['auto_login'])?$_COOKIE['auto_login']:'';
		if(empty($cookieValue)){
			return true;
		}
		$cookieValues = explode(":",base64_decode($cookieValue));
		if(count($cookieValues) != 3){
			return true;
		}
		
		$cookieTime = $cookieValues[1];
		if($cookieTime < time()){
			return true;
		}
		
		$mobile = $cookieValues[0];
		FileUtil::requireService('UserServ');
		$service = new UserServ();
		$user = $service->getMemberByMobile($mobile);
		if($user === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误";
			return $ret;
		}
		if(empty($user) || $user['status'] != 1){
			return true;
		}
		$md5Str = md5($user['mobile'].($user['password']).$cookieTime.'labring');
		if($cookieValues[2] != $md5Str){
			return true;
		}
		
		UserAgent::addUser($user);
		return false;
	}
	
}