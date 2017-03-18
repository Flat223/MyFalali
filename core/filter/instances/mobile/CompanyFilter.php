<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/filter/BaseFilter.php');
class CompanyFilter extends BaseFilter{
	
	public function __construct(){
		
	}
	
	public function doFilter($filter_path){
		$user = UserAgent::getUser();
		if($user == null){
			$Redirect = 'http://'.$_SERVER['HTTP_HOST'].$filter_path;
			header('Location: ../../handle/login.html?redirect='.$Redirect);
			return false;
		}
		if($user['type'] != 2){
			FileUtil::load404Html();
			return false;
		}
		return true;
	}
	
	
	
	
}