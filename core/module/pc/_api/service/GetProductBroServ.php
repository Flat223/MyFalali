<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class GetProductBroServ extends BaseAction
{

    public function action()
    {
        $id=isset($_POST['id'])?$_POST['id']:"";
        $dbAgent = DBAgent::getInstance();
        $sql="select propertyid,id from labring_product_property_val where propertyid in(select id from labring_product_property where ptid in (select ptid from labring_product where pid=$id))";
        $result=$dbAgent->queryRecords($sql,array());

        exit(json_encode($result));
    }
}