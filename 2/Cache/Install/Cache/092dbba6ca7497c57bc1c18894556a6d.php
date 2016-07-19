<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>安装详细过程 - TripEC 旅游电商网站 - 安装向导</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../Public/Js/jquery.min.js" language="javascript" type="text/javascript"></script>
<script src="../Public/Js/jquery.form.js" language="javascript" type="text/javascript"></script>
<script src="../Public/Js/jquery.validate.js" language="javascript" type="text/javascript"></script>
<link href="../Public/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript">
<!--
	$(document).ready(function(){
		$(".table tr").each(function(){ $(this).children("td").eq(0).addClass("on");});
		$("input[type='text']").addClass("input_blur").mouseover(function(){ $(this).addClass("input_focus");}).mouseout(function(){ $(this).removeClass("input_focus");});
		$(".table tr").mouseover(function(){ $(this).addClass("mouseover");}).mouseout(function(){ $(this).removeClass("mouseover");	});
	});
-->
</script>

</head>
<body>

<div class="main">
<h5>感谢您选择 TripEC 旅游电商网站</h5>

<div class="title"><ul>
<li  >安装许可协议</li>
<li  >运行环境检测</li>
<li  >安装参数设置</li>
<li  class="on" >安装详细过程</li>
<li  >安装完成</li>
</ul></div>

<div class="box"><div class="b1">


		<div class="left">
			
		</div>


 
	<div class="right">
		<h2>Step 4 of 5 </h2>

		<h1>正在安装</h1>
	 
		<div id="setupinfo">正在开始安装...<br></div>
		
		<div class="butbox">
		<input type="button" class="inputButton" value=" 上一步 " onclick="window.location.href='index.php?step=3';" style="margin-right:20px" />
		<input name="dosubmit" type="submit" class="inputButton nonext" id="dosubmit" value=" 下一步 " disabled  />
	</div>
	</div>
</div>
<div class="power">Powered by <a href="">TripEC</a></div>
</div></div>

<script language="javascript" type="text/javascript">
var n=0;
var data = <?php echo json_encode($_POST);?>;
function reloads(n,importfile) {
		var url =  "./index.php?step=4&install=1&n="+n+"&importfile="+importfile;
		$.ajax({
			 type: "POST",		
			 url: url,
			 data: data,
			 dataType: 'json',
			 beforeSend:function(){
			 },
			 success: function(msg){
				if(msg.n=='999999'){
					$('#setupinfo').append(msg.msg);
					$('#dosubmit').attr("disabled",false);
					$('#dosubmit').removeAttr("disabled");				
					$('#dosubmit').removeClass("nonext");
					setTimeout('gonext()',5000);
					return false;
				}
				if(msg.n || msg.n==0){
					$('#setupinfo').append(msg.msg);
					reloads(msg.n,msg.importfile);                                       
                                        var div=$('#setupinfo')[0];
                                        div.scrollTop = div.scrollHeight;
				}else{
			             alert(msg.msg);
				}			 
			}
		});
}
function gonext(){
	window.location.href='index.php?step=5';
}
$(document).ready(function(){
		reloads(n,"");
})
</script>
</body>
</html>