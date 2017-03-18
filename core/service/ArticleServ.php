<?php
class ArticleServ{
	
	public function __construct(){
	    
    }
    
    //获取我的文章数量
	function getUserArticleCount($mid){
		$arr=array();
		$arr[]=$mid;
		$sql= "select count(*) as count from #__article where type = 1 and mid = ? and status = 1 ";
		$dbAgent=DBAgent::getInstance();
		$result = $dbAgent->querySingleRecord($sql,$arr);
		if($result === false){
			return false;
		}
		return $result['count'];
	}
    
    //获取我的文章
    function getUserArticle($mid,$index,$size){
	    $arr=array();
		$arr[]=$mid;
		$sql= "select b.nickname as author, case when a.categoryId = '-1' then '其他' else c.name end as category,a.* from #__article a join #__member b on a. mid = b.mid left join #__article_category c on a.categoryId = c.id where a.type = 1 and a.mid = ? and a.status = 1 order by a.time desc limit  $index,$size ";
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->queryRecords($sql,$arr);
    }

    //获取我的文章(mobile)
    function getMyArticle($mid){
        $arr=array();
        $arr[]=$mid;
        $sql= "select b.nickname as author, case when a.categoryId = '-1' then '其他' else c.name end as category,a.* from #__article a join #__member b on a. mid = b.mid left join #__article_category c on a.categoryId = c.id where a.type = 1 and a.mid = ? and a.status = 1 order by a.time desc ";
        $dbAgent=DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }
    
    //获取个人中心最新动态数量
	function getUserDynamicCount($mids){//我以及我的好友的mid		
		$arr=array();
		$arr[]=$mids;
		$sql= "select count(*) as count from #__article where type = 1 and find_in_set(mid,?) and status = 1 ";
		$dbAgent=DBAgent::getInstance();
		$result = $dbAgent->querySingleRecord($sql,$arr);
		if($result === false){
			return false;
		}
		return $result['count'];
	}
    
    //分页获取个人中心最新动态
    function getUserDynamic($mids,$index,$size){//我以及我的好友的mid
	    $arr=array();
		$arr[]=$mids;
		$sql= "select b.nickname as author,case when a.categoryId = '-1' then '其他' else c.name end as category,a.* from #__article a join #__member b on a.mid = b.mid left join #__article_category c on a.categoryId = c.id where a.type = 1 and find_in_set(a.mid,?) and a.status = 1 order by a.time desc limit $index,$size ";
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->queryRecords($sql,$arr);
    }
    
	//根据id获取文章
	public function getArticleById($type,$aid){
		$table = "article";
		$columns = "*";
		$conditionColumns = array('id','type','status');
		$conditionVals = array($aid,$type,'1');
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->getSingleRecordFromTable($table,$columns,$conditionColumns,$conditionVals);
	}
	
	//根据mid获取文章
	function getArticleByMid($type,$mid,$order='time desc',$num=4){
		$arr=array();
		$arr[]=$mid;
		$arr[]=$type;
		$sql="select GROUP_CONCAT(c.name) as category,a.* from #__article a join #__member b on a.mid = b.mid join #__article_category c on a.categoryId = c.id where a.mid = ? and a.type = ? and a.status = 1 group by a.id order by $order limit 4";
		$dbAgent=DBAgent::getInstance();
		return $dbAgent->queryRecords($sql,$arr);
	}
	
	//获取所有文章分类
	function getArticleCategory($num=null){
		$arr=array();
		$arr[]=1;
		$sql="select * from #__article_category where status= ? and pId = 0";
        if($num){
            $sql.=" order by id desc limit $num";
        }
		$dbAgent=DBAgent::getInstance();
		return $result=$dbAgent->queryRecords($sql,$arr);
	}

    //获取所有文章数量
    function getAllArticleCount($type,$info=null){
        $sql="select count(*) as num from labring_article where type=$type and status=1";
        if($info){
            $sql.=" and like '%$info%'";
        }
        $dbAgent=DBAgent::getInstance();
        $result=$dbAgent->querySingleRecord($sql,array());
        return $result['num'];
    }

