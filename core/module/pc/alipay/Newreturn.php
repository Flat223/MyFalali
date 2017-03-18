<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Newreturn extends BaseAction{
	
	public function action(){
		include('return.php');
	}
}
