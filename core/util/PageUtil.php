<?php 
class PageUtil{
	
	private $pageSize;
	private $recordCount;
	private $currentPage;
	private $pageCount;
		
	//构造函数->默认载入函数
    public function __construct($pageSize,$recordCount,$currentPage){
    	$this->pageSize = $pageSize;
    	$this->recordCount = $recordCount;
    	$this->pageCount = $this->compPageCount($recordCount,$pageSize);
    	if($currentPage <= $this->pageCount){
	    	$this->currentPage = $currentPage;
    	}else{
	    	$this->currentPage = $this->pageCount;
    	}
    }
    
    private function compPageCount($recordCount,$pageSize){
	    $size = intval($recordCount/$pageSize);
	    $mod = $recordCount%$pageSize;
	    if($mod != 0){
		    $size++;
	    }
	    return $recordCount == 0?1:$size;
    }
    
    function getPageSize(){
	    return intval($this->pageSize);
    }
    
    function getRecordCount(){
	    return intval($this->recordCount);
    }
    
    function getCurrentPage(){
	    return intval($this->currentPage);
    }
    
    function getPageCount(){
	    return intval($this->pageCount);
    }
    
    function hasNext(){
	    return $this->currentPage < $this->pageCount;
    }
    
    function hasPrevious(){
	    return $this->currentPage > 1;
    }
    
    
    
}
?>