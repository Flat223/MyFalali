<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class BindingCompany extends BaseAction{

    public function action(){
        $page=isset($_REQUEST['page'])?$_REQUEST['page']:1;
        $ch = isset($_REQUEST['ch'])?trim($_REQUEST['ch']):0;

        FileUtil::requireService('CompanyServ');
        $serv = new CompanyServ();
        $pagesize = 10;
        $data = array();
        $company = array();
        $member = array();
        $pageUtil = "";
        if((int)$ch == 1){
            $data = $serv->getBindingCompany($ch);
            $count = sizeof($data);
            $data = array();
            $pageUtil = new PageUtil($pagesize,$count,$page);
            $index = ($pageUtil->getCurrentPage()-1)*$pagesize;
            $data = $serv->getPageBindingCompany($ch,$index,$pagesize);
            for ($i = 0;$i<count($data);$i++){
                $company[] = $serv->getCompanyById($data[$i]['cid']);
            }
            for ($i = 0;$i<count($data);$i++){
                $member[] = $serv->getMemberById($data[$i]['mid']);
            }
        }else if((int)$ch == 2){
            $data = $serv->getBindingCompany($ch);
            $count = sizeof($data);
            $data = array();
            $pageUtil = new PageUtil($pagesize,$count,$page);
            $index = ($pageUtil->getCurrentPage()-1)*$pagesize;
            $data = $serv->getPageBindingCompany($ch,$index,$pagesize);
            for ($i = 0;$i<count($data);$i++){
                $company[] = $serv->getCompanyById($data[$i]['cid']);
            }
            for ($i = 0;$i<count($data);$i++){
                $member[] = $serv->getMemberById($data[$i]['mid']);
            }
        }
        else if((int)$ch == 3){
            $data = $serv->getBindingCompany($ch);
            $count = sizeof($data);
            $data = array();
            $pageUtil = new PageUtil($pagesize,$count,$page);
            $index = ($pageUtil->getCurrentPage()-1)*$pagesize;
            $data = $serv->getPageBindingCompany($ch,$index,$pagesize);
            for ($i = 0;$i<count($data);$i++){
                $company[] = $serv->getCompanyById($data[$i]['cid']);
            }
            for ($i = 0;$i<count($data);$i++){
                $member[] = $serv->getMemberById($data[$i]['mid']);
            }
        }

        $params = array();
        $params['style'] = 'checkManager';
        $params['substyle'] = 'bindingCompany';
        $params['data'] = $data;
        $params['company'] = $company;
        $params['member'] = $member;
        $params['pager'] = $pageUtil;
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/checkManager/bindingCompany.html?ch='.$ch;
        return $params;
    }
}