<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/module/_baseClass/BaseAction.php';
class GetProductBrandServ extends BaseAction{
	public function action(){
        $name = isset($_REQUEST['name'])?trim($_REQUEST['name']):"";

        FileUtil::requireService('BrandServ');
        $serv = new BrandServ();
        $brand =  $serv->getBrandByLikeName($name);
        return $brand;
	}
}