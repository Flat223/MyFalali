<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Companynews extends BaseAction{

    public function action(){
        $page=isset($_GET['page'])?$_GET['page']:1;
        $info=isset($_GET['info'])?$_GET['info']:"";
        FileUtil::requireService("NewsServ");
        $News=new NewsServ();
        $num=10;
        $params['news']=$News->GetNewsBytime($num,$page,'id desc',null,$info);
        $params['count']=$News->Getcount($info);
        $params['style']='media';
        $params['substyle']='companynews';
        return $params;
    }

}