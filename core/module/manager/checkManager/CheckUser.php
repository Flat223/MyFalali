<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class CheckUser extends BaseAction{

    public function action(){
        $sta=isset($_GET['sta'])?$_GET['sta']:0;
        $page=isset($_GET['page'])?$_GET['page']:1;
        $_SERVER['PHP_SELF'] = $_SERVER['PHP_SELF']+$sta;
        $num=10;
        FileUtil::requireService("UserServ");
        $User=new UserServ();
        $params['identity']=$User->getUseridentity($num,$page,$sta);
        $params['count']=$User->getUserIdentityCount($sta);
		$params['substyle'] = 'CheckUser';
        return $params;
    }
}