<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>运行环境检测 - TripEC 旅游电商网站 - 安装向导</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../Public/Js/jquery.min.js" language="javascript" type="text/javascript"></script>
<script src="../Public/Js/jquery.form.js" language="javascript" type="text/javascript"></script>
<script src="../Public/Js/jquery.validate.js" language="javascript" type="text/javascript"></script>
<link href="../Public/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript">
	$(document).ready(function(){
		$("input[type='text']").addClass("input_blur").mouseover(function(){ $(this).addClass("input_focus");}).mouseout(function(){ $(this).removeClass("input_focus");});
		$(".table tr").mouseover(function(){ $(this).addClass("mouseover");}).mouseout(function(){ $(this).removeClass("mouseover");	});
	});
</script>
</head>
<body>
<div class="main">
  <h5>感谢您选择TripEC旅游电商网站</h5>
  <div class="title">
    <ul>
      <li  >安装许可协议</li>
      <li  class="on" >运行环境检测</li>
      <li  >安装参数设置</li>
      <li  >安装详细过程</li>
      <li  >安装完成</li>
    </ul>
  </div>
  <div class="box">
    <div class="b1">
      <div class="left">
        
      </div>
      <div class="right">
        <h2>Step 2 of 5 </h2>
        <h1>服务器信息</h1>
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table">
          <tr>
            <th width="300" align="left"><strong>参数</strong></th>
            <th width="424" align="left"><strong>值</strong></th>
          </tr>
          <tr>
            <td><strong>服务器域名/IP地址</strong></td>
            <td><?php echo ($name); ?>/<?php echo ($host); ?></td>
          </tr>
          <tr>
            <td><strong>服务器操作系统</strong></td>
            <td><?php echo ($os); ?></td>
          </tr>
          <tr>
            <td><strong>服务器解译引擎</strong></td>
            <td><?php echo ($server); ?></td>
          </tr>
          <tr>
            <td><strong>PHP版本</strong></td>
            <td><?php echo ($phpv); ?></td>
          </tr>
          <tr>
            <td><strong>安装路径</strong></td>
            <td><?php echo ($stiedit); ?></td>
          </tr>
          <tr>
            <td><strong>脚本超时时间</strong></td>
            <td><?php echo ($max_execution_time); ?></td>
          </tr>
        </table>
        <h1>系统环境要求</h1>
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="table">
          <tr>
            <th width="230"><strong>需开启的变量或函数</strong></th>
            <th width="100"><strong>系统要求</strong></th>
            <th width="400"><strong>实际状态及建议</strong></th>
          </tr>
          <tr>
            <td>GD 支持 </td>
            <td>支持</td>
            <td><?php echo ($gd); ?></td>
          </tr>
          <tr>
            <td>CURL支持 </td>
            <td>支持</td>
            <td><?php echo ($curl); ?></td>
          </tr>
          <tr>
            <td>fsockopen支持 </td>
            <td>支持</td>
            <td><?php echo ($fsockopen); ?></td>
          </tr>
          <tr>
            <td>邮件功能 (mail) </td>
            <td>支持</td>
            <td><?php echo ($mail); ?></td>
          </tr>
          <tr>
            <td>MySQL 支持</td>
            <td>支持</td>
            <td><?php echo ($mysql); ?></td>
          </tr>
          <tr>
            <td>upload</td>
            <td>支持</td>
            <td><?php echo ($uploadSize); ?></td>
          </tr>
          <tr>
            <td>session</td>
            <td>支持</td>
            <td><?php echo ($session); ?></td>
          </tr>
        </table>
        <h1>目录权限检测</h1>
        <div style="margin:10px auto; color:#666;"> 系统要求必须满足下列所有的目录权限全部可读写的需求才能使用。</div>
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table">
          <tr>
            <th width="230"><strong>目录名</strong></th>
            <th width="100"><strong>系统要求</strong></th>
            <th width="400"><strong>实际状态及建议</strong></th>
          </tr>
          <?php echo ($readwrite); ?>
        </table>
        <div class="butbox">
          <input type="button" class="inputButton" value=" 上一步 " onclick="history.back();" style="margin-right:20px" />
          <?php if(empty($err)): ?><input type="button" class="inputButton" value=" 下一步 " onclick="window.location.href='index.php?step=3';"   />
          <?php else: ?>
          <input type="button" class="inputButton" value=" 下一步 " onclick="window.location.href='index.php?step=3';" id="nonext"  disabled  /><?php endif; ?>
          <input type="button" class="inputButton" value=" 重新检测 " onclick="document.location.reload();" style="margin-left:20px" />
        </div>
      </div>
    </div>
    <div class="power">Powered by <a href="">TripEC</a></div>
  </div>
</div>
</body>
</html>