<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Coupon extends BaseAction{

    public function action(){
		
		$params['substyle'] = 'coupon';
        return $params;
    }
}