<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class SetSystemRoleMenuServ extends BaseAction{
	
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
	          
	        $roleid = $_POST['roleid'];
	        $menuids = $_POST['seletedids'];
	        $result= $RoleMenuServ->setJobMenu($roleid,$menuids);
		}

        return $result;

	}
}