<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class invoicelist extends BaseAction{

    public function action(){
        $page=isset($_GET['page'])?$_GET['page']:1;
        $type=isset($_GET['type'])?$_GET['type']:"";
        FileUtil::requireService("InvoiceServ");
        $Invoice=new InvoiceServ();
        $num=10;
        $params['invoice']=$Invoice->getOrderInvoice($page,$num,$type);
        $params['count']=$Invoice->getOrderNum($type);
		$params['substyle'] = 'invoicelist';
        return $params;
    }

}