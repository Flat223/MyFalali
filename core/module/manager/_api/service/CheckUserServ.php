<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class CheckUserServ extends BaseAction{

    public function action()
    {

        $id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) :"";
        $mid = isset($_REQUEST['mid']) ? trim($_REQUEST['mid']) :"";
        $name=isset($_REQUEST['name'])? trim($_REQUEST['name']):"";
        $status=isset($_REQUEST['status']) ?trim($_REQUEST['status']):"";
        $dbAgent = DBAgent::getInstance();
        $ret1=$dbAgent->updateRecords('member',array('is_certificate','name'),array($status,$name),array('mid'),array($mid));
        $ret2=$dbAgent->updateRecords('identity',array('status'),array($status),array('id'),array($id));

        if($ret1 && $ret2){
            $ret['ret'] = '1';
            $ret['msg'] = "成功！";

        } else {
            $ret['ret'] = '0';
            $ret['msg'] = "失败！";
        }
        exit(json_encode($ret));
    }
}