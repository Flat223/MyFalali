<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class UpdateUserServ extends BaseAction{
	
	public function action(){
		$user = UserAgent::getUser();
		FileUtil::requireService("UserServ");
		$service = new UserServ();
		$user2 = $service->getMemberByMid($user['mid']);
		if($user2 === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			return $ret;
		}
		if($user2 == null){
			unset($_SESSION['user']);
			$ret['ret'] = 0;
			$ret['msg'] = "请先登录";
			return $ret;
		}

		$name = isset($_REQUEST['name'])?trim($_REQUEST['name']):"";
		$face = isset($_REQUEST['face'])?trim($_REQUEST['face']):"";
		$nickname = isset($_REQUEST['nickname'])?trim($_REQUEST['nickname']):"";
		$sex = isset($_REQUEST['sex'])?trim($_REQUEST['sex']):"";
		$career = isset($_REQUEST['career'])?trim($_REQUEST['career']):"";
		
// 		$education = isset($_REQUEST['education'])?trim($_REQUEST['education']):"";
// 		$city = isset($_REQUEST['city'])?trim($_REQUEST['city']):"";
// 		$residential_district = isset($_REQUEST['residential_district'])?trim($_REQUEST['residential_district']):"";

		$province = isset($_REQUEST['province'])?trim($_REQUEST['province']):"";
		$city = isset($_REQUEST['city'])?trim($_REQUEST['city']):"";
		$country = isset($_REQUEST['country'])?trim($_REQUEST['country']):"";
		$address = isset($_REQUEST['address'])?trim($_REQUEST['address']):"";

		$identity_num = isset($_REQUEST['identity_num'])?trim($_REQUEST['identity_num']):"";
		$university = isset($_REQUEST['university'])?trim($_REQUEST['university']):"";
		$political_identity = isset($_REQUEST['political_identity'])?trim($_REQUEST['political_identity']):"";
		
		$personal_desc = isset($_REQUEST['personal_desc'])?trim($_REQUEST['personal_desc']):"";
		$education_experience = isset($_REQUEST['education_experience'])?trim($_REQUEST['education_experience']):"";
		$work_experience = isset($_REQUEST['work_experience'])?trim($_REQUEST['work_experience']):"";
		$research_achievement = isset($_REQUEST['research_achievement'])?trim($_REQUEST['research_achievement']):"";
		$patent = isset($_REQUEST['patent'])?$_REQUEST['patent']:"";
		$research_projects = isset($_REQUEST['research_projects'])?trim($_REQUEST['research_projects']):"";			
		$interest_labels = isset($_REQUEST['interest_labels'])?trim($_REQUEST['interest_labels']):"";
		
		if($name != ""){
			$member['name'] = $name;	
		}
		if($face != ""){
			$member['face'] = $face;	
		}
		if($nickname != ""){
			$member['nickname'] = $nickname;	
		}
		if($sex != ""){
			$member['sex'] = $sex;	
		}
		if($career != ""){
			$member['career'] = $career;	
		}
		
		if($province != ""){
			$member['province'] = $province;
		}
		if($city != ""){
			$member['city'] = $city;	
		}
		if($country != ""){
			$member['country'] = $country;
		}
		if($address != ""){
			$member['address'] = $address;
		}
		
		
/*
		if($education != ""){
			$member['education'] = $education;	
		}
		if($city != ""){
			$member['city'] = $city;	
		}
		if($residential_district != ""){
			$member['residential_district'] = $residential_district;	
		}
*/
		
		if($university != ""){
			$member['university'] = $university;	
		}
		if($identity_num != ""){
			$member['identity_num'] = $identity_num;	
		}
		if($political_identity != ""){
			$member['political_identity'] = $political_identity;	
		}
		if($personal_desc != ""){
			$member['personal_desc'] = $personal_desc;	
		}
		if($education_experience != ""){
			$member['education_experience'] = $education_experience;	
		}
		if($work_experience != ""){
			$member['work_experience'] = $work_experience;	
		}
		if($research_achievement != ""){
			$member['research_achievement'] = $research_achievement;	
		}
		if($patent != ""){
			$member['patent'] = $patent;	
		}
		if($research_projects != ""){
			$member['research_projects'] = $research_projects;	
		}
		if($interest_labels != ""){
			$member['interest_labels'] = $interest_labels;	
		}
		
		if(empty($member)){
			$ret['ret'] = 0;
			$ret['msg'] = "没有要更新的信息";
			return $ret;
		}
		
		$callback = $service->updateMemberInfo($user2['mid'],$member);	
		if($callback === false){
			$ret['ret'] = 0;
			$ret['msg'] = "抱歉，服务器错误，请稍后再试";
			$ret['member'] = $member;
			return $ret;
		}
		if($callback){
			$ret['ret'] = 1;
			$ret['msg'] = "更新成功"; 
		}
		
		$user3 = $service->getMemberByMid($user2['mid']);
		$_SESSION['user'] = $user3;
		return $ret;
	}
}