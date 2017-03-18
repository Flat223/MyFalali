<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Addlabel extends BaseAction{

    public function action(){
	    
	    $params['style'] = 'circle';
        return $params;
    }
}