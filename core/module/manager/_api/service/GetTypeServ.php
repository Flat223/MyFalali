<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetTypeServ extends BaseAction{
	
	public function action(){
        $tid = isset($_REQUEST['tid'])?trim($_REQUEST['tid']):"";
        if(!empty($tid)){
            FileUtil::requireService('LabListServ');
            $serv = new LabListServ();
            $ptype = array();
            $ptype = $serv->getChildType($tid);
            if($ptype == false){
                FileUtil::loadServerErrHtml();
                exit(0);
            }
            return $ptype;
        }

	}
}