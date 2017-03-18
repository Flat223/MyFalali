<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class CircleCommentServ extends BaseAction{
	
	public function action(){
        $id = isset($_REQUEST['id'])?$_REQUEST['id']:0;
        $content = isset($_REQUEST['content'])?$_REQUEST['content']:'';
		$ret=array();
		$user = UserAgent::getUser();
        if(empty($user)) {
            $ret['ret'] = -1;
            $ret['msg'] = "先登录哦~";
            return $ret;
        }

        FileUtil::requireService('CircleServ');
        $serv = new CircleServ();
        $data['id'] = $id;
        $data['mid'] = $user['mid'];
        $data['content'] = $content;
        $result = $serv->insertCircleComment($data);
        if($result == false){
            $ret['ret'] = -1;
            $ret['msg']="评论失败了！稍后再试试~";
            return $ret;
        }else{
            $ret['ret'] = 1;
            $ret['msg']="评论成功啦~";
            return $ret;
        }
	}
}