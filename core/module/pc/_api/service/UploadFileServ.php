<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class UploadFileServ extends BaseAction{
    public function action(){
        FileUtil::requireService("UploadServ");
        $upload = new UploadServ();
        $result = $upload->uploadBaseFile();
        if($result['ret'] == 1){
	    	$excelUtil = new ExcelToArray();
	        $result = $excelUtil->read($result['file_url']); 
        }
        return $result;
    }
}