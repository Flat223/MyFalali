<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Pushmessage extends BaseAction{

	public function action(){


		$params['style'] = 'msgmanager';


		return $params;
	}

}