<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class OASignServ extends BaseAction{
	
	public function action(){
		$user = UserAgent::getAdmin();
		if(empty($user)){
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}
		$type = isset($_REQUEST['type'])?trim($_REQUEST['type']):0;
        FileUtil::requireService("UserSignServ");
        $serv = new UserSignServ();
        $ip = Common::getIP();
        if($ip == false){
            $ip = 0;
        }
		$result = $serv->insertSign($user['aid'],$ip,$type);
        if($result == false){
            $ret['ret'] = -1;
            $ret['msg'] = "打卡失败！";
            return $ret;
        }else{
            $ret['ret'] = 1;
            $ret['msg'] = "打卡时间：".date('Y-m-d:H:i:m',time());
            return $ret;
        }
	}
}