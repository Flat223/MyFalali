<?php 
class RecommendServ {
	//构造函数->默认载入函数
    public function __construct(){
	    
    }
    //获取一级分类详情
    function getLevel1(){
	    $sql="select * from #__product_type where level=1 and status=1";
	    $dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,array());
    }
   
   function getRecommendLevel2(){
	   $sql="select a.*,b.name as ptname,c.name as pname,c.images from #__product_recommend a join #__product_type b on a.level2=b.ptid join #__product c on a.pid=c.pid where a.status=1 and b.status=1 and b.level=2 and c.status=1";
	    $dbAgent=DBAgent::getInstance();
	    return $result=$dbAgent->queryRecords($sql,array());
   }
   
    
}
?>