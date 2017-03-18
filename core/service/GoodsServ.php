<?php 
class GoodsServ{
	
	public function __construct(){
	    
    }
    
	public function getProType($ptid){
	    $arr=array();
	    $arr[]=$ptid;
	    $list=array();
	    $sql="select * from #__product_type where ptid=? and status=1";
	    $dbAgent=DBAgent::getInstance();
	    $result=$dbAgent->querySingleRecord($sql,$arr);
	    $list[]=$result;
	    $i=0;
	    if($result['level']==5){
		    $i=4;
	    }else if($result['level']==4){
		    $i=3;
	    }else if($result['level']==3){
		    $i=2;
	    }else if($result['level']==2){
		    $i=1;
	    }else if($result['level']==1){
		    $i=0;
	    }
	    
	    if($i==0){
		    return $list;
	    }
	    for($a=0;$a<$i;$a++){
		    $arr1=array();
		 	$arr1[]=$result['parentid'];
		 	$sql="select * from #__product_type where ptid=? and status=1";
		 	$result=$dbAgent->querySingleRecord($sql,$arr1);   
		 	if($result!==false&&$result!==null){
			 	$list[]=$result;
		 	}
	    }
	    return $list;
	}
	public function getPropertyList($ptid){
		$arr=array();
		$arr[]=$ptid;
		$sql="select * from #__product_property where ptid=? and status=1";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
		
	}
	
	//获取品牌列表
	function getBrandList(){
		$sql="select * from #__brand where status=1 ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,array());
	}
	
	//获取产品列表 sort 1 综合排序  2 销量  3 价格上升  5 评论数
	function getProductList($brandid,$ptid,$lprice,$rprice,$sort,$index,$pagesize,$property,$level,$sid=null){
		$arr=array();
		$arr[]=1;
		$sql="select * from #__product where status=? ";
		if($brandid>0){
			$arr[]=$brandid;
			$sql.="and brand_id=? ";
		}
		if($ptid>0){
			$arr[]=$ptid;
			if($level==1){
				$sql.="and first_tid=? ";
			}else if($level==2){
				$sql.="and second_tid=? ";
			}else if($level==3){
				$sql.="and third_tid=? ";
			}else if($level==4){
				$sql.="and forth_tid=? ";
			}else if($level==5){
				$sql.="and fifth_tid=? ";
			}
		}
		if($sid>0){
            $arr[]=$sid;
            $sql.="and sid=? ";
        }
		if($lprice>=0){
			$sql.="and price >= $lprice ";
		}
        if($rprice>0){
            $sql.="and price <= $rprice ";
        }
        $propertylist=explode(',', $property);
        foreach($propertylist as $single){
	        if(!empty($single)){
	            $sql.="and pid in(select pid from labring_sku where FIND_IN_SET($single,properties))";
	        }
        }		if($sort==1){

		}else if($sort==2){
			$sql.="order by sale_num desc ";
		}else if($sort==3){
			$sql.="order by price desc ";
		}else if($sort==4){
			$sql.="order by comment_num desc ";
		}else if($sort==5){
			$sql.="order by price asc ";
		}
		$sql .= " limit $index,$pagesize";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
//        return $sql;
	}
	
	
		//获取产品列表 sort 1 综合排序  2 销量  3 价格上升  5 评论数
	function getProductListO($brandid,$ptid,$lprice,$rprice,$sort,$index,$pagesize,$property,$level,$key){
		$arr=array();
		$arr[]=1;
		$sql="select * from #__product where status=? ";
		if($brandid>0){
			$arr[]=$brandid;
			$sql.="and brand_id=? ";
		}
		if($ptid>0){
			$arr[]=$ptid;
			if($level==1){
				$sql.="and first_tid=? ";
			}else if($level==2){
				$sql.="and second_tid=? ";
			}else if($level==3){
				$sql.="and third_tid=? ";
			}else if($level==4){
				$sql.="and forth_tid=? ";
			}else if($level==5){
				$sql.="and fifth_tid=? ";
			}
		}
		if($lprice>=0){
			$sql.="and price >= $lprice ";
		}
        if($rprice>0){
            $sql.="and price <= $rprice ";
        }
         if($key!=""&&$key!=null){
	        $sql.=" and name like \"%$key%\" ";
        }
        $propertylist=explode(',', $property);
        foreach($propertylist as $single){
	        if(!empty($single)){
	            $sql.="and pid in(select pid from labring_sku where FIND_IN_SET($single,properties))";
	        }
        }		
        if($sort==1){

		}else if($sort==2){
			$sql.="order by sale_num desc ";
		}else if($sort==3){
			$sql.="order by price desc ";
		}else if($sort==4){
			$sql.="order by comment_num desc ";
		}else if($sort==5){
			$sql.="order by price asc ";
		}
		$sql .= " limit $index,$pagesize";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}
	
	
	//获取城市id
	function getCityId($name){
		$arr=array();
		$arr[]=$name;
		$sql="select * from #__area where name = ? ";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->querySingleRecord($sql,$arr);
	}	
	//获取产品类型	
	public function getProductType($ptid){
		$arr=array();
		$arr[]=$ptid;
		$sql="select * from #__product_type where ptid=? and status=1";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->querySingleRecord($sql,$arr);
	
	}
	
