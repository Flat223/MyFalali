<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Search extends BaseAction{

    public function action(){
        $info = isset($_REQUEST['info'])?$_REQUEST['info']:"";
        FileUtil::requireService("ProductServ");
        $serv = new ProductServ();
        $data = $serv->getProductBySearch($info);
        $params['data'] = $data;
        return $params;
    }

}