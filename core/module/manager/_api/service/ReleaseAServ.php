<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class ReleaseAServ extends BaseAction{
	
	public function action(){
        $labId = isset($_REQUEST['id'])?trim($_REQUEST['id']):0;
        $check = isset($_REQUEST['check'])?trim($_REQUEST['check']):0;

		FileUtil::requireService("LabListServ");
		$service = new LabListServ();
        FileUtil::requireService('');
        $serv = new SupplyBuyReleaseServ();
        $info = $serv->getInfoById($labId);
        $ret = array();
        if($check == 1){
            $result = $service->checkReleaseById($labId);
            if($result != false){
                $ret['msg'] = "审核成功！";
                $ret['ret'] = 1 ;
                $serv->sendUserMessage($info['mid']);
                return $ret;
            }else{
                $ret['msg'] = "审核失败！";
                $ret['ret'] = -1 ;
                return $ret;
            }
        }else{
            $result = $service->deleteReleaseById($labId);
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