<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class LoadMoreUserDynamicServ extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		if(empty($user)){
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}
		
		$mid=isset($_REQUEST['mid'])?trim($_REQUEST['mid']):'';
		$page=isset($_REQUEST['page'])?trim($_REQUEST['page']):'1';
			
		if(!Common::isInteger($page) || $page <= 0){
			$page = 1;
		}
		
		if(empty($mid) && ($user['type'] == 4 || $user['sub_type'] != 0)){
			$ret['ret'] = 0;
			$ret['msg'] = "缺少参数";
			return $ret;
		}
		
		$pagesize = 4;
		
		FileUtil:: requireService("FriendsServ");
		$service = new FriendsServ();
		$friends = $service->getUserfriendsMid($mid);//获取我关注的好友mid
		if($friends === false){
			FileUtil::load404Html();
			exit(0);
		}
		if($user['sub_type'] != 0 && $user['type'] != 4){
			FileUtil:: requireService("UserServ");
			$userService = new UserServ();
			$memberArray = $userService->getAllCompanyMember($user['mid'],$user['type']);//获取高校或公司下所有成员
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
        FileUtil::requireService("ArticleServ");
		$service=new ArticleServ();
		$count = $service->getUserDynamicCount($dynamic_mid);
		if($count === false){
			FileUtil::load404Html();
			exit(0);
		}
		$pageUtil = new PageUtil($pagesize,$count,$page); 
		$index = ($pageUtil->getCurrentPage()-1)*$pagesize;
		$moreDynamic=$service->getUserDynamic($dynamic_mid,$index,$pagesize);
		
		if($moreDynamic === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		if($moreDynamic == null) {
			$ret['ret'] = 0;
			$ret['msg'] = "没有数据了";
			return $ret;
		}
		 
		$ret['ret'] = 1;
		$ret['msg'] = "获取成功"; 
		$ret['more'] = $moreDynamic;
		return $ret;
	}
}

?>