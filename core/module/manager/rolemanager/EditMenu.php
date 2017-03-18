<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class EditMenu extends BaseAction{

	public function action(){ 
        $pid=isset($_REQUEST['id'])?$_REQUEST['id']:0;  
        $result['id']=$pid; 
        $result['style']="rolemanager";
        $result['substyle']="menuList";

        return $result;

	}

}