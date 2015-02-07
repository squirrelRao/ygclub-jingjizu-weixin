<?php
//ret model class
class Ret{
	
	public $ret_code = "200";
	public $ret_memo = "OK";	
	public $ret_res = array();
	function ret_404(){
		
		$this->ret_code = "404";
		$this->ret_memo = "not found";
		
		return $this;
	}
}

?>