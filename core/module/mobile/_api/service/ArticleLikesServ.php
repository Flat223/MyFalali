<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class ArticleLikesServ extends BaseAction{
	
	public function action()
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
        $user = UserAgent::getUser();
        if (empty($user)) {
            $ret['ret'] = 0;
            $ret['msg'] = "尚未登录！";
            return $ret;
        }
        FileUtil::requireService('ArticleServ');
        $serv = new ArticleServ();
        $data = $serv->getLikesInfo($id, $user['mid']);
        if (empty($data)) {
            $result = $serv->articleLikes($id, $user['mid']);
            if ($result == false) {
                $ret['ret'] = -1;
                $ret['msg'] = "点赞失败~";
                return $ret;
            } else {
                $ret['ret'] = 1;
                $ret['msg'] = "点赞成功~";
                return $ret;
            }
        } else {
            $ret['ret'] = -1;
            $ret['msg'] = "您已赞过~";
            return $ret;
        }
    }
}