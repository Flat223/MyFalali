<?php
class RoleMenuServ extends BaseServ{
	
    public function __construct()
    {
	    parent::__construct("#__role_menu");
    }
    
    /*
	* 设置职位／角色菜单
    * $jobid 职位ID
    * $menuids 菜单ids 例如：1,2,3,4,5,5,6 以英文逗号间隔的菜单字符串
    */
    function setJobMenu($jobid,$menuids)
    {
	    $DBAgent = $this->DBAgent;
		$table = $this->handletable;
		$wherekeyvalue['roleid'] = $jobid;
		$deleteData = $DBAgent->DeleteTable($table,$wherekeyvalue);
		if($deleteData)
		{
			$ids = explode(",", $menuids);
			if(count($ids) > 0)
			{
				foreach($ids as $key=>$value)
				{
					$keyvalue['roleid'] = $jobid;
					$keyvalue['menuid'] = $value;
					$DBAgent->InsertTable($table,$keyvalue);
				}
				$result['ret'] = 1;
				$result['msg'] = "完成操作";
			}
			else
			{
				$result['ret'] = 0;
				$result['msg'] = "未提供数据，不继续操作";
			}
		}
		else
		{
			$result['ret'] = 0;
			$result['msg'] = "删除数据失败";
		}
		
		$DBAgent->CloseConnection();
        return $result;
    }
    
    //获取职位／角色菜单列表
    function getJobMenu($jobid)
    { 
	    $DBAgent = $this->DBAgent;
		$table = $this->handletable;
		$querystring = "SELECT menu.*,
						(
						    CASE
						       WHEN job.rid is null then 0
						    ELSE
						        1
						    END
						) as selected
						FROM `#__sys_menu` menu
						LEFT JOIN `".$table."` jobmenu ON jobmenu.menuid = menu.id AND jobmenu.roleid = $jobid
						LEFT JOIN `#__role` job ON job.rid = jobmenu.roleid
						WHERE 1 =1 ;";
		$sqlresult = $DBAgent->SelectQuery($querystring);
        $menulist = array();
        while($row = $DBAgent->FetchArray($sqlresult))
        {
            $menulist[] = $row;
        } 
		if(is_array($menulist))
		{
		    $result['ret'] = 1;
			$result['msg'] = "获取成功" ;
			$result['data'] = $menulist;  
		}
		else
		{
		    $result['ret'] = 0;
			$result['msg'] = "获取失败" ;
		}
		$DBAgent->CloseConnection();
        return $result;
    }
    
    
}