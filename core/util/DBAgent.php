<?php
require_once "Log.php";
class DBAgent{
	
	private $dbname = DBName;
    private $dbaddress = DBAddress;
    private $dbuser = DBUser;
    private $dbpassword = DBPassword;
    private $dbprefix = DBPrefix;
    private $log;
    private $pdoInstance = null;
    
    private static $instance = null;
	
	private function __construct(){}
    
    public static function getInstance($databaseAddress = DBAddress,$databaseName = DBName,$databaseUser = DBUser,$databasePsd = DBPassword,$databasePrefix = DBPrefix){
	    if(!self::$instance instanceof self){
		    self::$instance = new self();
		    self::$instance ->dbname = $databaseName;
		    self::$instance ->dbaddress = $databaseAddress;
		    self::$instance ->dbuser = $databaseUser;
		    self::$instance ->dbpassword = $databasePsd;
		    self::$instance ->dbprefix = $databasePrefix;
		    self::$instance ->log = new \cy\Log($_SERVER['DOCUMENT_ROOT'].'/core/logs/');
	    }
	    return self::$instance;
    }

	private function getPDOInstance(){
		if($this->pdoInstance == null){
			$dsn = "mysql:host=".$this->dbaddress.";dbname=".$this->dbname.";charset=utf8";
			try{
				$this->pdoInstance = new PDO($dsn,$this->dbuser,$this->dbpassword);
// 				$this->pdoInstance = new PDO($dsn,$this->dbuser,$this->dbpassword,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8';"));
				$this->pdoInstance->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			}catch(PDOException $e){
				$err = 'errCode:'.$e->getCode()."\n".'errMsg:'.$e->getMessage()."\n";
				$err .= "trace:\n".$e->getTraceAsString();
				$this->log->error($err);
				$this->pdoInstance = null;
			}
		}
		return $this->pdoInstance;
    }
    
