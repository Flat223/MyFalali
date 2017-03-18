<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddSystemMenuServ extends BaseAction{
	
	public function action(){
		$admin = UserAgent::getAdmin();
		if(empty($admin))
		{
			$result['ret'] = 0;
			$result['msg'] = "尚未登陆";
		}
		else
		{
			$ip = CommonFunc::getClientIP();
			$time = time();
			$mid = $admin['aid'];  
			$_POST['time'] = time();
			$_POST['ip'] = $ip;
			$_POST['mid'] = $mid; 
	        
	        FileUtil::requireService("RoleServ");
	        $RoleServ=new RoleServ(); 
	        $result =$RoleServ->AddData($_POST); 
		}

        return $result;

	}
}