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
		</script>
	</head>
	<body>
		<header class="mui-bar mui-bar-nav">
			<button class="mui-btn mui-pull-left" onclick="document.location.href='activity_list.php'">
				取 消
			</button>
			<button class="mui-btn mui-pull-right" onclick="document.location.href='person_info.php'">
				报 名
			</button>
			<h1 class="mui-title">
				罗丹雕塑艺术展
			</h1>
		</header>
		<div class="mui-content">
			<div class="mui-content-padded">
				<ul class="mui-table-view">
					<li class="mui-table-view-cell ">						
							活动时间:
						<label>2015-01-14 14:00 -16:00</label>
					</li>
					<li class="mui-table-view-cell">
					     离截止还有:
						<label style="color:purple;font-weight: bold;">11天9小时28分</label>
					</li>
					<li class="mui-table-view-cell">
						活动地点：
						<label> 东长安街16号 </label>
						<span class="mui-icon mui-icon-location"></span>
					</li>
					<li class="mui-table-view-divider">
						发起人：
					</li>
					<li class="mui-table-view-cell">
						
						
						<a href="#">
							<img class="mui-media-object" style="border-radius:50%"  src="http://dcloudio.github.io/mui/assets/img/cbd.jpg">
						</a>
			
						<button class="mui-btn mui-btn-positive mui-btn-primary" style="width:95px;height:40px;">
							联系发起人
						</button>
					</li>
					<li class="mui-table-view-divider">
						确认报名： <span class="mui-badge mui-badge-primary">12人已报名</span>
					</li>
					<li class="mui-table-view-cell">
						<span>
								<a href="#">
							<img class="mui-media-object" style="border-radius:50%"  src="http://dcloudio.github.io/mui/assets/img/cbd.jpg">
							</a>
								<a href="#">
							<img class="mui-media-object" style="border-radius:50%"  src="http://dcloudio.github.io/mui/assets/img/cbd.jpg">
							</a>
								<a href="#">
							<img class="mui-media-object" style="border-radius:50%"  src="http://dcloudio.github.io/mui/assets/img/cbd.jpg">
							</a>
								<a href="#">
							<img class="mui-media-object" style="border-radius:50%"  src="http://dcloudio.github.io/mui/assets/img/cbd.jpg">
							</a>
								<a href="#">
							<img class="mui-media-object" style="border-radius:50%"  src="http://dcloudio.github.io/mui/assets/img/cbd.jpg">
							</a>
		
								<a class="mui-navigate-down" id="more_person_icon" href="javascript:void(0)" onclick="showMorePerson()">
		</a>
					</li>
					<li class="mui-table-view-cell" id="more_person" style="display: none;">
						<span>
								<a href="#">
							<img class="mui-media-object" style="border-radius:50%"  src="http://dcloudio.github.io/mui/assets/img/cbd.jpg">
							</a>
								<a href="#">
							<img class="mui-media-object" style="border-radius:50%"  src="http://dcloudio.github.io/mui/assets/img/cbd.jpg">
							</a>
								<a href="#">
							<img class="mui-media-object" style="border-radius:50%"  src="http://dcloudio.github.io/mui/assets/img/cbd.jpg">
							</a>
								<a href="#">
							<img class="mui-media-object" style="border-radius:50%"  src="http://dcloudio.github.io/mui/assets/img/cbd.jpg">
							</a>
								<a href="#">
							<img class="mui-media-object" style="border-radius:50%"  src="http://dcloudio.github.io/mui/assets/img/cbd.jpg">
							</a>
					</li>
					<li class="mui-table-view-divider">活动详情</li>
					<li class="mui-table-view-cell">
					    2013年，楚天都市报和湖北美术馆联手，共同打造“筑梦时间——东湖2013全国青年雕塑家邀请赛暨优秀作品展”，旨在为雕塑家提供展示自我的平台，挖掘雕塑界新星。 
作为美化城市的重要艺术表现形式，公共雕塑已经成为大众生活的一部分。雕塑与自然如何更好的和谐共存，提升一座城市的文化氛围，正是我们要探讨的课题。 
最终，大赛将会把获奖的优秀作品陈列在东湖畔，湖光水色与雕塑作品交相辉映，为大众展示雕塑之美，将艺术融入一座城市。

					</li>
				</ul>
			</div>
		</div>
		
    </body>
</html>
