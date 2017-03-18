<?php
class BaseFilter{
	
	//构造函数->默认载入函数
    public function __construct(){
	    
    }
	
	public function doFilter($filter_path){
		return true;
	}
}