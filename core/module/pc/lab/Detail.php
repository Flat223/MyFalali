<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Detail extends BaseAction{
	
	public function action(){
        FileUtil::requireService('LabDetailServ');
        $labDetailServ = new LabDetailServ();
        /*获取实验室详情*/
        $labId = isset($_REQUEST['labId'])?trim($_REQUEST['labId']):0;
        $lab = $labDetailServ->getLabDetail($labId);
        if($lab == null || $lab == false){
            FileUtil::loadServerErrHtml();
            exit(0);
        }
        $labDetailServ->updateViewNum($labId,$lab['view_num']);

        /*获取实验室服务范围*/
        $serviceRange = $labDetailServ->getServiceRangeByLabId($lab['lab_id']);

        /*获取实验室仪器资源*/
        $instrument = $labDetailServ->getInstrumentByLabId($lab['lab_id']);

        /*获取该实验室科研数据*/
        $research = $labDetailServ->getLabResearch($labId);

        /*获取该实验室所属机构*/
        $institude = $labDetailServ->getBelongInstitude($lab['institude_id']);

        /*获取该所属机构其他实验室*/
        $institudeOtherLab = $labDetailServ ->getInstitudeOtherLab($institude['id'],$labId);

        /*获取实验室的科研数据分类*/
        $researchType = $labDetailServ->getResearchType();

        /*获取实验室专家*/
        $experts = $labDetailServ->getExpert($labId);

        /*页面调用*/
        $params = array();
        $params['style'] = 'share';
        $params['lab'] = $lab;
        $params['research'] = $research;
        $params['restype'] = $researchType;
        $params['institude'] = $institude;
        $params['otherLab'] = $institudeOtherLab;
        $params['expert'] = $experts;
        $params['serviceRange'] = $serviceRange;
        $params['instrument'] = $instrument;
        /*echo (json_encode($params['expert']));
        exit(0);*/
		return $params;
	}
	
}