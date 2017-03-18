<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class EditGroup extends BaseAction{
	
	public function action(){
		$id=isset($_REQUEST['id'])?trim($_REQUEST['id']):0;
		if($id<=0){
			FileUtil::load404Html();
			exit(0);
		}
		FileUtil::requireService("GroupServ");
		$service=new GroupServ();
		$pro=$service->getGroupDetail($id);
		if($pro===false){
			FileUtil::load404Html();
			exit(0);	
		}	
/*
		echo(json_encode($pro));
		exit(0);	
*/
		$params['style'] = 'myshop';
		$params['substyle'] = 'editGroup';
		$params['group']=$pro;
		$params['id']=$id;
		return $params;
	}
	
}