<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class SaveInvoiceServ extends BaseAction{

    public function action(){
	    $time=time();
	   	$rand=rand(100,999);
	   	$invoice_code=$time.$rand;
	    $ret=array();
	    $invoice="";
    	$invoice['type']=isset($_REQUEST['ttype'])?trim($_REQUEST['ttype']):0;//发票类型
    	$invoice['title']=isset($_REQUEST['title1'])?trim($_REQUEST['title1']):"";//发票抬头
    	$invoice['content']=isset($_REQUEST['type'])?trim($_REQUEST['type']):0;//发票内容
    	$invoice['mobile']=isset($_REQUEST['mobile'])?trim($_REQUEST['mobile']):"";//手机号
    	$invoice['email']=isset($_REQUEST['email'])?trim($_REQUEST['email']):"";//邮箱
    	$invoice['name']=isset($_REQUEST['name1'])?trim($_REQUEST['name1']):"";//姓名
    	$invoice['company_name']=isset($_REQUEST['cname'])?trim($_REQUEST['cname']):"";//公司名
    	$invoice['code']=isset($_REQUEST['code'])?trim($_REQUEST['code']):"";//识别码
    	$invoice['re_location']=isset($_REQUEST['relocation'])?trim($_REQUEST['relocation']):"";//注册地址
    	$invoice['re_mobile']=isset($_REQUEST['remobile'])?trim($_REQUEST['remobile']):"";//注册电话
    	$invoice['bank_name']=isset($_REQUEST['bankname'])?trim($_REQUEST['bankname']):"";//银行名称
    	$invoice['bank_account']=isset($_REQUEST['bankaccount'])?trim($_REQUEST['bankaccount']):"";//银行账户
    	$invoice['invoice_code']=$invoice_code;
    	FileUtil::requireService('InvoiceServ');
    	$service=new InvoiceServ();
    	$callback=$service->insertOrderInvoice($invoice);
    	if(!$callback){
	    	$ret['ret']=0;
	    	$ret['msg']="服务器错误";
	    	return $ret;
    	}
    	$ret['ret']=1;
    	$ret['msg']="添加成功";
    	$ret['code']=$invoice_code;
    	return $ret;
    } 

}