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
<link rel="stylesheet" href="../Public/css/travel.css"/>

<div class="wrapper w1200">
  <div class="clear"></div>
    <div class="col-24 step_top" style="padding-left:20px;">
        <div class="col-6 step"><img class="step_img" width="100%" src="./Public/images/step_bg.png"></img><span class="offset-2 step_font">1.选择路线</span></div>
        <div class="col-6 step_two"><img width="100%" src="./Public/images/step_bg3.png"></img><span class="offset-2 step_font">2.提交订单</span></div>
        <div class="col-6 step_three"><img width="100%" src="./Public/images/step_bg2.png"></img><span class="offset-2 step_font">3.等待支付</span></div>
        <div class="col-6 step_four"><img width="100%" src="./Public/images/step_bg4.png"></img><span class="offset-2 step_font">4.支付完成</span></div>
    </div>
  <div class="col-24 untreated">
    <div class="untreated_main">
      <div class="number">
        <div class="number_icon"><img src="../Public/images/r.jpg" width="53" height="48" /></div>
        <div class="number_font"> <span class="number_font_css">订单号：<span class="font_red"><?php echo ($usinfo["code"]); ?></span></span> <span class="number_font_css">订单已提交，正在为您处理（稍后我们工作人员确认订单后，请您进入会员中心进行支付）</span> </div>
      </div>
      <div class="handle">
        <div class="handle_font"> <span class="font_brown">如因供应商下班期间（22：00-8：30），我们无法为您立即核实信息，将在早上8：30后处理您的订单并回电给您。请您耐心等待。</span><span class="font_brown">请您在出团后60天内点评出团的旅游线路（超过60天视为放弃），我们将返还50元至您的帐户。</span> </div>
      </div>
      <div class="step_title">
        <h1 class="step_title_css">旅客信息</h1>
      </div>
      <div class="visitor">
        <table width="896px" class="table_list">
          <tr>
            <th>编号</th>
            <th>路线名称</th>
            <th>出发城市</th>
            <th>出发日期</th>
            <th>人数</th>
            <th>联系人</th>
            <th>联系电话</th>
            <th>总金额</th>
            <th>使用奖金</th>
            <th>使用卡券</th>
                <th>预付金额</th>
          </tr>
          <tr>
            <td><?php echo ($usinfo["code"]); ?></td>
            <td><?php echo ($usinfo["names"]); ?></td>
            <td><?php echo (_get_city($usinfo["go_city"])); ?></td>
            <td><?php echo ($usinfo["go_time"]); ?></td>
            <td><?php echo ($usinfo["num"]); ?></td>
            <td><?php echo ($usinfo["contact_name"]); ?></td>
            <td><?php echo ($usinfo["contact_num"]); ?></td>
            <td><?php echo ($usinfo["amount"]); ?></td>
            <td><?php echo ($usinfo["used_award"]); ?></td>
            <td><?php echo ($usinfo["pri_card"]); ?></td>
                 <td><?php echo ($usinfo["earnest"]); ?></td>
          </tr>
        </table>
      </div>
      <div class="handle_btn">
                <div class="handle_btn_one">
                   <a class="btn3 orange button" href="<?php echo U('userline/route_order',array('id'=>$_GET['order_id']));?>">查看本订单</a>
                </div>

                <div class="handle_btn_one">
                    <a class="btn3 orange button" href="<?php echo U('travel/detail',array('id'=>$_GET['order_id']));?>">返回线路详细页</a>
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