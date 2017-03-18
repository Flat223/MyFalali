<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddGroup extends BaseAction{
	
	public function action(){
		
		$params['style'] = 'myshop';
		$params['substyle'] = 'addGroup';
		return $params;
	}
}