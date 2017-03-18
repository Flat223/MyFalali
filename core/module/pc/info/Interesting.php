<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Interesting extends BaseAction
{

    public function action()
    {
        $id=isset($_GET['id'])?$_GET['id']:"";
        $page=isset($_GET['page'])?$_GET['page']:1;
        $user=UserAgent::getUser();
        FileUtil::requireService("InterestServ");
        $interest=new InterestServ();
        $interest->Addnum($id);
        $params['user'] = $user;
        if(!$user) {
            $params['recommend'] = $interest->GetbyTime(5);
        }else{
            if ($params['user']['interest_labels'] != '') {
                $inter = explode(',', $params['user']['interest_labels']);
            } else {
                $inter = array();
            }
            if ($params['user']['industry_ids'] != '') {
                $indus = explode(',', $params['user']['industry_ids']);
            } else {
                $indus = array();
            }
            $recommend = array();
            for ($i = 0; $i < count($inter); $i++) {
                $ret = $interest->GetInterestLabels($inter[$i], $params['user']['mid']);
                $recommend = array_merge($recommend, $ret);
            }
            if (count($recommend) < 5) {
                for ($i = 0; $i < count($indus); $i++) {
                    $res = $interest->GetIndustryIds($indus[$i], $params['user']['mid']);
                    $recommend = array_merge($recommend, $res);
                }
            }
            if (count($recommend) < 5) {
                $reu = $interest->GetById($params['user']['mid']);
                $recommend = array_merge($recommend, $reu);
            }
            if (count($recommend < 5)) {
                $rev = $interest->GetBynotinterest($params['user']['mid']);
                $recommend = array_merge($recommend, $rev);
            }
            $params['recommend'] = $recommend;
        }
        FileUtil::requireService('CircleServ');
        $Circle=new CircleServ();
        $params['circle']=$Circle->getCircleByid($id);
        if($params['circle'] == null || $params['circle'] == false){
            FileUtil::load404Html();
            exit(0);
        }
        $topic=$Circle->getTopicByid($id,$page,10);
        foreach($topic as $k=>$v){
            $topic[$k]['pic']=$this->GetStringImg($v['content']);
        }
        $params['topic']=$topic;
        $params['friends']=$Circle->getFriendByCid($params['circle']['circle_id'],$params['user']['mid'],8);
        $params['count']=$Circle->getCountByCid($params['circle']['circle_id']);
        FileUtil::requireService('PageUtil');
        $pageUtil = new PageUtil(10,$params['count'],$page);
        $params['baseurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/info/interesting.html?';
        $params['pager'] = $pageUtil;
        $params['check']=$Circle->Checkcircle($params['user']['mid'],$params['circle']['circle_id']);

        return $params;
    }

    function GetStringImg($string){
        $preg = "/<img.*?src=[\'\"](.+?)[\'\"].*?>/i";
        preg_match_all($preg, $string, $match);
        $imgurl = $match[1];
        return $imgurl;
    }
}
