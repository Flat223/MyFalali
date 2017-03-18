<?php
class UploadServ{
	
	private $fileMaxSize = 31457280;
    private $maxSize = 10485760;
    private $picTypes = array('png','jpg','jpeg','gif','bmp','tif');
    private $docTypes = array('doc','docx','xls','xlsx','pdf','cvs');
	private $formTypes = array('xls','xlsx');
	
    //构造函数->默认载入函数
    public function __construct(){
	    
    }
    
    function uploadBaseFile(){
        return $this->uploadFile(date("Y-m-d"),0);
    }
	
    function uploadProImg(){
        $adminAgent = new AdminAgent();
        $shop = $adminAgent->getShopInfo();
        $sid = $shop['sid'];
        return $this->uploadImage("products",$sid);
    }

    function uploadCarouselImg(){
        return $this->uploadImage("carousels",0);
    }

    function uploadTjImg(){
        return $this->uploadImage("tj",0);
    }
	
    function uploadBrandImg(){
        return $this->uploadImage(date("Y-m-d"),0);
    }

    function uploadTMProImg(){
        return $this->uploadImage("temai",0);
    }
    
    function uploadFile($type,$id){
		$result = array();
        if(!isset($_FILES['upload_file_input'])){
            $result['ret'] = 0;
            $result['msg'] = "没有上传文件或文件太大";
            return $result;
        }
        if($_FILES['upload_file_input']['error'] > 0){
            $result['ret'] = 0; 
            $result['msg'] = "文件上传出错";
            return $result;
        }
        $size = $_FILES['upload_file_input']['size'];
        if($size >$this->fileMaxSize){
            $result['ret'] = 0;
            $result['msg'] = "文件太大";
            return $result;
        }
        
        $fileName = $_FILES['upload_file_input']['name'];
        $name = pathinfo($fileName,PATHINFO_BASENAME);
        $extension = pathinfo($fileName,PATHINFO_EXTENSION);
        if($extension == ""){
            $result['ret'] = 0;
            $result['msg'] = "错误的文件类型";
            return $result;
        }
        $extension = strtolower($extension);
        if(!in_array($extension, $this->formTypes)){
            $result['ret'] = 0;
            $result['msg'] = "错误的文件类型";
            return $result;
        }
        
        $temp_file = $_FILES['upload_file_input']['tmp_name'];
        $prefix = $_SERVER['REQUEST_TIME'];
        $newfileName = md5($prefix."_".$name).".".$extension;
        $filePath = $_SERVER['DOCUMENT_ROOT'].'/upload/excel/'.$type.'/';
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/upload/excel/'.$type.'/';
        
        if($id !== 0){
            $filePath .= $id.'/';
            $url .= $id.'/';
        }
        if(!is_dir($filePath)){
            mkdir($filePath,0777,true);
        }
        $filePath .= $newfileName;
        $url .= $newfileName;
        //$filePath = dirname(__FILE__).'/../../upload/gy/'.$newfileName;
        move_uploaded_file($temp_file,$filePath);
        $result['ret'] = 1;
        $result['msg'] = "上传成功";
        $result['file_url'] = $url;
        return $result;
	}

    private function uploadImage($type,$id){
        $result = array();
        if(!isset($_FILES['upload_file_input'])){
            $result['ret'] = 0;
            $result['msg'] = "没有上传图片或图片太大";
            return $result;
        }
        if($_FILES['upload_file_input']['error'] > 0){
            $result['ret'] = 0;
            $result['msg'] = "图片上传出错";
            return $result;
        }
        $size = $_FILES['upload_file_input']['size'];
        if($size > $this->maxSize){
            $result['ret'] = 0;
            $result['msg'] = "图片太大";
            return $result;
        }
        $fileName = $_FILES['upload_file_input']['name'];
        $name = pathinfo($fileName,PATHINFO_BASENAME);
        $extension = pathinfo($fileName,PATHINFO_EXTENSION);
        if($extension == ""){
            $result['ret'] = 0;
            $result['msg'] = "错误的图片类型";
            return $result;
        }
        $extension = strtolower($extension);
        if(!in_array($extension, $this->picTypes)){
            $result['ret'] = 0;
            $result['msg'] = "错误的图片类型";
            return $result;
        }
        $temp_file = $_FILES['upload_file_input']['tmp_name'];
        $prefix = $_SERVER['REQUEST_TIME'];
        $newfileName = md5($prefix."_".$name).".".$extension;
        $filePath = $_SERVER['DOCUMENT_ROOT'].'/upload/'.$type.'/';
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/upload/'.$type.'/';
        if($id !== 0){
            $filePath .= $id.'/';
            $url .= $id.'/';
        }
        if(!is_dir($filePath)){
            mkdir($filePath,0777,true);
        }
        $filePath .= $newfileName;
        $url .= $newfileName;
        //$filePath = dirname(__FILE__).'/../../upload/gy/'.$newfileName;
        move_uploaded_file($temp_file,$filePath);
        $result['ret'] = 1;
        $result['msg'] = "上传成功";
        $result['file_url'] = $url;
        return $result;
    }