    //获取我的所有数量
    function getCountbycategoryid($type,$categoryid){
        $table = "article";
        $conditionColumns = array('type','categoryId','status');
        $conditionVals = array($type,$categoryid,'1');
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->getRecordCountsFromTable($table,$conditionColumns,$conditionVals);
    }

	//发布文章
	function publishArticle($mid,$categoryId,$title,$intro,$content,$images,$video){
		$table = "article";
        if(empty($video)){
            $insertColumns = array("type",'status','time','mid','categoryId','title','intro','content','images','city');
            $insertVals = array('1','1',time(),$mid,$categoryId,$title,$intro,$content,$images,time());
        }else{
            $insertColumns = array("type",'status','time','mid','categoryId','title','intro','content','images','city','video_url');
            $insertVals = array('1','1',time(),$mid,$categoryId,$title,$intro,$content,$images,time(),$video);
        }
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
	}
	
	//编辑文章
	function updateArticleById($id,$type,$categoryId,$title,$intro,$content,$images){
		$table = "article";
		$updateColumns = array('time','categoryId','title','intro','content','images');
		$updateVals = array(time(),$categoryId,$title,$intro,$content,$images);
		$conditionColumns = array('id','type');
		$conditionVals = array($id,$type);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}
	
	//删除文章
	function deleteArticleById($id,$type){
		$table = "article";
		$updateColumns = array('status');
		$updateVals = array('0');
		$conditionColumns = array('id','status','type');
		$conditionVals = array($id,1,$type);
		$dbAgent = DBAgent::getInstance();
		return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
	}

	//根据条件获取文章
    function getArticleByView($num,$order,$type=1,$info=null){
        $DBAgent = DBAgent::getInstance();
        $sql="select * from labring_article where type=$type and status=1 order by $order limit $num";
        $result=$DBAgent->queryRecords($sql,array());
        return $result;
    }
    //获取此篇文章分类
    public function getThisCategroy($categoryid){
        $dbAgent = DBAgent::getInstance();
        $sql="select name from labring_article_category where id=$categoryid and status=1";
        $name=$dbAgent->queryRecords($sql,array());
        if(empty($name)) {
            return '实验圈发布';
        }else{
            return $name['0']['name'];
        }
    }
    //根据ID获取文章和有关作者信息
    public function GetAriticle_Writer($id){
        $dbAgent = DBAgent::getInstance();
        $sql="select labring_member.nickname as wname,labring_member.face,labring_article.* from labring_article left join labring_member on labring_article.mid=labring_member.mid where labring_article.id=$id";
        $result=$dbAgent->queryRecords($sql,array());
        if(empty($result)){
            return false;
        }else {
            return $result['0'];
        }
    }
    //获取相关文章，既是一类文章
    public function GetAriticleByCategoryid($id=0,$category,$type=1,$num=6,$order='time'){
        $dbAgent = DBAgent::getInstance();
//        if(empty($order)){
//            $order='time desc';
//        }
        $sql="select * from labring_article where type=$type and categoryId=$category and id!=$id and status = 1 order by $order limit $num";
        $result=$dbAgent->queryRecords($sql,array());
        return $result;
    }
    //获取文章和作者num
    public function GetAriticleBynum($num,$page=1,$categoryid,$type=1,$recommend=0,$info=null){
        $a=($page-1)*$num;
        $dbAgent = DBAgent::getInstance();
        $sql="select labring_article_category.name as cname,labring_member.nickname as wname,labring_member.nickname,labring_member.face,labring_article.* from labring_article left join labring_member on labring_article.mid=labring_member.mid left join labring_article_category on labring_article_category.id=labring_article.categoryId where labring_article.type=$type and labring_article.status=1   and labring_article.recommend = $recommend";
        if($categoryid!=0){
            /*echo "<div>$categoryid.123</div>";*/
            $sql.=" and labring_article.categoryId=$categoryid";
        }
       /* echo "<div style='display:none'>$categoryid.$sql.123</div>";*/
        if($info){
            $sql.=" and labring_article.title like '%$info%'";
        }
        $sql.=" order by labring_article.time desc limit $a,$num";
       /* echo $sql;*/
        $result=$dbAgent->queryRecords($sql,array());
        return $result;
    }

