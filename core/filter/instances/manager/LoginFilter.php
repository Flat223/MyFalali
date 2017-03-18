<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/filter/BaseFilter.php');
class LoginFilter extends BaseFilter{
	
	public function __construct(){
		
	}
	
	public function doFilter($filter_path){
		if(!UserAgent::isAdminLogin()){
			if($filter_path == "/login.html"){
				return true;
			}
			$Redirect = 'http://'.$_SERVER['HTTP_HOST'].$filter_path;
			header('Location: ../../login.html?redirect='.$Redirect);
			return false;
		}
		return true;
	}	
}