<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class EditRoleMenu extends BaseAction{

	public function action(){ 
        $id=isset($_REQUEST['id'])?$_REQUEST['id']:0;  
        $params['id']=$id; 
        $params['style']="rolemanager";
        $params['substyle']="roleList";

        return $params;

	}

}