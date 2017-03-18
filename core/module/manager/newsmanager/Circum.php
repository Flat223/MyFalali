<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Circum extends BaseAction{

	public function action(){

		$params['style'] = 'newsmanager';



		return $params;
	}

}