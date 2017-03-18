<?php
class VideoServ{
    //获取视频大类介绍
    public function GetvideoinfoBy($id){
        $table = "article";
        $columns = "*";
        $conditionColumns = array('id','type','status');
        $conditionVals = array($id,4,'1');
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->getSingleRecordFromTable($table,$columns,$conditionColumns,$conditionVals);
    }
    //获取视频列表
    public function GetvideoByid($id,$num,$page,$info=null){
        $a=($page-1)*$num;
        $sql="select * from labring_article where type=3";
        if(!empty($id)){
            $sql.=" and categoryId=$id";
        }
        if($info){
            $sql.=" and title like '%$info%'";
        }
        $sql.=" and status=1 order by id desc";
        $sql.=" limit $a,$num";
        $dbAgent = DBAgent::getInstance();
        $result=$dbAgent->queryRecords($sql,array());
//        foreach($result as $k=>$v){
//            $result[$k]['img']=$this->get_youku($v['images']);
//        }
        return $result;
    }
    public function GetVideoCount($info=null){
        $sql="select count(*) as num from labring_article where status=1 and type=3";
        if($info){
            $sql.=" and title like '%$info%'";
        }
        $dbAgent = DBAgent::getInstance();
        $result = $dbAgent->querySingleRecord($sql,array());
        if($result === false){
            return false;
        }
        return $result['num'];
    }
    //获取改种类视频数
    public function GetVideoCountbycate($id){
        $sql="select count(*) as num from labring_article where status=1 and type=3 and categoryId=$id";
        $dbAgent = DBAgent::getInstance();
        $result = $dbAgent->querySingleRecord($sql,array());
        if($result === false){
            return false;
        }
        return $result['num'];
    }


    public function GetonevideoByid($id){
        $dbAgent = DBAgent::getInstance();
        $table='article';
        $columns="*";
        $conditionColumns=array('id','status');
        $conditionVals=array($id,'1');
        $result=$dbAgent->getSingleRecordFromTable($table,$columns,$conditionColumns,$conditionVals,$hasPrefix=true);
        $result['img']=$this->get_youku($result['images']);
        return $result;
    }
    public function Addviewnum($id){
        $dbAgent = DBAgent::getInstance();
        $sql="update labring_article set view_num=view_num+1 where id=$id";
        if($dbAgent->query($sql,array())){
            return true;
        }else{
            return false;
        }
    }

    function get_youku($url) {
        $patt="/[A-Z]\w*/";
        preg_match($patt, $url, $matches);
        $cnt = count($matches);
        if ($cnt>0){
            $link = "http://play.youku.com/play/get.json?vid={$matches['0']}==&ct=10";
        }else{
            return false;
        }
        $ch=@curl_init($link);
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $cexecute=@curl_exec($ch);
        @curl_close($ch);


        if ($cexecute) {
            $result = json_decode($cexecute,true);
            if(!empty($result['data']['video'])){
                $json = $result['data']['video'];
                $data['img'] = $json['logo']; // 视频缩略图
                $data['title'] = $json['title']; //标题啦
                $data['url'] = $url;
                $data['seconds'] = $json['seconds'];
                return $data;
            }
        } else {
            return false;
        }

    }

    public function getVideoType(){
        $arr = array();
        $arr[] = 1;
        $sql = "select * from #__article_category where status = ?";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->queryRecords($sql,$arr);
    }
}


?>