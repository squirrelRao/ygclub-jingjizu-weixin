<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
		<title></title>
		
		<link rel="stylesheet" href="css/mui.css" />
		
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
.btn{position: relative;overflow: hidden;margin-right: 4px;display:inline-block; 
*display:inline;padding:4px 10px 4px;font-size:14px;line-height:18px; 
*line-height:20px;color:#fff; 
text-align:center;vertical-align:middle;cursor:pointer;background:#5bb75b; 
border:1px solid #cccccc;border-color:#e6e6e6 #e6e6e6 #bfbfbf; 
border-bottom-color:#b3b3b3;-webkit-border-radius:4px; 
-moz-border-radius:4px;border-radius:4px;} 
.btn input{position: absolute;top: 0; right: 0;margin: 0;border:solid transparent; 
opacity: 0;filter:alpha(opacity=0); cursor: pointer;} 
.progress{position:relative; margin-left:100px; margin-top:-24px;  
width:200px;padding: 1px; border-radius:3px; display:none} 
.bar {background-color: green; display:block; width:0%; height:20px;  
border-radius:3px; } 
.percent{position:absolute; height:20px; display:inline-block;  
top:3px; left:2%; color:#fff } 
.files{height:22px; line-height:22px; margin:10px 0} 
.delimg{margin-left:20px; color:#090; cursor:pointer} 
		</style>
		<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
		<script src="js/jquery.js"></script>
		<script src="js/jquery.mobile-1.4.5.js"></script> 
<!--		<script src="js/jquery.form.js"></script> 
-->		<script src="js/mui.js"></script>
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
			
		
			
		$(function () {

});
			
			function showWarning(tip){
				
				$("#tip_span").text(tip);
				$("#tip").show();
			}
			
			function hideWarning(){
				
				$("#tip").hide();
			}
			
			//append activity info into html
			function update_photo_wall(obj){
				
				$("#photo_count").text( obj.length == 0 ? "没有上传过照片" : "上传了"+obj.length+"张照片（最多上传5张）");
				 $(obj).each(function(i) { 
                       	
                   
                       var item = "<li class=\"mui-table-view-cell mui-media mui-col-xs\">";
                       	   item += "<a href=\"#\">";
						   item +="<img class=\"mui-media-object\"  src=\""+obj[i].path+"\" />";
						   item += "<div class=\"mui-media-body\" style='height:20px' ><span class=\"mui-badge mui-badge-blue\" onclick=\"delete_photo('"+obj[i].path+"')\">删除照片</span></div>";
						   item +="</a>";
						   item += "</li>"
						
					 	$("#photo_wall").append(item);
                       	
                  }); 
			}
			
			
			//validate activity weather created 
			function query_photos(){
				
				var aid = $("#aid").val();
				var pid = $("#weixin_id").val();
				var url = "core/controller/ActivityController.php?action=queryPersonPhotos&aid="+aid+"&pid="+pid;
				
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

						if(obj.ret_code == "200"){
							if(obj.ret_res.length == 5){
								$("#upload_button").hide();
							}else{
								$("#upload_button").show();
							}
							update_photo_wall(obj.ret_res);
						}
					}
				});
				return result;
			
			}
			
			//validate activity weather created 
			function delete_photo(path){
				
				var url = "core/controller/ActivityController.php?action=deletePhoto&path="+path;
				
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
			
			
		
		  function backToList(){
		  		var weixin_id = 	 $("#weixin_id").val();
		  		var aid = $("#aid").val();
				document.location.href = "activity_detail.php?weixin_id="+weixin_id+"&aid="+aid+"&type=detail";

		  }

		</script>
	</head>
	<body>
		<header class="mui-bar mui-bar-nav">
			<button class="mui-btn mui-pull-left" onclick="backToList()">
				返回
			</button>
		</header>
		
		<div class="mui-content">
		<div data-role="content"> 
        <form action="uploadFileMobile.php?aid=<?php echo $_GET['aid']?>&weixin_id=<?php echo $_GET['weixin_id']?>" method="post" enctype="multipart/form-data" data-ajax="false"> 
        <input type="file" name="file" id="file" accept="image/*" class="mui-btn mui-btn-yellow"/>  
        <button type="submit" name="submit"  class="mui-btn mui-btn-blue"/>
        上传文件 
        </button>
        </form> 
    </div>
			</h5>
			</div>
			<div class="mui-content-padded">
				<h5><span class="mui-badge mui-badge-success" id="photo_count">---</span></h5>
				<ul class="mui-table-view mui-grid-view" id="photo_wall">
	
</ul>
			</div>
		</div>
		<!-- off-canvas backdrop -->
		<div class="mui-off-canvas-backdrop"></div>
		</div>
		<input type="hidden" id="weixin_id" name="weixin_id"  />
				<input type="hidden" id="aid" name="aid"  />

    </body>
    <script>
	  
	    hideWarning();
	    $("#weixin_id").val("<?php echo $_GET['weixin_id']?>");
	    	    $("#aid").val("<?php echo $_GET['aid']?>");
	query_photos();
 var url = "uploadFile.php?aid="+$("#aid").val()+"&pid="+$("#weixin_id").val();
        	 $("#photo_form").attr("action",url); 
//	$("#photo_form").wrap("<form id='myupload' action='uploadFile.php?aid="+$("#aid").val()+"&pid="+$("#weixin_id").val()+"' method='post' enctype='multipart/form-data'></form>");
	</script>
	
</html>
