<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class ExpertInfo extends BaseAction{
	
	public function action(){
        FileUtil::requireService('LabDetailServ');
        $serv = new LabDetailServ();

        $id = isset($_REQUEST['id'])?trim($_REQUEST['id']):0;
        $expert = $serv->getExpertInfoById($id);
        if($expert == false){
            FileUtil::loadServerErrHtml();
            exit(0);
        }
        /*页面调用*/
        $params = array();
        $params['exinfo'] = $expert;

		return $params;
	}
	
}