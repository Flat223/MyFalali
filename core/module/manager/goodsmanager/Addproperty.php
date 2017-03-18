<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Addproperty extends BaseAction{

    public function action(){

        $params['ptid']=isset($_GET['id'])?$_GET['id']:1;
        FileUtil::requireService('PropertyServ');
        $Property=new PropertyServ();
        $params['property']=$Property->Getprobyptid($params['ptid']);
        return $params;
    }
}