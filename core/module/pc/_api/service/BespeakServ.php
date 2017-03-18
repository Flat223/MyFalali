<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class BespeakServ extends BaseAction{
	
	public function action(){
        FileUtil::requireService('ShareServ');
        $serv = new ShareServ();
        $user = UserAgent::getUser();

        if ($user == null) {
            $ret['ret'] = -1;
            $ret['msg'] = "请先登录！";
            return $ret;
        }
        $result = $serv->bespeak($user['mid']);
        if($result == false){
            $ret['ret'] = -1;
            $ret['msg'] = "预约失败，稍后重试！";
            return $ret;
        }else{
            $ret['ret'] = 1;
            $ret['msg'] = "预约成功！我们会在5个工作日内与您联系！";
            return $ret;
        }
    }
}

?>