<?php
require("../dao/Db.class.php");
require("../entity/Activity.class.php");
session_start();

date_default_timezone_set('PRC'); 
 
class AdminBo{	

	//login 
	function login($username,$password){
			
		$logger = Logger::getLogger("main");
		
		$db = new Db();
		$conn = $db->connect();
		
		//query activity detail
		$sql = "select * from activity.admin where username='".$username."'";
//		$logger->info($sql);
		$result = $db->query($sql);
		
		$ret = new Ret();
	
		$activity = new Activity();
		while($row = mysql_fetch_array($result)){
				
			if($row["password"] == md5($password)){
				$_SESSION['username']=$row['username'];
				
				$db->closeConn($conn);
				return json_encode($ret);
			}
			
		}
		
		$db->closeConn($conn);
		$ret->ret_code="400";
		
		return json_encode($ret);
	}
	
	
	//add admin 
	function add_admin($username,$password){
			
		$logger = Logger::getLogger("main");
		
		$db = new Db();
		$conn = $db->connect();
		
		//query activity detail
		$sql = "insert into activity.admin(username,password) values('".$username."','".md5($password)."')";
		
		$db->insert($sql,$conn);		
		$db->closeConn($conn);
		$ret = new Ret();
		
		return json_encode($ret);
	}
	
	
	//is login 
	function is_login(){
			
		$logger = Logger::getLogger("main");
		
		$ret = new Ret();
//		$logger->info(isset($_SESSION['username']));
		if(!isset($_SESSION['username'])){	
			$ret->ret_code="401";
		}
		
		return json_encode($ret);
	}
	
	//is login 
	function logout(){
			
		$logger = Logger::getLogger("main");
		
		$ret = new Ret();
		unset($_SESSION['username']);
		
		return json_encode($ret);
	}

	
}

?>