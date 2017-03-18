<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Levelone extends BaseAction{

    public function action(){

        FileUtil::requireService('PropertyServ');
        $Property=new PropertyServ();
        $params['property']=$Property->getPropertyByLevl(1);

        return $params;
    }
}