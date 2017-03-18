<?php

class ImageAgent{
 
    public $watermark;
    public $watermark2x;
    public $emptywatermark;
 
	//构造函数->默认载入函数
    public function __construct()
    {
        $this->watermark = dirname(__FILE__).'/../../data/handle/watermark.png';
        $this->watermark2x = dirname(__FILE__).'/../../data/handle/watermark@2x.png';
        $this->emptywatermark = dirname(__FILE__).'/../../data/handle/emptywatermark.png';
    }
    
    /*
    * 处理html5 canves 生成的图片上传 
    * $data -> string 形如 ：data:image/jpeg;base64,xxx 数据
    * return string ->返回存储到服务器上的相对路径
    */
    function ConverHtml5Image($data)
    {
	    if(!empty($data))
	    {
		   $_data_arr = explode("base64,", $data);
		    $_file_arr = explode("/", $_data_arr[0]);
		    $_file_type = $_file_arr[0];
		    if($_file_type == "data:image")
		    {
			    $_file_extension = $_file_arr[1];
			    $image = base64_decode($_data_arr[1]);
		        //替换日期事件
		        $t = time();
		        $d = explode('-', date("Y-y-m-d-H-i-s"));
				$extension = ".".$_file_extension;
				$extension = str_replace(";", "", $extension);
				$filename = "poster-".$t.rand(0,100);
				$realpath = "../upload/image/".$d[0].$d[2].$d[3].'/'.$filename.$extension;
				$path = dirname(__FILE__)."/../../upload/image/".$d[0].$d[2].$d[3];
				//最后保存服务器地址
				if(!is_dir($path))
				  $result_mkdir = mkdir($path);
			         
			    //指定打开的文件  
			    $fp = @ fopen($path.'/'.$filename.$extension, 'a');  
			    //写入图片到指定的文本  
			    fwrite($fp, $image);
			    fclose($fp);
			    
			    $result['ret'] = 1;
			    $_realpath = str_replace("../", "", $realpath);    
			    $result['absolute'] = "http://".$_SERVER['HTTP_HOST']."/".$_realpath;
		        $result['msg'] = "成功了";
		    }
		    else
		    {
			    $result['ret'] = 0;
		        $result['msg'] = "图片格式错误";
		        $result['data_arr'] = $_data_arr;
		        $result['file_type'] = $_file_type;
		    } 
	    }
	    else
	    {
		    $result['ret'] = 0;
		    $result['msg'] = "没有数据";
	    }
	    return $result;
    }
    
