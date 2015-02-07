<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
		<title></title>
		<link rel="stylesheet" href="../css/mui.css" />
		<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
		<script src="../js/mui.js"></script>
		<script src="../js/jquery.js"></script>
		<script type="text/javascript">
			//通过config接口注入权限验证配置
			wx.config({
			    debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
			    appId: '', // 必填，公众号的唯一标识
			    timestamp: '<?php echo time();?>', // 必填，生成签名的时间戳
			    nonceStr: '<?php echo $nonceStr;?>', // 必填，生成签名的随机串
			    signature: '<?php echo $signature;?>',// 必填，签名
			    jsApiList: [] // 必填，需要使用的JS接口列表
			});
			//通过ready接口处理成功验证
			wx.ready(function(){
				// config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后
			});
			
			var deadline_time = null;
			
			function timer(){
				var d_date = deadline_time.split(" ")[0].split("-");
				var d_time = deadline_time.split(" ")[1].split(":");
				var deadline = new Date();
				deadline.setFullYear(parseInt(d_date[0]));
				deadline.setMonth(parseInt(d_date[1])-1);
				deadline.setDate(parseInt(d_date[2]));
				deadline.setHours(parseInt(d_time[0]));
				deadline.setMinutes(parseInt(d_time[1]));
				deadline.setSeconds(0);
//				alert(d_date+"  "+d_time+"   "+deadline);
				var ts = deadline - (new Date());//计算剩余的毫秒数
				if($("#status").val() == "0"){
				   	document.getElementById("time_escape").innerHTML = "活动已取消";
				}else if(ts < 0){
					
					document.getElementById("time_escape").innerHTML = "报名已截止";
					$("#signupButton").hide();
					$("#photo_wall_li").show();
					$("#photo_wall").show();
				}else{
					$("#photo_wall_li").hide();
					$("#photo_wall").hide();
					var dd = parseInt(ts / 1000 / 60 / 60 / 24, 10);//计算剩余的天数
					var hh = parseInt(ts / 1000 / 60 / 60 % 24, 10);//计算剩余的小时数
					var mm = parseInt(ts / 1000 / 60 % 60, 10);//计算剩余的分钟数
					var ss = parseInt(ts / 1000 % 60, 10);//计算剩余的秒数
                    if(dd == 0 && hh != 0 && mm != 0){
               				document.getElementById("time_escape").innerHTML =  hh + "小时" + mm + "分"; 

                    }else if(dd == 0 && hh == 0 && mm != 0){
                    	  		document.getElementById("time_escape").innerHTML =  mm + "分"; 

                    }else if(dd == 0 && hh == 0 && mm == 0 && ss != 0){
                    	        document.getElementById("time_escape").innerHTML =  "不到1分钟"; 
                    	
                    	}else{
						document.getElementById("time_escape").innerHTML = dd + "天" + hh + "小时" + mm + "分";
					} 
//					+ ss + "秒";
				}
				
				
				
				
				setInterval("timer()",60000);
			}
			
	

			
			function showDetail(){
				//document.getElementById("showDetail").display = "block";
				if(document.getElementById("activity_detail").style.display == 'block'){
					document.getElementById("activity_detail").style.display="none";
					document.getElementById("detail_title").setAttribute("class","mui-navigate-down");
				}else{
					document.getElementById("activity_detail").style.display="block";
					document.getElementById("detail_title").setAttribute("class","mui-navigate-up");	
				}
				
			}
				function showMorePerson(){
				//document.getElementById("showDetail").display = "block";
				
				if(document.getElementById("more_person").style.display == 'block'){
					document.getElementById("more_person").style.display="none";
					document.getElementById("more_person_icon").setAttribute("class","mui-navigate-down");
				}else{
					document.getElementById("more_person").style.display="block";
					document.getElementById("more_person_icon").setAttribute("class","mui-navigate-up");	
				}
				
			}
			
			//query activity detail
			function query_activity_detail(){
				
				var aid = "<?php echo $_GET['aid']?>";
				if(aid == ""){
					alert("缺失必要数据");
					return;
				}
				$("#aid").val(aid);
				
				var url = "../core/controller/ActivityController.php?action=queryDetail&aid="+aid;
//				alert(url);
				$.ajax({
					type:"get",
					url: url,
					timeout:2000,
					async:true,
					success:function(data,status){
//					alert(data);
						var obj = eval("("+data+")");
						if(obj.ret_code == "200"){
							 $(obj.ret_res).each(function(i) { 
								 var info = obj.ret_res[i];
                       			 //update activity info
                       			 update_content(info);
                       			 //update participant info
                       			 update_participant(info.participant);
                       			 
                       			 if(info.participant.length == 0 && info.orgnizer != $("#weixin_id").val()){
									$("#quitButton").hide();
									$("#signupButton").show();
								 }
                       			 //update photos wall
                       			 update_photo_wall(info.photos);
                  			  }); 
						}else{
							alert("服务器繁忙...请稍后再试");
						}
					}
				});
			}
			
			//update page content
			function update_content(obj){
				
				$("#theme").text(obj.theme);
				$("#activity_time").text(obj.activity_time);
				$("#location").text(obj.location);
				$("#activity_detail").text(obj.detail);
				$("#orgnizer").text(obj.orgnizer);
				$("#status").val(obj.status);
				$("#orgnizer_phone").val(obj.phone);
				deadline_time = obj.deadline_time;
				
					$("#_orgnizer_phone").text(obj.phone);
				$("#_orginizer_name").text(obj.orgnizer);
				
				var self = $("#weixin_id").val();
				if(obj.orgnizer == self){
					$("#signup_info").show();
					$("#contact_orgnizer").hide();
					$("#edit_activity").show();
				}else{
					$("#signup_info").hide();
					$("#contact_orgnizer").show();
					$("#edit_activity").hide();

				}
				
//				alert(self+"  "+obj.orgnizer);
				if(obj.status == '1' && obj.orgnizer == self && $("#type").val() == "detail"){
					$("#cancelActivityButton").show();
				}else if(obj.status == '1'&& obj.orgnizer != self  && $("#type").val() == "detail"){
					$("#quitButton").show();
				}
				if(obj.status=='0'){
					document.getElementById("time_escape").innerHTML = "活动已取消";
				}else{
						timer();

				}
				
				showDetail();
				if(document.getElementById("time_escape").innerHTML =="报名已截止"){
					showDetail();
				}
				
				
			}
			
			//update participant info
			function update_participant(obj){

				var lineShowCount = 5;
				
				$("#participant_count").text( obj.length == 0 ? "还没有人报名，有机会抢到沙发噢" : obj.length+"个小伙伴已经报名啦");
				 $(obj).each(function(i) { 
                       	
                       var item = "<a href=\"#\">";
						   item +="<img class=\"mui-media-object\" style=\"border-radius:50%\"  src=\"http://dcloudio.github.io/mui/assets/img/cbd.jpg\" />";
						   item +="</a>";
					
					   if(i < lineShowCount){
					   	  
					   	  $("#more_person_icon").before(item);
					   }else if(i > lineShowCount){
					   	  $("#more_person_span").append(item);
					   	  if(i%lineShowCount == 0){
					   	  	$("#more_person_span").append("<br>");
					   	  }
					   }
                       	
                  }); 
			}
			
			
			
			//append activity info into html
			function update_photo_wall(obj){
				
				$("#photo_count").text( obj.length == 0 ? "还没有人贴照片，快贴快贴" : "墙上已经有"+obj.length+"张照片啦");
				 $(obj).each(function(i) { 
                       	
                   	     var item = "<li class=\"mui-table-view-cell mui-media mui-col-xs\">";
						   item +="<img class=\"mui-media-object\"  src=\"../"+obj[i].path+"\" />";
//						   item += "<div class=\"mui-media-body\">"+obj[i].description+"</div>";
						   item += "<div class=\"mui-media-body\" style='height:30px' onclick=\"delete_photo('"+obj[i].path+"')\"><span class=\"mui-badge mui-badge-blue\" >删除照片</span></div>";

						   item += "</li>"
						

							$("#photo_wall").append(item);

                  }); 
                
			}
			
		  function signup(){
		  	
		  	   var aid = $("#aid").val();
		  	   var selfId = $("#weixin_id").val();
		  	   document.location.href='person_info.php?weixin_id='+selfId+'&aid='+aid;
		  }
		  
		  //cancel activity
		  function cancelActivity(){
		  	
		  	if(!window.confirm("确定要残忍的取消活动嘛?")){
					return;
			}
				
		  	var aid = $("#aid").val();
		  	var url = "../core/controller/ActivityController.php?action=cancelActivity&aid="+aid;
//				alert(url);
				$.ajax({
					type:"get",
					url: url,
					timeout:2000,
					async:true,
					success:function(data,status){
//					alert(data);
						var obj = eval("("+data+")");
						if(obj.ret_code == "200"){
							alert("好可惜,活动已取消...");
							$("#cancelActivityButton").hide();
							$("#time_escape").text("活动已取消");
						}else{
							alert("服务器繁忙...请稍后再试");
						}
					}
				});
		  	  
		  }
		  
		  function backToList(){
		  		var weixin_id = 	 $("#weixin_id").val();
				document.location.href = "activity_admin.php?weixin_id="+weixin_id;

		  }
		  
		   function toEditActivity(){
		  		var weixin_id = 	 $("#weixin_id").val();
		  		var aid = $("#aid").val();
				document.location.href = "edit_activity.php?weixin_id="+weixin_id+"&aid="+aid;

		  }
		  
		  
		  function goToPhotoList(){
		  		var weixin_id = 	 $("#weixin_id").val();
				document.location.href = "photo_list.php?weixin_id="+weixin_id+"&aid="+$("#aid").val();

		  }
		  
		   function showParticipants(){
				document.location.href = "participant_list.php?aid="+$("#aid").val()+"&weixin_id="+$("#weixin_id").val();

		  }
		  
		    //cancel activity
		  function quitActivity(){
		  	
		  	if(!window.confirm("确定要退出本次活动嘛?")){
					return;
			}
				
		  	var aid = $("#aid").val();
		  	var url = "../core/controller/ActivityController.php?action=quitActivity&aid="+aid+"&pid="+$("#weixin_id").val();
//				alert(url);
				$.ajax({
					type:"get",
					url: url,
					timeout:2000,
					async:true,
					success:function(data,status){
//					alert(data);
						var obj = eval("("+data+")");
						if(obj.ret_code == "200"){
							alert("好可惜，已经退出了本次活动...");
							$("#quitButton").hide();
							$("#signupButton").show();
							document.location.reload();
						}else{
							alert("服务器繁忙...请稍后再试");
						}
					}
				});
		  	  
		  }
		  
		  	//is login 
			function is_login(){
				
				var url = "../core/controller/AdminController.php?action=isLogin";
//				alert(url);
				$.ajax({
					type:"get",
					url: url,
					timeout:2000,
					async:true,
					success:function(data,status){
//						alert(data);
						var obj = eval("("+data+")");
						if(obj.ret_code == "200"){		     			
			     			return;
						}else{							
							document.location.href='index.php';
						}
					}
				});		
			}
			
				//validate activity weather created 
			function delete_photo(path){
				
				var url = "../core/controller/ActivityController.php?action=deletePhoto&path="+path;
				
//				alert(url);
				if(!window.confirm("确定要删除这张照片嘛?")){
					return;
				}
				var result = false;
				$.ajax({
					type:"get",
					url: url,
					timeout:2000,
					async:false,
					success:function(data,status){
//						alert(data);
						var obj = eval("("+data+")");

						if(obj.ret_code == "200"){
							alert("照片已成功删除");
							document.location.reload();	
						}
					}
				});
				return result;
			
			}

 function show_orgnizer_info(){
		  	
		  	mui('#mui-popover').popover('toggle');

		  }
		</script>
	</head>
	<body>
		<header class="mui-bar mui-bar-nav">
			<button class="mui-btn mui-pull-left" onclick="backToList()">
				返 回
			</button>
			
			<button class="mui-btn mui-pull-right" id="cancelActivityButton" onclick="cancelActivity()">
				取消活动
			</button>
			
			<h1 class="mui-title" id="theme">
				-
			</h1>
		</header>
		<div class="mui-content">
					<div id="mui-popover" class="mui-popover mui-popover-action mui-popover-up" style="width:100%">
 
  <ul class="mui-table-view">
    <li class="mui-table-view-cell" >发起人信息</li>
   <li class="mui-table-view-cell">发起人: <span style="color:purple"  id="_orginizer_name">-</span></li>
   <li class="mui-table-view-cell" > 电话: <span style="color:purple" id="_orgnizer_phone">-</span></li>
  </ul>
