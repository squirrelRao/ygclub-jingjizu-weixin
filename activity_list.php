<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
		<title></title>
		<link rel="stylesheet" href="css/mui.css" />
		<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
		<script src="js/mui.js"></script>
		<script src="js/jquery.js"></script>
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
			
			//list activities 
			function query_activities(){
				
				var url = "core/controller/ActivityController.php?action=queryAll&weixin_id="+$("#weixin_id").val();

				$.ajax({
					type:"get",
					url: url,
					timeout:2000,
					async:true,
					success:function(data,status){
//						alert(data);
						var obj = eval("("+data+")");
						if(obj.ret_code == "200"){
							 $(obj.ret_res).each(function(i) { 
                       			 append_activity_info(obj.ret_res[i]);
                  			  }); 
						}else{
							alert("服务器繁忙...请稍后再试");
						}
					}
				});
			}
			
			//append activity info into html
			function append_activity_info(obj){
				var selfId = $("#weixin_id").val();
				var dom = "<ul class=\"mui-table-view\">";
					dom += "	<li class=\"mui-table-view-cell\">";
					if(obj.orgnizer==selfId || obj.signup == '1'){
						dom	+= "	<a href=\"activity_detail.php?weixin_id="+selfId+"&aid="+obj.id+"&type=detail\">";
					}else{
						 dom	+= "	<a href=\"activity_detail.php?weixin_id="+selfId+"&aid="+obj.id+"&type=signup\">";
					}
				    dom += "	<div class=\"mui-media-body\">";
				    dom += obj.theme;
				    
				    dom += "	<p class=\"mui-ellipsis\">";
				    dom += "	<span class=\"mui-icon mui-icon-star\"></span>";
				    dom +=  obj.activity_time;
				    dom += "</p>";
				    dom += "<p class=\"mui-ellipsis\"><span class=\"mui-icon mui-icon-location\"></span>";
				    dom +=  obj.location;
				    dom += "</p><p class=\"mui-ellipsis\"><span class=\"mui-badge mui-badge-success\">";
				    var lastTime = getLastTime(obj.deadline_time);
				    dom +=  lastTime+"</span><span class=\"mui-badge mui-badge-primary\">"+(obj.person_count == 0 ? "还没有小伙伴报名" : obj.person_count+"人报名")+"</span>";
				    if(obj.orgnizer.trim()==selfId.trim()){
				    	  dom += "<span class=\"mui-badge mui-badge-yellow\">我发起滴</span>";
				    }
				    dom += "</p></div></a>";
					if(obj.status == '0'){
						dom +="<button class=\"mui-btn mui-btn-primary mui-btn-yellow\" height=\"40px\" onclick=\"document.location.href='activity_detail.php?weixin_id="+selfId+"&aid="+obj.id+"&type=detail'\">已取消</button>";
					}else if(obj.orgnizer==selfId || obj.signup == "1" || lastTime == '报名已截止'){
						dom +="<button class=\"mui-btn mui-btn-primary mui-btn-primary\" height=\"40px\" onclick=\"document.location.href='activity_detail.php?weixin_id="+selfId+"&aid="+obj.id+"&type=detail'\">详 情</button>";
				    }else{
						dom += "<button class=\"mui-btn mui-btn-primary mui-btn-positive\" height=\"40px\" onclick=\"document.location.href='activity_detail.php?weixin_id="+selfId+"&aid="+obj.id+"&type=signup'\">报 名</button></li>";
	                }
	                dom += "</ul>";
				$("#content_div").append(dom);
			}
			
			
			function getLastTime(deadline_time){
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
				
				if(ts < 0){
					
					return "报名已截止";
					
				}else{
				
					var dd = parseInt(ts / 1000 / 60 / 60 / 24, 10);//计算剩余的天数
					var hh = parseInt(ts / 1000 / 60 / 60 % 24, 10);//计算剩余的小时数
					
					if(dd == 0 && hh != 0){
						return hh+"小时后截止";
					}else if (hh == 0 && dd != 0){
						return dd+"天后截止";
					}else if(dd == 0 && hh == 0){
						return "即将截止";
					}else{
						return dd+"天"+hh+"小时后截止";
					}
				}
			}
		</script>
	</head>
	<body>
		<header class="mui-bar mui-bar-nav">
			<button class="mui-btn mui-pull-left" onclick="document.location.href='index.php'">
				返回
			</button>
			<h1 class="mui-title">
				往期精彩
			</h1>
		</header>
		<div class="mui-content" height="100%">
			<div class="mui-content-padded" id="content_div">
	
			</div>
		</div>
				<input type="hidden" id="weixin_id" name="weixin_id"  />

		<div id="mui-popover" class="mui-popover">
  <header class="mui-bar mui-bar-nav">
    <h1 class="mui-title">Popover title</h1>
  </header>
</div>
<script>
	 $("#weixin_id").val("<?php echo $_GET['weixin_id']?>");
	query_activities();
	
</script>
    </body>
</html>
