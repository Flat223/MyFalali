<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class DeleteBrandServ extends BaseAction{
	
	public function action(){
		$ret = array();
        $id = isset($_REQUEST['id'])?$_REQUEST['id']:0;
        FileUtil::requireService("BrandServ");
        $serv = new BrandServ();
        $result = $serv->deleteBrand($id);
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