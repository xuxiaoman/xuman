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
	
		
<link rel="stylesheet"  href="../Public/css/route_q.css"/>
    <div class="col-19 omega page_content route_mag_nav">
    <div style="margin-top:12px;"></div>
		<ul class="side1 ">
            
           
             <li <?php if(($_GET['s']) == ""): ?>class="col-2 title alpha on col-full"<?php else: endif; ?>><a href="<?php echo U('userline/route_mag');?>" class="col-2 title">全部</a></li>
             <li <?php if(($_GET['s']) == "1"): ?>class="col-2 title alpha on col-full"<?php else: endif; ?>><a href="<?php echo U('userline/route_mag',array('s'=>'1'));?>" class="col-2 title">待处理</a></li>
             <li <?php if(($_GET['s']) == "2"): ?>class="col-2 title alpha on col-full"<?php else: endif; ?>><a href="<?php echo U('userline/route_mag',array('s'=>'2'));?>" class="col-2 title">待付款</a></li>
             <li <?php if(($_GET['s']) == "3"): ?>class="col-2 title alpha on col-full"<?php else: endif; ?>><a href="<?php echo U('userline/route_mag',array('s'=>'3'));?>" class="col-2 title">已付款</a></li>
             <li <?php if(($_GET['s']) == "4"): ?>class="col-2 title alpha on col-full"<?php else: endif; ?>><a href="<?php echo U('userline/route_mag',array('s'=>'4'));?>" class="col-2 title">已取票</a></li>
             <li <?php if(($_GET['s']) == "5"): ?>class="col-2 title alpha on col-full"<?php else: endif; ?>><a href="<?php echo U('userline/route_mag',array('s'=>'5'));?>" class="col-2 title">已点评</a></li>
             <li <?php if(($_GET['s']) == "6"): ?>class="col-2 title alpha on col-full"<?php else: endif; ?>><a href="<?php echo U('userline/route_mag',array('s'=>'6'));?>" class="col-2 title">已结束</a></li>
             <li <?php if(($_GET['s']) == "7"): ?>class="col-2 title alpha on col-full"<?php else: endif; ?>><a href="<?php echo U('userline/route_mag',array('s'=>'7'));?>" class="col-2 title">已取消</a></li>
              
        </ul>
            <div class="col-18 alpha body">
            	<table width="100%" class="table_list">
                	<tr>
                      <th>编号</th>
                      <th>名称</th>
                      <th>出发日期</th>
                      <th>总金额</th>
                      <th>点评金额</th>
                      <th>下单时间</th>
                      <th>状态</th>
                      <th>操作</th>
                    </tr>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                      <td><?php echo ($vo["code"]); ?></td>
                      <td>
                                        <li><a href="<?php echo U('travel/detail',array(id=>$vo['line_id']));?>" target="_blank"><?php echo ($vo["names"]); ?></a></li>
                      </td>
                      <td><?php echo (f_date($vo["go_time"])); ?></td>
                      <td><?php echo ($vo["amount"]); ?>元</td>
                      <td><?php echo ($vo["review_award"]); ?></td>
                      <td><?php echo (f_date($vo["create_time"])); ?></td>
                      <td><?php if($vo["o_status"] == '1'): ?>待处理
                          <?php elseif($vo["o_status"] == '2'): ?>待付款
                              <?php elseif($vo["o_status"] == '3'): ?>已付款
                                  <?php elseif($vo["o_status"] == '4'): ?>已出团
                                      <?php elseif($vo["o_status"] == '5'): ?>已点评
                                          <?php elseif($vo["o_status"] == '6'): ?>已结束
                                              <?php elseif($vo["o_status"] == '7'): ?>已取消<?php endif; ?></td>
                      <td><a href="<?php echo U('userline/route_order',array('id'=>$vo['o_id']));?>">查看</a> <?php if($vo["o_status"] == '1'): ?><a href="<?php echo U('userline/del_order',array('id'=>$vo['o_id']));?>" onclick="return confirm('确定撤销该订单吗?',this.href)">撤销</a>
                          <?php elseif($vo["o_status"] == '2'): ?><a href="<?php echo U('userline/del_order',array('id'=>$vo['o_id']));?>" onclick="return confirm('确定撤销该订单吗?',this.href)">撤销</a> <a href="<?php echo U('travel/order_success',array(order_id=>$vo['o_id']));?>">付款</a>
                              <?php elseif($vo["o_status"] == '3'): ?>
                                  <?php elseif($vo["o_status"] == '4'): ?><a href="<?php echo U('userline/route_impr',array('id'=>$vo['o_id']));?>">点评</a>
                                      <?php elseif($vo["o_status"] == '5'): ?>
                                          <?php elseif($vo["o_status"] == '6'): ?>
                                              <?php elseif($vo["o_status"] == '7'): endif; ?></td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </table>
                    <div class="page"><?php echo ($page); ?></div>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>
<div id="footer">途乐欢迎您
    </div>
</body>
</html>