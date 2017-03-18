<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class MoreLab extends BaseAction{
	
	public function action(){
        $cate = isset($_REQUEST['cate'])?trim($_REQUEST['cate']):0;
        $page = isset($_REQUEST['page'])?trim($_REQUEST['page']):1;

		FileUtil::requireService('ShareServ');
        $shareServ = new ShareServ();
        $lab = array();
        $pagesize = 6;
        $count = 0;
        if($cate == 1){
            /*获取更多合作实验室*/
            $lab = $shareServ->getAllLab();
            if($lab === false){
                FileUtil::loadServerErrHtml();
                exit(0);
            }
            $count1 = sizeof($lab);
            $lab = array();
            $pageUtil = new PageUtil($pagesize,$count1,$page);
            $index1 = ($pageUtil->getCurrentPage()-1)*$pagesize;
            $lab = $shareServ->getPageAllLab($index1,$pagesize);
            $count = $count1;
        }else if($cate == 2){
            /*获取更多热门实验室*/
            $lab = $shareServ->getMoreHotLab();
            if($lab === false){
                FileUtil::loadServerErrHtml();
                exit(0);
            }
            $count2 = sizeof($lab);
            $lab = array();
            $pageUtil = new PageUtil($pagesize,$count2,$page);
            $index2 = ($pageUtil->getCurrentPage()-1)*$pagesize;
            $lab = $shareServ->getPageMoreHotLab($index2,$pagesize);
            $count = $count2;
        }

        $user = UserAgent::getUser();
        $cl = array();
        if(!empty($user)){
            $colab = $shareServ->getCollectedLab($user['mid']);
            for($i = 0;$i<count($colab);$i++){
                $cl[] = $colab[$i]['aid'];
            }
        }

        /*页面调用*/
        $params = array();
        $params['style'] = 'share';
        $params['lab'] = $lab;
        $params['cate'] = $cate;
        $params['count'] = $count;
        $params['cl'] = $cl;
        $params['pager'] = $pageUtil;
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/lab/moreLab.html?cate='.$cate;
        return $params;
	}

	
}