<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Controller extends BaseAction{
    public function action(){
        include($_SERVER['DOCUMENT_ROOT'].'/core/module/pc/ueditorphp/controller.php');
        exit();
    }
}
?>