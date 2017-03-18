<?php
class PropertyServ{
	
	//获取二级属性分类总数
    public function getprocount(){
        $sql="select count(*) as num from labring_product_type where status=1 and level=2 ";
        $dbAgent=DBAgent::getInstance();
        $result=$dbAgent->querySingleRecord($sql,array());
        return $result['num'];
    }
	
	//根据等级获取属性分类
    public function getPropertyByLevl($level){
	    $arr = array();
	    $arr[] = $level;
	    if($level == 2){
		    $sql="select b.name as pname,a.* from #__product_type a join #__product_type b on a.parentid = b.ptid where a.level = ? and a.status = 1 and b.status = 1 ";
	    } else {
			$sql="select * from #__product_type where level = ? and status = 1 ";    
	    }
        
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }
    
    //根据ptid获取属性分类
    public function getProTypeByPtid($ptid){
        $sql="select * from #__product_type where status = 1 and ptid = $ptid ";
        $dbAgent=DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,array());
    }
    
    //根据名称获取属性分类
    public function getProTypeByName($name){
	    $arr = array();
	    $arr[] = $name;
	    $sql="select * from #__product_type where name = ? and status = 1 ";
        $dbAgent=DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }
    
    //获取所有属性
    function getAllProperty(){
	    $sql = "select * from #__product_property where status = 1 ";
	    $dbAgent=DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,array());
    }
    
    //根据id获取产品属性
    public function getPropertyById($id){
        $sql="select * from #__product_property where status = 1 and id = $id ";
        $dbAgent=DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,array());
    }
    
    //添加属性分类
    public function addPropertyType($name,$parentid,$level){
	    $arr = array();
	    $arr[] = $name;
	    $arr[] = $parentid;
	    $arr[] = $level; 
	    $sql = "insert into #__product_type (name,parentid,level,status) values (?,?,?,1) ";
	    $dbAgent = DBAgent::getInstance();
        return $dbAgent->query($sql,$arr);
    }
    
    //修改属性分类名称
    public function updatePropertyType($ptid,$name){
	    $arr = array();
	    $arr[] = $name;
	    $arr[] = $ptid;
	    $sql = "update #__product_type set name = ? where ptid = ? and status = 1 ";
	    $dbAgent = DBAgent::getInstance();
        return $dbAgent->query($sql,$arr);
    }
    
    //修改属性名称
    public function updateProperty($proid,$name){
	    $arr = array();
	    $arr[] = $name;
	    $arr[] = $proid;
	    $sql = "update #__product_property set name = ? where id = ? and status = 1 ";
	    $dbAgent = DBAgent::getInstance();
        return $dbAgent->query($sql,$arr);
    }

    public function Getprobyptid($id){
        $sql="select * from labring_product_property where status=1 and ptid=$id";
        $dbAgent=DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$params=array());
    }
    
    //根据属性名称查询属性
    public function getPropertyByNames($ptid,$names){
	    $arr = array();
	    $arr[] = $ptid;
	    $arr[] = $names;
        $sql="select * from #__product_property where ptid = ? and find_in_set(name,?) and status = 1 ";
        $dbAgent=DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }
    
    //根据id删除属性
    public function deleteProperty($ptid,$proid){
    	$arr = array();
	    $arr[] = $ptid;
	    $arr[] = $proid;
	    $sql = "update #__product_property set status = 0 where ptid = ? and id = ? and status = 1 ";
	    $dbAgent = DBAgent::getInstance();
        return $dbAgent->query($sql,$arr);
    }
    //增加labring_product_property
    public function Addprotype($ptid,$name){
        $dbAgent=DBAgent::getInstance();
        $table="product_property";
        $insertColumns=array('name','ptid','status');
        $insertVals=array($name,$ptid,1);
        if($dbAgent->insertRecord($table,$insertColumns,$insertVals,$hasPrefix=true)){
            $ret['ret']="1";
            $ret['msg']="成功";
        }else{
            $ret['ret']="0";
            $ret['msg']="失败";
        }
        return $ret;
    }

    //查找数量labring_product_property按ptid
    public function getpronum($ptid){
        $sql="select count(*) as num from labring_product_property where ptid=$ptid and status=1";
        $dbAgent=DBAgent::getInstance();
        $result=$dbAgent->querysingleRecord($sql,array());
        return $result['num'];
    }

    //删除labring_product_type按id
    public function deleteprotypebyid($id){
        $dbAgent=DBAgent::getInstance();
        $table="product_type";
        $updateColumns=array('status');
        $updateVals=array(0);
        $conditionColumns=array('ptid');
        $conditionVals=array($id);
        if($dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals,$hasPrefix=true)){
            $ret['ret']="1";
            $ret['msg']="成功";
        }else{
            $ret['ret']="0";
            $ret['msg']="失败";
        }
        return $ret;
    }
}

?>