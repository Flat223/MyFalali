<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class SaveTitleServ extends BaseAction{

    public function action(){
	    $ret=array();
		$title=isset($_REQUEST['title'])?trim($_REQUEST['title']):"";
		if(empty($title)){
			$ret['ret']=0;
			$ret['msg']="抬头不能为空";
			return $ret;
		}
		$user=UserAgent::getUser();
		$mid=$user['mid'];
    	FileUtil::requireService('InvoiceServ');
    	$service=new InvoiceServ();
    	$callback=$service->saveTitle($title,$mid);
    	if(!$callback){
	    	$ret['ret']=0;
	    	$ret['msg']="服务器错误";
	    	return $ret;
    	}
    	$ret['ret']=1;
    	$ret['msg']="添加成功";
    	return $ret;
    } 

}