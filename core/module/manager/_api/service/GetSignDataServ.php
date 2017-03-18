<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetSignDataServ extends BaseAction{
	
	public function action(){
		$user = UserAgent::getAdmin();
		if(empty($user)){
			FileUtil::load404Html();
			exit(0);
		}
		$date = isset($_REQUEST['date'])?trim($_REQUEST['date']):"";
        FileUtil::requireService("UserSignServ");
        $serv = new UserSignServ();
        $result = $serv->GetSignList($user['aid'],$date);
        if($result == false){
            FileUtil::loadServerErrHtml();
            exit(0);
        }else{
            return $result;
        }
	}
}