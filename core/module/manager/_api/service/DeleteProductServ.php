<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class DeleteProductServ extends BaseAction{
	
	public function action(){

		$id = isset($_REQUEST['id'])?trim($_REQUEST['id']):'';

		FileUtil::requireService("ProductServ");
		$service = new ProductServ();
		$result = $service->deleteProductById($id);
        if($result == false){
            $ret['ret'] = -1;
            $ret['msg'] = "删除失败！";
            return $ret;
        }else{
            $ret['ret'] = 1;
            $ret['msg'] = "删除成功！";
            return $ret;
        }
	}
}