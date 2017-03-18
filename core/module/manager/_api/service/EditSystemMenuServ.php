<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class EditSystemMenuServ extends BaseAction{
	
	public function action(){
		$admin = UserAgent::getAdmin();
		if(empty($admin))
		{
			$result['ret'] = 0;
			$result['msg'] = "尚未登陆";
		}
		else
		{ 
	        
	        FileUtil::requireService("RoleServ");
	        $RoleServ=new RoleServ();  
	        
	        $wherekeyvalue['id'] = $_POST['id'];
			$updatekeyvalue = $_POST['keyvalue'];
			$result= $RoleServ->EditData($wherekeyvalue,$updatekeyvalue);
		}

        return $result;

	}
}