<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class UpdateArticleServ extends BaseAction{
	
	public function action(){		
		$type = isset($_REQUEST['type'])?trim($_REQUEST['type']):""; //1:编辑 2:删除;
		$aid = isset($_REQUEST['aid'])?trim($_REQUEST['aid']):"";
		$categoryId = isset($_REQUEST['categoryId'])?trim($_REQUEST['categoryId']):"";
		$title = isset($_REQUEST['title'])?trim($_REQUEST['title']):"";
		$intro = isset($_REQUEST['intro'])?trim($_REQUEST['intro']):"";
		$content = isset($_REQUEST['content'])?trim($_REQUEST['content']):"";
//		$province = isset($_REQUEST['province'])?trim($_REQUEST['province']):"";
//		$city = isset($_REQUEST['city'])?trim($_REQUEST['city']):"";
//		$country = isset($_REQUEST['country'])?trim($_REQUEST['country']):"";
        $images=isset($_REQUEST['images'])?trim($_REQUEST['images']):"";
		
		FileUtil::requireService("ArticleServ");
		$service = new ArticleServ();
		if ($type == 1){
			$callback = $service->updateArticleById($aid,1,$categoryId,$title,$intro,$content,$images);
		} else {
			$callback = $service->deleteArticleById($aid,1);
		}
		$ret = array();		
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		if($callback){
			$ret['ret'] = 1;
			$ret['msg'] = ($type == 1) ? "更新成功" : "删除成功"; 
		}
		return $ret;
	}
}