<?php
class BaseServ{

	
	public $handletable;
	public $DBAgent;
	//构造函数->默认载入函数
    public function __construct($table){
	    $this->handletable = $table;//"#__flag"
        $this->DBAgent = new iChukDBAgent();
    }
	
	//增
    function AddData($keyvalue)
    {
	    $DBAgent = $this->DBAgent;
	    $tablename = $this->handletable;
        $sqlresult = $DBAgent->SelectTable($tablename,$keyvalue,1,10);
        if(!is_array($sqlresult))
        {
	        $sqlresult = $DBAgent->InsertTable($tablename,$keyvalue);
	        if($sqlresult)
	        {
		        $result['ret'] = 1;
                $result['id'] = mysql_insert_id();
		        $result['msg'] = '添加成功';
	        }
	        else
	        {
		        $result['ret'] = 0;
		        $result['msg'] = '添加失败';
		        $result['error'] = mysql_error();
	        }
	    }
	    else
	    {
		    $result['ret'] = 0;
		    $result['msg'] = '已存在相同内容';
		    $result['data'] = $sqlresult;
	    }
        
        $DBAgent->CloseConnection();
        return $result;
    }

	//自增
	function AutoPlus($wherekeyvalue,$updatekeyvalue)
	{
		$DBAgent = $this->DBAgent;
	    $tablename = $this->handletable;
		foreach($wherekeyvalue as $wherekey => $whereval)
	    {
		    $wherekeyarr[] = "`".$wherekey."`";
		    $wherevalarr[] = "'".$whereval."'";
		    $wherekeyvalarr[] = "`".$wherekey."`='".$whereval."'";
	    }
		$conditionquery = join("AND",$wherekeyvalarr);
		$updatekey = "`".$updatekeyvalue."`";
		$query = "UPDATE ".$tablename." SET $updatekey=$updatekey+1 WHERE $conditionquery; ";
	    $result = $DBAgent->Query($query); 
        $DBAgent->CloseConnection();
        return $result;
	}
    
    //删
    function DeleteData($wherekeyvalue,$updatekeyvalue)
    {
	    $result = $this->EditData($wherekeyvalue,$updatekeyvalue);
	    if($result['ret'] == 1)
	    {
		    $result['msg'] = '删除成功';
	    }
	    else if($result['ret'] == 0)
	    {
		    $result['msg'] = '删除失败';
	    }
        return $result;
    }
    
    //改
    function EditData($wherekeyvalue,$updatekeyvalue)
    {
	    $DBAgent = $this->DBAgent;
	    $tablename = $this->handletable;
        $sqlresult = $DBAgent->UpdateTable($tablename,$updatekeyvalue,$wherekeyvalue);
        if($sqlresult)
        {
	        $result['ret'] = 1;
	        $result['msg'] = '修改成功';
        }
        else
        {
	        $result['ret'] = 0;
	        $result['msg'] = '修改失败';
	        $result['error'] = mysql_error();
        }
        
        $DBAgent->CloseConnection();
        return $result;
    }
    
    //查
    function GetData($wherekeyvalue,$page,$pagesize,$orderby='',$orderway='',$search=false)
    {
	    $DBAgent = $this->DBAgent;
	    $tablename = $this->handletable;
	    $page = empty($page) ? 1 : intval($page);
		$pagesize = empty($pagesize) ? 10 : intval($pagesize);
        $sqlresult = $DBAgent->SelectTable($tablename,$wherekeyvalue,$page,$pagesize,$orderby,$orderway,$search);
        if(!is_array($sqlresult))
        {
	        $result['ret'] = 0;
	        $result['msg'] = '没有需要的数据';
	    }
	    else
	    {
		    $total = $DBAgent->SelectCountTable($tablename,$wherekeyvalue,$search); 
		    $result['ret'] = 1;
		    $result['msg'] = '获取成功';
		    $result['data'] = $sqlresult;
		    $result['total'] = $total;
		    $result['page'] = $page;
		    $result['pagesize'] = $pagesize;
	    }
        
        $DBAgent->CloseConnection();
        return $result;
    }
	 
}