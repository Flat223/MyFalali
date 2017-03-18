<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetSystemMenulistServ extends BaseAction{
	
	public function action(){
        $page = !empty($_REQUEST['page'])?trim($_REQUEST['page']):1;
        $pagesize = !empty($_REQUEST['pagesize'])?trim($_REQUEST['pagesize']):10;
        $pid=isset($_REQUEST['pid'])?$_REQUEST['pid']:0;
        
        FileUtil::requireService("RoleServ");
        $RoleServ=new RoleServ(); 
        $wherekeyvalue['pid'] = $pid;
        $result= $RoleServ->GetData($wherekeyvalue,1,200);

        return $result;

	}
}