    /*
	 * $url -> 图片绝对地址
	 * $cache -> true 表示永久储存，按照URL 的 MD5 为关文件名保存文件并检查文件是否有重复
	 * return url 返回存储相对位置
	*/
    function DownloadImage($url,$water=false,$cache=false)
    { 
	    //替换日期事件
        $t = time();
        $d = explode('-', date("Y-y-m-d-H-i-s"));
		$extension = strrchr($url, '.');
		$downloaded = true;
		if(!$cache)
		{
			$filename = "dl-".$t.rand(0,100);
			$realpath = "../upload/image/".$d[0].$d[2].$d[3].'/'.$filename.$extension;
			$path = dirname(__FILE__)."/../../upload/image/".$d[0].$d[2].$d[3];
		}
		else
		{
			$filename = "dl-".md5($url);
			$realpath = "../upload/image/download/".$filename.$extension;
			$path = dirname(__FILE__)."/../../upload/image/download/";
			
			if(file_exists($path.$filename.$extension))
		    {
			    $downloaded = false;
			    $result['ret'] = 1;
	            $result['msg'] = "图片于".transferTime(filemtime($path.$filename.$extension),"full")."下载";
	            
	            if($water)
	            {
		            $realpath = "../upload/image/download/".$filename."-water".$extension;
	            }
	            
	            $fileUrl = explode("../upload", $realpath);
				$fileUrl = "../upload".$fileUrl[1];
		        $result['absolute'] = str_replace("../", "http://".$_SERVER['HTTP_HOST']."/", $fileUrl);
	            $result['relative'] = str_replace("../", "http://".$_SERVER['HTTP_HOST']."/", $fileUrl);
		    }
		}
		
	    if ($url == '') {
	        $result['ret'] = 0;
	        $result['msg'] = "图片地址错误";
	        $downloaded = false;
	    } 
	    
	    if($downloaded)
	    {
		    //最后保存服务器地址
			if(!is_dir($path))
			  $result_mkdir = mkdir($path);
		       
		    //读取图片  
		    $img = file_get_contents($url);  
		    //指定打开的文件  
		    $fp = @ fopen($path.'/'.$filename.$extension, 'a');  
		    //写入图片到指定的文本  
		    fwrite($fp, $img);  
		    fclose($fp);
		    
		    $result['ret'] = 1; 
	        if($water)
	        {
		        $waterresult = $this->PatchWaterMark($realpath,false);
		        if($waterresult['ret'] == 1)
		        {
			        $realpath = $waterresult['file'];
		        }
	        }
	        $fileUrl = explode("../upload", $realpath);
			$fileUrl = "../upload".$fileUrl[1];
	        $result['absolute'] = str_replace("../", "http://".$_SERVER['HTTP_HOST']."/", $fileUrl);
            $result['relative'] = str_replace("../", "http://".$_SERVER['HTTP_HOST']."/", $fileUrl);
	        
	    }
	    return $result;
    }
    
    /*
	 * $type -> 类型 0-> 文字 1->网址
	 * $value -> 类型对应值
	 * $width -> 生成图片宽度 
	 * $logo -> logo 绝对地址
	 * return url
	*/
    function MakeQrCode($type,$value,$width,$logo,$source)
    {
	    ini_set("display_errors", "On");
	    $iChuk = new iChukCore();
	    $value = urlencode($value);
	    $width = intval($width); // 10point -> 290px
	    $logo = urlencode($logo);
	    $source = 1;
	    if ($source == 0)
	    {
		    $api_url = 'http://218.4.254.249:50051/QR/QR?text='.$value.'&w='.$width.'&logo='.$logo;
			//$result_aucode = $iChuk->REQUEST_GET($api_url);
		    $resultArray['ret'] = 1;
		    $resultArray['url'] = $api_url;
	    }
	    else
	    {
		    include dirname(__FILE__)."/plug/phpqrcode/qrlib.php";
		    // how to save PNG codes to server  
			$realpath = dirname(__FILE__)."/../../upload/qr/";
			$path = $realpath;
		    $tempDir = $path;
		    $codeContents = urldecode($value); 
		     
		    // we need to generate filename somehow,  
		    // with md5 or with database ID used to obtains $codeContents... 
		    $fileName = 'qr_'.md5($codeContents).'.png'; 
		     
		    $pngAbsoluteFilePath = $tempDir.$fileName; 
		    $urlRelativeFilePath = $realpath.$fileName; 
		     
		    // generating 
		    if (!file_exists($pngAbsoluteFilePath)) { 
		        $QRcode = QRcode::png($codeContents, $pngAbsoluteFilePath,"L",$width*0.05, 2);
		        $QR = imagecreatefrompng($pngAbsoluteFilePath);
		        if(!empty($logo))
		        {
			        $IN_LOGO = imagecreatefromstring(file_get_contents($logo));
				    $QR_width = imagesx($QR);//二维码图片宽度   
				    $QR_height = imagesy($QR);//二维码图片高度   
				    $logo_width = imagesx($IN_LOGO);//logo图片宽度   
				    $logo_height = imagesy($IN_LOGO);//logo图片高度   
				    $logo_qr_width = $QR_width / 5;   
				    $scale = $logo_width/$logo_qr_width;   
				    $logo_qr_height = $logo_height/$scale;   
				    $from_width = ($QR_width - $logo_qr_width) / 2;
				    $imagecopyresampled = imagecopyresampled($QR, $IN_LOGO, $from_width, $from_width, 0, 0, $logo_qr_width,    
	    $logo_qr_height, $logo_width, $logo_height);
				    $resultArray['imagecopyresampled'] = $imagecopyresampled;
		        }  
			    
				//输出图片   
				$imagepng = imagepng($QR,$pngAbsoluteFilePath);
		    }  
		    $urlRelativeFilePath = explode("../upload", $urlRelativeFilePath);
			$urlRelativeFilePath = "upload".$urlRelativeFilePath[1];
			if(!file_exists($pngAbsoluteFilePath))
			{
				$resultArray['ret'] = 0;
				$resultArray['msg'] = "生成失败";
				$resultArray['codeContents'] = $codeContents;
				$resultArray['QRcode'] = $QRcode;
				$resultArray['QR'] = $QR;
				$resultArray['imagepng'] = $imagepng;
			}
		    else
		    {
			    $resultArray['ret'] = 1;
				$resultArray['msg'] = "生成成功";
		    }
		    $resultArray['url'] = "http://".$_SERVER['HTTP_HOST']."/".$urlRelativeFilePath;
		    $resultArray['AbsoluteFilePath'] = $pngAbsoluteFilePath;  
	    } 
	    return $resultArray;
    }
    
