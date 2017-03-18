<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/filter/BaseFilter.php');
class LoginFilter extends BaseFilter{
	
	public function __construct(){
		
	}
	
	public function doFilter($filter_path){
		if(UserAgent::isLogin()){
			return true;
		}
		$Redirect = 'http://'.$_SERVER['HTTP_HOST'].$filter_path;
		header('Location: ../../login/login.html?redirect='.$Redirect);
		return false;
		
		
		
		
		
		
/*
		if(UserAgent::isLogin()){
			$Redirect = $_REQUEST["redirect"];
			if(!empty($Redirect))
			{
				header('Location: '.$Redirect);
			}
			else
			{
				header('Location: '.'http://'.$_SERVER['HTTP_HOST']);
			}
			return false;
		}
  
		return true;
*/
	}
	
	
}