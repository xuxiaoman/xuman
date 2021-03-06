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
<link rel="stylesheet" href="../Public/css/register.css"/>
<link rel="stylesheet" href="../Public/css/feedback.css"/>
<div class="wrapper w1200">
  <div class="col-24 content alpha">
    <div class="content_top alpha">
      <h1 class="col-10 content_top_font">意见反馈</h1>
    </div>
    <div class="clear"></div>
    <div style="text-align:center">
      <form action="<?php echo U('feedback');?>" method="post" name="myform" id="myform" class="check-form">
        <table width="100%" border="0">
          <thead>
            <tr>
              <td colspan="3">
                <span>
                	您的宝贵建议是我们前进的动力。
                </span>
              </td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th width="400"><span>*</span>&nbsp;请填写您的意见或建议：</th>
              <td width="300"><textarea name="content" id="content" datatype="*" nullmsg="请填写您的意见或建议" sucmsg=" " errmsg="请填写您的意见或建议"></textarea></td>
              <td><div class="Validform_tipbox" for="content" style="margin:0;"></div></td>
            </tr>
            <tr>
              <th>请填写您的邮箱地址：</th>
              <td><input type="text" id="email" name="email"></td>
              <td rowspan="3" valign="top">
              </td>
            </tr>
            <tr>
              <th>请填写您的QQ号码：</th>
              <td><input type="text" id="qq" name="qq"></td>
            </tr>
            <tr>
              <th>请填写您的联系电话：</th>
              <td><input type="text" id="phone" name="phone"></td>
            </tr>
            <tr>
              <th></th>
              <td><input name="sendout" id="sendout" type="submit" value="   提交   " class="btn green"></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</div>

<div class="clear"></div>
<div id="footer">途乐欢迎您
    </div>
</body>
</html>