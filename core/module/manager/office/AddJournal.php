<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class AddJournal extends BaseAction{

	public function action(){
        $user = UserAgent::getAdmin();
        if(empty($user)){
            FileUtil::load404Html();
            exit(0);
        }
        $id = isset($_REQUEST['id'])?$_REQUEST['id']:0;

        FileUtil::requireService('UserSignServ');
        $serv = new UserSignServ();
        $journal = $serv->getJournalById($id);

        $params = array();
        $params['style'] = 'office';
        $params['substyle'] = 'addJournal';
        $params['journal'] = $journal;
        return $params;
	}
}