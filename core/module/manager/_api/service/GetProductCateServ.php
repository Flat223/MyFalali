<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetProductCateServ extends BaseAction{

    public function action(){
        $name = isset($_REQUEST['name'])?trim($_REQUEST['name']):"";

        FileUtil::requireService("ProductServ");
        $serv = new ProductServ();
        $data = $serv->geProductCateByName($name);
        return $data;
    }
}