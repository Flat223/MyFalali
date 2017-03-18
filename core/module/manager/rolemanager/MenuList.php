<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class MenuList extends BaseAction{

	public function action(){
        $page = !empty($_REQUEST['page'])?trim($_REQUEST['page']):1;
        $pagesize = !empty($_REQUEST['pagesize'])?trim($_REQUEST['pagesize']):10;
        $pid=isset($_REQUEST['pid'])?$_REQUEST['pid']:0;
        FileUtil::requireService("RoleServ");
        $RoleServ=new RoleServ(); 
        $wherekeyvalue = array();
        $result= $RoleServ->GetData($wherekeyvalue,$page,$pagesize,"id","desc");
         
        $result['pid']=$pid; 
         
        $result['style']="rolemanager";
        $result['substyle']="menuList"; 
        return $result;

	}

}