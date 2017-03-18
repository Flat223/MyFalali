<?php
class BannerServ{	
	//构造函数->默认载入函数
	public function __construct(){
	    
    }
    
    function getBannerlist($type){
	    $arr=array();
		$arr[]=$type;
		$sql="select * from #__banner where type= ? and status=1";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
    }

    /*实验室共享Banner*/
    function getShareBanner($type){
        $arr=array();
        $arr[]=$type;
        $sql="select * from #__banner where type= ? and status=1";
        $dbAgent=DBAgent::getInstance();
        return $result=$dbAgent->queryRecords($sql,$arr);
    }

    function getIndexCoopImg(){
        $arr = array();
        $arr[] = 5;
        $sql="select * from #__banner where type= ? and status=1";
        $dbAgent=DBAgent::getInstance();
        return $result=$dbAgent->querySingleRecord($sql,$arr);
    }

    function getIndexActivityImg(){
        $arr = array();
        $arr[] = 4;
        $sql="select * from #__banner where type= ? and status=1";
        $dbAgent=DBAgent::getInstance();
        return $result=$dbAgent->querySingleRecord($sql,$arr);
    }

    function updateThemeImg($data){
        $table = "banner";
        $updateColumns = array('image','name');
        $updateVals = array($data['img'],$data['name']);
        $conditionColumns = array('id','status');
        $conditionVals = array($data['id'],1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }
    	
}	
?>