    function cutImage($type,$id,$maxWidth,$maxHeight,$minWidth,$minHeight){
        $imageName = isset($_REQUEST['imageName'])?$_REQUEST['imageName']:"";
        $width = isset($_REQUEST['width'])?$_REQUEST['width']:0;
        $height = isset($_REQUEST['height'])?$_REQUEST['height']:0;
        $x = isset($_REQUEST['x'])?$_REQUEST['x']:0;
        $y = isset($_REQUEST['y'])?$_REQUEST['y']:0;
        if($imageName == "" || !is_numeric($width) || !is_numeric($height) || !is_numeric($x) || !is_numeric($y)){
            $result['ret'] = 0;
            $result['msg'] = "无效的参数";
            return $result;
        }
        if($width < $minWidth || $width > $maxWidth || $height < $minHeight || $height > $maxHeight){
            $result['ret'] = 0;
            $result['msg'] = "剪切的图片宽在".$minWidth."到".$maxWidth."像素内，高在".$minHeight."到".$maxHeight."像素内";
            return $result;
        }
        $imagePath = $_SERVER['DOCUMENT_ROOT'].'/Uploads/'.$type.'/'.$id.'/'.$imageName;
        if(!file_exists($imagePath)){
            $result['ret'] = 0;
            $result['msg'] = "剪切的源图片不存在";
            return $result;
        }
        $imageInfo = getimagesize($imagePath);
        $info_width = $imageInfo[0];
        $info_height = $imageInfo[1];
        $info_mime = $imageInfo['mime'];
        if(($info_width < $x + $width) || ($info_height < $y + $height)){
            $result['ret'] = 0;
            $result['msg'] = "无效的剪切参数";
            return $result;
        }
        $source_image;
        switch($info_mime){
            case 'image/gif':
                $source_image = imagecreatefromgif($imagePath);
                break;
            case 'image/jpeg':
                $source_image = imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                $source_image = imagecreatefrompng($imagePath);
                break;
            default:
                $source_image = null;
                break;
        }
        if($source_image == null){
            $result['ret'] = 2;
            $result['msg'] = "只支持gif|jpg|png格式的裁剪";
            $result['url'] = $imagePath;
            return $result;
        }
        $cropped_image = imagecreatetruecolor($width, $height);
        imagecopy($cropped_image, $source_image, 0, 0, $x, $y, $width, $height);
        $dir = $_SERVER['DOCUMENT_ROOT'].'/Uploads/'.$type.'/'.$id;
        if(!is_dir($dir)){
            mkdir($dir);
        }
        $filePath = $_SERVER['DOCUMENT_ROOT'].'/Uploads/'.$type.'/'.$id.'/'.$imageName;
        imagejpeg($cropped_image,$filePath,90);
        imagedestroy($cropped_image);
        imagedestroy($source_image);
        $result['ret'] = 1;
        $result['msg'] = "剪裁成功";
        $result['url'] = "http://".$_SERVER['HTTP_HOST']."/Uploads/".$type."/".$id."/".$imageName;
        return $result;
    }


    function cutProImg(){
        $adminAgent = new AdminAgent();
        $shop = $adminAgent->getShopInfo();
        $sid = $shop['sid'];
        return $this->cutImage("products",$sid,801,801,50,50);
    }



    function deldir($dir) {
        //先删除目录下的文件：
        $dh=opendir($dir);
        while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullpath=$dir."/".$file;
                if(!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->deldir($fullpath);
                }
            }
        }
        closedir($dh);
        //删除当前文件夹：
        if(rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }
}