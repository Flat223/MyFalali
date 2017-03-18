<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class LabServiceRange extends BaseAction{

	public function action(){
        $page = isset($_REQUEST['page'])?trim($_REQUEST['page']):1;
        $search = isset($_REQUEST['data'])?trim($_REQUEST['data']):"";
        FileUtil::requireService('LabServiceServ');
        $serv = new LabServiceServ();
        if(empty($search)){
            $servRange = $serv->getLabServiceRange();
            $pagesize = 10;
            $count = sizeof($servRange);
            $servRange = array();
            $pageUtil = new PageUtil($pagesize,$count,$page);
            $index = ($pageUtil->getCurrentPage()-1)*$pagesize;
            $servRange = $serv->getPageLabServiceRange($index,$pagesize);
            $blab = array();
            for ($i = 0;$i<count($servRange);$i++){
                $blab[] = $serv->getBelongLab($servRange[$i]['lab_id']);
            }
            if($blab == false){
                FileUtil::loadServerErrHtml();
                exit(0);
            }
        }else{
            $servRange = $serv->getLabServiceBySearch($search);
            $pagesize = 10;
            $count = sizeof($servRange);
            $servRange = array();
            $pageUtil = new PageUtil($pagesize,$count,$page);
            $index = ($pageUtil->getCurrentPage()-1)*$pagesize;
            $servRange = $serv->getPageLabServiceBySearch($search,$index,$pagesize);
            $blab = array();
            for ($i = 0;$i<count($servRange);$i++){
                $blab[] = $serv->getBelongLab($servRange[$i]['lab_id']);
            }
            if($blab == false){
                FileUtil::loadServerErrHtml();
                exit(0);
            }
        }

        $params = array();
        $params['style'] = 'labmanager';
        $params['substyle'] = 'labServiceRange';
        $params['servRange'] = $servRange;
        $params['blab'] = $blab;
        $params['data'] = $search;
        $params['count'] = $count;
        $params['pager'] = $pageUtil;
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/labmanager/labServiceRange.html?bj=1&data='.$search;
		return $params;
	}

}