<?php
error_reporting(E_ALL);

define('TimeZone', +8.0);

function _GET($n) { return isset($_GET[$n]) ? $_GET[$n] : NULL; }
function _SERVER($n) { return isset($_SERVER[$n]) ? $_SERVER[$n] : '[undefine]'; }

if (_GET('act') == 'phpinfo') {
  if (function_exists('phpinfo')) phpinfo();
  else echo 'phpinfo() has been disabled.';
  exit;
}

$Info = array();
$Info['php_ini_file'] = function_exists('php_ini_loaded_file') ? php_ini_loaded_file() : '[undefine]';

if (_GET('act') == 'getip') {
  $i = _SERVER('SERVER_NAME').'|'._SERVER('REMOTE_ADDR').'|'._SERVER('SERVER_SOFTWARE').'|'.function_exists('mysql_close') ? mysql_get_client_info() : ''.'|'._SERVER('DOCUMENT_ROOT');
  $c = @file_get_contents('http://phpnow.org/myip.php?'.base64_encode($i));
  if (preg_match('/^\d+\.\d+\.\d+\.\d+$/', $c) == 1) echo $c;
  else echo 'false';
  exit;
}

function colorhost() {
  $c = array('#87cefa', '#ffa500', '#ff6347', '#9acd32', '#32cd32', '#ee82ee');
  $a = str_split(_SERVER('SERVER_NAME'));
  $k = $l = 0;
  foreach ($a as &$d) {
    while ($k==$l) $k = array_rand($c);
    $d = '<b style="color: '.$c[$k].';">'.$d.'</b>';
    $l = $k;
  }
  return implode('', $a);
}

function get_ea_info($name) { $ea_info = eaccelerator_info(); return $ea_info[$name]; }
function get_gd_info($name) { $gd_info = gd_info(); return $gd_info[$name]; }

define('YES', '<span style="color: #008000; font-weight : bold;">Yes</span>');
define('NO', '<span style="color: #ff0000; font-weight : bold;">No</span>');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>JeeStore</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="YinzCN" />
<meta name="reply-to" content="YinzCN@Gmail.com" />
<meta name="copyright" content="YinzCN" />
<link rel="stylesheet" type="text/css" href="skin/css/global.css">
<style type="text/css">
<!--
body {
	font-family : verdana, tahoma;
	font-size : 12px;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}

form {
margin : 0;
}

table {
border-collapse : collapse;
}

.info tr td {
border : 1px solid #000000;
padding : 3px 10px 3px 10px ;
}

.info th {
border : 1px solid #000000;
font-weight : bold;
height : 16px;
padding : 3px 10px 3px 10px;
}

input {
border : 1px solid #000000;
background-color : #fafafa;
}

a {
text-decoration : none;
color : #000000;
}

a:hover {
text-decoration : underline;
}

a.arrow {
font-family : webdings, sans-serif;
font-size : 10px;
}

a.arrow:hover {
color : #ff0000;
text-decoration : none;
}

.item {
white-space: nowrap;
text-align: right;
}
-->
</style>
<script type="text/JavaScript">
function $(id) { return document.getElementById(id); }

function get_ip() {
  var XMLHttp, r;
  XMLHttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
  XMLHttp.onreadystatechange = function() {
    if (XMLHttp.readyState == 4)
    {
      r = XMLHttp.responseText;
      //if (r == 'false') $('ip_r').innerHTML = '锟斤拷取锟斤拷锟斤拷 IP 失锟斤拷!';
      //else $('ip_r').innerHTML = '锟剿凤拷锟斤拷锟斤拷j锟斤拷 IP<br /><a href="http://'+r+'" style="color: #999999;">'+r+'</a>';
    }
  }
  XMLHttp.open("GET", "?act=getip", true);
  XMLHttp.send();
}
</script>
</head>
<body onload="get_ip();">

<table width="100%" class="info" align="center">
  <tr>
    <td class="item">SERVER_NAME</td>
    <td><?=_SERVER('SERVER_NAME')?></td>
  </tr>

  <tr>
    <td class="item">SERVER_ADDR:PORT</td>
    <td><?=_SERVER('SERVER_ADDR').':'._SERVER('SERVER_PORT')?></td>
  </tr>

  <tr>
    <td class="item">SERVER_SOFTWARE</td>
    <td><?=stripos(_SERVER('SERVER_SOFTWARE'), 'PHP')?_SERVER('SERVER_SOFTWARE'):_SERVER('SERVER_SOFTWARE').' PHP/'.PHP_VERSION?></td>
  </tr>

  <tr>
    <td class="item">PHP_SAPI</td>
    <td><?=PHP_SAPI?></td>
  </tr>

  <tr>
    <td class="item" style="color: #ff0000;">php.ini</td>
    <td><?=$Info['php_ini_file']?></td>
  </tr>

  <tr>
    <td class="item">网站主目录</td>
    <td><?=_SERVER('DOCUMENT_ROOT')?></td>
  </tr>

  <tr>
    <td class="item">Server Date / Time</td>
    <td><?=gmdate('Y-m-d', time()+TimeZone*3600)?> <?=gmdate('H:i:s', time()+TimeZone*3600)?> <span style="color: #999999;">(<?=(TimeZone<0?'-':'+').gmdate('H:i', abs(TimeZone)*3600)?>)</span></td>
  </tr>

  <tr>
    <td class="item">Other Links</td>
    <td>&nbsp;</td>
  </tr>
</table>

<table width="100%" class="info">
<tr>
    <td class="item">Zend Optimizer</td>
    <td><?=defined('OPTIMIZER_VERSION') ? YES.' / '.OPTIMIZER_VERSION : NO?></td>
  </tr>

  <tr>
    <td class="item">MySQL 支持</td>
    <td><?=function_exists('mysql_close') ? YES.' / client lib version '.mysql_get_client_info() : NO?></td>
  </tr>

  <tr>
    <td class="item">GD library</td>
    <td><?=function_exists('gd_info') ? YES.' / '.get_gd_info('GD Version') : NO?></td>
  </tr>

  <tr>
    <td class="item">eAccelerator</td>
    <td><?=function_exists('eaccelerator_info') ? YES.' / '.get_ea_info('version') : NO?></td>
  </tr>
</table>

</body>
</html>