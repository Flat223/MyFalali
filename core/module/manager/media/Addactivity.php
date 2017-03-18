<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Addactivity extends BaseAction{

    public function action(){
		$params['substyle'] = 'activity';
		return $params;
    }

}