<?php
require("../dao/Db.class.php");
require("../entity/Activity.class.php");

date_default_timezone_set('PRC'); 
 
class ActivityBo{	
	//create new activity
	function createActivity($theme,$activity_time,$deadline_time,$location,$orgnizer,$detail,$phone){
		
		$create_time = date('Y-m-d h:i:s',time());
		$logger = Logger::getLogger("main");
		
		$sql = "insert into activity.activity(theme,activity_time,deadline_time,location,orgnizer,detail,status,create_time,phone) 
		                    values('".$theme."','".$activity_time."','".$deadline_time."','".$location."','".$orgnizer."','".$detail."','1','".$create_time."','".$phone."')";
		
//		$logger->info($sql);
		$db = new Db();
		$conn = $db->connect();
		
		$db->insert($sql,$conn);
		$db->closeConn($conn);
		
		return json_encode(new Ret());
	}
	
	//update activity
	function updateActivity($theme,$activity_time,$deadline_time,$location,$detail,$phone,$aid){
		
		$create_time = date('Y-m-d h:i:s',time());
		$logger = Logger::getLogger("main");
		
		$sql = "update activity.activity set theme = '".$theme."', activity_time = '".$activity_time."',deadline_time = '".$deadline_time."',location = '".$location."',detail = '".$detail."',phone = '".$phone."' where id = ".$aid;
//		$logger->info($sql);
		$db = new Db();
		$conn = $db->connect();
		
		$db->insert($sql,$conn);
		$db->closeConn($conn);
		
		return json_encode(new Ret());
	}
	
	//sign up 
	function signup($aid,$pid,$name,$phone,$sex){
		
		$signup_time = date('Y-m-d h:i:s',time());
		$logger = Logger::getLogger("main");
		
		$check_signup = "select count(*) as count from activity.participant where activity_id = ".$aid." and participant_id ='".$pid."'";
//		$logger->info($check_signup);
		$db = new Db();
		$conn = $db->connect();
		$count = 0;
		$result = $db->query($check_signup);
		while($row = mysql_fetch_array($result)){
			$count = $row["count"];
		}
		
		if($count > 0){
			$updateSql = "update activity.participant set name='".$name."',status = '1',phone='".$phone."',sex='".$sex."',signup_time ='".$signup_time."' where activity_id=".$aid." and participant_id = '".$pid."'";
			$db->insert($updateSql, $conn);
//			$logger->info($updateSql);
		}else{
			$sql = "insert into activity.participant(activity_id,participant_id,name,phone,sex,status,signup_time) 
		                    values('".$aid."','".$pid."','".$name."','".$phone."','".$sex."','1','".$signup_time."')";
			$db->insert($sql,$conn);
		}
		
		$db->closeConn($conn);
		
		return json_encode(new Ret());
	}
	
	
	//query all activities
	function queryAllActivity($weixin_id){
		
		$logger = Logger::getLogger("main");
		$sql = "select * from activity.activity  order by activity_time desc";
		
		$db = new Db();
		$conn = $db->connect();
		$result = $db->query($sql);
		$resArray = array();
		while($row = mysql_fetch_array($result)){
			$items = array(
			    'id' => $row["id"],
				'theme' => $row["theme"],
				'activity_time' => $row["activity_time"],
				'deadline_time' => $row["deadline_time"],
				'orgnizer' => $row["orgnizer"],
				'location' => $row["location"],
				'person_count' => 0,
				'phone' => $row["phone"],
				'status' => $row["status"],
				'signup' => '0'
			);			
				$p_count = 0;
				$sql_p =  "select * from activity.participant where status = '1' and activity_id =".$items["id"];
				$result_p = $db->query($sql_p);
				while($row_p = mysql_fetch_array($result_p)){
				   if($row_p["participant_id"] == $weixin_id){
				   	  $items["signup"] = "1";
				   }
				   $p_count += 1;
				}
				$items['person_count'] = $p_count;	 
		 	array_push($resArray,$items);
		}
		
		$db->closeConn($conn);
		$ret = new Ret();
		$ret->ret_res = $resArray;
		return json_encode($ret);
	}
	
	//query person photos
	function queryPersonPhotos($aid,$pid){
		
		$logger = Logger::getLogger("main");
		
		$db = new Db();
		$conn = $db->connect();
		$sql = "select * from activity.activity_photo where status = '1' and activity_id=".$aid." and author = '".$pid."'";
		$result = $db->query($sql);
		$photos = array();
		while($row = mysql_fetch_array($result)){
			
			$items = array(
				'activity_id' => $row["activity_id"],
			    'author' => $row["author"],
				'path' => $row["path"],
				'description' => $row["description"],
				'status' => $row["status"],
				'upload_time' => $row["upload_time"]
			);			
		 	array_push($photos,$items);
			
		}
		$ret = new Ret();
		$ret->ret_res=$photos;
		return json_encode($ret);
		
	}
	
	
	//query activity 
	function queryActivity($aid){
			
		$logger = Logger::getLogger("main");
		
		$db = new Db();
		$conn = $db->connect();
		
		//query activity detail
		$sql = "select * from activity.activity where id=".$aid;
		$result = $db->query($sql);
		
		$activity = new Activity();
		while($row = mysql_fetch_array($result)){
				
			$activity->id = $row["id"];
			$activity->theme = $row["theme"];
			$activity->location = $row["location"];
			$activity->orgnizer = $row["orgnizer"];
			$activity->activity_time = $row["activity_time"];
			$activity->deadline_time = $row["deadline_time"];
			$activity->detail = $row["detail"];
			$activity->status = $row['status'];
			$activity->phone = $row['phone'];
			
		}
		
		$db->closeConn($conn);
		
		$ret = new Ret();
		array_push($ret->ret_res,$activity);
		
		return json_encode($ret);
	}
	
	
	
