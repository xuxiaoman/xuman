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
<link rel="stylesheet" href="../Public/css/hotels.css"/>

<div class="wrapper w1200">
  <div class="clear"></div>
  <div class="col-24 " style="margin-top:10px; background:#FFF; padding:10px 0;">
   <div class="col-24 step_top1">
    <div class="col-6 step_one">
    <img  class="step_img" src="../Public/images/step_bg.png" width="100%"/> <span class="offset-1 col-5 step_font">1.选择酒店/房间</span></div>
    <div class="col-6 step_two">
     <img src="../Public/images/step_bg3.png" width="100%"/> <span class="offset-1 col-5 step_font">2.填写核对订单</span></div>
    <div class="col-6 step_three">
     <img src="../Public/images/step_bg2.png" width="100%"/> <span class="offset-1 col-5 step_font">3.成功提交订单/支付</span></div>
    <div class="col-6 step_four">
     <img src="../Public/images/step_bg4.png"  width="100%" /> <span class="offset-1 col-5 step_font">4.点评拿奖金</span></div>
  </div>
  <div class="col-24 untreated">
    <div class="untreated_main">
      <div class="handle">
       <div class="number">
        <div class="number_icon"><img src="../Public/images/r.jpg" width="53" height="48" /></div>
        <div class="number_font"> <span class="number_font_css"><span class="font_red">订单号：<?php echo ($usinfo["serial_num"]); ?></span></span> <span class="number_font_css">订单已提交，正在为您处理（稍后我们工作人员确认订单后，请您进入会员中心进行支付）</span> </div>
       </div>        <div class="handle_font"> <span class="font_brown" style='height:70px;'></span></div>
      </div>
      <div class="step_title">
        <h1 class="step_title_css">旅客信息</h1>
      </div>
      <div class="visitor">
          <table width="860px" height="70" class="table_list_pay">
            <tr>
              <th width="135px">编号</th>
              <th width="150px">景点名称</th>
              <th width="180px">门票张数</th>
              <th width="130px">总金额</th>
              <th width="180px" >使用卡券</th>
              <th width="155px">使用奖金</th>
            </tr>
            <tr>
              <td><?php echo ($usinfo["serial_num"]); ?></td>
              <td><?php echo ($usinfo["view_name"]); ?></td>
              <td><?php echo ($usinfo["ticket_num"]); ?>张</td>
              <td><?php echo ($usinfo["all_money"]); ?>元</td>
              <td><?php if(($usinfo["coupon_num"]) != ""): echo ($usinfo["coupon_num"]); ?>
                  <?php else: ?>
                  -<?php endif; ?></td>
              <td><?php if(($usinfo["use_award"]) != ""): echo ($usinfo["use_award"]); ?>元
                  <?php else: ?>
              0.00<?php endif; ?></td>
            </tr>
            </table>
            <table width="900px" height="70" class="table_list_pay">
            <tr>
              <th width="135px" >联系人</th>
              <th width="150px">联系电话</th>
              <th width="340px" style="text-align:center">门票</th>
              <th width="150px" >应付金额</th>
            </tr>
            <tr>
              <td><?php echo ($usinfo["contact_name"]); ?></td>
              <td><?php echo ($usinfo["contact_phone"]); ?></td>
              <td style="text-align:center">
                <?php if(is_array($usinfo["tickets"])): $i = 0; $__LIST__ = $usinfo["tickets"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; $tiks[$key] = $vo['ticket_type']."（".$vo['ticket_count']."张）"; endforeach; endif; else: echo "" ;endif; ?>
                <?php echo (join("；", $tiks)); ?>
              </td>
              <td><?php echo ($usinfo["must_pay"]); ?>元</td>              
            </tr>
          </table>
      </div>
      <center>
        <div class="handle_btn">
          <center>
            <div class="handle_btn_one"> <a class="btn3 orange button" href="<?php echo U('userviewpoint/order');?>/id/<?php echo ($usinfo["id"]); ?>">查看本订单</a> </div>
            <div class="handle_btn_one"> <a class="btn3 orange button" href="<?php echo U('viewpoint/detail');?>/id/<?php echo ($usinfo["view_id"]); ?>">继续预定该景点</a> </div>
            <div class="handle_btn_one"> <a class="btn3 orange button" href="<?php echo U('user/index');?>">返回用户中心</a> </div>
          </center>
        </div>
      </center>
    </div>
  </div>
 </div>
</div>

<div class="clear"></div>
<div id="footer">途乐欢迎您
    </div>
</body>
</html>