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
			
			function cancelSignup(){
				
			   var aid = $("#aid").val();
		  	   document.location.href='activity_detail.php?type=signup&aid='+aid;
			}
			
			//submit new activity 
			function submit_person_info(){
				
				var aid = $("#aid").val();
				var pid = $("#weixin_id").val();
				var name = $("#name").val();
				var phone= $("#phone").val();
				
				var sex = $("input[name='sex']:checked").val();

				if(!validateName()){
					showWarning("名字还没有填的啊");
					return;
				}
				
				if(!validatePhone()){
					return;
				}
				
				hideWarning();
				if(!window.confirm("非常欢迎报名活动啊，点确定吧 ?")){
					return;
				}
				
				var params = "aid="+aid+"&pid="+pid+"&name="+name+"&phone="+phone+"&sex="+sex;
				var url = "core/controller/ActivityController.php?action=signup&"+params;
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
							alert("恭喜,报名成功啦...");
							document.location.href="activity_detail.php?weixin_id="+$("#weixin_id").val()+"&type=detail&aid="+aid;
						}else{
							alert("报名活动失败啦...请稍后再试");
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
				
				if($("#name").val()==null || $("#name").val().trim() == ""){
					return false;
				}
				return true;
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
		
		
		</script>
	</head>
	<body>
		<header class="mui-bar mui-bar-nav">
			<button class="mui-btn mui-pull-left" onclick="cancelSignup()">
				取消
			</button>
			<h1 class="mui-title">
				填写个人信息
			</h1>
		</header>
		<div class="mui-content" height="100%">
			<div class="mui-content-padded">
			<h5 id="tip"><span class="mui-icon mui-icon-info" style="color:red"></span><span style="color:red" id="tip_span">活动主题还没有填写呀 ...</span></h5>
			<h5 ><span class="mui-icon mui-icon-flag"></span>您的真实姓名</h5>
			<input  type="text"  name="name" id="name" value="" placeholder="无法修改，请慎重填写"/>
		    <h5 ><span class="mui-icon mui-icon-phone"></span>您的手机号码</h5>	
		    <input type="datetime" name="phone" id="phone" maxlength="11"  value="" placeholder="无法修改，请慎重填写"/>	    
		    <div class="mui-input-row">
			<h5><span class="mui-icon mui-icon-person"></span>性别：</h5>
			<div class="mui-card">
			<form class="mui-input-group">
							<div class="mui-input-row mui-radio">
								<label>男</label>
								<input name="sex" type="radio" checked value="m">
							</div>
							<div class="mui-input-row mui-radio">
								<label>女</label>
								<input name="sex" type="radio" value="f">
							</div>
						</form>
			</div>
			<div class="mui-button-row">
				<button class="mui-btn mui-btn-positive mui-btn-primary" style="width:280px;height:40px;" onclick="submit_person_info()">确 定</button>
		</div>
		</div>
			</div>
		</div>
		<!-- off-canvas backdrop -->
		<div class="mui-off-canvas-backdrop"></div>
		<input type="hidden" id="aid" name="aid" >
				<input type="hidden" id="weixin_id" name="weixin_id" >

    </body>
    <script>
    	    	var aid = "<?php echo $_GET['aid']?>"
    	    		 $("#weixin_id").val("<?php echo $_GET['weixin_id']?>");

        $("#aid").val(aid);
    		hideWarning();
    </script>
</html>
