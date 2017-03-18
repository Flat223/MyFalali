<?php 
class FileUtil{
	
	//构造函数->默认载入函数
    public function __construct(){
    }
    
    public static function loadHtml($html,$params=array()){
	    $file = $_SERVER['DOCUMENT_ROOT'].'/html/'.Theme.'/'.$html;
	    if(file_exists($file)){
		    include($file);
	    }else{
		    include($_SERVER['DOCUMENT_ROOT'].'/html/'.Theme.'/404.html');
	    }
    }
    
    public static function loadHtml2($path,$params=array()){
	    include($path);
    }
        
    public static function loadServerErrHtml(){
	    $theme = Theme;
		if(isset($GLOBALS['theme'])){
			$theme = $GLOBALS['theme'];
		}
	    include($_SERVER['DOCUMENT_ROOT'].'/html/'.$theme.'/500.html');
    }
    
    public static function load404Html(){
	    $theme = Theme;
		if(isset($GLOBALS['theme'])){
			$theme = $GLOBALS['theme'];
		}
	    include($_SERVER['DOCUMENT_ROOT'].'/html/'.$theme.'/404.html');
    }
    
    public static function load0000Html($params=array()){
	     $theme = Theme;
		if(isset($GLOBALS['theme'])){
			$theme = $GLOBALS['theme'];
		}
	    include($_SERVER['DOCUMENT_ROOT'].'/html/'.$theme.'/0000.html');
    }
    
    public static function requireBean($filename){
	    $file =  $_SERVER['DOCUMENT_ROOT'].'/core/bean/'.$filename.'.php';
	    if(file_exists($file)){
		    require_once($file);
	    }
    }
    
    public static function requireXml($filename){
	    $file = $_SERVER['DOCUMENT_ROOT'].'/core/xml/'.$filename.'.xml';
	    if(file_exists($file)){
		    require_once($file);
	    }
    }
    
    public static function requireService($filenames){
	    $type = gettype($filenames);
	    if($type == 'array'){
		    foreach($filenames as $filename){
			    $file = $_SERVER['DOCUMENT_ROOT'].'/core/service/'.$filename.'.php';
			    if(file_exists($file)){
				    require_once($file);
			    }
		    }
	    }else if($type == 'string'){
		    $file = $_SERVER['DOCUMENT_ROOT'].'/core/service/'.$filenames.'.php';
		    if(file_exists($file)){
			    require_once($file);
		    }
	    }
    }
    
    
    
    
    function loadMobileHtml($html,$params=array()){
	    $file = $_SERVER['DOCUMENT_ROOT'].'/html/mobile/'.$html;
	    if(file_exists($file)){
		    include($file);
	    }else{
		    include($_SERVER['DOCUMENT_ROOT'].'/html/mobile/404.html');
	    } 
    }

	/*
	* PUBLIC
	* 上传图片接口
	* UPLOAD_IMAGE($type,$tempFile)
	*/
	function UPLOAD_IMAGE($type,$utype,$name,$mid,$water=true,$filename,$crop="")
	{
		$ApiAgent = new ImageAgent();
		set_time_limit(0);//该页最久执行时间0表示没有限制
		$dev = empty($_REQUEST['dev'])?"":trim($_REQUEST['dev']); 
		//替换日期事件
        $t = time();
        $d = explode('-', date("Y-y-m-d-H-i-s"));
		$fileParts = pathinfo($_FILES[$name]['name']);
		//得到上传的临时文件流
		$tempFile = $_FILES[$name]['tmp_name'];  
		//得到文件原名
		$fileName = @iconv("GB18030","UTF-8//IGNORE",$_FILES[$name]["name"]);
		$fileNameArr = explode(".", $fileName);
		unset($fileNameArr[(count($fileNameArr)-1)]);
		$fileName = implode("-",$fileNameArr);
		$crop = empty($crop)?$crop:json_decode($crop);
		if($type == "food")
		{
			$fileName = !empty($fileName)?$fileName:md5(time());
		}
		else
		{
			$fileName = !empty($filename)?$filename:md5($fileName.time());
		}
        $filepath = $d[0].$d[2].$d[3];
		//允许的图片后缀
		$fileTypes = array('jpg','jpeg','gif','png','JPG','JPEG','GIF','PNG');
		//允许的视频后缀
		$fileTypes_video = array('mp4','avi','flv','mpeg4','m4v','mov','MP4','AVI','FLV','MPEG4','M4V','MOV');
		if($type == 'upload'){
			if(in_array($fileParts['extension'], $fileTypes))
			{
				$path = dirname(__FILE__)."/../../upload/image/".$filepath."/";
				$realpath = "../upload/image/".$filepath.'/';
			}
			else if(in_array($fileParts['extension'], $fileTypes_video))
			{
				$path = dirname(__FILE__)."/../../upload/video/".$filepath."/";
				$realpath = "../upload/video/".$filepath.'/';
			}
		    else
		    {
			    $path = dirname(__FILE__)."/../../upload/file/".$filepath."/";
				$realpath = "../upload/file/".$filepath.'/';
		    }
		} 
		if($utype == 'file' && isset($type)){
			if (!empty($_FILES) ) {
				
				$fileUrl = $realpath.$fileName.'.'.$fileParts['extension'];  
				//最后保存服务器地址
				if(!is_dir($path))
				  $result_mkdir = mkdir($path,0777,true);
				
				$destination = file_exists($path.$fileName.'.'.$fileParts['extension'])?$path.$fileName.'-'.time().'.'.$fileParts['extension']:$path.$fileName.'.'.$fileParts['extension'];
				if (move_uploaded_file($tempFile, $destination)){
					if(!empty($crop)&& in_array($fileParts['extension'], $fileTypes))
					{
						$image = $fileUrl;
						$_result = $ApiAgent->CropImage($image,$crop,false);
				        if($_result['ret']==1)
				        {
					        $fileUrl = $_result['file']; 
				        }
				        $result['crop']=$_result;
					}
					
					if($water && in_array($fileParts['extension'], $fileTypes)){
						$image = $fileUrl;
				        $_result = $ApiAgent->PatchWaterMark($image,false);
				        if($_result['ret']==1)
				        {
					        $fileUrl = $_result['file']; 
				        }
					}
					 
			        $result['ret']=1;
					$result['msg']='上传成功';
					if($dev == 'ios'||$dev == 'android')
					{
						$result['fileurl']=base64_encode($fileUrl);
					}
					else
					{
						$result['fileurl']=$fileUrl;
					}
					$fileUrl = explode("../upload", $fileUrl);
					$fileUrl = "../upload".$fileUrl[1];
					$fileUrl = str_replace("../", "http://".$_SERVER['HTTP_HOST']."/", $fileUrl);
					
					$result['fileurl'] = $fileUrl;
					$result['locationurl']=$fileUrl;
					$result['data']=$_FILES;
					
				}else{ 
					$result['ret']=0;
					$result['msg']='上传失败';
					$result['data']=$_FILES;
					$result['path']=$path;
					$result['tempFile']=$tempFile;
					$result['realFile']=$path.$fileName.'.'.$fileParts['extension'];
					$result['mkdir']=$result_mkdir;
				}
			}
			else
			{
				$result['ret']=2;
				$result['msg']='没有数据';
				$result['data']=$_FILES;
			}
		}
		else
		{
			$result['ret']=-1;
			$result['data']=$_FILES;
		}
		return $result;
	}
    
		
}
?>