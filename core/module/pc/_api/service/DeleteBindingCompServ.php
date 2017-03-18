<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class DeleteBindingCompServ extends BaseAction{
    public function action(){
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
        FileUtil::requireService('CompanyServ');
        $serv = new CompanyServ();
        $ret = array();
        $result = $serv->deleteData($id);
        if($result == false){
            $ret['ret'] = -1;
            $ret['msg'] = "删除失败！";
            return $ret;
        }else{
            $ret['ret'] = 1;
            $ret['msg'] = "删除成功！";
            return $ret;
        }
    }
}
?>