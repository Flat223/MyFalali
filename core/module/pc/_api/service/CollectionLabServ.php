<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class CollectionLabServ extends BaseAction{
    public function action(){
	    $user = UserAgent::getUser();
        $ret = array();
        if(empty($user)){
            $ret['ret'] = -2;
            $ret['msg'] = "请先登录";
            return $ret;
        }
        $id=isset($_REQUEST['id'])?trim($_REQUEST['id']):0;
        FileUtil::requireService("ShareServ");
		$serv = new ShareServ();
        $data = $serv->isCollectioned($user['mid'],$id);
        if(empty($data)){
            $result = $serv->collectionLab($user['mid'],$id);
            if($result == false){
                $ret['ret'] = -1;
                $ret['msg'] = "操作失败！稍后重试！";
                return $ret;
            }else{
                $ret['ret'] = 1;
                $ret['msg'] = "收藏成功！";
                return $ret;
            }
        }else{
            $ret['ret'] = -3;
            $ret['msg'] = "已经收藏过啦！";
            return $ret;
        }
    }
}