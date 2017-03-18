<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Addfile extends BaseAction{

	public function action(){

		$params['style'] = 'newsmanager';
		return $params;
	}

}