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
			function queryParticipants(){
				
				var url = "core/controller/ActivityController.php?action=queryParticipants&aid="+$("#aid").val();

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
                       			 append_info(obj.ret_res[i]);
                  			  }); 
						}else{
							alert("服务器繁忙...请稍后再试");
						}
					}
				});
			}
			
			//append activity info into html
			function append_info(obj){
				var selfId = $("#weixin_id").val();
				var dom = "<ul class=\"mui-table-view\">";
					dom += "	<li class=\"mui-table-view-cell\">";
//			
				    dom += "	<div class=\"mui-media-body\">";
				    dom += obj.participant_id;
				    
				    dom += "	<p class=\"mui-ellipsis\">";
				    dom += "	<span class=\"mui-icon mui-icon-person\"></span>";
				    dom +=  obj.name;
				    dom += "</p>";
				    dom += "<p class=\"mui-ellipsis\"><span class=\"mui-icon mui-icon-phone\"></span>";
				    dom +=  obj.phone;
				    dom += "</p><p class=\"mui-ellipsis\"><span class=\"mui-badge mui-badge-success\">";
				    if(obj.status == '0'){
				    	   dom+="已退出活动";
				    }else{
				    	   dom+="参加活动";
				    }
	                dom += "</p></ul>";
				$("#content_div").append(dom);
			}
			
			
		  function backToDetail(){
		  		var weixin_id = 	 $("#weixin_id").val();
		  		var aid = $("#aid").val();
				document.location.href = "activity_detail.php?weixin_id="+weixin_id+"&aid="+aid+"&type=detail";

		  }
			
	
		</script>
	</head>
	<body>
		<header class="mui-bar mui-bar-nav">
			<button class="mui-btn mui-pull-left" onclick="backToDetail()">
				返回
			</button>
			<h1 class="mui-title">
				报名参加活动的小伙伴
			</h1>
		</header>
		<div class="mui-content" height="100%">
			<div class="mui-content-padded" id="content_div">
	
			</div>
		</div>
				<input type="hidden" id="weixin_id" name="weixin_id"  />
								<input type="hidden" id="aid" name="aid"  />


		<div id="mui-popover" class="mui-popover">
  <header class="mui-bar mui-bar-nav">
    <h1 class="mui-title">Popover title</h1>
  </header>
</div>
<script>
	 $("#weixin_id").val("<?php echo $_GET['weixin_id']?>");
	 	 $("#aid").val("<?php echo $_GET['aid']?>");

	queryParticipants();
	
</script>
    </body>
</html>
