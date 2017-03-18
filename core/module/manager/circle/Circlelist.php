<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Circlelist extends BaseAction{

    public function action(){
        $page=isset($_GET['page'])?$_GET['page']:1;
        FileUtil::requireService("CircleServ");
        $Circle=new CircleServ();
        
        $params['style'] = 'circle';
		$params['substyle'] = 'circlelist';
        $params['circle']=$Circle->GetAllCircle($page,10);
        $params['count']=$Circle->GetNum();
        $params['pages']=ceil($params['count']/10);
        return $params;
    }

}