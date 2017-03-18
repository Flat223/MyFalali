<?php

class WithDrawServ{
 
    public $handletable;
    public $DBAgent;
	public $CommonFunc;
    
	//构造函数->默认载入函数
    public function __construct()
    {  
        $this->handletable = "withdraw";
        $this->DBAgent = DBAgent::getInstance();
		$this->CommonFunc = new CommonFunc();
    }
    
    //增
    function AddData($insertColumns,$insertVals)
    {
	    $handle = $this->DBAgent->insertRecord($this->handletable,$insertColumns,$insertVals);
		if($handle){ 
			$result['ret'] = 1;
			$result['msg'] = "发布成功"; 
			
		}
		else
		{
			$result['ret'] = 0;
			$result['msg'] = "发布失败";
		} 
        return $result;
    }
    
    //删
    function DeleteData($updateColumns,$updateVals,$conditionColumns,$conditionVals)
    { 
		$Handle = $this->DBAgent->updateRecords($this->handletable,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
		if(!$Handle )
		{
			$result['ret'] = 0;
			$result['msg'] = "删除失败";
		}
		else
		{
			$result['ret'] = 1;
			$result['msg'] = "删除成功";
		} 
        return $result;
    }
    
    //改
    function EditData($updateColumns,$updateVals,$conditionColumns,$conditionVals)
    {
	    $Handle = $this->DBAgent->updateRecords($this->handletable,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
        if($Handle)
        {
	        $result['ret'] = 1;
	        $result['msg'] = '修改成功';
        }
        else
        {
	        $result['ret'] = 0;
	        $result['msg'] = '修改失败';
        }
         
        return $result;
    }
    
    //查单条
    function GetData($columns,$conditionColumns,$conditionVals)
    { 
        $sqlresult = $this->DBAgent->getSingleRecordFromTable($this->handletable,$columns,$conditionColumns,$conditionVals);
        if(!is_array($sqlresult))
        {
	        $result['ret'] = 0;
	        $result['msg'] = '没有需要的数据';
	    }
	    else
	    {
		    $result['ret'] = 1;
		    $result['msg'] = '获取成功';
		    $result['data'] = $sqlresult;
	    }
         
        return $result;
    }

	//查多条
    function GetDatas($columns,$conditionColumns,$conditionVals,$page,$pagesize)
    { 
        $sqlresult = $this->DBAgent->getRecordsFromTable($this->handletable,$columns,$conditionColumns,$conditionVals,$page,$pagesize);
        if(!is_array($sqlresult))
        {
	        $result['ret'] = 0;
	        $result['msg'] = '没有需要的数据';
	    }
	    else
	    {
		    $total = $DBAgent->SelectCountTable($tablename,$wherekeyvalue); 
		    $result['ret'] = 1;
		    $result['msg'] = '获取成功';
		    $result['data'] = $sqlresult;
		    $result['total'] = $total['total'];
		    $result['page'] = $page;
		    $result['pagesize'] = $pagesize;
	    }
        
        $DBAgent->CloseConnection();
        return $result;
    }
 
    
}

?>