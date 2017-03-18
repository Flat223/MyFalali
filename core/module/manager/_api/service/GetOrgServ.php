<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetOrgServ extends BaseAction{

    public function action(){
        $name = isset($_REQUEST['name'])?trim($_REQUEST['name']):0;

        FileUtil::requireService("CompanyServ");
        $serv = new CompanyServ();
        $data = $serv->getCompanyByName($name);
        return $data;
    }
}