    //修正图片旋转
    //return $filename
    function RotateFix($filename)
    {
	    if(function_exists('exif_read_data'))
	    {
		    $exif = exif_read_data($filename);
		    if(is_array($exif))
		    {
			    if(in_array("Orientation", $exif))
			    {
				    $ort = $exif['Orientation'];
				    if(!empty($ort))
				    {
					    include dirname(__FILE__)."/plug/wideimage/WideImage.php";
					    $image = WideImage::load($filename);
						// GD doesn't support EXIF, so all information is removed.
						$image->exifOrient($ort)->saveToFile($filename);
				    }
			    } 
		    }  
	    }
		return $filename;
    }
    
    //裁切图片
    function CropImage($_sourceimage,$option,$replace=false)
    {
	    if(!file_exists($_sourceimage))
	    {
		    $sourceimage = dirname(__FILE__).'/'.$_sourceimage;
		    if (!file_exists($sourceimage))
		    {
			    $sourceimage = dirname(__FILE__).'/../upload/image/'.$_sourceimage;
			    if (!file_exists($sourceimage))
			    {
				    $sourceimage = dirname(__FILE__).'/../upload/'.$_sourceimage;
				    if (!file_exists($sourceimage))
				    {
					    $sourceimage = dirname(__FILE__).'/../'.$_sourceimage;
				    }
			    }
		    }
	    }
	    else
	    {
		    $sourceimage = $_sourceimage;
	    }
	    if($_sourceimage != "")
	    {
		    if (file_exists($sourceimage))
		    {
			    //原始图像 
				$dst = $sourceimage; //注意图片路径要正确
				$this->RotateFix($sourceimage);
			    //定义水印图片名称
				if ($replace){
					$result['file'] = $dst;
				}
				else
				{
					$pathinfo = pathinfo($sourceimage);
					$dirname = $pathinfo['dirname'];
					$extension = $pathinfo['extension'];
					$filename = $pathinfo['filename']."-crop";
					$result['file'] = $dirname."/".$filename.".".$extension;
					$result['originalfile'] = $dirname."/original/".$pathinfo['filename'].".".$extension;
	                if(!is_dir($dirname."/original/"))
				      $result_mkdir = mkdir($dirname."/original/");
				}
			    $_opt_ = array();
			    foreach($option as $k=>$v)
			    {
				    $_opt_[$k] = intval($v);
			    } 
	            $result['option'] = $_opt_;
			    $targ_w = $_opt_['w'];
			    $targ_h = $_opt_['h'];
				$jpeg_quality = 90;
			
				$src = $sourceimage;
				//得到原始图片信息 
				$dst_info = getimagesize($src);
				$sourseanalyze = true;
				$dst_r = imagecreatetruecolor( intval($targ_w), intval($targ_h) );
				switch ($dst_info[2]) 
				{ 
					case 1: 
						$dst_im =imagecreatefromgif($src);
						break; 
					case 2: 
						$dst_im =imagecreatefromjpeg($src);
						break; 
					case 3: 
						$dst_im =imagecreatefrompng($src);
						break; 
					case 6: 
						$dst_im =imagecreatefromwbmp($src);
						break; 
					default: 
						$sourseanalyze = false;
						break;
				} 
				$saveresult = imagecopyresampled($dst_r,$dst_im,0,0,intval($_opt_['x1']),intval($_opt_['y1']),
				intval($targ_w),intval($targ_h),intval($targ_w),intval($targ_h));
			    $result['fileinfo'] = $dst_info;
				//保存图片
				$saveresult = true;
				switch ($dst_info[2]){ 
				case 1: 
					imagegif($dst_r,$result['file']);
					break;
				case 2:
					imagejpeg($dst_r,$result['file']);
					break; 
				case 3: 
					imagepng($dst_r,$result['file']);
					break; 
				case 6: 
					imagewbmp($dst_r,$result['file']);
					break; 
				default: 
					$saveresult = false;
					break;
				}
				// Destroy image 
				imagedestroy($dst_r);
				if($saveresult)
				{
					$result['ret']=1;
	                $result['msg']="成功生成";
	                $result['sourceimage']=$sourceimage; 
	                $result['moved']= rename($sourceimage, $result['originalfile']);
	                $result['file'] = str_replace("/var/www/html/cancanwang.com/core/..", "..", $result['file']);
				}
				else
				{
					$result['ret']=0;
	                $result['msg']="错误类型2";
				}
		    }
		    else
		    {
			    $result['ret']=0;
			    $result['msg']="没有该文件";
		    } 
	    }
	    else
	    {
		    $result['ret']=0;
			$result['msg']="文件地址为空";
	    }
	    return $result;
    }
    
