<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Newnotify extends BaseAction{
	
	public function action(){
		include('notify.php');
	}
}
