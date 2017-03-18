<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class BindingCompServ extends BaseAction{
    public function action(){

        $id = isset($_REQUEST['id'])?$_REQUEST['id']:0;
        $mid = isset($_REQUEST['mid'])?$_REQUEST['mid']:0;
        $bj = isset($_REQUEST['bj'])?$_REQUEST['bj']:0;
        FileUtil::requireService('CompanyServ');
        $serv = new CompanyServ();
        $ret = array();
        if($bj == 0){
            $result = $serv->checkData($id,$bj);
            $res = $serv->updateMemberBindState($mid,$bj);
            if($result == false || $res == false){
                $ret['ret'] = -1;
                $ret['msg'] = "审核失败！";
                return $ret;
            }else{
                $ret['ret'] = 1;
                $ret['msg'] = "审核成功！";
                return $ret;
            }
        }else{
            $result = $serv->checkData($id,$bj);
            $res = $serv->updateMemberBindState($mid,$bj);
            if($result == false || $res == false){
                $ret['ret'] = -1;
                $ret['msg'] = "审核失败！";
                return $ret;
            }else{
                $ret['ret'] = 1;
                $ret['msg'] = "审核成功！";
                return $ret;
            }
        }
    }
}

?>