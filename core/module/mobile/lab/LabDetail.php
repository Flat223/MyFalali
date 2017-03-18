<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class LabDetail extends BaseAction{

    public function action(){
        $labId = isset($_REQUEST['id'])?trim($_REQUEST['id']):0;
        FileUtil::requireService('LabDetailServ');
        $labDetailServ = new LabDetailServ();
        /*获取实验室详情*/
        $lab = $labDetailServ->getLabDetail($labId);
        if($lab == null || $lab == false){
            FileUtil::loadServerErrHtml();
            exit(0);
        }


        $params = array();
        $params['style'] = 'lab';
        $params['lab'] = $lab;
        return $params;
    }

}