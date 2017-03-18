<?php

class iChukDBAgent{

    public $dbname = DBName;
    public $dbaddress = DBAddress;
    public $dbuser = DBUser;
    public $dbpassword = DBPassword;
    public $dbprefix = DBPrefix;
    public $dbconnect;
    
	//构造函数->默认载入函数
    public function __construct($databasename=DBName,$databaseprefix=DBPrefix)
    {
	    $this->dbname = isset($databasename)?$databasename:DBName;
	    $this->dbprefix = isset($databaseprefix)?$databaseprefix:DBPrefix;
        $this->InitType();
    }
 
    function InitType()
    {
	    $this->NewConnection();
    }
    
    /*
    * InsertTable 插入数据
    * $tablename #__tablename
    * $keyvalue $keyvalue["tablekey"] = $tablevalue
    * return boolean
    */
    function InsertTable($tablename,$keyvalue)
    {
	    $keyarr = array();
	    $valarr = array();
	    foreach($keyvalue as $key=>$val)
	    {
		    $keyarr[] = "`".$key."`";
		    $valarr[] = "'".mysql_real_escape_string($val)."'";
	    }
	    
	    if(count($keyarr) == count($valarr) && (count($keyarr) != 0 && count($valarr) != 0))
	    {
		    $keyquery = join(",",$keyarr);
		    $valquery = join(",",$valarr);
		    $HandleQuery = "INSERT INTO ".$tablename." ($keyquery) VALUES ($valquery);"; 
		    $sqlresult = $this->Query($HandleQuery); 
		    return $sqlresult;
	    }
	    else
	    {
		    return false;
	    }
    }
    
     /*
    * DeleteTable 删除数据（物理删除）
    * $tablename #__tablename
    * $keyvalue $keyvalue["tablekey"] = $tablevalue
    * return boolean
    */
    function DeleteTable($tablename,$wherekeyvalue)
    { 
	    $wherekeyarr = array();
	    $wherevalarr = array();
	    $wherekeyvalarr = array(); 
	    
	    foreach($wherekeyvalue as $wherekey => $whereval)
	    {
		    $wherekeyarr[] = "`".$wherekey."`";
		    $wherevalarr[] = "'".$whereval."'";
		    $wherekeyvalarr[] = "`".$wherekey."`='".$whereval."'";
	    }
	    
	    if( count($wherekeyvalarr) != 0)
	    { 
		    $conditionquery = join("AND",$wherekeyvalarr);
		    $HandleQuery = "DELETE FROM ".$tablename." WHERE $conditionquery;";
		    $sqlresult = $this->Query($HandleQuery);
		    return $sqlresult;
	    }
	    else
	    {
		    return false;
	    }
    }
    
    
    /*
    * UpdateTable 更新数据
    * $tablename #__tablename
    * $keyvalue $keyvalue["tablekey"] = $tablevalue
    * $wherekeyvalue $wherekeyvalue["tablekey"] = $condition
    * return boolean
    */
    function UpdateTable($tablename,$keyvalue,$wherekeyvalue)
    {
	    $keyarr = array();
	    $valarr = array();
	    $keyvalarr = array();
	    $wherekeyarr = array();
	    $wherevalarr = array();
	    $wherekeyvalarr = array();
	    foreach($keyvalue as $key=>$val)
	    {
		    $keyarr[] = "`".$key."`";
		    $valarr[] = "'".$val."'";
		    $keyvalarr[] = "`".$key."`='".mysql_real_escape_string($val)."'";
	    }
	    
	    foreach($wherekeyvalue as $wherekey => $whereval)
	    {
		    $wherekeyarr[] = "`".$wherekey."`";
		    $wherevalarr[] = "'".$whereval."'";
		    $wherekeyvalarr[] = "`".$wherekey."`='".$whereval."'";
	    }
	    
	    if(count($keyarr) == count($valarr) && (count($keyarr) != 0 && count($valarr) != 0 && count($wherekeyvalarr) != 0))
	    {
		    $keyvalquery = join(",",$keyvalarr);
		    $conditionquery = join("AND",$wherekeyvalarr);
		    $HandleQuery = "UPDATE ".$tablename." SET $keyvalquery WHERE $conditionquery;";
		    $sqlresult = $this->Query($HandleQuery);
		    return $sqlresult;
	    }
	    else
	    {
		    return false;
	    }
    }
    
    /*
    * SelectTable 查询数据
    * $tablename #__tablename
    * $keyvalue $keyvalue["tablekey"] = $tablevalue
    * $wherekeyvalue $wherekeyvalue["tablekey"] = $condition
    * 注意：此时判断的条件中 WHERE 都是 =
    * 当 $search == true 时，则优先处理 like 字段
    * return boolean
    */
    
