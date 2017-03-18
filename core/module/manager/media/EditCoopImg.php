<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class EditCoopImg extends BaseAction{

    public function action(){
        $flag = isset($_REQUEST['flag'])?$_REQUEST['flag']:0;
        FileUtil::requireService('BannerServ');
        $serv = new BannerServ();
        if($flag == 1){
            $ban = $serv->getIndexCoopImg();
        }else{
            $ban = $serv->getIndexActivityImg();
        }

        $params['img'] = $ban;
        return $params;
    }

}