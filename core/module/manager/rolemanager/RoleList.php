<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class RoleList extends BaseAction{

	public function action(){
        $page = !empty($_REQUEST['page'])?trim($_REQUEST['page']):1;
        $pagesize = !empty($_REQUEST['pagesize'])?trim($_REQUEST['pagesize']):10; 
        FileUtil::requireService("RolesServ");
        $RolesServ=new RolesServ(); 
        $wherekeyvalue['status'] = 1;
        $result= $RolesServ->GetData($wherekeyvalue,$page,$pagesize,"rid","desc");
          
        
        $result['style']="rolemanager";
        $result['substyle']="roleList"; 
        return $result;

	}

}