    function SelectTable($tablename,$wherekeyvalue,$page=1,$pagesize=10,$orderby="",$orderway="",$search=false)
    {
	    $keyarr = array();
	    $valarr = array();
	    $result = null;
	    $wherekeyvalarr = array();
	    $orderstring = "";
	    foreach($wherekeyvalue as $wherekey => $whereval)
	    {
		    $wherekeyarr[] = "`".$wherekey."`";
		    $wherevalarr[] = "'".$whereval."'";
		    $wherekeyvalarr[] = ($search&&!is_numeric($whereval))?"`".$wherekey."`like'%".$whereval."%'":"`".$wherekey."`='".$whereval."'";
	    }
	    if(!empty($orderby))
	    {
		    $orderstring .= "ORDER BY ".$orderby;
	    }
	    
	    if(!empty($orderway))
	    {
		    $orderstring .= " ".$orderway;
	    }
	    
	    $page = empty($page) ? 1 : intval($page);
		$pagesize = empty($pagesize) ? 10 : intval($pagesize);
		$startNum = ($page-1)*$pagesize;
	    $conditionquery = join("AND",$wherekeyvalarr);
	    $HandleQuery = (count($wherekeyvalarr) != 0)?"SELECT * FROM ".$tablename." WHERE $conditionquery $orderstring LIMIT $startNum,$pagesize;":"SELECT * FROM ".$tablename." $orderstring LIMIT $startNum,$pagesize;"; 
	    $sqlresult = $this->SelectQuery($HandleQuery); 
	    while($row = $this->FetchArray($sqlresult))
		{
			$result[] = $row;
		} 
	    return $result;
    }
    
    /*
    * SelectCountTable 数据库计数
    * $tablename #__tablename
    * $wherekeyvalue $wherekeyvalue["tablekey"] = $condition
    * 注意：此时判断的条件中 WHERE 都是 =
    * 当 $search == true 时，则优先处理 like 字段
    * return intval
    */
    function SelectCountTable($tablename,$wherekeyvalue,$search=false)
    {
	    $keyarr = array();
	    $valarr = array();
	    $wherekeyvalarr = array();
	    foreach($wherekeyvalue as $wherekey => $whereval)
	    {
		    $wherekeyarr[] = "`".$wherekey."`";
		    $wherevalarr[] = "'".$whereval."'";
		    $wherekeyvalarr[] = ($search&&!is_numeric($whereval))?"`".$wherekey."`like'%".$whereval."%'":"`".$wherekey."`='".$whereval."'";
	    }
	    
	    $conditionquery = join("AND",$wherekeyvalarr);
	    $HandleQuery = (count($wherekeyvalarr) != 0)?"SELECT COUNT(*) as total FROM ".$tablename." WHERE $conditionquery;":"SELECT COUNT(*) as total FROM ".$tablename;
	    $sqlresult = $this->SelectQuery($HandleQuery); 
	    $row = $this->FetchArray($sqlresult); 
	    return $row['total'];
    }
    
    function Query($query)
    {
        $query = $this->FixQuery($query);
	    if (!mysql_query($query))
		{
		    return false;
		}
		else
		{
			return true;
		}
    }
    
    function SelectOne($query)
    {
	    $sqlresult = $this->SelectQuery($query);
	    $result = $this->FetchArray($sqlresult);
	    return $result;
    }
    
    function SelectQuery($query)
    {
	    $query = $this->FixQuery($query);
	    $result = mysql_query($query);
	    return $result;
    }
    
    function FixQuery($query)
    {
	    $query = str_replace("#_",$this->dbprefix,$query);
	    return $query;
    }
    
    function FetchArray($data)
    {
	    if($data)
	    {
		    return mysql_fetch_array($data,MYSQL_ASSOC);
	    }
	    else
	    {
		    return false;
	    } 
    }
    
    function NewConnection()
    {
	    $this->dbconnect = @mysql_connect($this->dbaddress,$this->dbuser,$this->dbpassword);
		if (!$this->dbconnect)
		{
		    die('Could not connect: ' . mysql_error());
		}else{
			mysql_select_db($this->dbname, $this->dbconnect);
		    mysql_query("SET NAMES utf8 COLLATE utf8_general_ci"); 
	        mysql_query("SET CHARACTER SET utf8mb4 COLLATE utf8_general_ci"); 
	        mysql_query("SET CHARACTER_SET_RESULTS=utf8 COLLATE utf8_general_ci");
		}
    }
    
    function CloseConnection()
    {
	    if(is_resource($this->dbconnect))
	    {
		    mysql_close($this->dbconnect); 
	    } 
    }
}

?>