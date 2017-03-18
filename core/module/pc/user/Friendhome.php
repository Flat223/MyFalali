<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Friendhome extends BaseAction{

	public function action(){
        $mid1=isset($_GET['mid'])?$_GET['mid']:"";
        $user = UserAgent::getUser();
        
        FileUtil:: requireService("UserServ");
		$service = new UserServ();
		
        if($mid1 == ""){
	        FileUtil::load404Html();
			exit(0);
        }
        
		$friend = $service->getMemberByMid($mid1);
		if($friend === false || $friend == null){
			FileUtil::load404Html();
			exit(0);
		}
		$mid = $friend['mid'];
		$friendLabs = $service->getUserInterestLab($mid);
		if($friendLabs === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		FileUtil:: requireService("FriendsServ");
		$service = new FriendsServ();
		$is_friend = $service->checkfriends($user['mid'],$mid);
		$following = $service->getUserFriendsCount($mid,1);
        if($following === false){
			FileUtil::load404Html();
			exit(0);
		}
		$fans = $service->getUserFriendsCount($mid,2);
		if($fans === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		FileUtil:: requireService("ArticleServ");
		$service = new ArticleServ();
		$article = $service-> getUserArticleCount($mid);
		if($article === false){	
			FileUtil::load404Html();
			exit(0);
		}
		$dynamic=$service->getUserArticle($mid,0,4);
		if($dynamic === false){
			FileUtil::load404Html();
			exit(0);
		}
        FileUtil:: requireService("CollegeServ");
        $service = new CollegeServ();
        $collegeSubType = $service->getCollegeSubType();//获取高校下所有成员身份
        $params['friend']= $friend;
        $params['is_friend']= $is_friend;
		$params['following'] = $following;
		$params['fans'] = $fans;
		$params['article'] = $article;
		$params['friendLabs'] = $friendLabs;
		$params['dynamic']= $dynamic;
        $params['sub_type']= $collegeSubType;
        return $params;		
	}
}