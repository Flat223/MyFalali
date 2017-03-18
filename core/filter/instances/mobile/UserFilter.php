<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/filter/BaseFilter.php');
class UserFilter extends BaseFilter{
	
	public function __construct(){
		
	}
	
	public function doFilter($filter_path){
		if(UserAgent::isLogin()){
			return true;
		}
		$Redirect = 'http://'.$_SERVER['HTTP_HOST'].$filter_path;
		header('Location: ../../handle/login.html?redirect='.$Redirect);
		return false;
		
	}
	
	
	
	
	
	
	
}