	//根据搜索内容获取产品
    public function getProductByinfo($info,$page,$pagesize,$ob,$brand){
        $dbAgent=DBAgent::getInstance();
        $index=($page-1)*$pagesize;
	    $sql="select distinct * from #__product  where 
	    (name like \"%$info%\" or EnglishName like \"$info%\" or alias like \"%$info%\" or size like \"%$info%\" or code like \"%$info%\" or goods_code like \"%$info%\" or CASnumber like \"%$info%\")  and status = 1";
	    $sqlall="select count(*) as ab from #__product  where (name like \"%$info%\" or EnglishName like \"$info%\" or alias like \"%$info%\" or size like \"%$info%\" or code like \"%$info%\" or goods_code like \"%$info%\" or CASnumber like \"%$info%\") and status = 1";
        if($brand>0){
	        $sql.=" and brand_id='$brand' ";
        }
        if($ob == 2){
            $sql .= " order by sale_num desc";
        }else if($ob == 3){
            $sql .= " order by price desc";
        }else if($ob == 5){
	        $sql .= " order by price asc"; 
        }else{
            $sql.= " order by pid desc";
        }
        /*echo $sql;*/
        $sql .= " limit $index,$pagesize";
       
        $result['data']=$dbAgent->queryRecords($sql,array());
        $count=$dbAgent->querySingleRecord($sqlall,$params=array());
        $result['count']=$count['ab'];
        return $result;
    }
    //获取产品详情
    function getProductDetail($pid){
	    $arr=array();
	    $arr[]=$pid;
	    $sql="select * from #__product where pid=? and status=1";
	    $dbAgent=DBAgent::getInstance();
	    return $result=$dbAgent->querySingleRecord($sql,$arr);
    }
    
    
    function getprotype15($pid){
	    $arr=array();
	    $arr[]=$pid;
	    $sql="select * from #__product where md5(pid)=? and status=1";
	    $dbAgent=DBAgent::getInstance();
	    $product=$dbAgent->querySingleRecord($sql,$arr);
	    $temp=array();
		if($product['first_tid']>0){
			$temp[]=GoodsServ::gettypedetail($product['first_tid']);
		}
		if($product['second_tid']>0){
			$temp[]=GoodsServ::gettypedetail($product['second_tid']);
		}
		if($product['third_tid']>0){
			$temp[]=GoodsServ::gettypedetail($product['third_tid']);
		}
		if($product['forth_tid']>0){
			$temp[]=GoodsServ::gettypedetail($product['forth_tid']);
		}
		if($product['fifth_tid']>0){
			$temp[]=GoodsServ::gettypedetail($product['fifth_tid']);
		}
		return $temp;
		
    }
    
    function gettypedetail($ptid){
	    $arr=array();
	    $arr[]=$ptid;
	    $sql="select * from #__product_type where ptid = ? and status = 1 ";
	    $dbAgent=DBAgent::getInstance();
	    return $type=$dbAgent->querySingleRecord($sql,$arr);
    }
    
    //获取评论列表
    function getProductComment($pid){
	    $arr=array();
	    $arr[]=$pid;
	    $sql="select a.*,b.face,b.nickname from #__product_comment a join #__member b on a.mid=b.mid where MD5(a.pid)=? and a.status=1 and b.status=1";
	    $dbAgent=DBAgent::getInstance();
	    return $result=$dbAgent->queryRecords($sql,$arr);
    }
    
    //获取省的信息
    function getProvince(){
	    $sql="select * from #__area where parent_id= 0 and category= 1 ";
	    $dbAgent=DBAgent::getInstance();
	    return $result=$dbAgent->queryRecords($sql,array());
    }
    
    function getCityTown($parent_id){
	   $arr=array();
	   $arr[]=$parent_id;
	   $sql="select * from #__area where parent_id= ? ";
	   $dbAgent=DBAgent::getInstance();
	   return $result=$dbAgent->queryRecords($sql,$arr);
    }
    
	function getSearchType($key){
		$sql="select * from #__product_type where name like \"%$key%\" and level<>1 and level<>2 and status=1";
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,array());
	}
	
