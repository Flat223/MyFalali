<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class CircleDetail extends BaseAction{

    public function action(){
        $id = isset($_GET['id'])?$_GET['id']:"";
        $user = UserAgent::getUser();
        FileUtil::requireService('CircleServ');
        $Circle = new CircleServ();
        $circle = $Circle->getCircleByid($id);
        $Circle = new CircleServ();
        if($circle == null || $circle == false){
            FileUtil::load404Html();
            exit(0);
        }
        /*$topic=$Circle->getTopicByid($id,1,10);
        foreach($topic as $k=>$v){
            $topic[$k]['pic']=$this->GetStringImg($v['content']);
        }*/
        $dynamic = $Circle->getUserCircleDynamic($circle['circle_id'],10);
        if($dynamic === false){
            FileUtil::load404Html();
            exit(0);
        }
        $params['circle'] = $circle;
        $params['dynamic'] = $dynamic;
        $params['circle'] = $Circle->getCircleByid($id);
        $params['count'] = $Circle->getCountByCid($circle['circle_id']);
        /*$params['topic']=$topic;*/
        return $params;
    }
    function GetStringImg($string){
        $preg = "/<img.*?src=[\'\"](.+?)[\'\"].*?>/i";
        preg_match_all($preg, $string, $match);
        $imgurl = $match[1];
        return $imgurl;
    }
}