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
		
		
			
			//submit new activity 
			function addAdmin(){
				var username = $("#username").val();
				var pwd = $("#password").val();
	
				if(!validateName()){
					showWarning("登录名还没有填的啊");
					return;
				}
				
				if(!validatePassword()){
					showWarning("密码还没有填的啊");
					return;
				}
				
				hideWarning();
				
				
				var params = "&username="+username+"&password="+pwd;
				var url = "../core/controller/AdminController.php?action=addAdmin"+params;
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
			     			
			     			alert("新增了一枚管理员！");
			     			hideWarning();
							
						}else{
							showWarning("系统异常,请稍后再试...");
						}
					}
				});
			
				
			}
			
			function showWarning(tip){
				
				$("#tip_span").text(tip);
				$("#tip").show();
			}
			
			function hideWarning(){
				
				$("#tip").hide();
			}
			
			function validateName(){
				
				if($("#username").val()==null || $("#username").val().trim() == ""){
					return false;
				}
				return true;
			}
			
			
			function validatePassword(){
				
				if($("#password").val()==null || $("#password").val().trim() == ""){
					return false;
				}
				return true;
			}
			
			
		
		</script>
	</head>
	<body>
		<header class="mui-bar mui-bar-nav">
			<button class="mui-btn mui-pull-left" onclick="document.location.href='http://121.42.40.81/weixin/'">
				返回
			</button>
			<h1 class="mui-title">
				新增管理员
			</h1>
		</header>
		<div class="mui-content" height="100%">
			<div class="mui-content-padded">
			<h5 id="tip"><span class="mui-icon mui-icon-info" style="color:red"></span><span style="color:red" id="tip_span"></span></h5>
			<h5 ><span class="mui-icon mui-icon-flag"></span>管理员用户名</h5>
			<input  type="text"  name="username" id="username" value="" />
		    <h5 ><span class="mui-icon mui-icon-flag"></span>设置密码</h5>	
		    <input type="password" name="password" id="password"  value="" />	    
			<div class="mui-button-row">
				<button class="mui-btn mui-btn-positive mui-btn-primary" style="width:280px;height:40px;" onclick="addAdmin()">增加管理员</button>
		</div>
		</div>
			</div>
		</div>
		<!-- off-canvas backdrop -->
		<div class="mui-off-canvas-backdrop"></div>
    </body>
    <script>
    	 
    </script>
</html>
