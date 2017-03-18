<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class Index extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		FileUtil::requireService("UserServ");
		
		if($user['type'] == 3){
			FileUtil::load404Html();
			exit(0);
		}
		
        $service = new UserServ();
		$user2 = $service->getMemberByMid($user['mid']);
		if($user2 === false){
			FileUtil::load404Html();
			exit(0);
		}
		$_SESSION['user'] = $user2;
		$mid = $user2['mid'];
		
		$myLabs = $service->getUserInterestLab($mid);
		if($myLabs === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		FileUtil:: requireService("CollegeServ");
		$service = new CollegeServ();
		$collegeSubType = $service->getCollegeSubType();//获取高校下所有成员身份
		if($collegeSubType === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		FileUtil:: requireService("FriendsServ");
		$service = new FriendsServ();
		$friends = $service->getUserfriends($mid,1);//获取我关注的好友信息
		if($friends === false){
			FileUtil::load404Html();
			exit(0);
		}
		$fans = $service->getUserFriendsCount($mid,2);//获取我的粉丝数量
		if($fans === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		FileUtil:: requireService("ArticleServ");
		$service = new ArticleServ();
		$article = $service-> getUserArticleCount($mid);//获取我的文章数量
		if($article === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		if($user['sub_type'] == 0 && $user['type'] != 4){
			FileUtil:: requireService("UserServ");
			$userService = new UserServ();
			$memberArray = $userService->getAllCompanyMember($user['mid'],$user['type']);//获取高校或公司下所有成员
			$dynamic_mid = "";
			foreach ($memberArray as $member){
				if(empty($dynamic_mid)){
					$dynamic_mid = $member['mid'];	
				} else {
					$dynamic_mid .= ','.$member['mid'];
				}
			} 
		} else {
			$dynamic_mid = $mid;
			if(!empty($friends)){
				foreach ($friends as $friend){
					$dynamic_mid .= ','.$friend['mid'];
				}
			}
		}
		$dym_count = $service->getUserDynamicCount($dynamic_mid);
		if($dym_count === false){
			FileUtil::load404Html();
			exit(0);
		}
		$dynamic=$service->getUserDynamic($dynamic_mid,0,4);
		if($dynamic === false){
			FileUtil::load404Html();
			exit(0);
		}
		
		$params['dym_count'] = $dym_count;
		$params['dynamic']= $dynamic;
		
		FileUtil:: requireService("ProductServ");
		$service = new ProductServ();
		$relatedProduct = $service-> getUserRecommendProduct(1,4);
		if($relatedProduct === false){
			FileUtil::load404Html();
			exit(0);
		}
		$recommendProduct = $service-> getUserRecommendProduct(2,4);
		if($recommendProduct === false){
			FileUtil::load404Html();
			exit(0);
		}
        FileUtil::requireService("AdvertServ");
        $Advert=new AdvertServ();
        $advertself=$Advert->getAdvert(4,1);
        
        $params['style'] = 'user';
		$params['advertself']= $advertself;
		$params['sub_type']= $collegeSubType;
		$params['following'] = count($friends);
		$params['fans'] = $fans;
		$params['article'] = $article;
		$params['myLabs'] = $myLabs;
		$params['related']= $relatedProduct;
		$params['recommend']= $recommendProduct;
		return $params;
	}
}