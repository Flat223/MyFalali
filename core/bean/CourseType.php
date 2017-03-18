<?php
class CourseType{
	
	private $ctid;
	private $name;
	private $parentid;
	private $status;
	private $sort;
	
	public function getCtid(){
		return $this->ctid;
	}
	
	public function setCtid($val){
		$this->ctid = $val;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setName($val){
		$this->name = $val;
	}
	
	public function getParentid(){
		return $this->parentid;
	}
	
	public function setParentid($val){
		$this->parentid = $val;
	}
	
	public function getStatus(){
		return $this->status;
	}
	
	public function setStatus($val){
		$this->status = $val;
	}
	
	public function getSort(){
		return $this->sort;
	}
	
	public function setSort($val){
		$this->sort = $val;
	}
	
}