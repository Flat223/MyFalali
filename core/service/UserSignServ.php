<?php
class UserSignServ{

    //构造函数->默认载入函数
    public function __construct(){

    }

    /*根据用户id添加签到记录*/
    function insertSignRecord($mid){
        $table = "sign";
        $insertColumns = array('m_id','sign_time','sign_count','status');
        $insertVals = array($mid,time(),1,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals,$hasPrefix=true);
    }

    /*根据用户id获取签到记录*/
/*
    function getSignRecordById($mid){
        $arr = array();
        $arr[] = $mid;
        $sql = "select * from #__sign where mid = ? and status=1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }
*/

    /*根据用户id获取签到记录*/
    function getSignRecordById($mid){
        $table = "sign";
        $columns = "*";
        $conditionColumns = array('m_id','status');
        $conditionVals = array($mid,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->getSingleRecordFromTable($table,$columns,$conditionColumns,$conditionVals,$hasPrefix=true);
    }

    //更新连续签到的次数
    function updateRecord($id,$count,$mid){
        $count = $count+1;
        $table = 'sign';
        $updateColumns = array('sign_time','sign_count');
        $updateVals = array(time(),$count);
        $conditionColumns = array('m_id','id');
        $conditionVals = array($mid,$id);
        $dbAgent = DBAgent::getInstance();
        return $result = $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals,$hasPrefix=true);
    }

    /*获取上班打卡信息*/
    function getAmSignInfoByAid($aid){
        $time = strtotime(date("Y-m-d"));
        $time1 = intval($time)+86399;
        $sql = "select * from #__oa_sign where aid = $aid and time between $time AND $time1 and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,array());
    }

    /*获取下班打卡信息*/
    function getPmSignInfoByAid($aid){
        $time = strtotime(date("Y-m-d"));
        $time1 = intval($time)+86399;
        $sql = "select * from #__oa_sign where aid = $aid and time between $time AND $time1 and status = 2";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,array());
    }

    //上下班打卡
    function insertSign($aid,$ip,$type){
        $table = "oa_sign";
        $insertColumns = array('aid','time','ip','status');
        $insertVals = array($aid,time(),$ip,intval($type));
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals,$hasPrefix=true);
    }

    function GetSignList($mid,$date){
        $DBAgent = DBAgent::getInstance();
        $mid = empty($mid)? 0 : intval($mid);
        if(intval($mid) > 0){
            $querystring = "SELECT * FROM #__oa_sign WHERE aid = '$mid' AND date_format(FROM_UNIXTIME(time),'%Y-%m') = '$date' and status != 0";
            $countstring = "SELECT COUNT(*) as total FROM #__oa_sign WHERE aid = '$mid' AND date_format(FROM_UNIXTIME(time),'%Y-%m') = '$date' and status != 0";
        }
        $sqlresult = $DBAgent->queryRecords($querystring,array());
        /*echo json_encode($sqlresult);exit(0);
        $postlist = array();
        while($row = self::FetchArray($sqlresult)) {
            $postlist[] = $row;
        }*/
        $total = $DBAgent->querySingleRecord($countstring);
        $result["items"] =$sqlresult;
        $result["total"] = intval($total['total']);
        return $result;
    }

    /*function FetchArray($data){
        if($data) {
            return mysql_fetch_array($data,MYSQL_ASSOC);
        }
        else {
            return false;
        }
    }*/

    /*根据id获取日志*/
    function getJournalById($id){
        $sql = "select * from #__oa_journal where id = $id and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,array());
    }

    /*获取日总和*/
    function getCountJournal($aid){
        $sql = "select count(*) as num from #__oa_journal where aid = $aid and status = 1";
        $dbAgent = DBAgent::getInstance();
        $result = $dbAgent->queryRecords($sql,array());
        return $result[0]['num'];
    }

    /*获取分页日志*/
    function getJournalByAid($aid,$index,$size){
        $sql = "select * from #__oa_journal where aid = $aid and status = 1";
        $sql.= " limit $index,$size";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,array());
    }

    /*添加日志*/
    function addJournal($data){
        $table = "oa_journal";
        $insertColumns = array('aid','time','title','content','status');
        $insertVals = array($data['aid'],time(),$data['name']."&nbsp;".date('Y-m-d',time()),$data['content'],1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals,$hasPrefix=true);
    }

    /*更新日志*/
    function updateJournal($id,$content){
        $table = 'oa_journal';
        $updateColumns = array('content');
        $updateVals = array($content);
        $conditionColumns = array('id');
        $conditionVals = array($id);
        $dbAgent = DBAgent::getInstance();
        return $result = $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals,$hasPrefix=true);
    }
}
