<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';



class Sign extends BaseAction{
	
	public function action(){
        $user = UserAgent::getUser();
        FileUtil::requireService("UserSignServ");
        $serv = new UserSignServ();
        $count = 0;
        $flag = 0;
        $sign = array();
        if(!empty($user)){
            $sign = $serv->getSignRecordById($user['mid']);
            if(!empty($sign)){
                $count = $sign['sign_count'];
                if(date('Y-m-d') == date('Y-m-d',$sign['sign_time'])){
                    $flag = 1;
                }
            }
        }

        $params = array();
        $params['count'] = $count;
        $params['flag'] = $flag;
		return $params;
	}
	
}