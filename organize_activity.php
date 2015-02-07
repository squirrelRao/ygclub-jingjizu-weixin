<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
		<title></title>
		
		<link rel="stylesheet" href="css/mui.css" />
		<link rel="stylesheet" href="css/jquery-ui.css" />
		<link rel="stylesheet" href="css/jquery-ui.structure.css" />
		<link rel="stylesheet" href="css/jquery-ui.theme.css" />
		<link rel="stylesheet" href="css/jquery-ui-timepicker-addon.css" />
		<style>
			.mui-popover {
				height: 300px;
			}
			.mui-content {
				padding: 10px;
			}
			#topPopover {
				position: fixed;
				top: 16px;
				right: 6px;
			}
			#topPopover .mui-popover-arrow {
				left: auto;
				right: 6px;
			}

		</style>
		<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
		<script src="js/jquery.js"></script>
	    <script src="js/jquery-ui.js"></script>
	    <script src="js/jquery.ui.datepicker-zh-CN.js"></script>
	    	<script src="js/jquery-ui-timepicker-addon.js"></script>
	    	<script src="js/jquery-ui-timepicker-zh-CN.js"></script>
	    	
		<script src="js/mui.js"></script>
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
			
			function showWarning(tip){
				
				$("#tip_span").text(tip);
				$("#tip").show();
			}
			
			function hideWarning(){
				
				$("#tip").hide();
			}
			
			function validateTheme(){
				
				if($("#theme").val()==null || $("#theme").val().trim() == ""){
					return false;
				}
				return true;
			}
			
			function validateActivity_time(){
				
				if($("#activity_time").val()==null || $("#activity_time").val().trim() == ""){
					return false;
				}
				return true;
			}
			
			function validateDeadline_time(){
				
				if($("#deadline_time").val()==null || $("#deadline_time").val().trim() == ""){
					return false;
				}
				return true;
			}
			
			function validateLocation(){
				
				if($("#location").val()==null || $("#location").val().trim() == ""){
					return false;
				}
				return true;
				
				
			}
			
			function validateDetail(){
				
				if($("#detail").val()==null || $("#detail").val().trim() == ""){
					return false;
				}
				return true;
			}
			
			
			//validate activity weather created 
			function check_activity(theme,activity_time,location,orgnizer){
				
				var url = "core/controller/ActivityController.php?action=checkActivity&theme="+theme+"&activity_time="+activity_time+"&location="+location+"&orgnizer="+orgnizer;
				
//				alert(url);
				var result = false;
				$.ajax({
					type:"get",
					url: url,
					timeout:2000,
					async:false,
					success:function(data,status){
//						alert(data);
						var obj = eval("("+data+")");

						if(obj.ret_code == "200" && obj.ret_memo == "0"){
							result = true;
						}
					}
				});
				return result;
			
			}
			
			function check_activityTime(){
				var time = $("#activity_time").val();
				var d_date = time.split(" ")[0].split("-");
				var d_time = time.split(" ")[1].split(":");
				var t_date = new Date();
				t_date.setFullYear(parseInt(d_date[0]));
				t_date.setMonth(parseInt(d_date[1])-1);
				t_date.setDate(parseInt(d_date[2]));
				t_date.setHours(parseInt(d_time[0]));
				t_date.setMinutes(parseInt(d_time[1]));
				t_date.setSeconds(0);
				var ts = t_date - (new Date());//计算剩余的毫秒数
				if(ts < 0){
					showWarning("活动时间须在当前时间之后");
					return false;
				}
				return true;
			}
			
			function check_deadlineTime(){
				var time = $("#activity_time").val();
				var d_date = time.split(" ")[0].split("-");
				var d_time = time.split(" ")[1].split(":");
				var t_date = new Date();
				t_date.setFullYear(parseInt(d_date[0]));
				t_date.setMonth(parseInt(d_date[1])-1);
				t_date.setDate(parseInt(d_date[2]));
				t_date.setHours(parseInt(d_time[0]));
				t_date.setMinutes(parseInt(d_time[1]));
				t_date.setSeconds(0);
				
				var deadline = $("#deadline_time").val();
				var deadline_date = deadline.split(" ")[0].split("-");
				var deadline_time = deadline.split(" ")[1].split(":");
				var td_date = new Date();
				td_date.setFullYear(parseInt(deadline_date[0]));
				td_date.setMonth(parseInt(deadline_date[1])-1);
				td_date.setDate(parseInt(deadline_date[2]));
				td_date.setHours(parseInt(deadline_time[0]));
				td_date.setMinutes(parseInt(deadline_time[1]));
				td_date.setSeconds(0);
				
				var ts = t_date - td_date;//计算剩余的毫秒数
				
				if(ts < 0 || (td_date - (new Date())) < 0){
					showWarning("截止时间须在活动时间之前,当前时间之后");
					return false;
				}
				return true;
			}
			
			//submit new activity 
			function submit_activity(){
				
				
				var theme = $("#theme").val();
				var activity_time = $("#activity_time").val();
				var deadline_time = $("#deadline_time").val();
				var activity_loc= $("#location").val();
				var orgnizer = $("#weixin_id").val();
				var detail = $("#detail").val();
				var phone = $("#phone").val();
				
				if(!validatePhone()){
					return;
				}
				
				if(!validateTheme()){
					showWarning("还没有填写活动主题呀");
					return;
				}
				
				if(!validateActivity_time()){
					showWarning("活动时间咧 ？");
					return;
				}
				
				if(!check_activityTime()){
					return;
				}
				
				
				if(!validateDeadline_time()){
					showWarning("报名截止时间也要写啊");
					return;
				}
				
				if(!check_deadlineTime()){
					return;
				}
				
				if(!validateLocation()){
					showWarning("活动地点一定要填呀");
					return;
				}
		
				if(!check_activity(theme,activity_time,activity_loc,orgnizer)){
					showWarning("已经发起过这个活动啦...不要重复发起噢");
					return;
				}
				
				if($("#theme").val().length > 10){
					showWarning("主题字数超长，最多10个字");
					return;
				}
				
				if($("#detail").val().length > 240){
					showWarning("主题字数超长，最多240个字");
					return;
				}
				
				if(!window.confirm("确定要发起活动嘛?")){
					return;
				}
				
				var orgnizer = $("#weixin_id").val();
				
				var params = "theme="+theme+"&activity_time="+activity_time+"&deadline_time="+deadline_time+"&location="+activity_loc+"&detail="+detail+"&orgnizer="+orgnizer+"&phone="+phone;
				var url = "core/controller/ActivityController.php?action=newActivity&"+params;
				
//				alert(url);

				$.ajax({
					type:"get",
					url: url,
					timeout:2000,
					async:true,
					success:function(data,status){
						var obj = eval("("+data+")");
						if(obj.ret_code == "200"){
							alert("恭喜,成功发起了一次活动....");
							document.location.href="activity_list.php?weixin_id="+$("#weixin_id").val();
						}else{
							alert("发起活动失败...请稍后再试");
						}
					}
				});
			
				
			}
		
		  function backToList(){
		  		var weixin_id = 	 $("#weixin_id").val();
				document.location.href = "activity_list.php?weixin_id="+weixin_id;

		  }
		  
		  function validatePhone(){
				
				if($("#phone").val()==null || $("#phone").val().trim() == ""){
					showWarning("需要填写一下你的手机号");

					return false;
				}
				var reg=/^(13[0-9]{9})|(15[89][0-9]{8})|(186[0-9]{8})|(170[0-9]{8})$/;      
				if (!reg.test($("#phone").val())) {
					showWarning("手机号不是天朝的吧...");

					return false	;			
				}
				
				return true;
			}
			
			function textCounter(field, countfield, maxlimit) {    
			
			var lastLength = maxlimit - $("#"+field).val().length;
			if(lastLength < 0){
				$("#"+countfield).attr("class","mui-badge mui-badge-danger");
				$("#"+countfield).text("超长"+lastLength+"个字"); 
			}else{
				//在记数区文本框内显示剩余的字符数；    
				$("#"+countfield).attr("class","mui-badge mui-badge-royal");
				$("#"+countfield).text("还可输入"+lastLength+"个字"); 
			}
			
			   
			}    

		</script>
	</head>
	<body>
		<header class="mui-bar mui-bar-nav">
			<button class="mui-btn mui-pull-left" onclick="document.location.href='index.php'">
				取消
			</button>
			<button class="mui-btn mui-pull-right" onclick="submit_activity()">
				发起
			</button>
			<h1 class="mui-title">
				发起活动
			</h1>
		</header>
		<div class="mui-content">
			<div class="mui-content-padded">
			<h5 id="tip"><span class="mui-icon mui-icon-info" style="color:red"></span><span style="color:red" id="tip_span">活动主题还没有填写呀 ...</span></h5>
			<h5 ><span class="mui-icon mui-icon-phone"></span>发起人手机号：</h5>
			<input  type="text"  name="phone" id="phone" maxlength="11" value=""/>
			<h5 ><span class="mui-icon mui-icon-flag"></span>活动主题<span class="mui-badge mui-badge-royal" id="theme_count">最多10个字写明主题</span></h5>
			<input  type="text"  name="theme" id="theme" value="" onKeyDown="textCounter('theme','theme_count',10);" onKeyUp=