</div>
			<div class="mui-content-padded">
				<ul class="mui-table-view">
					<li class="mui-table-view-cell ">						
							活动时间:
						<label id="activity_time">-</label>
					</li>
					<li class="mui-table-view-cell">
					     离截止还有:
						<label style="color:purple;font-weight: bold;" id="time_escape">---</label>
					</li>
					<li class="mui-table-view-cell">
						活动地点：
						<label id="location"> - </label>
						<span class="mui-icon mui-icon-location"></span>
					</li>
					<li class="mui-table-view-divider">
						发起人							<span class="mui-badge mui-badge-yellow" id="edit_activity" onclick="toEditActivity()">修改活动</span>

					</li>
					<li class="mui-table-view-cell">

						<a href="#">
							
							<img class="mui-media-object" style="border-radius:50%;margin-left:20px" src="http://dcloudio.github.io/mui/assets/img/cbd.jpg">
							<label id="orgnizer"></label>
						</a>
							<button id="contact_orgnizer" class="mui-btn mui-btn-positive mui-btn-primary" style="width:95px;height:40px;" onclick="show_orgnizer_info()">
							联系发起人
							</button>
							<input type="hidden" id="orgnizer_phone" name="orgnizer_phone" />
							<button id="signup_info" onclick="showParticipants()" class="mui-btn mui-btn-positive mui-btn-primary" style="width:95px;height:40px;">
							报名情况
							</button>
					</li>
					<li class="mui-table-view-divider">
						确认报名： <span class="mui-badge mui-badge-primary" id="participant_count"></span>
					</li>
					<li class="mui-table-view-cell" id="participant">
						<span>
		
							<a class="mui-navigate-down" id="more_person_icon" href="javascript:void(0)" onclick="showMorePerson()">
		</a>
		</span>
					</li>
					<li class="mui-table-view-cell " id="more_person" style="display: none;">
						<span id="more_person_span">
												没有更多小伙伴的信息啦....

					</li>
					<li class="mui-table-view-divider">
						<a class="mui-navigate-down" id="detail_title" href="javascript:void(0)" onclick="showDetail()">
			
					活动详情
		</a>
						<!--活动详情
					 	 <span class="mui-icon mui-icon-arrowright" ></span>
					     <span class="mui-icon mui-icon-arrowup" style="margin-left: 180px;display: none;"></span>-->
					</li>
					<li class="mui-table-view-cell" id="activity_detail" style="display: none;">
					</li>
					<li class="mui-table-view-divider" id="photo_wall_li">
						照片秀 						<span class="mui-badge mui-badge-royal" id="photo_count"></span>
						<!--<span id="photo_edit" class="mui-icon mui-icon-compose" style="margin-left:10px"></span>
						<span id="photo_add" class="mui-icon mui-icon-plus" style="margin-left:10px;cursor: hand;"  onclick="goToPhotoList()"></span>
						-->
					</li>
				</ul>
				
				<ul class="mui-table-view mui-grid-view" id="photo_wall">
					
				</ul>
			</div>
		</div>
				<input type="hidden" id="aid" name="aid" >
				<input type="hidden" id="type" name="type" >
				<input type="hidden" id="status" name="status" >
				<input type="hidden" id="weixin_id" name="weixin_id"  />

    </body>
    <script>
    	var type = "<?php echo $_GET['type']?>"

	is_login();
    	query_activity_detail();
    	

            
  
    </script>
</html>
