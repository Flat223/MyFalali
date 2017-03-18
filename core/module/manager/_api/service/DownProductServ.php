<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class DownProductServ extends BaseAction{

    public function action(){

        $id = isset($_POST['id']) ? $_POST['id'] : "";
        $reason = isset($_POST['text']) ? $_POST['text'] : "";

        FileUtil::requireService('ProductServ');
        $serv = new ProductServ();
        $ret = array();
        $product = $serv->getProductById($id);
        $result = $serv->downProduct($id);
        if($result == false){
            $ret['ret'] = -1;
            $ret['msg'] = "下架失败！";
            return $ret;
        }else{
            $ret['ret'] = 1;
            $ret['msg'] = "下架成功！";
            $shop = $serv->getShopByPid($id);
            $data = $serv->sendUserDownMessage($shop['mid'],$reason,$product);
            return $ret;
        }
    }
}