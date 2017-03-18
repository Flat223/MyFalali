<?php
class ArchiveServ{

    private $DBAgent;
	private $CommonFunc;

    //构造函数->默认载入函数
    public function __construct(){
        $this->DBAgent = DBAgent::getInstance();
		$this->CommonFunc = new CommonFunc();
    }

    
	public function HandleArchivePost($type,$id)
	{
	    $permission = array("delete");
	    if(in_array($type, $permission))
	    { 
		    if($type == "delete")
		    {
				$updateColumns = array("id","rank");
				$updateVals = array($id,"-1");

				$conditionColumns = array("id");
				$conditionVals = array($id);
		    }

			$HandleAdmin= $this->DBAgent->updateRecords("post",$updateColumns,$updateVals,$conditionColumns,$conditionVals);
			if(!$HandleAdmin)
			{
				$result['ret'] = 0;
				$result['msg'] = "更新文档时失败";
			}
			else
			{
				$result['ret'] = 1;
				$result['msg'] = "更新文档成功";
			} 
	    }
	    else
	    {
			$result['ret'] = 0;
			$result['msg'] = "参数错误";
	    }
	    return $result;
	}
 
	function ArchiveGetPost($aid,$click,$cache=false)
    {
	    $results =$this->DBAgent->querySingleRecord("SELECT post.*,user.name as nickname,posttype.type as typename,posttype.tag as typetag
			    FROM #__post post
			    LEFT JOIN #__admin user ON user.aid = post.mid
			    LEFT JOIN #__posttype posttype ON posttype.id = post.typeid
			    WHERE post.id='$aid';" );
		if(!$results){
			$result['ret']=0;
			$result['msg']="无此内容";
		}
		else
		{
			$result['ret']=1;
			$result['msg']="获取成功";
			$results['litpic'] = empty($results['litpic'])?"http://".$_SERVER['HTTP_HOST'].'/images/defaultlitpic.jpg':$results['litpic'];
			$results['writer'] = empty($results['nickname'])?$results['writer']:$results['nickname']; 
			$results['body'] = str_replace(PHP_EOL, '', $results['body']);   
			$result['data'] = $results; 
		}

	    if($click)
	    {
		    $this->DBAgent->query("UPDATE #__post SET click=click+1 WHERE id ='$aid'; " );
	    }
	    
        return $result;
    }

    /*
    * 提交文档
    */
    function ArchiveAddPost($data)
    {
        $ImageAgent = new ImageAgent();
        $videoposter = empty($data["videoposter"])?"":trim($data["videoposter"]);//视频封面
        $poster_result = $ImageAgent->ConverHtml5Image($videoposter); 
		$videoposter = (intval($poster_result['ret']) == 1)?$poster_result['relative']:"";
        $title =  empty($data['title'])?"":trim($data['title']);
        $description = empty($data["description"])?"":trim($data["description"]);
        $litpic = empty($data['litpic'])?"":trim($data['litpic']);
        $cover =  empty($data['cover'])?"":trim($data['cover']);
        $type = empty($data['posttype'])?"":trim($data['posttype']);
        $writer =  empty($data['writer'])?"":trim($data['writer']);
        $mid = empty($data['mid'])?0:trim($data['mid']);
        $data["body"] = str_replace(PHP_EOL, '',$data["body"]); 
        $body =$data["body"];
        $rank = empty($data["rank"])?0:intval($data["rank"]);
        $tag = empty($data["tag"])?"":trim($data["tag"]);
        $stick = empty($data["stick"])?0:intval($data["stick"]);
        $flag = empty($data["flag"])?"":trim($data["flag"]);
        $shopname = empty($data["shopname"])?"":trim($data["shopname"]);
        $location = empty($data["location"])?"":trim($data["location"]);
        $lat = empty($data["lat"])?"":trim($data["lat"]);
        $lon = empty($data["lon"])?"":trim($data["lon"]);
        $privace = empty($data["privace"])?1:intval($data["privace"]);
        $photoalbum = empty($data["photoalbum"])?"":trim($data["photoalbum"]);
        $material = empty($data["material"])?"":trim($data["material"]);
        $cooking = empty($data["cooking"])?"":trim($data["cooking"]);
        $cron = empty($data["cron"])?"":trim($data["cron"]);
        $jumpurl = empty($data["jumpurl"])?"":trim($data["jumpurl"]);
        $followid = empty($data["followid"])?0:intval($data["followid"]);
        $activityjoinable = empty($data["activityjoinable"])?0:intval($data["activityjoinable"]);
        $commentable = empty($data["commentable"])?(isset($data["commentable"])?0:1):intval($data["commentable"]); 
        $description = empty($description)?$this->CommonFunc->cn_substr_utf8(strip_tags($body),200, $start=0):$description;
        $originalurl = empty($data["original"])?"":trim($data["original"]);
		
        $privace = empty($data["privace"])?1:intval($data["privace"]);
        $_tag_arr = explode(",",$tag);
        foreach($_tag_arr as $_tag)
        {
	        $this->AddTag($_tag,1,1,$type);
        } 
        $photoalbum = empty($data["photoalbum"])?"":trim($data["photoalbum"]);
        $creatdate = time();
        if(!empty($cron))
        {
            $pubdate = $creatdate =$sort= $cron;
        }
        else
        {
	        $pubdate = $sort= $creatdate;
        }
        $sort = $sort + $stick;
        $ip = $this->CommonFunc->GetRemoteAddress();
        $_flag_arr = explode(",", $flag);
        $jumpurl = in_array("g", $_flag_arr)?trim($jumpurl):"";
        if(!empty($litpic) && !in_array("d", $_flag_arr)){
	        $_flag_arr[] = 'd';
        }
        else if(!empty($videoposter) && !in_array("e", $_flag_arr))
        {
	        $_flag_arr[] = 'e';
        }
        $_flag = implode(",", $_flag_arr);
        $flag = ($_flag != "")?$_flag:"f";
        if(!empty($body))
        {
	        $sendable = true;
	        if(!empty($originalurl))
	        {
		        $rank = 1;
				$conditionColumns = array("originalurl");
				$conditionVals = array($originalurl);
				$CheckPostSend = $this->DBAgent->getRecordsFromTable("post","*",$conditionColumns,$conditionVals,1,10);
		        if($CheckPostSend)
		        {
			        $sendable = false;
		        }
	        }
	        if($sendable)
	        {

				$conditionColumns = array("title","body");
				$conditionVals = array($title,$body);
				$CheckPostRegist = $this->DBAgent->getRecordsFromTable("post","*",$conditionColumns,$conditionVals,1,10);

				if(!$CheckPostRegist)
				{
					$insertColumns = array("title"," litpic","description","cover","videoposter","photoalbum","typeid","mid","followid","writer","body","ip","pubdate","creatdate","sort","rank","privace","flag","tag","shopname","location","lat","lon","commentable","material","cooking","jumpurl","activityjoinable","originalurl");
					$insertVals = array($title,$litpic,$description,$cover,$videoposter,$photoalbum,$type,$mid,$followid,$writer,$body,$ip,$pubdate,$creatdate,$sort,$rank,$privace,$flag,$tag,$shopname,$location,$lat,$lon,$commentable,$material,$cooking,$jumpurl,$activityjoinable,$originalurl);
					$callback = $this->DBAgent->insertRecord('post',$insertColumns,$insertVals); 

					if($callback){ 
						$result['ret'] = 1;
						$result['msg'] = "发布成功"; 
						
					}
					else
					{
						$result['ret'] = 0;
						$result['msg'] = "发布失败:".mysql_error();
					}
				}
				else
				{
				    $result['ret'] = 0;
				    $result['msg'] = "发布失败，已存在该文档";
			        $result['id'] = $CheckPostRegist[0]['id'];
				}
	        }
	        else
	        {
		        $result['ret'] = 0;
			    $result['msg'] = "已发布过";
			    $result['id'] = $__result__['id'];
	        }
	    }
	    else
	    {
		    $result['ret'] = 0;
		    $result['msg'] = "至少写点吧";
	    }
         
        return $result;
    }

	function AddTag($tag,$count,$close,$typeid)
    {
	    if($tag!="")
	    { 
		    $typeid = intval($typeid);
			$conditionColumns = array("name","typeid");
			$conditionVals = array($tag,$typeid);
			$CheckTagRegist = $this->DBAgent->getRecordsFromTable("tag","*",$conditionColumns,$conditionVals,1,10); 
		    if(!$CheckTagRegist){
			    $count = empty($count) ? 0 : 1; 
				$insertColumns = array("name","typeid","times");
				$insertVals = array("$tag","$typeid","$count");
				$callback = $this->DBAgent->insertRecord('tag',$insertColumns,$insertVals);
		        if($callback){ 
			        $result['ret'] = 1; 
			        $result['msg'] = "添加成功";
		        }
		        else
		        {
		            $result['ret'] = 0;
			        $result['msg'] = "添加失败";
		        }
		    }
		    else
		    {
			    if(!empty($count))
			    {
					$updateColumns = array("times");
					$updateVals = array("times+1");

					$conditionColumns = array("name","typeid");
					$conditionVals = array($tag,$typeid);

					$HandleTag= $this->DBAgent->updateRecords("posttype",$updateColumns,$updateVals,$conditionColumns,$conditionVals);
  
			        if($HandleTag){ 
				        $result['ret'] = 1;
				        $result['msg'] = "记录成功";
			        }
			        else
			        {
			            $result['ret'] = 0;
				        $result['msg'] = "记录失败";
			        }
			    }
			    else
			    { 
				    $result['ret']=0;
				    $result['msg']="已存在";
			    }
		    }
	    }
	    else
	    {
		    $result['ret']=0;
			$result['msg']="空标签";
	    }
        return $result;
    }

	public function HandleArchiveType($type,$id)
	{
	    $permission = array("delete");
	    if(in_array($type, $permission))
	    { 
		    if($type == "delete")
		    {
				$updateColumns = array("id","status");
				$updateVals = array($id,"0");

				$conditionColumns = array("id","status");
				$conditionVals = array($id,"1");
		    }

			$HandleAdmin= $this->DBAgent->updateRecords("posttype",$updateColumns,$updateVals,$conditionColumns,$conditionVals);
			if(!$HandleAdmin)
			{
				$result['ret'] = 0;
				$result['msg'] = "更新类型时失败";
			}
			else
			{
				$result['ret'] = 1;
				$result['msg'] = "更新类型成功";
			} 
	    }
	    else
	    {
			$result['ret'] = 0;
			$result['msg'] = "参数错误";
	    }
	    return $result;
	}

	public function AddArchiveType($typename,$typetag,$typetemplet,$typekeywords,$typedescription,$typestatus,$typeicon)
	{ 
	    $typeflag = "";
		$conditionColumns = array("type","tag","templet","keywords","description","status","flag","icon");
		$conditionVals = array("$typename","$typetag","$typetemplet","$typekeywords","$typedescription","$typestatus","$typeflag","$typeicon");
	    $CheckAdminRegist = $this->DBAgent->getRecordsFromTable("posttype","*",$conditionColumns,$conditionVals,1,10);
		if(!$CheckAdminRegist)
		{
			$insertColumns = array("type","tag","templet","keywords","description","status","flag","icon");
			$insertVals = array("$typename","$typetag","$typetemplet","$typekeywords","$typedescription","$typestatus","$typeflag","$typeicon");
			$callback = $this->DBAgent->insertRecord('posttype',$insertColumns,$insertVals);
			if(!$callback)
			{
				$result['ret'] = 0;
				$result['msg'] = "创建类型失败";
			}
			else
			{
				$result['ret'] = 1;
				$result['msg'] = "成功添加类型";
			}
		}
		else
		{
		    $result['ret'] = 0;
		    $result['msg'] = "该类型已注册";
		}
		return $result;
	}
	
	public function HandleArchiveFlag($type,$id)
	{
	    $permission = array("delete");
	    if(in_array($type, $permission))
	    { 
		    if($type == "delete")
		    {
				$updateColumns = array("id","status");
				$updateVals = array($id,"0");

				$conditionColumns = array("id","status");
				$conditionVals = array($id,"1");
		    }

			$HandleAdmin= $this->DBAgent->updateRecords("flag",$updateColumns,$updateVals,$conditionColumns,$conditionVals);
			if(!$HandleAdmin)
			{
				$result['ret'] = 0;
				$result['msg'] = "更新标签时失败";
			}
			else
			{
				$result['ret'] = 1;
				$result['msg'] = "更新标签成功";
			} 
	    }
	    else
	    {
			$result['ret'] = 0;
			$result['msg'] = "参数错误";
	    }
	    return $result;
	}

	public function AddArchiveFlag($description,$flag)
	{
		$conditionColumns = array("description","flag","status");
		$conditionVals = array($description,$flag,"1");
	    $CheckAdminRegist = $this->DBAgent->getRecordsFromTable("flag","*",$conditionColumns,$conditionVals,1,10);
		if(!$CheckAdminRegist)
		{
			$insertColumns = array('description','flag');
			$insertVals = array($description,$flag);
			$callback = $this->DBAgent->insertRecord('flag',$insertColumns,$insertVals);
			if(!$callback)
			{
				$result['ret'] = 0;
				$result['msg'] = "创建标签失败";
			}
			else
			{
				$result['ret'] = 1;
				$result['msg'] = "成功添加标签";
			}
		}
		else
		{
		    $result['ret'] = 0;
		    $result['msg'] = "该标签已注册";
		}
		return $result;
	}
}
?>