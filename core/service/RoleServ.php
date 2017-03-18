<?php
class RoleServ extends BaseServ{
	
    public function __construct()
    {
	    parent::__construct("#__sys_menu");
    }
    
    public function getMenuList($pagesize,$page,$pid=0){
	    $table = $this->handletable;
        $a=($page-1)*$pagesize;
        $sql="select * from ".$table." where status=1";
        if(!empty($pid)){
            $sql.=" and pid=$pid";
        }
        $sql.=" limit $a,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $result=$dbAgent->queryRecords($sql,array());
    }
    
    public function getMenuCount($pid=0){
	    $table = $this->handletable;
        $sql="select count(*) as num from ".$table." where status=1";
        if(!empty($pid)){
            $sql.=" and pid=$pid";
        }
        $dbAgent = DBAgent::getInstance();
        $result=$dbAgent->querySingleRecord($sql,array());
        return $result['num'];
    }
    
    
    
}