<?php 
class BrandServ{
	//构造函数->默认载入函数
    public function __construct(){

    }
    
    function getBrandRecommend(){
	    $arr=array();
		$arr[]=1;
		$sql="select * from #__brand where status= ? and brand_id > 0 limit 18";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);	
    } 
    
    function getBrandRecommend2(){
	    $arr=array();
		$arr[]=1;
		$sql="select * from #__brand where status= ?  and brand_id > 0  limit 9";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);	
    } 

    
    //根据ptid获取类型
	function getSingleType($id){
		$arr=array();
		$arr[]=$id;
		$sql="select * from #__product_type where ptid= ? and status=1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->querySingleRecord($sql,$arr);	
	}
        
    //获取全部品牌
    function getBrandList(){
	    $arr=array();
		$arr[]=1;
        $sql="select * from #__brand where status= ?  and brand_id > 0  ";
		$dbAgent=DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    function getBrandCount($info){
        $arr=array();
        $arr[]=$info;
        if(empty($info)){
            $sql="select count(*) num from #__brand where status= 1  and brand_id > 0  ";
            $dbAgent=DBAgent::getInstance();
	        $result=$dbAgent->queryRecords($sql,array());
	        return $result[0]['num'];

        }else{
	      	if(preg_match('/[a-zA-Z]/', $info)){
				if(strlen($info)==1){
					$info=strtolower($info);
					$sql="select count(*) as num from #__brand where status = 1 and sort_letter = ?  and brand_id > 0  ";
				}else {
					$sql="select count(*) as num from #__brand where status= 1 and name like \"% ? %\"  and brand_id > 0  ";
				}
			}
			else{             
				$sql="select count(*) as num from #__brand where status= 1 and name like \"% ? %\"  and brand_id > 0  ";
			}
        }
        $dbAgent=DBAgent::getInstance();
        $result=$dbAgent->querySingleRecord($sql,$arr);
        return $result['num'];
    }
	function getBrandCountE($name){
		$arr=array();
		$arr[]=$name;
		$sql="select count(*) as num from #__brand where name = ? and status = 1  and brand_id > 0  ";
		$dbAgent=DBAgent::getInstance();
		$result=$dbAgent->querySingleRecord($sql,$arr);
		return $result['num'];
	}
    //获取产品类型
    function getLevel3ByL2($ptid){
		$arr=array();
		$arr[]=$ptid;
		$sql="select ptid from #__product_type where status=1 and level=3 and parentid=? ";
		$dbAgent=DBAgent::getInstance();
		$result=$dbAgent->queryRecords($sql,$arr);
		$temp=array();
		foreach($result as $value2){
			$temp[]=$value2['ptid'];	
		}
		return $temp;
	}
	
	
	
	function getBrandListBG($num,$page,$key){
		$sql="select a.brand_id,a.name,a.sort_letter,a.image,b.ptids from #__brand a join #__brand_type b on a.brand_id=b.brandid ";
		$sql.="where a.status=1 and b.status=1";
		$dbAgent=DBAgent::getInstance();
		$result=$dbAgent->queryRecords($sql,array());
		if($result===false||$result===null){
			return false;
		}
		foreach($result as $key=>$brand){
			$ptnames=BrandServ::getBrandTypeName($brand['ptids']);
			if($ptnames===false){
				return false;
			}
			$result[$key]['ptnames']=$ptnames;
		}
		return $result;			
	}
		
	function getBrandTypeName($ptids){
		$arrs=array();
		$sqls=array();
		
		$ptarray=explode(',', $ptids);
		foreach($ptarray as $ptid){
			$arr=array();
			$arr[]=$ptid;
			$sql="select name from #__product_type where ptid=? and status=1";
			array_push($arrs, $arr);
			array_push($sqls, $sql);
		}
		$dbAgent=DBAgent::getInstance();
	    $result = $dbAgent->QueryWithTransaction($sqls,$arrs);
	    if($result===false||$result===null){
		    return false;
	    }
	    $names="";
	    foreach($result as $name ){
		    $names.=$name['name'].",";
	    }
		return substr($names,0,strlen($names)-1);
	}
	
	function getLevel3ByL1($ptid){
		$arr1=array();
		$arr1[]=$ptid;
		$sql="select ptid from #__product_type where parentid=? and status=1 and level=2 ";
		$dbAgent=DBAgent::getInstance();
		$result1=$dbAgent->queryRecords($sql,$arr1);
		$ptids=array();
		foreach($result1 as $value){
			$ptids[]=$value['ptid'];
		}
		$three=array();
		foreach($ptids as $value1){
			$arr=array();
			$arr[]=$value1;
			$sql="select ptid from #__product_type where status=1 and level=3 and parentid=? ";
			$result=$dbAgent->queryRecords($sql,$arr);
			$temp=array();
			foreach($result as $value2){
				$temp[]=$value2['ptid'];	
			}
			$temp1=implode(',',$temp);
			$three[]=$temp1;
		}		
		$last=implode(',', $three);
		return $last;
	}

    
    function getBrandListByPtid($ptid){
	    $type=BrandServ::getSingleType($ptid);
	    $dbAgent=DBAgent::getInstance();
	    $sql="select a.brand_id,a.name,a.image from #__brand a join #__brand_type b on a.brand_id=b.brandid where a.status=1 and b.status=1  and a.brand_id > 0 ";
	    if($type['level']==1){
		    $sql.=" and FIND_IN_SET($ptid,b.first_ptid)";
	    }else if($type['level']==2){
		     $sql.=" and FIND_IN_SET($ptid,b.second_ptid)";
	    }else if($type['level']==3){
		    $sql.=" and FIND_IN_SET($ptid,b.third_ptid)";
	    }else if($type['level']==4){
		    $sql.=" and FIND_IN_SET($ptid,b.forth_ptid)";
	    }else if($type['level']==5){
		    $sql.=" and FIND_IN_SET($ptid,b.fifth_ptid)";
	    }
		$temp=$dbAgent->queryRecords($sql,array());
	    return $temp;  
    }
    
    function getBrandDetail($id){
	    $arr=array();
		$arr[]=$id;
		$sql="select * from #__brand where brand_id=? and status= 1  and brand_id > 0  ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->querySingleRecord($sql,$arr);
    }
    public function getPageBrandList($index,$pagesize,$info){
        $arr = array();
        $arr [] = $info;
        if(empty($info)){
            $sql = "select * from #__brand where status = 1  and brand_id > 0  order by sort_letter asc";
            $sql .= " limit $index,$pagesize";
	        $dbAgent = DBAgent::getInstance();
	        return $dbAgent->queryRecords($sql,array());
        }else{
	        if(preg_match('/[a-zA-Z]/', $info)){
		        if(strlen($info)==1){
			        $info=strtolower($info);
					$sql="select * from #__brand where status = 1 and sort_letter = ?  and brand_id > 0  ";
				}else{
					 $sql = "select * from #__brand where status = 1 and name like \"% ? %\"  and brand_id > 0  order by sort_letter asc";
				}
	        }else {
		        $sql = "select * from #__brand where status = 1 and name like \"% ? %\"  and brand_id > 0  order by sort_letter asc"; 
	        }
        }
        $sql .= " limit $index,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    //删除品牌
    function deleteBrand($id){
        $table = "brand";
        $updateColumns = array('status');
        $updateVals = array('0');
        $conditionColumns = array('brand_id','status');
        $conditionVals = array($id,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }

    //获取品牌
    function getBrandById($id){
        $arr = array();
        $arr[] = $id;
        $sql = "select * from #__brand where brand_id = ? and status = 1  and brand_id > 0 ";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }

    //添加品牌
    public function addBrand($brand){
        $table = "brand";
        $insertColumns = array("name","sort_letter","image","introduction","status");
        $insertVals = array($brand['name'],strtolower($brand['sort']),$brand['image'],$brand['intro'],1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }

    //删除品牌
    function updateBrand($id,$brand){
        $table = "brand";
        $updateColumns = array('name','sort_letter','image','introduction');
        $updateVals = array($brand['name'],strtolower($brand['sort']),$brand['image'],$brand['intro']);
        $conditionColumns = array('brand_id','status');
        $conditionVals = array($id,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }

    public function getBrandByLikeName($name){
        $arr = array();
        $arr [] = 1;
        $sql = "select * from #__brand where name like \"%$name%\" and status = ?  and brand_id > 0 ";
        $sql.= " limit 0,20";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    public function getBrandByName($name){
        $arr = array();
        $arr [] = $name;
        $sql = "select * from #__brand where name = ? and status = 1  and brand_id > 0 ";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }

    public function getBrandCateByBId($bid){
        $arr = array();
        $arr [] = $bid;
        $sql = "SELECT
                        pt.*
                    FROM
                        labring_product_type pt
                    JOIN (
                    SELECT 
                        CONCAT_WS(',',first_ptid,second_ptid,third_ptid,forth_ptid,fifth_ptid) as typeids
                    FROM 
                        labring_brand_type bt 
                    WHERE bt.brandid = ?
                    )ptids ON FIND_IN_SET(pt.ptid,ptids.typeids)
                    WHERE
                        pt.status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }
}	
?>