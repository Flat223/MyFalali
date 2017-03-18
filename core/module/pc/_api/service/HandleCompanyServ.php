<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class HandleCompanyServ extends BaseAction{
    public function action(){
        $user = UserAgent::getUser();
        if(empty($user)){
	        $ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
        }
        
        $mid = isset($_REQUEST['mid'])?$_REQUEST['mid']:'';
        $type = isset($_REQUEST['type'])?$_REQUEST['type']:'';//1:同意 2:拒绝 3:删除
        $reason = isset($_REQUEST['reason'])?$_REQUEST['reason']:"";
        
        if(empty($mid) || empty($type) || ($type != 1 && $type != 2 && $type != 3)){
			$ret['ret'] = 0;
			$ret['msg'] = "参数错误";
			return $ret;
		}
		
		if($type != 1 && empty($reason)){
			$ret['ret'] = 0;
			$ret['msg'] = $type == 2 ? "请填写拒绝理由" : "请填写删除理由";
			return $ret;
		}
        
        FileUtil::requireService('CompanyServ');
        $service = new CompanyServ();
        
        FileUtil::requireService("MessageServ");
		$msgservcie=new MessageServ();
        
        if($type == 3){
	    	$callback = $service->deleteCompanyMember($user['mid'],$mid); 
	    	if($callback === false){
		    	$ret['ret'] = 0;
	            $ret['msg'] = "抱歉，服务器错误1，请稍后再试";
	            return $ret;
	    	}
        } else {
	    	$callback = $service->handleCompanyBind($user['mid'],$mid,$type);
	    	if($callback === false){
		    	$ret['ret'] = 0;
	            $ret['msg'] = "抱歉，服务器错误2，请稍后再试";
	            return $ret;
	    	}
        }
        
        $title = "";
		$message = "";
		if($type == 3){
	        $title = "绑定企业删除通知";
	        $message = "抱歉,你被从本公司删除;理由:".$reason;
        } else {
	        $title = "绑定企业审核通知";
	        $message = $type == 1 ? "您的企业绑定申请已通过审核" : "抱歉,你的企业绑定申请被拒绝;理由:".$reason;
        }
        $callback=$msgservcie->sendMessage($mid,$title,$message,$user['mid']);
		if($callback===false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误3，请稍后再试";
			return $ret;
		}
		
		$ret['ret'] = 1;
		if($type == 1){
			$ret['msg'] = "已通过该绑定申请"; 	
		} else if($type == 2){
			$ret['msg'] = "已拒绝该绑定申请"; 	
		} else {
			$ret['msg'] = "已删除该用户"; 	
		}
		return $ret;
    }
}

?>