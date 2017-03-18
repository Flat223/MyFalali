<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/filter/BaseFilter.php');
class AutoLoginFilter extends BaseFilter{
	
	public function __construct(){
		
	}
	
	public function doFilter($filter_path){
		if(UserAgent::isLogin()){
			return true;
		}
		
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
		if($user == null || $user['status'] != 1){
			return true;
		}
		$md5Str = md5($user['mobile'].($user['password']).$cookieTime.'labring');
		if($cookieValues[2] != $md5Str){
			return true;
		}
		
		UserAgent::addUser($user);
		return true;
	}
	
}