    //$replace true->替换源文件 false->新文件
    function PatchWaterMark($_sourceimage,$replace){
	    if(!file_exists($_sourceimage))
	    {
		    $sourceimage = dirname(__FILE__).'/'.$_sourceimage;
		    if (!file_exists($sourceimage))
		    {
			    $sourceimage = dirname(__FILE__).'/../upload/image/'.$_sourceimage;
			    if (!file_exists($sourceimage))
			    {
				    $sourceimage = dirname(__FILE__).'/../upload/'.$_sourceimage;
				    if (!file_exists($sourceimage))
				    {
					    $sourceimage = dirname(__FILE__).'/../'.$_sourceimage;
				    }
			    }
		    }
	    }
	    else
	    {
		    $sourceimage = $_sourceimage;
	    }
	    if($_sourceimage != "")
	    {
		    if (file_exists($sourceimage))
		    {
			    //原始图像 
				$dst = $sourceimage; //注意图片路径要正确
				//允许的图片后缀 GIF 不打水印
		        $fileTypes = array('jpg','jpeg' ,'png','JPG','JPEG','PNG');
		        $pathinfo = pathinfo($sourceimage);
				$dirname = $pathinfo['dirname'];
				$extension = $pathinfo['extension'];
				if(in_array($extension, $fileTypes))
				{
					//定义水印图片名称
					if ($replace){
						$result['file'] = $dst;
					}
					else
					{ 
						$filename = $pathinfo['filename']."-water";
						$result['file'] = $dirname."/".$filename.".".$extension;
						//替换日期事件 
						$realpath = $dirname.'/'.$filename.".".$extension; 
					}
					$result['source'] = $sourceimage;
					//得到原始图片信息 
					$dst_info = getimagesize($dst);
					$sourseanalyze = true;
					switch ($dst_info[2]) 
					{ 
						case 1: 
							$dst_im =imagecreatefromgif($dst);
							break; 
						case 2: 
							$dst_im =imagecreatefromjpeg($dst);
							break; 
						case 3: 
							$dst_im =imagecreatefrompng($dst);
							break; 
						case 6: 
							$dst_im =imagecreatefromwbmp($dst);
							break; 
						default: 
							$sourseanalyze = false;
							break;
					}
					if($sourseanalyze)
					{
						//水印图像
						// 当图片宽度 大于 800 时 我们启用 大水印图
						if ($dst_info[1] >= 800 )
						{
							$src = $this->watermark2x; // 使用大水印图
						}
						else if ($dst_info[1] >= 300 && $dst_info[1] < 800)
						{
							$src = $this->watermark; // 使用小水印图 
						}
						else
						{
							$src = $this->emptywatermark; // 使用空白水印
						}
						
						$src_info = getimagesize($src);
						$wateranalyze = true;
						switch ($src_info[2]) 
						{ 
							case 1: 
								$src_im =imagecreatefromgif($src);
								break; 
							case 2: 
								$src_im =imagecreatefromjpeg($src);
								break; 
							case 3: 
								$src_im =imagecreatefrompng($src);
								break; 
							case 6: 
								$src_im =imagecreatefromwbmp($src);
								break; 
							default: 
								$wateranalyze = false;
							    break;
						} 
						
						if($wateranalyze)
						{
							//半透明格式水印 
							//$alpha = 50;//水印透明度 
							//imagecopymerge($dst_im,$src_im,$dst_info[0]-$src_info[0]-10,$dst_info[1]-$src_info[1]-10,0,0,$src_info[0],$src_info[1],$alpha); 
							
							//支持png本身透明度的方式 
							imagecopy($dst_im,$src_im,$dst_info[0]-$src_info[0]-10,$dst_info[1]-$src_info[1]-10,0,0,$src_info[0],$src_info[1]); 
							
							//保存图片
							$saveresult = true;
							switch ($dst_info[2]){ 
							case 1: 
								imagegif($dst_im,$result['file']);
								break;
							case 2:
								imagejpeg($dst_im,$result['file']);
								break; 
							case 3: 
								imagepng($dst_im,$result['file']);
								break; 
							case 6: 
								imagewbmp($dst_im,$result['file']);
								break; 
							default: 
								$saveresult = false;
								break;
							}
							if($saveresult)
							{
								$result['ret']=1;
				                $result['msg']="成功生成";
				                $result['file'] = $realpath;
							}
							else
							{
								$result['ret']=0;
				                $result['msg']="错误类型2";
							}
						}
						else
						{
							$result['ret']=0;
				            $result['msg']="错误类型1";
						}
					}
					else
					{
						$result['ret']=0;
				        $result['msg']="错误类型";
					}
					imagedestroy($dst_im); 
					imagedestroy($src_im); 
				}
				else
				{
					$result['ret']=0;
	                $result['msg']="GIF不打水印";
				}
		    }
		    else
		    {
			    $result['ret']=0;
			    $result['msg']="没有该文件";
		    } 
	    }
	    else
	    {
		    $result['ret']=0;
			$result['msg']="文件地址为空";
	    } 
	    return $result;
    }
}

class WideImage_Operation_ExifOrient
{
  /**
   * Rotates and mirrors and image properly based on current orientation value
   *
   * @param WideImage_Image $img
   * @param int $orientation
   * @return WideImage_Image
   */
  function execute($img, $orientation)
  {
    switch ($orientation) {
      case 2:
        return $img->mirror();
        break;

      case 3:
        return $img->rotate(180);
        break;

      case 4:
        return $img->rotate(180)->mirror();
        break;

      case 5:
        return $img->rotate(90)->mirror();
        break;

      case 6:
        return $img->rotate(90);
        break;

      case 7:
        return $img->rotate(-90)->mirror();
        break;

      case 8:
        return $img->rotate(-90);
        break;

      default: return $img->copy();
    }
  }
}

?>