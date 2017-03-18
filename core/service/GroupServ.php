<?php
class GroupServ{
	
	public function __construct(){
	    
    }
    //组合列表获取
    public function getGroupList($sid,$num,$page){
	    $a=($page-1)*$num;
	    $arr=array();
	    $arr[]=$sid;
	    $sql="select * from #__product_group where sid=? and status=1  limit $a,$num";
	    $dbAgent=DBAgent::getInstance();
	    $result=$dbAgent->queryRecords($sql,$arr);
	    foreach($result as $key=>$group){
		    $skuids=$group['skuids'];
		    $skuidlist=explode(',', $skuids);
		    for($a=0;$a<count($skuidlist);$a++){
			    $r=GroupServ::getBuyProductDetail($skuidlist[$a]);
			    $result[$key]['product'][]=$r;
		    }
	    }
	    return $result;
    }
    
    
    //获取组合详情
    public function getGroupDetail($id){
	    $arr=array();
	    $arr[]=$id;
	    $sql="select * from #__product_group where id=? and status=1";
		$dbAgent=DBAgent::getInstance();
		$result=$dbAgent->querySingleRecord($sql,$arr);
		if($result===false||$result===null){
			return false;
		}
		$skuids=$result['skuids'];
		$skulist=explode(',', $skuids);
		$last="";
		$last['name']=$result['name'];
		$last['price']=$result['price'];
		$temp=array();
		foreach($skulist as $sin){
			$arr1=array();
			$arr1[]=$sin;
			$sql="select a.pid,a.name,a.images,b.price,b.skuid from #__product a join #__sku b on a.pid=b.pid where b.skuid=? and a.status=1 and b.status=1";
			$pr=$dbAgent->querySingleRecord($sql,$arr1);
			if($pr!==false&&$pr!==null){
				$temp[]=$pr;
			}
		}
		$last['productlist']=$temp;
		return $last;
    }
    
    //更新组合
    function updateGroup($name,$price,$skuids,$id){
	    $table = "product_group";
        $updateColumns = array('name','price','skuids');
        $updateVals = array($name,$price,$skuids);
        $conditionColumns = array('id','status');
        $conditionVals = array($id,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }
    
    //添加组合
    public function insertGroup($skuids,$name,$price,$sid){
	    $arr=array();
	    $arr[]=$skuids;
	    $arr[]=$name;
	    $arr[]=$price;
	    $arr[]=$sid;
	    $sql="insert into #__product_group (skuids,name,price,sid,status) values(?,?,?,?,1) ";
	    $dbAgent=DBAgent::getInstance();
	    return $dbAgent->query($sql,$arr);
    }
    
	//删除组合
	function deleteGroup($sid,$id){
		$table = "product_group";
        $updateColumns = array('status');
        $updateVals = array(0);
        $conditionColumns = array('sid','id','status');
        $conditionVals = array($sid,$id,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}
	
    //获取购买产品详情
	function getBuyProductDetail($skuid){
		$arr=array();
		$arr[]=$skuid;
		$sql="select a.name as pname,a.images,a.intro,a.v1_discount,a.v2_discount,a.v3_discount,a.v4_discount,a.can_testing,a.quality_testing,a.can_guarantee,a.guarantee_1,a.guarantee_2,a.guarantee_5,b.* from #__product a join #__sku b on a.pid=b.pid where b.skuid=? and a.status=1 and b.status=1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->querySingleRecord($sql,$arr);
	}
	
	
	//获取组合数量
	 public function getGroupCount($sid){
		 $arr=array();
		 $arr[]=$sid;
		 $sql="select count(*) as num from #__product_group where sid=? and status=1";
		 $dbAgent=DBAgent::getInstance();	
		 $result=$dbAgent->querySingleRecord($sql,$arr);
		 return $result['num'];	 
	 }
	
    
}
?>