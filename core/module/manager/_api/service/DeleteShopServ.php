<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class DeleteShopServ extends BaseAction{
	
	public function action()
    {
        $id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : 0;

        FileUtil::requireService("ShopServ");
        $serv = new ShopServ();
        $ret = array();
        $result = $serv->deleteShopById($id);
        if ($result != false) {
            $ret['msg'] = "删除成功！";
            $ret['ret'] = 1;
            return $ret;
        } else {
            $ret['msg'] = "删除失败！";
            $ret['ret'] = -1;
            return $ret;
        }
    }
}