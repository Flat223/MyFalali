<?php
class ManagerServ {
	public function __construct(){
		
	}

    //获取今日科研基金申请数
    public function getTodayFundCount(){
        $arr = array();
        $arr[] = strtotime(date('Y-m-d',time()));
        $arr[] = strtotime(date('Y-m-d',time()))+86399;
        $DBAgent = DBAgent::getInstance();
        $sql="select count(*) a from labring_college_fund_apply where apply_time between ? AND ? and state = 1";
        $result=$DBAgent->queryRecords($sql,$arr);
        return $result['0']['a'];
    }

    //获取今日需求数
    public function getTodayReleaseCount(){
        $arr = array();
        $arr[] = strtotime(date('Y-m-d',time()));
        $arr[] = strtotime(date('Y-m-d',time()))+86399;
        $DBAgent = DBAgent::getInstance();
        $sql="select count(*) a from labring_release where time between ? AND ? and is_check = 0";
        $result=$DBAgent->queryRecords($sql,$arr);
        return $result['0']['a'];
    }

    //获取今日发布实验室数
    public function getTodayLabCount(){
        $arr = array();
        $arr[] = strtotime(date('Y-m-d',time()));
        $arr[] = strtotime(date('Y-m-d',time()))+86399;
        $DBAgent = DBAgent::getInstance();
        $sql="select count(*) a from labring_lab where release_time between ? AND ? and is_check = 0";
        $result=$DBAgent->queryRecords($sql,$arr);
        return $result['0']['a'];
    }

    //获取今日下单数
    public function getTodayOrderCount(){
        $arr = array();
        $arr[] = strtotime(date('Y-m-d',time()));
        $arr[] = strtotime(date('Y-m-d',time()))+86399;
        $DBAgent = DBAgent::getInstance();
        $sql="select count(*) a from labring_order where time between ? AND ?";
        $result=$DBAgent->queryRecords($sql,$arr);
        return $result['0']['a'];
    }
    //获取今日媒体数
    public function getTodayMediaCount(){
        $arr = array();
        $arr[] = strtotime(date('Y-m-d',time()));
        $arr[] = strtotime(date('Y-m-d',time()))+86399;
        $DBAgent = DBAgent::getInstance();
        $sql="select count(*) a from labring_article where time between ? AND ?";
        $result=$DBAgent->queryRecords($sql,$arr);
        return $result['0']['a'];
    }

    //获取今日新增会员数
    public function getTodayVipCount(){
        $arr = array();
        $arr[] = strtotime(date('Y-m-d',time()));
        $arr[] = strtotime(date('Y-m-d',time()))+86399;
        $DBAgent = DBAgent::getInstance();
        $sql="select count(*) a from labring_order_vip where time between ? AND ?";
        $result=$DBAgent->queryRecords($sql,$arr);
        return $result['0']['a'];
    }

}
?>