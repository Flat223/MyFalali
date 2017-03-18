<?php
class SupplyBuyReleaseServ {
	public function __construct(){
		
	}
    /*发布共享实验室*/
   public function insertSupplyInfo($supply){
       $table = "release";
       $insertColumns = array('status','is_check','time');
       $insertVals = array(1,0,time());
       if(isset($supply['type'])){
           $insertColumns[] = 'type';
           $insertVals[] = $supply['type'];
       }
       if(isset($supply['trade'])){
           $insertColumns[] = 'trade';
           $insertVals[] = $supply['trade'];
       }
       if(isset($supply['title'])){
           $insertColumns[] = 'title';
           $insertVals[] = $supply['title'];
       }
       if(isset($supply['linkman'])){
           $insertColumns[] = 'linkman';
           $insertVals[] = $supply['linkman'];
       }
       if(isset($supply['phone'])){
           $insertColumns[] = 'phone';
           $insertVals[] = $supply['phone'];
       }
       if(isset($supply['mail'])){
           $insertColumns[] = 'mail';
           $insertVals[] = $supply['mail'];
       }
       if(isset($supply['endDate'])){
           $insertColumns[] = 'endDate';
           $insertVals[] = $supply['endDate'];
       }
       if(isset($supply['category'])){
           $insertColumns[] = 'category';
           $insertVals[] = $supply['category'];
       }
       if(isset($supply['description'])){
           $insertColumns[] = 'description';
           $insertVals[] = $supply['description'];
       }
       $dbAgent = DBAgent::getInstance();
       return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }

    //给用户发送审核通过消息
    public function sendUserMessage($mid){
        $table = "message";
        $insertColumns = array('from_id',"mid","title","content","time","is_read","status");
        $insertVals = array(0,$mid,"发布通知","您的发布信息已通过审核！",time(),0,1);
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->insertRecord($table,$insertColumns,$insertVals);
    }

    public function getInfoById($id){
        $arr = array();
        $arr[] = $id;
        $sql = "select * from #__release where id = ? and status = 1 ";
        $dbAgent = DBAgent::getInstance();
        return $dbAgent->querySingleRecord($sql,$arr);
    }
}
?>