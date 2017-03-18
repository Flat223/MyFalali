<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class LabListingServ extends BaseAction{
	
	public function action(){
        $labId = isset($_REQUEST['id'])?trim($_REQUEST['id']):0;
        $check = isset($_REQUEST['check'])?trim($_REQUEST['check']):0;

		FileUtil::requireService("LabListServ");
		$service = new LabListServ();
        $ret = array();
        if($check == 1){
            $result = $service->checkLabById($labId);
            if($result != false){
                $ret['msg'] = "审核成功！";
                $ret['ret'] = 1 ;
                return $ret;
            }else{
                $ret['msg'] = "审核失败！";
                $ret['ret'] = -1 ;
                return $ret;
            }
        }else{
            $result = $service->deleteLabById($labId);
            if($result != false){
                $ret['msg'] = "删除成功！";
                $ret['ret'] = 1 ;
                return $ret;
            }else{
                $ret['msg'] = "删除失败！";
                $ret['ret'] = -1 ;
                return $ret;
            }
        }
	}
}