<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class FriendsManage extends BaseAction{

    public function action(){
        $user = UserAgent::getUser();
        if(empty($user)){
            FileUtil::load404Html();
            exit(0);
        }
        FileUtil::requireService("FriendsServ");
        $service=new FriendsServ();

        $friends = $service->getUserfriends($user['mid'],1);
        if($friends === false){
            FileUtil::load404Html();
            exit(0);
        }

        $params = array();
        $params['friends'] = $friends;
        return $params;
    }

}