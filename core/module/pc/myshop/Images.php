<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Images extends BaseAction{

    public function action(){
        $images=isset($_GET['images'])?$_GET['images']:"";
        $params['images']=explode(',',$images);
        return $params;
    }

}