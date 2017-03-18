<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Addadvert extends BaseAction{

    public function action(){
		$params['substyle'] = 'advert';
		return $params;	
    }

}