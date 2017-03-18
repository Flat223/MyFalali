<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddMenu extends BaseAction{

	public function action(){ 
        
        
        $params['style']="rolemanager";
        $params['substyle']="menuList";

        return $params;

	}

}