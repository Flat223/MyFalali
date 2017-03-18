<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Addlabel extends BaseAction{

    public function action(){
        $parentid = isset($_GET['parentid'])?$_GET['parentid']:'';
        $level = isset($_GET['level'])?$_GET['level']:"";
        
        if(empty($level) || ($level > 2 && empty($parentid))){
	    	FileUtil::load404Html();
			exit(0);
        }
        
        FileUtil::requireService('PropertyServ');
        $service = new PropertyServ();
        
        if($level == 2){
	    	$first_type = $service->getPropertyByLevl(1);
	    	if($first_type === false){
		    	FileUtil::load404Html();
				exit(0);
	    	}
	    	$params['first_type'] = $first_type;
        }
        if($level == 1){
	        $parentid = 0;
        }
        
        $params['level'] = $level;
        $params['parentid'] = $parentid;
        return $params;
    }
}