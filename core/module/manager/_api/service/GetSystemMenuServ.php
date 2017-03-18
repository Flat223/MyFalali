<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetSystemMenuServ extends BaseAction{
	
	public function action(){
        $id=isset($_REQUEST['id'])?$_REQUEST['id']:0;
        
        FileUtil::requireService("RoleServ");
        $RoleServ=new RoleServ(); 
        $wherekeyvalue['id'] = $id;
        $result= $RoleServ->GetData($wherekeyvalue,1,10);

        return $result;

	}
}