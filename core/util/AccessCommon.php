<?php
class AccessCommon{
	
	//添加访问记录
	public static function addAccessLog($path){
		$from_url = "";
		if(isset($_SERVER['HTTP_REFERER'])){
			$from_url = $_SERVER['HTTP_REFERER'];
		}
		$ip = Common::getIP();
		$cookie = json_encode($_COOKIE);
		$time = time();
		$url = $_SERVER['HTTP_HOST'].$path;
		$sql = "insert into #__access_log(url,ip,cookie,from_url,time)values(?,?,?,?,?) ";
		$dbAgent = DBAgent::getInstance();
		$dbAgent->query($sql,array($url,$ip,$cookie,$from_url,$time));
	}
}