<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class DeleteMessageServ extends BaseAction{
	
	public function action(){ 
		$user_msg_id = isset($_REQUEST['user_msg_id'])?trim($_REQUEST['user_msg_id']):"";
		$system_msg_id = isset($_REQUEST['system_msg_id'])?trim($_REQUEST['system_msg_id']):"";
		if (empty($user_msg_id) && empty($system_msg_id)){
			$ret['ret'] = 0;
			$ret['msg'] = "请选择要删除的消息";
			return $ret;
		}
		
		$user = UserAgent::getUser();
		FileUtil::requireService("MessageServ");
		$service = new MessageServ();
		
		$callback = "";
		if (!empty($user_msg_id)) {
			$callback = $service->deleteUserMessage($user_msg_id,$user['mid']);
		}
		if (!empty($system_msg_id)){
			foreach (explode(",",$system_msg_id) as $sid){
				if ($sid > 0) {//未读
					$callback = $service->insertSysMessageStatus($sid,$user['mid']);
				} else {//已读
					$callback = $service->updateSysMessageStatus(abs($sid),$user['mid']);
				}
			} 
		}
		
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		if($callback){
			$ret['ret'] = 1;
			$ret['msg'] = "删除成功"; 
		}
		return $ret;
	}
}