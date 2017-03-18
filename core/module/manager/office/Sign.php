<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Sign extends BaseAction{

	public function action(){
        $user = UserAgent::getAdmin();
        if(empty($user)){
            FileUtil::load404Html();
            exit(0);
        }
        FileUtil::requireService('UserSignServ');
        $serv = new UserSignServ();

        $am = $serv->getAmSignInfoByAid($user['aid']);
        $pm = $serv->getPmSignInfoByAid($user['aid']);
        $params = array();
        $params['style'] = 'office';
        $params['substyle'] = 'sign';
        $params['aid'] = $user['aid'];
        $params['am'] = $am;
        $params['pm'] = $pm;
        return $params;
	}
}