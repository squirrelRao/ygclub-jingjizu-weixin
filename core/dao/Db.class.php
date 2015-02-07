<?php
class Db{
	
	private $db_host="127.0.0.1";
	private $db_name = "activity"; 
	private	$db_username="root"; 
	private	$db_password = "admin123!";
	
	function connect(){
		 
		  $conn=mysql_connect($this->db_host, $this->db_username,$this->db_password);
	      if(!$conn){
			die('Could not connect: ' . mysql_error());
		  }
	      
	      $selected_db = mysql_select_db($this->db_name,$conn);
		  if (!$selected_db) {
			die ('Could not select database' . ':' . mysql_error ());
		  }
		  
		  return $conn;
	}
	
	function closeConn($conn){
		mysql_close($conn);
	}
	
	function insert($sql,$conn){
		mysql_query("set names utf8");
		mysql_query($sql,$conn);
	}
	
	function query($sql){
		mysql_query("set names utf8");
		$result = mysql_query($sql);
		
		return $result;
	}

}

?>