<!DOCTYPE HTML> 

<html> 
   
<head> 
    <meta  http-equiv="Content-Type"  content="text/html;  charset=utf-8"  /> 
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1"> 
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a4.1/jquery.mobile-1.0a4.1.min.css"> 
    <link rel="stylesheet" href="css/mui.css" />
		<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
		<script src="js/mui.js"></script>
    <script src="js/jquery.js"></script> 
    <!--<script src="http://code.jquery.com/mobile/1.0a4.1/jquery.mobile-1.0a4.1.min.js"></script>--> 
   <script>
   	
		  function backToList(){
		  		var weixin_id = 	 $("#weixin_id").val();
		  		var aid = $("#aid").val();
				document.location.href = "photo_list.php?weixin_id="+weixin_id+"&aid="+aid;

		  }
   	
   </script>
</head> 
   
<body> 
   
<!-- 显示图片信息 --> 
<div data-role="page"> 
   
    <header class="mui-bar mui-bar-nav">
			
			<h1 class="mui-title">
				上传图片
			</h1>
		</header>
    <div data-role="content" class="mui-content"> 
			<div class="mui-content-padded">

        <?php 
           require("core/dao/Db.class.php");
        
            if ($_FILES["file"]["error"] > 0) 
            { 
                echo "错误代码: " . $_FILES["file"]["error"] . "<br />"; 
            } 
            else 
            {
            	$picname = $_FILES['file']['name'];
            	$type = strstr($picname, '.');
			if ($type != ".gif" && $type != ".jpg" && $type != ".png") {
				echo '图片格式错误，请重新上传...';
				exit;
			}
			$rand = rand(100, 999);
            	$pics = date("YmdHis") . $rand . $type; 
                echo "文件名称: " . $pics . "<br/>"; 
                echo "文件大小: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
				
                 echo "上传成功！";
 
           	 move_uploaded_file($_FILES["file"]["tmp_name"],"photo/".$pics); 
			 		$upload_time = date('Y-m-d h:i:s',time());
			 
		$db = new Db();
		$conn = $db->connect();
		$sql = "insert into activity.activity_photo(activity_id,author,photo_name,description,status,path,upload_time) 
		                    values(".$_GET['aid'].",'".$_GET['weixin_id']."','".$pics."',' ','1','photo/".$pics."','".$upload_time."')";
//				$logger->info($sql);
		
		$db->insert($sql,$conn);
		
		$db->closeConn($conn);
			}
   
        ?> 
    </div>
    <div class="mui-button-row">
				<button class="mui-btn mui-btn-positive mui-btn-primary" style="width:280px;height:40px;" onclick="backToList()">确 定</button>
		</div>
    <div>
    </div>
    </div>
   <input type="hidden" id="weixin_id" name="weixin_id"  />
				<input type="hidden" id="aid" name="aid"  />
    
</div>
<script>
   $("#weixin_id").val("<?php echo $_GET['weixin_id']?>");
	    	    $("#aid").val("<?php echo $_GET['aid']?>");
</script>
</body>
</html>
