<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class PersonalCenter extends BaseAction{

    public function action(){
        $user = UserAgent::getUser();

        $params = array();
        $params['user'] = $user;
        return $params;
    }

}