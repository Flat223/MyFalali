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
                $st = date('Y-m-d',$sign['sign_time']);
                if(date('Y-m-d') == $st){
                    $flag = 1;
                }
                if(strtotime(date('Y-m-d',time())) - strtotime(date($st,time())) > 86400){
                    $count = 0;
                }
            }
        }

        $params = array();
        $params['count'] = $count;
        $params['flag'] = $flag;
		return $params;
	}

}