<?php
	require_once './PHPExcel-1.8/Classes/PHPExcel.php';
	class ExcelToArray{
		
		public $cellArray;
		
		public function __construct(){
			$this->cellArray = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		}
		
		public function read($fileUrl){
			if(empty($fileUrl)){
				$result['ret'] = 0;
		       	$result['msg'] = '文件地址错误';
			   	return $result;
			}
			$fileArray = explode('upload',$fileUrl);
			$fileName = $fileArray[1];
			$excelpath = $_SERVER['DOCUMENT_ROOT'].'/upload'.$fileName;
			if(file_exists($excelpath)){
				$objPHPExcel = PHPExcel_IOFactory::load($excelpath); 
			 	$sheet = $objPHPExcel->getSheet(0);  
				$highestRow = $sheet->getHighestRow();           //取得总行数
				$highestColumn = $sheet->getHighestColumn(); //取得总列数 
				$cellArray = $this->cellArray;
				for($j=1;$j<=$highestRow;$j++)                        //从第一行开始读取数据
			    { 
					$str="";
					for($i=0; $i<count($cellArray); $i++)//($k='A';$k<='AJ';$k++)            //从A列读取数据
					{ 
						$k = $cellArray[$i];
			            $str .=$objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue().'|*|';//读取单元格
			        } 
			        $str = mb_convert_encoding($str,'UTF-8','auto');//根据自己编码修改 
			        $strs = explode("|*|",$str);
			        if($j == 1)
			        {
				        $sectiontitle = $strs;
			        }
			        else
			        {
				        $sections[] = $strs;
			        }
			    }
				
				$result['ret'] = 1;
				$result['msg'] = "读取成功";
				$result['sheet'] = $sheet;
				$result['highestRow'] = $highestRow; 
				$result['highestColumn'] = $highestColumn; //取得总列数
				$result['sectiontitle'] = $sectiontitle; //列明
				$result['sections'] = $sections; //列值
				$result['file'] = $excelpath;
				return $result;
			} else {
				$result['ret'] = 0;
				$result['msg'] = "找不到文件:".$excelpath;
				return $result;
			}
	        
		}	
	}
?>