<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetSystemRoleMenuServ extends BaseAction{
	
	public function action(){
		$admin = UserAgent::getAdmin();
		if(empty($admin))
		{
			$result['ret'] = 0;
			$result['msg'] = "尚未登陆";
		}
		else
		{ 
	        
	        FileUtil::requireService("RoleMenuServ");
	        $RoleMenuServ =new RoleMenuServ();  
	          
	        $roleid = $_POST['id'];
	        $result= $RoleMenuServ->getJobMenu($roleid);
		}

        return $result;

	}
}