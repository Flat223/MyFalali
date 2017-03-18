<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Includeu extends BaseAction{
    public function action(){
        include($_SERVER['DOCUMENT_ROOT'].'/core/module/manager/ueditorphp/controller.php');
        exit();
    }
}
?>