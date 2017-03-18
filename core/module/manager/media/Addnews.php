<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Addnews extends BaseAction{

    public function action(){
		$params['substyle'] = 'companynews';
        return $params;
    }

}