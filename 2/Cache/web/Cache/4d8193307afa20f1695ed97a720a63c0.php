<?php if (!defined('THINK_PATH')) exit();?> 
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="../Public/assets/css/workless.css">
    <link rel="stylesheet" href="../Public/css/style_index.css">
    <script src="../Public/assets/js/jquery-1.8.3.min.js" type="text/javascript"></script>
    <script src="../Public/assets/js/workless.js" preload="*" type="text/javascript"></script>
    <script src="../Public/js/jquery.city_list_plugs.js" type="text/javascript"></script>
    <script src="../Public/js/index.js" type="text/javascript"></script>
    <script src="__ROOT__/Public/Plugins/jquery.artDialog/jquery.artDialog.js?skin=default" type="text/javascript"></script>
    <script type="text/javascript" src="__ROOT__/Public/Plugins/jquery.artDialog/iframeTools.js"></script>
    <title>途乐 世界这么大，我想去走走</title>
</head>
<body>

<div id="header">
    <div class="topbar">
        <div class="wrapper w1200">
            <ul class="login">

                <div class="citybg"><a href="#" class="city">成都</a> [<a href="#" tabindex="1" class="citychange cityoff">切换城市</a>]
                    <div class="cityList" tabindex="2">
                        <div class="outer">
                            <ul class="clearfix">
                                <?php if(is_array($cityList)): $i = 0; $__LIST__ = $cityList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$city_item): $mod = ($i % 2 );++$i; if(($city_item["cid"]) != $currentCity["id"]): ?><li>
                                            <a href="<?php echo U('Common/handoff',array('city'=>$city_item['names_en']));?>"><?php echo ($city_item["names"]); ?></a>
                                        </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                                <li class="clear"></li>
                            </ul>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <?php if(isset($_SESSION['user_id'])): ?><a href="<?php echo U('/userinfo/revise_userdetail');?>"  class="highlight"><?php echo ($_SESSION['user_name']); ?></a> | <a href="<?php echo U('login/logout');?>"  class="highlight">注销</a>
                <?php else: ?>
                <a href="<?php echo U('/login');?>" class="highlight">登陆</a> | <a href="<?php echo U('register/register');?>"  class="highlight">注册</a><?php endif; ?>
            </ul>
            <ul class="help">
                <a href="<?php echo U('article/detail');?>?detail=2">帮助中心</a> | <a href="<?php echo U('index/feedback');?>" target="_blank">意见反馈</a> | <a onclick="addFavorite('<?php echo U('index/index');?>', document.title)" href="javascript:void(0)">收藏本站</a> | <a href="<?php echo U('article/detail');?>?detail=1">关于我们</a> | <a href="<?php echo U('article/notice');?>">网站公告</a>
            </ul>
        </div>
    </div>
    <div class="wrapper w1200">
         <div style="float:left;margin-top:30px"><iframe name="sinaWeatherTool" src="http://weather.news.sina.com.cn/chajian/iframe/weatherStyle2.html?city=%E6%88%90%E9%83%BD" width="200" height="80" marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="no"></iframe></div>
        <div class="logo"><img src="../Public/images/logo.jpg"></div>
        <ul class="nav">
            <li><a href="__Index__"  <?php if(($current) == "index"): ?>class="onthis"<?php endif; ?>>首页</a></li>
            <?php if(is_array($tour_type)): $i = 0; $__LIST__ = $tour_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tid): $mod = ($i % 2 );++$i;?><li>
                    <a href="<?php echo U('travel/travel',array('type'=>$tid['id'],'current_city'=>$currentCity['en']));?>"
                    <?php if(($current) == $tid["id"]): ?>class="onthis"<?php endif; ?>><?php echo ($tid["names"]); ?>
                    </a>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
            <li><a href="<?php echo U('/hotel');?>"  <?php if(($current) == "hotel"): ?>class="onthis"<?php endif; ?>>酒店宾馆</a></li>
            <li><a href="<?php echo U('/viewpoint');?>" <?php if(($current) == "viewpoint"): ?>class="onthis"<?php endif; ?>>景点门票</a></li>
            <li><a href="<?php echo U('article/detail');?>?detail=2"  <?php if(($current) == "help"): ?>class="onthis"<?php endif; ?>>帮助中心</a></li>
        </ul>
    </div>
</div>
<!--#names 登录页面 #-->
<link rel="stylesheet" href="../Public/css/register.css"/>
<div class="wrapper w1200">
  <div class="col-24 content alpha">
    <div class="col-24 content_top alpha">
      <h1 class="col-10 content_top_font">会员登录</h1>
    </div>
    <div class="clear"></div>
    <div class="col-24" style="padding-top:20px;">
        
      <div class="col-12 content_main_left" style='width:600px;'>
          
        <form action="<?php echo U('login/login',$_GET);?>" method="post" class="check-form">
          <div class="col-12 content_main_left_number"><span class="col-2 content_main_left_number_title tit_msg">会员账号 :</span>
	    
            <div class="col-5 content_main_left_number_number2">
              <input name="username" datatype="s5-18" type="text" class="col-5 content_main_left_number_number2_box">
            </div>
              <div class="Validform_tipbox" for="username"></div>
          </div>
          <div class="col-12 content_main_left_number">
            <span class="col-2 content_main_left_number_title">登录密码 :</span>
	    
            <div class="col-5 content_main_left_number_number2">
              <input name="password" datatype="s5-18" type="password" class="col-5 content_main_left_number_number2_box" autocomplete="off">
            </div>
            <div class="Validform_tipbox" for="password"></div>
            <div class="col-3">
            </div>
          </div>
          <div class="clear"></div>
          <div class="col-7 login_forget">
            
            <div class="col-2 remember_id"><input type="checkbox" name="login_state" value="1" class=" checkbox_login">记住密码</div>
	    <div class="col-3 forget_font"><a href="<?php echo U('register/retrieve_password');?>">忘记密码？</a></div> 	  
	    
            <div class="col-3 btn_login1"><input type="submit" class="btn green" value="登 录"></div>
          </div>
        </form>
      </div>
      <div class="col-8 content_main_right">
        <div class="font_login">
          <div class="font_login_title"><span class=" content_main_right_number_title">没有账号，立即注册</span></div>
          <div class="btn_login">
            <a href="<?php echo U('register/register');?>"><img src="../Public/images/btn_user reg.jpg" width="135" height="32"/></a></div>
        </div>
      </div>
    </div>
  </div>
  <div class="clear"></div>
</div>

<div class="clear"></div>
<div id="footer">途乐欢迎您
    </div>
</body>
</html>