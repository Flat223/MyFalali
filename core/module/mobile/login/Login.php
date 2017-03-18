<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Login extends BaseAction{

    public function action(){
        $redirect=isset($_GET['redirect'])?trim($_GET['redirect']):"";

        $params['redirect'] = $redirect;
        return $params;
    }

}