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
			
			
			function goOrgnizeActivity(){
				
				var weixin_id = 	 $("input[name='radio']:checked").val();
				document.location.href = "organize_activity.php?weixin_id="+weixin_id;

			}
			
			function goViewActivity(){
				
					var weixin_id = 	 $("input[name='radio']:checked").val();
				document.location.href = "activity_list.php?weixin_id="+weixin_id;
			}
		</script>
	</head>
	<body>
		<div class="mui-content-padded">
<ul class="mui-table-view">
	<li class="mui-table-view-cell">
		阳光经济组官微功能测试入口
	</li>
	
	<li class="mui-table-view-cell">
		测试步骤： 1. 在下面列表中选择用户名（缺乏微信环境，无法获取到用户微信号）    2.选择测试功能
		
	</li>

	<li class="mui-table-view-cell mui-radio mui-left">
		<input name="radio" type="radio" value="xiaoming" checked>小明
	</li>
	<li class="mui-table-view-cell mui-radio mui-left">
		<input name="radio" type="radio" value="xiaowang">小王
	</li>
	<li class="mui-table-view-cell mui-radio mui-left">
		<input name="radio" type="radio" value="xiaozhang">小张
	</li>
	<li class="mui-table-view-cell mui-radio mui-left">
		<input name="radio" type="radio" value="xiaoli">小李
	</li>
	<li class="mui-table-view-cell">
		测试发起活动：
		<button class="mui-btn mui-btn-green" onclick="goOrgnizeActivity()">
			发起活动
		</button>
	</li>
	<li class="mui-table-view-cell">
		测试往期活动：
		<button class="mui-btn mui-btn-primary" onclick="goViewActivity()">
			往期活动
		</button>
	</li>
	<li class="mui-table-view-cell">
		后台管理：
		<button class="mui-btn mui-btn-primary  mui-btn-outlined" onclick="document.location.href='http://121.42.40.81/weixin/admin/'">
			前往后台
		</button>
	</li>
	<li class="mui-table-view-cell">
	    添加管理员：
	    <button class="mui-btn mui-btn-green mui-btn-outlined" onclick="document.location.href='http://121.42.40.81/weixin/admin/addAdmin.php'">
			前往添加
		</button>
	</li>
</ul>
</div>
		
	</body>
</html>
