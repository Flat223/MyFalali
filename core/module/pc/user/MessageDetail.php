<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class MessageDetail extends BaseAction{
	
	public function action(){
		$from_id = isset($_REQUEST['from_id'])?trim($_REQUEST['from_id']):"";
		$sid = isset($_REQUEST['sid'])?trim($_REQUEST['sid']):"";
		
		$user = UserAgent::getUser();
		FileUtil::requireService("MessageServ");
		$service = new MessageServ();
		$message = "";
		$type = 0;
		if ($from_id == "") {//系统消息
			$type = 2;
			$message = $service->getMessageById(abs($sid),$type);
			$message['sender'] = "系统消息";
		} else {//用户消息
			$type = 1;
			$message = $service->getMessageById($sid,$type);	
		}
		
		$callback = $message;
		if($callback === false){
/*
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
*/
		}
		if($callback){
/*
			$ret['ret'] = 1;
			$ret['msg'] = "删除成功";		
*/ 		
			
			if ($type == 1) {//用户消息
				if ($message['is_read'] == 0){
					$service->setMessageReaded($sid);		
				}
			} else if($sid > 0){//系统未读消息
				$service->setSystemMessageReaded($user['mid'],$sid);		
			}	
		}
		
		$params = array();
		$params['style'] = 'user';
		$params['substyle'] = 'messageDetail';
		$params['message'] = $message;
		return $params;
	}
	
}