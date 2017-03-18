<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Addbrand extends BaseAction{

    public function action(){
        $id = isset($_REQUEST['id'])?$_REQUEST['id']:"";
        FileUtil::requireService('BrandServ');
        $serv = new BrandServ();
        $brand = array();
        if(!empty($id)){
            $brand = $serv->getBrandById($id);
            if($brand == false){
                FileUtil::load404Html();
            }
        }

        $params['substyle'] = 'brandList';
        $params['brand'] = $brand;
        return $params;
    }
}