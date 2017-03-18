<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class MyCircleJoined extends BaseAction{

    public function action(){
        $user = UserAgent::getUser();
        if(empty($user)){
            FileUtil::loadHtml('/login/login.html',array());
        }
        FileUtil::requireService("CircleServ");
        FileUtil::requireService("InterestServ");
        $interest=new InterestServ();
        $Circle=new CircleServ();
        $count=$Circle->getUserCircleCount($user['mid']);

        $followfcircle=$Circle->getUserCircleList($user['mid'],0,$count);

        if ($user['interest_labels'] != '') {
            $inter = explode(',', $user['interest_labels']);
        } else {
            $inter = array();
        }
        if ($user['industry_ids'] != '') {
            $indus = explode(',', $user['industry_ids']);
        } else {
            $indus = array();
        }
        $recommend = array();
        for ($i = 0; $i < count($inter); $i++) {
            $ret = $interest->GetInterestLabels($inter[$i], $user['mid']);
            $recommend = array_merge($recommend, $ret);
        }
        if (count($recommend) < 5) {
            for ($i = 0; $i < count($indus); $i++) {
                $res = $interest->GetIndustryIds($indus[$i], $user['mid']);
                $recommend = array_merge($recommend, $res);
            }
        }
        if (count($recommend) < 5) {
            $reu = $interest->GetById($user['mid']);
            $recommend = array_merge($recommend, $reu);
        }
        if (count($recommend < 5)) {
            $rev = $interest->GetBynotinterest($user['mid']);
            $recommend = array_merge($recommend, $rev);
        }
        $rec=array();
        $rect=array();
        foreach ($recommend as $k=>$v){
            $rec[$k]=implode($v,'()()');
        }
        $rec=array_unique($rec);
        foreach ($rec as $k=>$v){
            array_push($rect,$recommend[$k]);
        }
        $params['recommend'] = $rect;

        $params['followcircle']=$followfcircle;
        $params['count']=$count;
        return $params;
    }

}