<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class LabList extends BaseAction{
	
	public function action(){
        //获取参数
        $labtId = isset($_REQUEST['labtId'])?trim($_REQUEST['labtId']):0;
        $parentId  = isset($_REQUEST['parentId'])?trim($_REQUEST['parentId']):0;
        $obtype = isset($_REQUEST['obtype'])?trim($_REQUEST['obtype']):0;
        $page = isset($_REQUEST['page'])?trim($_REQUEST['page']):1;

        if(!Common::isInteger($labtId)||$labtId<=0){
            FileUtil::loadServerErrHtml();
            exit(0);
        }
        if(!Common::isInteger($page) || $page <= 0){
            $page = 1;
        }

        //请求service
        FileUtil::requireService('LabListServ');
        $labListServ = new LabListServ();
        $labType = $labListServ->getLabType($labtId);
        if($labType == null||$labType === false){
            FileUtil::loadServerErrHtml();
            exit(0);
        }

        $level = $labType['level'];
        $parentTypeList = $labListServ->getParentType();
        $childTypeList = array();
        $labArr = array();
        if($level == 1){
            $labArr[]  = $labListServ->getLabCountById($labType['lab_tid'],$obtype);
        }else if($level == 2){
            $labArr[] = $labListServ->getLabCountById($labType['lab_tid'],$obtype);
        }


        $pagesize = 10;
        $count = 0;
        if($level == 1){
            $count = sizeof($labArr[0]);
            $labArr = array();
            $pageUtil = new PageUtil($pagesize,$count,$page);
            $index = ($pageUtil->getCurrentPage()-1)*$pagesize;
            $childTypeList = $labListServ->getChildType($labType['lab_tid']);
            $labArr[] = $labListServ->getLabById($labType['lab_tid'],$obtype,$index,$pagesize);
        }else if($level == 2){
            $count = sizeof($labArr[0]);
            $labArr = array();
            $pageUtil = new PageUtil($pagesize,$count,$page);
            $index = ($pageUtil->getCurrentPage()-1)*$pagesize;
            $childTypeList = $labListServ->getChildType($labType['parentid']);
            $labArr[] = $labListServ->getLabById($labType['lab_tid'],$obtype,$index,$pagesize);
        }
        $user = UserAgent::getUser();
        $cl = array();
        FileUtil::requireService('ShareServ');
        $shareServ = new ShareServ();
        if(!empty($user)){
            $colab = $shareServ->getCollectedLab($user['mid']);
            for($i = 0;$i<count($colab);$i++){
                $cl[] = $colab[$i]['aid'];
            }
        }

        $params = array();
        $params['style'] = 'share';
        $params['labtId'] = $labtId;
        $params['parentId'] = $parentId;
        $params['typeName'] = $labType['name'];
        $params['count'] = $count;
        $params['cl'] = $cl;
        $params['partypeList'] = $parentTypeList;
        $params['childtypeList'] = $childTypeList;
        $params['labArr'] = $labArr;
        $params['pager'] = $pageUtil;
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/lab/labList.html?labtId='.$labtId.'&parentId='.$parentId.'&obtype='.$obtype;
		return $params;
	}
	
}