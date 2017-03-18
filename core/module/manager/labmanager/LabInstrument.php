<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class LabInstrument extends BaseAction{

	public function action(){
        $page = isset($_REQUEST['page'])?trim($_REQUEST['page']):1;
        $search = isset($_REQUEST['data'])?trim($_REQUEST['data']):"";
        FileUtil::requireService('LabServiceServ');
        $serv = new LabServiceServ();
        if(empty($search)){
            $strument = $serv->getLabInstrument();
            $pagesize = 10;
            $count = sizeof($strument);
            $strument = array();
            $pageUtil = new PageUtil($pagesize,$count,$page);
            $index = ($pageUtil->getCurrentPage()-1)*$pagesize;
            $strument = $serv->getPageLabInstrument($index,$pagesize);
            $blab = array();
            for ($i = 0;$i<count($strument);$i++){
                $blab[] = $serv->getBelongLab($strument[$i]['lab_id']);
            }
            if($blab == false){
                FileUtil::loadServerErrHtml();
                exit(0);
            }
        }else{
            $strument = $serv->getLabInstrumentBySearch($search);
            $pagesize = 10;
            $count = sizeof($strument);
            $strument = array();
            $pageUtil = new PageUtil($pagesize,$count,$page);
            $index = ($pageUtil->getCurrentPage()-1)*$pagesize;
            $strument = $serv->getPageLabInstrumentBySearch($search,$index,$pagesize);
            $blab = array();
            for ($i = 0;$i<count($strument);$i++){
                $blab[] = $serv->getBelongLab($strument[$i]['lab_id']);
            }
            if($blab == false){
                FileUtil::loadServerErrHtml();
                exit(0);
            }
        }


        $params = array();
        $params['style'] = 'labmanager';
        $params['substyle'] = 'labInstrument';
        $params['instrument'] = $strument;
        $params['blab'] = $blab;
        $params['data'] = $search;
        $params['count'] = $count;
        $params['pager'] = $pageUtil;
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/labmanager/labInstrument.html?bj=1&data='.$search;
		return $params;
	}

}