	//根据关键词获取提示
	function getSearchHint($info,$type){
		$sql="";
		if($type==1){
			$sql="select pid,name from #__product where (name like \"$info%\" or EnglishName like \"%$info%\" or alias like \"%$info%\" or size like \"%$info%\" or code like \"%$info%\" or goods_code like \"%$info%\" or CASnumber like \"%$info%\") and status=1 limit 5";
		}else if($type==2){
			$sql="select lab_id,name from #__lab where name like '%$key%' and status=1 limit 5";
		}
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,array());
	}
	//根据拼音适配关键词
	function getSearchHintPin($key,$type){
		$sql="";
		if($type==1){
			$sql="select pid,name from #__product where pinyin like '$key%' and status=1 limit 5";
		}else if($type==2){
			$sql="select lab_id,name from #__lab where pinyin like '$key%' and status=1 limit 5";
		}
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,array());
	}
	
	//获取产品列表产品个数
	function getProductListCount($brandid,$ptid,$lprice,$rprice,$property,$level,$key){
		$arr=array();
		$arr[]=1;
		$sql="select count(*) as num from #__product where status=? ";
		if($brandid>0){
			$arr[]=$brandid;
			$sql.="and brand_id=? ";
		}
		if($ptid>0){
			$arr[]=$ptid;
			if($level==1){
				$sql.="and first_tid=? ";
			}else if($level==2){
				$sql.="and second_tid=? ";
			}else if($level==3){
				$sql.="and third_tid=? ";
			}else if($level==4){
				$sql.="and forth_tid=? ";
			}else if($level==5){
				$sql.="and fifth_tid=? ";
			}
		}
		if($lprice>=0){
			$sql.="and price >= $lprice ";
		}
        if($rprice!=0){
            $sql.=" and price <= $rprice ";
        }
        if($key!=""&&$key!=null){
	        $sql.=" and name like \"%$key%\" ";
        }
        $propertylist=explode(',', $property);
        foreach($propertylist as $single){
	        if(!empty($single)){
	            $sql.="and pid in(select pid from labring_sku where FIND_IN_SET($single,properties))";
	        }
        }
		$dbAgent=DBAgent::getInstance();
		$result=$dbAgent->querySingleRecord($sql,$arr);
		return $result['num'];
	}
	
}	
?>