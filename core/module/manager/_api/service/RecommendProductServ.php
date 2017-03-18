<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class RecommendProductServ extends BaseAction{
    public function action(){
        $id = isset($_REQUEST['id'])?$_REQUEST['id']:0;
        $flag = isset($_REQUEST['flag'])?$_REQUEST['flag']:0;
        $lv = isset($_REQUEST['lv'])?$_REQUEST['lv']:0;
        FileUtil::requireService('ProductServ');
        $serv = new ProductServ();

        if($flag == 2){
            $product = $serv->getProductByLevel1($lv);
            if($product == false){
                FileUtil::loadServerErrHtml();
                exit(0);
            }
            return $product;
        }else{
            $product = $serv->getProductById($id);
            if($flag == 1){
                $result = $serv->recommendProductById($id,$flag,$product);
                if($result == false){
                    $ret['ret'] = -1;
                    $ret['msg'] = "取消失败！";
                    return $ret;
                }else{
                    $ret['ret'] = 1;
                    $ret['msg'] = "取消成功！";
                    return $ret;
                }
            }else{
                $result = $serv->recommendProductById($id,$flag,$product);
                if($result == false){
                    $ret['ret'] = -1;
                    $ret['msg'] = "推荐失败！";
                    return $ret;
                }else{
                    $ret['ret'] = 1;
                    $ret['msg'] = "推荐成功！";
                    return $ret;
                }
            }
        }
    }
}
?>