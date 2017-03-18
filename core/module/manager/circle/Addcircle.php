<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Addcircle extends BaseAction{

    public function action(){
        FileUtil::requireService("CircleServ");
        $Circle=new CircleServ();
        $params['label']=$Circle-> Getlabel();
        $params['substyle'] = 'circlelist';
        return $params;
    }

}