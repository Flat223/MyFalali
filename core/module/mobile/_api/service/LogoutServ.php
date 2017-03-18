<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class LogoutServ extends BaseAction{
	public function action(){
		unset($_SESSION['user']);		
		$result['ret'] = 1;
		$result['msg'] = "操作成功";
		return $result;
	}
}