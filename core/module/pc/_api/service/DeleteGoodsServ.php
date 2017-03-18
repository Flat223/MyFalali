<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class DeleteGoodsServ extends BaseAction{
    public function action(){
        $user = UserAgent::getUser();
        if(empty($user)){
            $result['ret'] = 0;
            $result['msg'] = "请先登录！";
            return $result;
        }
        
        $cid = isset($_REQUEST['id'])?$_REQUEST['id']:"";
        $type = isset($_REQUEST['type'])?$_REQUEST['type']:"";
        
        if(empty($cid) || empty($type)){
	        $result['ret'] = 0;
            $result['msg'] = "参数错误1";
            return $result;
        }
        if($type != 1 && $type != 2){
	        $result['ret'] = 0;
            $result['msg'] = "参数错误2";
            return $result;
        }
        
        FileUtil::requireService("UserServ");
        $service = new UserServ();
        $callback = $service->unfavorite($user['mid'],$cid,$type);
        if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		
		if($type == 1){
			$msg = "已取消收藏该商品";
		} else if($type == 2){
			$msg = "已取消收藏该实验室";
		}
        $result['ret'] = 1;
        $result['msg'] = $msg;
        return $result;
    }
}
