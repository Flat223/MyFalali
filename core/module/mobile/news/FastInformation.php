<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class FastInformation extends BaseAction{

    public function action(){
        FileUtil::requireService("NewsServ");
        $News=new NewsServ();
        $params['news']=$News->GetNewsThisweek();
//        $params['height']=(count($params['news'])*73).'px';
        return $params;
    }

}