	//query activity detail
	function queryDetail($aid){
			
		
		$logger = Logger::getLogger("main");
		
		$db = new Db();
		$conn = $db->connect();
		
		//query activity detail
		$sql = "select * from activity.activity where id=".$aid;
		$result = $db->query($sql);
		
		$activity = new Activity();
		while($row = mysql_fetch_array($result)){
				
			$activity->id = $row["id"];
			$activity->theme = $row["theme"];
			$activity->location = $row["location"];
			$activity->orgnizer = $row["orgnizer"];
			$activity->activity_time = $row["activity_time"];
			$activity->deadline_time = $row["deadline_time"];
			$activity->detail = $row["detail"];
			$activity->status = $row['status'];
			$activity->phone = $row['phone'];
			
		}
		
		//query participant
		$sql = "select * from activity.participant where status = '1' and activity_id=".$aid;
		$result = $db->query($sql);
		$participant = array();
		while($row = mysql_fetch_array($result)){
			
			$items = array(
				'activity_id' => $row["participant_id"],
			    'participant_id' => $row["participant_id"],
				'name' => $row["name"],
				'phone' => $row["phone"],
				'sex' => $row["sex"],
				'status' => $row["status"],
				'signup_time' => $row["signup_time"],
				'quit_time' => $row["quit_time"]
			);			
		 	array_push($participant,$items);
			
		}
		
		//query photos
		$sql = "select * from activity.activity_photo where status = '1' and activity_id=".$aid;
		$result = $db->query($sql);
		$photos = array();
		while($row = mysql_fetch_array($result)){
			
			$items = array(
				'activity_id' => $row["activity_id"],
			    'author' => $row["author"],
				'path' => $row["path"],
				'description' => $row["description"],
				'status' => $row["status"],
				'upload_time' => $row["upload_time"]
			);			
		 	array_push($photos,$items);
			
		}
		
		$activity->participant = $participant;
		$activity->photos = $photos;
		
		$db->closeConn($conn);
		
		$ret = new Ret();
		array_push($ret->ret_res,$activity);
		
		return json_encode($ret);
	}

	//query activity detail
	function checkActivity($theme,$activity_time,$location,$orgnizer){
			
		
		$logger = Logger::getLogger("main");
		
		$db = new Db();
		$conn = $db->connect();
		
		//query activity detail
		$sql = "select count(*) as count from activity.activity where status = '1' and orgnizer = '".$orgnizer."' and theme='".$theme."' and activity_time='".$activity_time."' and location='".$location."'";
//		$logger->info($sql);
		$result = $db->query($sql);
		$ret = new Ret();
		while($row = mysql_fetch_array($result)){
			
		   $ret -> ret_memo = $row["count"];
		}
		
		return json_encode($ret);
		
	}
	
	//cancel activity
	function cancelActivity($aid){
			
		$cancelTime = date('Y-m-d h:i:s',time());
		$logger = Logger::getLogger("main");
		
		$sql = "update activity.activity set status = '0',cancel_time ='".$cancelTime."' where id=".$aid;
		
//		$logger->info($sql);
		$db = new Db();
		$conn = $db->connect();
		
		$db->insert($sql,$conn);
		$db->closeConn($conn);
		
		return json_encode(new Ret());
	}
	
	//cancel activity
	function quitActivity($aid,$pid){
			
		$quitTime = date('Y-m-d h:i:s',time());
		$logger = Logger::getLogger("main");
		
		$sql = "update activity.participant set status = '0',quit_time ='".$quitTime."' where activity_id=".$aid." and participant_id = '".$pid."'";
		
//		$logger->info($sql);
		$db = new Db();
		$conn = $db->connect();
		
		$db->insert($sql,$conn);
		$db->closeConn($conn);
		
		return json_encode(new Ret());
	}
	
	
	//cancel activity
	function deletePhoto($path){
			
		$logger = Logger::getLogger("main");
		
		$sql = "update activity.activity_photo set status = '0' where path='".$path."'";
		
//		$logger->info($sql);
		$db = new Db();
		$conn = $db->connect();
		
		$db->insert($sql,$conn);
		$db->closeConn($conn);
		
		return json_encode(new Ret());
	}

	
	//sign up 
	function record_photo($aid,$pid,$name,$path){
		
		$upload_time = date('Y-m-d h:i:s',time());
		$logger = Logger::getLogger("main");
		
		$db = new Db();
		$conn = $db->connect();
		$sql = "insert into activity.activity_photo(activity_id,author,photo_name,description,status,path,upload_time) 
		                    values(".$aid.",'".$pid."','".$name."',' ','1','".$path."','".$upload_time."')";
//				$logger->info($sql);
		
		$db->insert($sql,$conn);
		
		$db->closeConn($conn);
		
	}
	
	function queryParticipants($aid){
		
		
		$logger = Logger::getLogger("main");
		
		$db = new Db();
		$conn = $db->connect();
		
		$sql = "select * from activity.participant where status = '1' and activity_id=".$aid;
		$result = $db->query($sql);
		$participant = array();
		while($row = mysql_fetch_array($result)){
			
			$items = array(
				'activity_id' => $row["participant_id"],
			    'participant_id' => $row["participant_id"],
				'name' => $row["name"],
				'phone' => $row["phone"],
				'sex' => $row["sex"],
				'status' => $row["status"],
				'signup_time' => $row["signup_time"],
				'quit_time' => $row["quit_time"]
			);			
		 	array_push($participant,$items);
			
		}
		
		$ret = new Ret();
		$ret->ret_res = $participant;
		
		return json_encode($ret); 
	}
	
}

?>