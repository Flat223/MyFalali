<?php
namespace cy;
class Log{
	
	private $__uri;
	private $handle;
	private $filesize;
	
	public function __construct($uri=null,$fsize=10485760){
		if($uri){
			$this->__uri = $uri;
		}
		$this->filesize = $fsize;
	}
	
	private function checkFile(){
		if(gettype($this->__uri) != "string" || empty($this->__uri)){
			return false;
		}
		$file = $this->__uri.'/'.date("y-m-d").'.log';
		if(!file_exists($file)){
			$this->handle = fopen($file, "a");
		}else{
			$size = filesize($file);
			if($size > $this->filesize){
				$i = 1;
				$newname = $this->__uri.'/'.date("y-m-d").'.log.bak';
				while(file_exists($newname)){
					$i++;
					$newname = $this->__uri.'/'.date("y-m-d").'_'.$i.'.log.bak';
				}
				rename($file, $newname);
				$this->handle = fopen($file, "a");
			}else{
				$this->handle = fopen($file, "a");
			}
		}
		if($this->handle){
			return true;
		}
		return false;
	}
	
	private function write($msg,$levelStr){
		if($this->checkFile()){
			$msg = '['.date('Y-m-d H:i:s').']['.$levelStr.'] '.$msg."\n";
			fwrite($this->handle, $msg,4096);
		}
	}
	
	function error($msg){
		$this->write($msg,"error");
	}
	
	function warn($msg){
		$this->write($msg,"warn");
	}
	
	function debug($msg){
		$this->write($msg,"debug");
	}
	
	function info($msg){
		$this->write($msg,"info");
	}
	

	public function __destruct(){
		if($this->handle){
			fclose($this->handle);
		}
	}
	
}