"textCounter('theme','theme_count',10);"/>
		    <h5 ><span class="mui-icon mui-icon-star"></span>活动时间</h5>	
		    <div id="datepicker" width="90%"></div>
  			<input type="text" name="activity_time" id="activity_time" value="" />
		    <h5 ><span class="mui-icon mui-icon-starhalf"></span>报名截止时间</h5>
		    	<input type="text" name="deadline_time" id="deadline_time" value="" />

		    <h5 ><span class="mui-icon mui-icon-location"></span>活动地点</h5>
		    <input type="text" name="location" id="location" value="" />
		    <h5><span class="mui-icon mui-icon-spinner-cycle mui-spin"></span>活动详情<span class="mui-badge mui-badge-royal" id="detail_count">可写240个字</span></h5>
			<textarea name="detail" id="detail" rows="3" onKeyDown="textCounter('detail','detail_count',240);" onKeyUp=
"textCounter('detail','detail_count',240);"></textarea>
			</div>
		</div>
		<!-- off-canvas backdrop -->
		<div class="mui-off-canvas-backdrop"></div>
		

		</div>
		<input type="hidden" id="weixin_id" name="weixin_id"  />
    </body>
    <script>
	    $('#activity_time').datetimepicker();
	    $('#deadline_time').datetimepicker(); 
	    hideWarning();
	    $("#weixin_id").val("<?php echo $_GET['weixin_id']?>");
	</script>
</html>
