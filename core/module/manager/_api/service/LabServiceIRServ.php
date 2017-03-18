<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class LabServiceIRServ extends BaseAction{
	
	public function action()
    {
        $sid = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : 0;

        FileUtil::requireService("LabServiceServ");
        $service = new LabServiceServ();
        $ret = array();
        $result = $service->deleteServiceById($sid);
        if ($result != false) {
            $ret['msg'] = "删除成功！";
            $ret['ret'] = 1;
            return $ret;
        } else {
            $ret['msg'] = "删除失败！";
            $ret['ret'] = -1;
            return $ret;
        }
    }
}