    //手机
    public function getMobileArticle($start,$end){
        $sql = "SELECT
                        a.*, c. NAME AS cname
                    FROM
                        labring_article a
                    JOIN labring_article_category c ON a.categoryId = c.id
                    WHERE
                        a.type = 1
                    AND a.recommend = 1
                    AND a.status = 1
                    AND a.video_url = ''
                    ORDER BY a.time DESC";
        $sql.= " limit ".$start.",".$end;
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,array());
    }

    //获取文章
    public function getArticleByCategoryId($num,$page,$categoryId){
        $a=($page-1)*$num;
        $sql = "SELECT a.*,ac.name as cname FROM labring_article a JOIN labring_article_category ac ON a.categoryId = ac.id JOIN (SELECT GROUP_CONCAT(c.id) AS tids FROM labring_article_category c WHERE";
        if($categoryId == 0){
            $sql.= " c.status = 1";
        }else{
            $sql.= " c.pId = $categoryId OR c.id = $categoryId";
        }
        $sql.=" )labids ON FIND_IN_SET(a.categoryId, labids.tids) WHERE a. STATUS = 1 AND a.recommend = 1 AND a.type = 1 ORDER BY a.time DESC";
        $sql.=" limit $a,$num";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,array());
    }

    public function getManagerArticle($info){
        $sql = "";
        if(empty($info)){
            $sql = "SELECT a.*, c. NAME AS cname,m. nickname AS mname FROM labring_article a LEFT JOIN labring_article_category c ON a.categoryId = c.id LEFT JOIN labring_member m ON a.mid = m.mid WHERE a. STATUS = 1 AND a.type = 1 ";
        }else{
            $sql = "SELECT a.*, c. NAME AS cname,m. nickname AS mname FROM labring_article a LEFT JOIN labring_article_category c ON a.categoryId = c.id LEFT JOIN labring_member m ON a.mid = m.mid WHERE a. STATUS = 1 AND a.type = 1 AND a.title like \"%$info%\" ";
        }
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,array());
    }

    public function getPageManagerArticle($info,$index,$pagesize,$ob){
        $sql = "";
        if($ob == 1){
            $ob = "view_num";
        }else{
            $ob = "time";
        }
        if(empty($info)){
            $sql = "SELECT a.*, c. NAME AS cname,m. nickname AS mname FROM labring_article a LEFT JOIN labring_article_category c ON a.categoryId = c.id LEFT JOIN labring_member m ON a.mid = m.mid WHERE a. STATUS = 1 AND a.type = 1 ORDER BY a.$ob DESC";
        }else{
            $sql = "SELECT a.*, c. NAME AS cname,m. nickname AS mname FROM labring_article a LEFT JOIN labring_article_category c ON a.categoryId = c.id LEFT JOIN labring_member m ON a.mid = m.mid WHERE a. STATUS = 1 AND a.type = 1 AND a.title like \"%$info%\" ORDER BY a.$ob DESC";
        }
        $sql .= " limit $index,$pagesize";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,array());
    }



    public function getMemberByMid($mid){
        $sql = "select m.nickname from labring_member m where m.mid = $mid and m.status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,array());
    }

    public function getCategoryByCid($cid){
        $sql = "select c.name from labring_article_category c where c.id = $cid and c.status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,array());
    }

    function getCoopArticle(){
        $arr = array();
        $arr[] = 1;
        $sql = "select * from #__article where type = 2 and status = ?";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    function getActArticle(){
        $arr = array();
        $arr[] = 1;
        $sql = "select * from #__article where type = 5 and status = ?";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }
    //阅读量加一
    public function Addviewnum($id){
        $dbAgent = DBAgent::getInstance();
        $sql="update labring_article set view_num=view_num+1 where id=$id";
        if($dbAgent->query($sql,array())){
            return true;
        }else{
            return false;
        }
    }

    //获取视频文章
    public function getvideo($num,$order){
            $DBAgent = DBAgent::getInstance();
            $sql="select * from labring_article where type=4 and status=1 order by $order limit $num";
            $result=$DBAgent->queryRecords($sql,array());
            return $result;
    }

    /*根据id获取该文章评论*/
    public function getCommentById($id,$num,$page=1){
        $a=($page-1)*$num;
        $arr = array();
        $arr[] = $id;
        $sql="select * from #__article_comment where pid = ? and status = 1 ORDER BY time desc limit $a,$num";
        $DBAgent=DBAgent::getInstance();
        return $DBAgent->queryRecords($sql,$arr);
    }

    /*根据评论的用户id 获取该用户*/
    public function getUserById($uid){
        $arr = array();
        $arr[] = $uid;
        $sql="select u.mid,u.name,u.nickname,u.mobile from #__member u  where mid = ? and status = 1";
        $DBAgent=DBAgent::getInstance();
        return $DBAgent->querySingleRecord($sql,$arr);
    }
    public function Checkcircle($mid,$cid){
        $DBAgent=DBAgent::getInstance();
        $sql = "select * from labring_circle_member where mid=$mid and circle_id=$cid";
        $check = $DBAgent->querySingleRecord($sql, array());
        if ($check != null && $check['status'] == '1') {
            return 0;
        }else {
            return 1;
        }
    }
    //根据文章id获得评论数
    public function GetCommentnumByid($id){
        $DBAgent = DBAgent::getInstance();
        $sql="select count(*) as a from labring_article_comment where pid=$id and status=1";
        $result=$DBAgent->queryRecords($sql,array());
        return $result['0']['a'];
    }

    //获取文章分类
    public function getArticleType(){
        $sql = "select * from labring_article_category where status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,array());
    }

    public function getArticleTypeById($id){
        $sql = "select * from labring_article_category where id=$id and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,array());
    }

    public function getArticlePTypeByPId($pid){
        $sql = "select * from labring_article_category where id=$pid and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,array());
    }

    //更新
    function updateArticleTypeById($data){
        $table = "article_category";
        $updateColumns = array('name');
        $updateVals = array($data['name']);
        $conditionColumns = array('id','status');
        $conditionVals = array($data['id'],1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }

    /*添加分类*/
    public function insertArticleType($data){
        $table = "article_category";
        $insertColumns = array('pid','name','status');
        $insertVals = array($data['id'],$data['name'],1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }

    //删除
    function deleteArticleTypeById($id){
        $table = "article_category";
        $updateColumns = array('status');
        $updateVals = array(0);
        $conditionColumns = array('id','status');
        $conditionVals = array($id,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->updateRecords($table,$updateColumns,$updateVals,$conditionColumns,$conditionVals);
    }

    public function getSecondArticleTypeById($id){
        $arr = array();
        $arr[] = $id;
        $sql = "select * from #__article_category where pId = ? and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }

    /*手机*/
    public function getLikesNumById($aid){
        $sql = "select count(*) a from #__article_likes where aid = $aid and status = 1";
        $dbAgent = DBAgent::getInstance();
        $result = $dbAgent->queryRecords($sql,array());
        return $result[0]['a'];
    }

    /*手机*/
    public function getLikesInfo($aid,$mid){
        $sql = "select * from #__article_likes where aid = $aid and mid = $mid and status = 1";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,array());
    }

    /*文章点赞  手机*/
    public function articleLikes($aid,$mid){
        $table = "article_likes";
        $insertColumns = array('mid','aid','time','status');
        $insertVals = array($mid,$aid,time(),1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }


}
?>