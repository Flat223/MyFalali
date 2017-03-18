<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Addcooperate extends BaseAction{

    public function action(){
		$params['substyle'] = 'cooperate';
        return $params;
    }

}