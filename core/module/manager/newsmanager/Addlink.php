<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Addlink extends BaseAction{

    public function action(){
		$params['substyle'] = 'friendly';
        return $params;
    }
}