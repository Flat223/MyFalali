<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';

class LoadPro extends BaseAction{
	
	public function action(){
		$id = isset($_GET['id'])?trim($_GET['id']):1;
		if(!Common::isInteger($id) || $id < 0){
			$id = 1;
		}
		
		$params['id'] = $id;
		return $params;
	}
}

?>