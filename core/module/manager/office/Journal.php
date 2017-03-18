<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Journal extends BaseAction{

	public function action(){
        $user = UserAgent::getAdmin();
        if(empty($user)){
            FileUtil::load404Html();
            exit(0);
        }
        $page = isset($_REQUEST['page'])?$_REQUEST['page']:1;

        FileUtil::requireService('UserSignServ');
        $serv = new UserSignServ();
        $count = $serv->getCountJournal($user['aid']);
        $size = 10;
        $pageUtil = new PageUtil($size,$count,$page);
        $index = ($pageUtil->getCurrentPage()-1)*$size;
        $journal = $serv->getJournalByAid($user['aid'],$index,$size);
        $params = array();
        $params['style'] = 'office';
        $params['substyle'] = 'journal';
        $params['journal'] = $journal;
        $params['count'] = $count;
        $params['pager'] = $pageUtil;
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/office/journal.html?';
        return $params;
	}
}