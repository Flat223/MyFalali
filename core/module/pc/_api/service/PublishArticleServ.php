<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class PublishArticleServ extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		$mid = $user['mid'];
		$categoryId = isset($_REQUEST['categoryId'])?trim($_REQUEST['categoryId']):"";
		$title = isset($_REQUEST['title'])?trim($_REQUEST['title']):"";
		$intro = isset($_REQUEST['intro'])?trim($_REQUEST['intro']):"";
		$content = isset($_REQUEST['content'])?trim($_REQUEST['content']):"";
		$province = isset($_REQUEST['province'])?trim($_REQUEST['province']):"";
		$city = isset($_REQUEST['city'])?trim($_REQUEST['city']):"";
		$country = isset($_REQUEST['country'])?trim($_REQUEST['country']):"";
        $images=isset($_REQUEST['images'])?trim($_REQUEST['images']):"";
        $video=isset($_REQUEST['video'])?trim($_REQUEST['video']):"";
        FileUtil::requireService("ArticleServ");
		$service = new ArticleServ();
		$callback = $service->publishArticle($mid,$categoryId,$title,$intro,$content,$images,$video);
		$ret = array();		
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
		} else if($callback){
			$ret['ret'] = 1;
			$ret['msg'] = "发布成功"; 
		}
		return $ret;
	}
}
?>