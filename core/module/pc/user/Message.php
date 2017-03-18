<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Message extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		FileUtil::requireService("MessageServ");
		$service = new MessageServ();
		$user_msg = $service->getUserMessageByMid($user['mid']);
		$system_msg = $service->getSystemMessageByMid($user['mid']);
		
/*
		for ($j=0;$j<count($system_msgs);$j++){
			$system_msg = $system_msgs[$j];
			$system_msg['sender'] = "实验圈";
			$system_msg['from_id'] = 0;
			$messages[count($user_msgs)+$j] = $system_msg;
		}
		
		foreach($messages as $message){
			$times[] = $message['time'];
		}
		array_multisort($times,SORT_DESC,$messages);
*/
		
		$params = array();
		$params['style'] = 'user';
		$params['substyle'] = 'message';
		$params['user_msg'] = $user_msg;
		$params['system_msg'] = $system_msg;
		return $params;
	}
	
}