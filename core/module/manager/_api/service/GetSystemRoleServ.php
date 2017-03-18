<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetSystemRoleServ extends BaseAction{
	
	public function action(){
        $id=isset($_REQUEST['id'])?$_REQUEST['id']:0;
        
        FileUtil::requireService("RolesServ");
        $RolesServ=new RolesServ(); 
        $wherekeyvalue['rid'] = $id;
        $result= $RolesServ->GetData($wherekeyvalue,1,10);

        return $result;

	}
}