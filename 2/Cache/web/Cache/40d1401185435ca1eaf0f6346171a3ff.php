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
<link rel="stylesheet" href="../Public/css/user.css"/>
<link rel="stylesheet" href="../Public/css/viewpoint.css"/>
<link rel="stylesheet" href="../Public/css/center.css"/>
<link rel="stylesheet" href="../Public/css/route_q.css"/>
<link rel="stylesheet" href="../Public/css/feature.css"/>
<div class="wrapper w1200">
    <div class="clear"></div>
	<div class="row">
<div class="page_button_list">

  <div class="col-5 member_menu member_menu_nav">
  <div class="title1"><h4><a href="<?php echo U('user/index');?>" style="color:white;">我的会员首页</a></h4></div>
<ul class="sideMenu">
    <li><h5>旅游线路</h5></li>
    <ul class="sm_list">
      <li><a href="<?php echo U('userline/route_mag');?>">旅游路线订单</a></li>
      <li><a href="<?php echo U('userline/route_review');?>">旅游路线点评</a></li>
      <li><a href="<?php echo U('userline/route_questions');?>">旅游路线问答</a></li>
      <li><a href="<?php echo U('userline/route_coll');?>">旅游路线收藏</a></li>
    </ul>
    <li ><h5>酒店管理</h5></li>
    <ul class="sm_list">
      <li><a href="<?php echo U('userhotel/hotel');?>">酒店订单</a></li>
      <li><a href="<?php echo U('userhotel/hotel_review');?>">酒店点评</a></li>
      <li><a href="<?php echo U('userhotel/hotel_questions');?>">酒店问答</a></li>
      <li><a href="<?php echo U('userhotel/hotel_coll');?>">酒店收藏</a></li>
    </ul>
    <li><h5>景点管理</h5></li>
    <ul class="sm_list">
      <li><a href="<?php echo U('userviewpoint/index');?>">景点订单</a></li>
      <li><a href="<?php echo U('userviewpoint/faq');?>">景点问答</a></li>
      <li><a href="<?php echo U('userviewpoint/collection');?>">景点收藏</a></li>
    </ul>
    
    <li><h5>个人账户信息</h5></li>
    <ul class="sm_list">
      <li><a href="<?php echo U('userinfo/revise_userdetail');?>">我的个人资料</a></li>
      <li><a href="<?php echo U('userinfo/finance_list');?>">账户明细</a></li>
      <!--<li><a href="<?php echo U('withdraw/withdraw_list');?>">申请提现</a></li>-->
      <li><a href="<?php echo U('userinfo/message_list');?>">我的短信</a></li>
    </ul>
  </ul>
</div>

</div>
	
		
		<div class="col-19 omega page_content page_content_nav">
      <div style="margin-top:12px;"></div>
           <div class=" alpha order">
        	 <div class="title_order">
               <div class="order_font">查看订单</div>
             </div>
             <div class="col-18 alpha order_main">
               订单号：<?php echo ($list["code"]); ?>[下单时间：<?php echo (f_time($list["create_time"])); ?>]
             <br />订单状态：<?php if($list["o_status"] == '1'): ?>待处理
                          <?php elseif($list["o_status"] == '2'): ?>待付款
                              <?php elseif($list["o_status"] == '3'): ?>已付款
                                  <?php elseif($list["o_status"] == '4'): ?>已出团
                                      <?php elseif($list["o_status"] == '5'): ?>已点评
                                          <?php elseif($list["o_status"] == '6'): ?>已结束
                                              <?php elseif($list["o_status"] == '7'): ?>已取消<?php endif; ?></div>
             <div class="clear"></div>
             <div class="title_order2">
             <div class="col-18 alpha order_main">
               旅游线路名称：<?php echo ($list["names"]); ?></div>
             </div>
             <div class="col-18 alpha order_main">
               出游人数：<?php echo (count($guest_list)); ?>人</div>
            
             <div class="clear"></div>
             <div class="title_order2">
             <div class="col-18 alpha order_main">
               总金额：<span class="order_main_red"><b><?php echo ($list["amount"]); ?>元</b></span></div>
             </div>
             
             <div class="clear"></div>
             
             <div class="col-18 alpha order_main">
                使用奖金：<span class="order_main_red"><b><?php echo ($list["used_award"]); ?>元</b></span></div>
             <div class="clear"></div>
             <div class="title_order2">
             <div class="col-18 alpha order_main">定金：<span class="order_main_red"><b><?php echo ($list["earnest"]); ?>元</b></span></div>
             </div>
             <div class="col-18 alpha order_main">
               出发日期：<span><?php echo (f_date($list["go_time"])); ?></span></div>
               <div class="clear"></div>
             <div class="title_order2">
             <div class="col-18 alpha order_main">
               联系人：<?php echo ($list["contact_name"]); ?></div>
             </div>
             <div class="col-18 alpha order_main">
               联系方式：<span>电话：<?php echo ($list["contact_num"]); ?>   E-mail：<?php echo ($list["contact_email"]); ?></span></div>
               <div class="clear"></div>
               <?php if(is_array($guest_list)): $i = 0; $__LIST__ = $guest_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="title_order2">
             <div class="col-18 alpha order_main">
                 第<?php echo ($i); ?>位游客：<span>姓名：<?php echo ($vo["names"]); ?> / </span><?php if($vo["credentials"] == '0'): ?>身份证<?php elseif($vo["credentials"] == '1'): ?>护照<?php elseif($vo["credentials"] == '2'): ?>军官证<?php elseif($vo["credentials"] == '3'): ?>回乡证<?php elseif($vo["credentials"] == '4'): ?>台胞证<?php elseif($vo["credentials"] == '5'): ?>国际海员证<?php else: ?>--<?php endif; ?>：<?php echo ($vo["content"]); ?></div>
                   
             </div><?php endforeach; endif; else: echo "" ;endif; ?>
               <div class="title_order2" style="background-color: #FFF;">
               <div class="col-18 alpha order_main">
               其他要求：<?php echo ($list["contact_msg"]); ?></div>
               </div>
               <div class="title_order2">
             <div class="col-18 alpha order_main" style="">
               点评奖金：<span class="order_main_red"><b><?php echo ($list["review_award"]); ?>元</b></span></div>
             </div>
           </div>
             
        </div>
    </div>
</div>
<div class="clear"></div>
<div id="footer">途乐欢迎您
    </div>
</body>
</html>