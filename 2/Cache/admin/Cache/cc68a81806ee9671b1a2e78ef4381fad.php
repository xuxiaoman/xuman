<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=<?php echo C('DEFAULT_CHARSET');?>"/><title><?php echo C('welcome');?></title><script type="text/javascript">        var _root_ = '__ROOT__';
        var _url_ = '__URL__';
        var _upload_ = '__UPLOAD__';
        var _app_ = '__APP__';
        var _public_ = '__PUBLIC__';
        var _index_ = '__Index__';
    </script><link rel="stylesheet" type="text/css" href="../Public/css/style.css"/><script type="text/javascript" src="__ROOT__/Public/js/jquery-1.8.2.min.js"></script><script type="text/javascript" src="__ROOT__/Public/Plugins/jquery.validate.js"></script><script type="text/javascript" src="__ROOT__/Public/Plugins/jquery.validate.check.js"></script><script type="text/javascript">            if (self != top) {
                window.top.location.href = '<?php echo U("login/index");?>';
            }

            function getRandom(n) {
                return Math.floor(Math.random() * n + 1)
            }
            function getsource(obj) {
                var url = "<?php echo U('Common/verify');?>/r/";
                obj.src = url + Math.random()
            }
    </script></head><body width="100%"><div id="result" class="result none"></div><div class="login"><script type="text/javascript"></script><form action="__URL__/" method="post"><table class="login" border="0" cellspacing="0" cellpadding="0" align="center"><tr><td width="45%" align="right"><img src="../Public/images/top_logo.gif"/></td><td width="25px" align="center"><img src="../Public/images/login_line.gif"/></td><td><table border="0" cellspacing="0" cellpadding="0"><tr><td width="67" height="35" align="right" id="td_loginname">用户名：</td><td><label><input type="text" name="login_name" id="login_name"></label></td></tr><tr><td height="35px" align="right" id="td_pwd">密&nbsp;&nbsp;&nbsp;码：</td><td><label><input type="password" name="pwd" id="pwd"></label></td></tr><tr><td height="35" align="right" id="td_check">验证码：</td><td><label><input maxlength="10" size="4" name="verify" id="verify"></label>    &nbsp;<img src="<?php echo U('Common/verify');?>" align="absmiddle" onclick="getsource(this);" style="cursor:pointer;"/></td></tr><tr><td height="35">&nbsp;</td><td><label><input name="submit" id="submit" type="submit" value="登 录" style="width:80px; height:30px;"></label></td></tr><tr><td height="25">&nbsp;</td><td><div class="div_msg"><?php echo ($message); ?></div></td></tr></table></td></tr></table></form><div class="clear"></div><div id="footer">途乐欢迎您
    </div></body></html>