    //获取数据库记录
    function queryRecords($sql,$params=array()){
	    $pdo = $this->getPDOInstance();
	    if(!$pdo){
		    return false;
	    }
	    $sql = str_replace("#_", $this->dbprefix, $sql);
	    try{
		    $stmt = $pdo->prepare($sql);
			$stmt->execute($params);
			$resultSet = array();
		    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			    $resultSet[] = $row;
		    }
		    return $resultSet;
	    }catch(PDOException $e){
			$err = 'errCode:'.$e->getCode()."\n".'errMsg:'.$e->getMessage()."\n";
			$err .= "trace:\n".$e->getTraceAsString();
			$this->log->error($err);
			return false;
		}
    }
    
    //获取数据库单条记录
    function querySingleRecord($sql,$params=array()){
	    $pdo = $this->getPDOInstance();
	    if(!$pdo){
		    return false;
	    }
	    $sql = str_replace("#_", $this->dbprefix, $sql);
	    try{
		    $stmt = $pdo->prepare($sql);
			$stmt->execute($params);
			$resultSet = array();
		    if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			    return $row;
		    }
		    return null;
	    }catch(PDOException $e){
			$err = 'errCode:'.$e->getCode()."\n".'errMsg:'.$e->getMessage()."\n";
			$err .= "trace:\n".$e->getTraceAsString();
			$this->log->error($err);
			return false;
		}
    }
    
    //增删改数据库的方法
    function query($sql,$params=array()){
	    $pdo = $this->getPDOInstance();
	    if(!$pdo){
		    return false;
	    }
	    $sql = str_replace("#_", $this->dbprefix, $sql);
	    try{
		    $stmt = $pdo->prepare($sql);
			$stmt->execute($params);
			return true;
	    }catch(PDOException $e){
		    $err = 'errCode:'.$e->getCode()."\n".'errMsg:'.$e->getMessage()."\n";
			$err .= "trace:\n".$e->getTraceAsString();
			$this->log->error($err);
			return false;
	    }
    }
    
    //事务处理
    function queryWithTransaction($sqls,$paramArray){
	    $pdo = $this->getPDOInstance();
	    if(!$pdo){
		    return false;
	    }
		$pdo->setAttribute(PDO::ATTR_AUTOCOMMIT,0);
		$result = false;
	    try{
		    $pdo->beginTransaction();
		    for($i=0;$i<count($sqls);$i++){
			    $sql = str_replace("#_", $this->dbprefix, $sqls[$i]);
			    $stmt = $pdo->prepare($sql);
				$stmt->execute($paramArray[$i]);
		    }
		    $pdo->commit();
		    $result = true;
	    }catch(PDOException $e){
		    $pdo->rollback();
		    $err = 'errCode:'.$e->getCode()."\n".'errMsg:'.$e->getMessage()."\n";
			$err .= "trace:\n".$e->getTraceAsString();
			$this->log->error($err);
			$result = false;
	    }
	    $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT,1);
	    return $result;
    }
    
    //拼接查询
    private function fixQuerySql($table,$columns,$conditionColumns,$conditionVals,$hasPrefix,$hasPage,$offset,$size,$orderby){
	    if($hasPrefix){
		    if($this->dbprefix != ''){
			    $table = $this->dbprefix.'_'.$table;
		    }
	    }
	    $sql = 'select ';
	    if($columns == '*'){
		    $sql .= '* ';
	    }else if($columns == 'count(*)'){
		    $sql .= 'count(*) as counts ';
	    }else{
		    $columnStr = '';
		    foreach($columns as $column){
			    $columnStr .= $column.',';
		    }
		    if(strlen($columnStr) > 0){
			    $columnStr = substr($columnStr,0, strlen($columnStr)-1);
		    }
		    $sql .= $columnStr.' ';
	    }
	    $sql .= 'from '.$table.' ';
	    if(count($conditionColumns) > 0){
		    $sql .= 'where ';
		    $conditionStr = '';
		    foreach($conditionColumns as $conditionColumn){
			    $conditionStr .= $conditionColumn.' = ? ';
			    $conditionStr .= 'and ';
		    }
		    $sql .= $conditionStr.'1 = 1 ';
	    }
	    if($orderby != ''){
		    $sql .= 'order by '.$orderby.' ';
	    }
	    if($hasPage){
		    $sql .= 'limit '.$offset.','.$size;
	    }
	    return $sql;
    }
    
    //获取数据
    function getRecordsFromTable($table,$columns,$conditionColumns=array(),$conditionVals=array(),$hasPrefix=true,$hasPage=false,$offset=0,$size=10,$orderby=''){
	    $sql = $this->fixQuerySql($table,$columns,$conditionColumns,$conditionVals,$hasPrefix,$hasPage,$offset,$size,$orderby);
	    if(count($conditionColumns) <= 0){
		    $conditionVals = array();
	    }
		return $this->queryRecords($sql,$conditionVals);
    }
    
    //获取单条数据
    function getSingleRecordFromTable($table,$columns,$conditionColumns=array(),$conditionVals=array(),$hasPrefix=true){
	     $sql = $this->fixQuerySql($table,$columns,$conditionColumns,$conditionVals,$hasPrefix,false,0,0,'');
	     if(count($conditionColumns) <= 0){
		    $conditionVals = array();
	    }
	    return $this->querySingleRecord($sql,$conditionVals);
    }
    
    //获取数据条数
    function getRecordCountsFromTable($table,$conditionColumns=array(),$conditionVals=array(),$hasPrefix=true){
	     $sql = $this->fixQuerySql($table,'count(*)',$conditionColumns,$conditionVals,$hasPrefix,false,0,0,'');
	     if(count($conditionColumns) <= 0){
		    $conditionVals = array();
	    }
	    $result = $this->querySingleRecord($sql,$conditionVals);
	    if($result === false){
		    return false;
	    }
	    $counts = $result['counts'];
	    return $counts;
    }
    
    //更新记录
    function updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals,$hasPrefix=true){
	    if($hasPrefix){
		    if($this->dbprefix != ''){
			    $table = $this->dbprefix.'_'.$table;
		    }
	    }
	    $sql = 'update '.$table.' set ';
	    $columnStr = '';
	    foreach($updateColumns as $updateColumn){
		    $columnStr .= $updateColumn.' = ?,';
	    }
	    if(strlen($columnStr) > 0){
		    $columnStr = substr($columnStr,0, strlen($columnStr)-1);
	    }
	    $sql .= $columnStr.' ';
	    if(count($conditionColumns) > 0){
		    $sql .= 'where ';
		    $conditionStr = '';
		    foreach($conditionColumns as $conditionColumn){
			    $conditionStr .= $conditionColumn.' = ? ';
			    $conditionStr .= 'and ';
		    }
		    $sql .= $conditionStr.'1 = 1 ';
	    }
	    $params = array();
	    foreach($updateVals as $val){
		    $params[] = $val;
	    }
	    foreach($conditionVals as $val){
		    $params[] = $val;
	    }
	    return $this->query($sql,$params);
    }
    
    //添加记录
    function insertRecord($table,$insertColumns,$insertVals,$hasPrefix=true){
	    if($hasPrefix){
		    if($this->dbprefix != ''){
			    $table = $this->dbprefix.'_'.$table;
		    }
	    }
	    $sql = 'insert into '.$table;
	    $insertStr = '';
	    foreach($insertColumns as $insertColumn){
		    $insertStr .= $insertColumn.',';
	    }
	    if(strlen($insertStr) > 0){
		    $insertStr = substr($insertStr,0, strlen($insertStr)-1);
	    }
	    $sql .= '('.$insertStr.')values';
	    $valStr = '';
	    for($i=1;$i<=count($insertColumns);$i++){
		    $valStr .= '?,';
	    }
	    if(strlen($valStr) > 0){
		    $valStr = substr($valStr,0, strlen($valStr)-1);
	    }
	    $sql .= '('.$valStr.') ';
	    return $this->query($sql,$insertVals);
    }
    
    //删除记录
    function deleteRecords($table,$conditionColumns,$conditionVals,$hasPrefix=true){
	    if($hasPrefix){
		    if($this->dbprefix != ''){
			    $table = $this->dbprefix.'_'.$table;
		    }
	    }
	    $sql = 'delete from '.$table.' ';
	    if(count($conditionColumns) > 0){
		    $sql .= 'where ';
		    $conditionStr = '';
		    foreach($conditionColumns as $conditionColumn){
			    $conditionStr .= $conditionColumn.' = ? ';
			    $conditionStr .= 'and ';
		    }
		    $sql .= $conditionStr.'1 = 1 ';
	    }
	    return $this->query($sql,$conditionVals);
    }
	
	private function __clone(){}
	
}