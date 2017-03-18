<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class CircleDetail extends BaseAction{

    public function action(){
        $id = isset($_REQUEST['id'])?$_REQUEST['id']:1;
        $page = isset($_REQUEST['page'])?trim($_REQUEST['page']):1;
        FileUtil::requireService("CircleServ");
        $serv = new CircleServ();
        $member = $serv->getCircleMembers($id);
        $pagesize = 10;
        $count = sizeof($member);
        $member = array();
        $pageUtil = new PageUtil($pagesize,$count,$page);
        $index = ($pageUtil->getCurrentPage()-1)*$pagesize;
        $member = $serv->getPageCircleMembers($id,$index,$pagesize);

		$params['substyle'] = 'circlelist';
        $params['members'] = $member;
        $params['pager'] = $pageUtil;
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/circle/circleDetail.html?pj=1';
        return $params;
    }

}