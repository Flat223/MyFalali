<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Updatenews extends BaseAction{

    public function action(){
        $id=isset($_GET['id'])?$_GET['id']:"";
        FileUtil::requireService("NewsServ");
        $News=new NewsServ();
        $params['news']=$News->GetNewsById($id);

		$params['substyle'] = 'companynews';
        return $params;
    }

}