<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddSystemRoleServ extends BaseAction{
	
	public function action(){
		$admin = UserAgent::getAdmin();
		if(empty($admin))
		{
			$result['ret'] = 0;
			$result['msg'] = "尚未登陆";
		}
		else
		{
	        
	        FileUtil::requireService("RolesServ");
	        $RolesServ=new RolesServ(); 
	        $result =$RolesServ->AddData($_POST); 
		}

        return $result;

	}
}