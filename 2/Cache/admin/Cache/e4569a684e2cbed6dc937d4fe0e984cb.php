<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><script type="text/javascript" src="__ROOT__/Public/js/jquery-1.8.2.min.js"></script><script type="text/javascript" src="__ROOT__/Public/js/jquery.artDialog.js?skin=default"></script><script type="text/javascript" src="__ROOT__/Public/Js/iframeTools.js"></script><link href="../Public/css/style.css" rel="stylesheet" type="text/css"/><title>途乐管理后台</title></head><body style="background:#E2E9EA"><div id="header" class="header"><div class="logo"><a href="<?php echo dirname(dirname(__index__));?>" target="_blank"><img src="../Public/images/admin_logo.gif" width="180"></a></div><div class="nav f_r"><a href="__ROOT__/dc.php" target="main">更新缓存</a><i>|</i><a href="http://www.tripec.cn" target="_blank">官方网站</a><i>|</i><a href="http://www.tripec.cn" target="_blank">支持论坛</a><i>|</i><a href="http://www.tripec.cn" target="_blank">帮助中心</a><i>|</i> &nbsp;&nbsp;
    </div><div class="nav">        &nbsp;&nbsp;&nbsp;&nbsp;欢迎你！<?php echo ($user_info["login_name"]); ?><i>|</i>[<?php echo ($group_name); ?>] <i>|</i>        [<a href="<?php echo U('login/logout');?>" target="_top">退出</a>] <i>|</i><a href="<?php echo dirname(dirname(__index__));?>" target="_blank">前台首页</a></div><div class="topmenu"><ul><?php echo ($admin_nav); ?></ul></div><div class="header_footer"></div></div><div id="Main_content"><div id="MainBox"><div class="main_box"><iframe name="main" id="Main" src='<?php echo U("index/main");?>' frameborder="false" scrolling="auto" width="100%"
                    height="auto" allowtransparency="true"></iframe></div></div><div id="leftMenuBox"><div id="leftMenu"><div style="padding-left:12px;_padding-left:10px;"><?php echo ($admin_left_nav); ?></div></div><div id="Main_drop"><a href="javascript:toggleMenu(1);" class="on"><img src="../Public/images/admin_barclose.gif" width="11" height="60" border="0"/></a><a href="javascript:toggleMenu(0);" class="off" style="display:none;"><img src="../Public/images/admin_baropen.gif" width="11" height="60" border="0"/></a></div></div></div><div id="footer" class="footer"><a href="" target="_blank">tule</a><span id="run"></span></div><script language="JavaScript">    if (!Array.prototype.map)
        Array.prototype.map = function (fn, scope) {
            var result = [], ri = 0;
            for (var i = 0, n = this.length; i < n; i++) {
                if (i in this) {
                    result[ri++] = fn.call(scope, this[i], i, this);
                }
            }
            return result;
        };
    var getWindowWH = function () {
        return ["Height", "Width"].map(function (name) {
            return window["inner" + name] ||
                    document.compatMode === "CSS1Compat" && document.documentElement[ "client" + name ] || document.body[ "client" + name ]
        });
    }
    window.onload = function () {
        if (!+"\v1" && !document.querySelector) { //IE6 IE7
            document.body.onresize = resize;
        } else {
            window.onresize = resize;
        }
        function resize() {
            wSize();
            return false;
        }
    }
    function wSize() {
        var str = getWindowWH();
        var strs = new Array();
        strs = str.toString().split(","); //字符串分割
        var h = strs[0] - 95 - 30;
        $('#leftMenu').height(h);
        $('#Main').height(h);
    }
    wSize();


    function sethighlight(n, url) {
        $('.topmenu li span').removeClass('current');
        $('#menu_' + n + ' span').addClass('current');
        $('#leftMenu dl').hide();
        $('#nav_' + n).show();
        $('#leftMenu dl dd').removeClass('on');
        $('#nav_' + n + ' dd').eq(0).addClass('on');
        url = url ? url : $('#nav_' + n + ' dd a').eq(0).attr('href');
        window.main.location.href = url;
    }
    function sethigh(){
        var id_char=$('.topmenu li:eq(0)').attr("id");
        var id_cut=id_char.split("_");
        var n=id_cut[1];
        $('#menu_' + n + ' span a').trigger("click");
    }
    sethigh();
    function gourl(n, url) {
        $('#leftMenu dl dd').removeClass('on');
        $('#nav_' + n).addClass('on');
        window.main.location.href = url;
    }

    function gocacheurl() {
        mainurl = window.main.location.href;
        window.main.location.href = "__APP__" + encodeURIComponent(mainurl);
    }
    function toggleMenu(doit) {
        if (doit == 1) {
            $('#Main_drop a.on').hide();
            $('#Main_drop a.off').show();
            $('#MainBox .main_box').css('margin-left', '24px');
            $('#leftMenu').hide();
        } else {
            $('#Main_drop a.off').hide();
            $('#Main_drop a.on').show();
            $('#leftMenu').show();
            $('#MainBox .main_box').css('margin-left', '224px');
        }
    }
</script></body></html>