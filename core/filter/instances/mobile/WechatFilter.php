<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/filter/BaseFilter.php');
class WechatFilter extends BaseFilter{
	
	public function __construct(){
		
	}
	
	public function doFilter($filter_path){
		$param2 = isset($_REQUEST['__param2'])?trim($_REQUEST['__param2']):"";
		if($param2 != "notify" && $param2 != "qrcode"){
			include($_SERVER['DOCUMENT_ROOT'].'/html/'.Theme.'/404.html');
			return false;
		}
		return true;
	}
	
	
	
	
}