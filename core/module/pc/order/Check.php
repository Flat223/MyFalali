<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Check extends BaseAction{
	
	public function action(){
		$params = array();
		
		
		$params['style'] = 'order';
		$params['substyle'] = 'check';
		return $params;
	}
}