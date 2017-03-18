<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class UploadimgServ extends BaseAction
{
    public function action()
    {
        FileUtil::requireService("UploadServ");
        $upload = new UploadServ();
        $result=$upload->uploadBrandImg();
        return $result;
    }
}