<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Friendly extends BaseAction{

    public function action(){
        $page=isset($_GET['page'])?$_GET['page']:1;
        FileUtil::requireService("FriendlyServ");
        $Friendly=new FriendlyServ();
        $params['friendly']=$Friendly->getFriendlyLink('lid desc',10,$page);
        $params['count']=$Friendly->getFriendlynum();
        $params['substyle'] = 'friendly';
        return $params;
    }

}