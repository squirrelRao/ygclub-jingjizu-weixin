<?php

require("core/dao/Db.class.php");

define('ROOT',dirname(__FILE__).'/');  
$action = $_GET['act'];
if($action=='delimg'){
	$filename = $_POST['imagename'];
	if(!empty($filename)){
		unlink('files/'.$filename);
		echo '1';
	}else{
		echo '删除失败.';
	}
}else{
	$picname = $_FILES['mypic']['name'];
	$picsize = $_FILES['mypic']['size'];
	if ($picname != "") {
		if ($picsize > 2048000) {
			echo '图片大小不能超过2M';
			exit;
		}
		$type = strstr($picname, '.');
		if ($type != ".gif" && $type != ".jpg" && $type != ".png") {
			echo '图片格式不对！';
			exit;
		}
		$rand = rand(100, 999);
		$pics = date("YmdHis") . $rand . $type;
		//上传路径
//		$pic_path = "photo/". $pics;
		$pic_path = ROOT."/photo/".$pics;

		move_uploaded_file($_FILES['mypic']['tmp_name'], $pic_path);
		
//			   (new ActivityBo())->record_photo($_GET['aid'],$_GET['pid'],$pics,"photo/".$pics,$_GET['path']);
		$upload_time = date('Y-m-d h:i:s',time());
		
		$db = new Db();
		$conn = $db->connect();
		$sql = "insert into activity.activity_photo(activity_id,author,photo_name,description,status,path,upload_time) 
		                    values(".$_GET['aid'].",'".$_GET['pid']."','".$pics."',' ','1','photo/".$pics."','".$upload_time."')";
//				$logger->info($sql);
		
		$db->insert($sql,$conn);
		
		$db->closeConn($conn);
		
	}
	$size = round($picsize/1024,2);
	$arr = array(
		'name'=>$picname,
		'pic'=>$pic_path,
		'size'=>$size
	);
	echo json_encode($arr);
}
?>