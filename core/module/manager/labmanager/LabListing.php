<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class LabListing extends BaseAction{

	public function action(){
        $page = isset($_REQUEST['page'])?trim($_REQUEST['page']):1;
        $ch = isset($_REQUEST['ch'])?trim($_REQUEST['ch']):0;
        $data = isset($_REQUEST['data'])?trim($_REQUEST['data']):"";
        FileUtil::requireService('ShareServ');
        FileUtil::requireService('LabListServ');
        $shareServ = new ShareServ();
        $listServ = new LabListServ();
        $pagesize = 10;
        $lab = array();
        $list = array();
        if(!empty($data)){
            if((int)$ch != 2){
                $lab = $shareServ->getNCLabByName($data);
                $count = sizeof($lab);
                $lab =array();
                $pageUtil = new PageUtil($pagesize,$count,$page);
                $index = ($pageUtil->getCurrentPage()-1)*$pagesize;
                $lab = $shareServ->getPageNCLabByName($index,$pagesize,$data);
                for($i = 0;$i<count($lab);$i++){
                    $list[] = $listServ->getLabType($lab[$i]['type_id']);
                }
            }else{
                $lab = $shareServ->getCDLabByName($data);
                $count1 = sizeof($lab);
                $lab =array();
                $pageUtil = new PageUtil($pagesize,$count1,$page);
                $index1 = ($pageUtil->getCurrentPage()-1)*$pagesize;
                $lab = $shareServ->getPageCDLabByName($index1,$pagesize,$data);
                for($i = 0;$i<count($lab);$i++){
                    $list[] = $listServ->getLabType($lab[$i]['type_id']);
                }
            }
        }else{
            if((int)$ch != 2){
                $lab = $shareServ->getNotCheckLab();
                $count2 = sizeof($lab);
                $lab =array();
                $pageUtil = new PageUtil($pagesize,$count2,$page);
                $index2 = ($pageUtil->getCurrentPage()-1)*$pagesize;
                $lab = $shareServ->getPageNotCheckLab($index2,$pagesize);
                for($i = 0;$i<count($lab);$i++){
                    $list[] = $listServ->getLabType($lab[$i]['type_id']);
                }
            }else{
                $lab = $shareServ->getCheckedLab();
                $count3 = sizeof($lab);
                $lab =array();
                $pageUtil = new PageUtil($pagesize,$count3,$page);
                $index3 = ($pageUtil->getCurrentPage()-1)*$pagesize;
                $lab = $shareServ->getPageCheckedLab($index3,$pagesize);
                for($i = 0;$i<count($lab);$i++){
                    $list[] = $listServ->getLabType($lab[$i]['type_id']);
                }
            }
        }



        $params = array();
        $params['style'] = 'labmanager';
		$params['substyle'] = 'labListing';
        $params['lab'] = $lab;
        $params['data'] = $data;
        $params['list'] = $list;
        $params['pager'] = $pageUtil;
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/labmanager/labListing.html?ch='.$ch.'&data='.$data;
		return $params;
	}

}