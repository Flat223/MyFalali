<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetScrollLabServ extends BaseAction{
    public function action(){
        $start = isset($_REQUEST['start'])?trim($_REQUEST['start']):0;
        FileUtil::requireService("ShareServ");
        $serv = new ShareServ();
        $lab = $serv->getInitNumLab($start,15);
       /* echo json_encode($lab);*/
        return $lab;
    }
}

?>