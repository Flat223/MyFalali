<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class DeleteprotypeServ extends BaseAction{

    public function action(){
		$admin = UserAgent::getAdmin();
	    if(empty($admin)){
		    $ret['ret'] = 0;
            $ret['msg'] = "请先登录";
            return $ret;
	    }
        $ptid = isset($_REQUEST['ptid'])?$_REQUEST['ptid']:"";
        if(empty($ptid)){
	        $ret['ret'] = 0;
            $ret['msg'] = "缺少参数";
            return $ret;
        }
        
        FileUtil::requireService('PropertyServ');
        $service = new PropertyServ();
        
        $proType = $service->getProTypeByPtid($ptid);
		if($proType === false){
			$ret['ret'] = 0;
            $ret['msg'] = "抱歉，服务器错误0，请稍后再试";
            return $ret;
		}
		if(empty($proType)){
			$ret['ret'] = 0;
            $ret['msg'] = "未找到该产品分类";
            return $ret;
		}
        
		$callback = $service->deleteprotypebyid($ptid);
		if($callback === false){
			$ret['ret'] = 0;
            $ret['msg'] = "抱歉，服务器错误，请稍后再试";
            return $ret;
		}
		$ret['ret'] = 1;
        $ret['msg'] = "删除成功";
        return $ret;
    }
}