<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class CheckinvoicelistServ extends BaseAction{
    public function action(){
        $vid=isset($_REQUEST['vid'])?$_REQUEST['vid']:"";
        $status=isset($_REQUEST['status'])?$_REQUEST['status']:"";
        FileUtil::requireService("InvoiceServ");
        $Invoice=new InvoiceServ();
        if($Invoice->CheckInvoice($vid,$status)){
            $ret['ret']=1;
            $ret['msg']='成功';
        }else{
            $ret['ret']=0;
            $ret['msg']='失败';
        }
        return $ret;
    }
}

?>