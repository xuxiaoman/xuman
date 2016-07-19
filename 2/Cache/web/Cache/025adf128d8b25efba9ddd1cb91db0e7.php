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
<link rel="stylesheet" href="../Public/css/hotel_detailed.css"/>
<link rel="stylesheet" href="../Public/css/hotel.css"/>
<link rel="stylesheet" href="../Public/css/hotels.css"/>
<script type="text/javascript" src="../Public/js/jquery.city_list_plugs.js"></script>
<script type="text/javascript" src="../Public/js/hotel.js"></script>

<div class="wrapper w1200">
  <div class="col-24 suffix_20"></div>
  <div class="wrapper w1200">
    <div class="col-6 col-first nav nav_adv1">
      <div class="step_main_add col-only ">
        <div class=" sidebar_title">
          <h5><strong>酒店查询</strong></h5>
        </div>
        <div class="col-6 side_form1">
          <form action="<?php echo U('lists');?>" name="hotelForm">
            <div class="side_form2">
                <div class="form_nav">目的城市：<input name="cid" type="hidden" id="cid" value="<?php echo ($search["city_info"]["cid"]); ?>"><input type="text" size="28px" for-val="cid" id="cityName" class="inputText city-list-plugs" url="<?php echo U('get_hotel_city');?>" value="<?php echo ($search["city_info"]["names"]); ?>"></div>
                <div class="form_nav">入住日期：<input name="start_date" type="text" class="ui-date" size="28px" value="<?php echo ($search["start_date"]); ?>"></div>
                <div class="form_nav">离店日期：<input name="end_date" type="text" class="ui-date" size="28px" value="<?php echo ($search["end_date"]); ?>"></div>
                <div class="form_nav">酒店名称：<input name="" type="text" size="28px"></div>
                <div colspan="2" class="filter_check  btn_z" ><button class="btn info">酒店查询</button></div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div>
      <div class="col-18 col-last advertisement" style="position: relative">
        <div class="eislideshow" id="ei-slider" style="height: 250px">
          <ul class="ei-slider-large">
                <?php $_result=M("advert")->table("jee_advert ad") ->join("jee_advert_area area on ad.area_id=area.id") ->where("area.status=1 and area.names_en='hotel_banner' and ad.start_time<=1465877099 and (ad.end_time=0 or ad.end_time>=1465877099)") ->order("ad.id desc") ->limit("3") ->field("ad.*,area.names") ->select(); if ($_result):$adcount=count($_result);$i=0; foreach($_result as $ad):++$i;?><li><a target="_blank" href="<?php echo ($ad["url"]); ?>"><img src="<?php echo (get_file($ad["pic"])); ?>" alt="<?php echo (get_file($ad["pic"],'names')); ?>"/></a></li><?php endforeach; endif;?>
            </ul>
            <ul class="ei-slider-thumbs">
                <?php for($i=0;$i<$adcount;$i++): if(($i) == "0"): ?><li class="active_thumbs"><a href="#"></a></li>
                    <?php else: ?>
                    <li><a href="#"></a></li><?php endif; endfor;?>
            </ul>
        </div>
      </div>
    </div>
  </div>

  <div class="clear" style="padding:3px;"></div>

  <div class="wrapper w1200">

	 <div class="col-6 " style="margin-left:0;">

      <div class="col-6 col-first nav nav_adv" style="margin-bottom:10px;">
        <div class=" alpha" style="margin:0">
          <div class="step_main_right_add alpha">
            <div class=" sidebar_title alpha">
              <h5 class="col-5 sidebar_title_f"><strong>酒店点评</strong></h5>
            </div>
            <ul >
              <?php if(is_array($impr)): $i = 0; $__LIST__ = $impr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="hotel_search">
                  <div class="hotel_comment" style=" padding-left:8px;"><span class=" font_brown1"><b><a href="<?php echo U('hotel_detailed');?>?hotel=<?php echo ($vo["hotelid"]); ?>"><?php echo ($vo["names"]); ?></a></b></span><br/>
                    <div class=" font_brown1"><?php echo ($vo["content"]); ?></div>
                    <div>用户
                      <?php if(($vo["show_name"]) == "1"): echo ($vo["username"]); ?>
                        <?php else: ?>
                        （匿名）<?php endif; ?>
                      获得<span class="c_red">￥<?php echo ($vo["bonus_comm"]); ?></span> 奖金 </div>
                  </div>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
          </div>
        </div>
      </div>
  
      <div class="clear"></div>
  
      <div class="col-6 col-first nav nav_adv">
          <div class="step_main_right_add alpha ">
              <dl>
                <dt><h5><strong>可靠保障 值得信赖</strong></h5></dt>
                <dd class="chang adv_down_right1" ><a href="#"><span class="adv_down_btn">酒店订单状态说明</span></a></dd>
                <dd class="chang adv_down_right2"><a href="#"><span class="adv_down_btn">酒店网上预订须知</span></a></dd>
                <dd class="chang adv_down_right3"><a href="#"><span class="adv_down_btn">酒店预定常见问题</span></a></dd>
                <dd class="chang adv_down_right4"><a href="#"><span class="adv_down_btn">酒店预订流程</span></a></dd>
              </dl>
          </div>
      </div>

    </div>

    <div class="col-18 col-last">
      <div class="col-18 hotel_main">
        <div class="title col-18">
          <h4><strong>热门酒店</strong></h4>
          <span class="hot_more"><a class="btn default xs more" href="javascript:;" onClick="hotelForm.submit();">更多</a></span>
          <div class="clear"></div>
          <div class="title-bottom"></div>
        </div>
        <ul>
          <?php if(is_array($hot_hotels)): $i = 0; $__LIST__ = $hot_hotels;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="clear"></div>
            <div class="filter_list_main list"> <img src="<?php echo (get_hotel_img($vo["id"],'p')); ?>" style="width:150px;height:104px;"/>
              <div class="list_content">
                <div class="on"><a class="title" href="<?php echo U('hotel_detailed',array('hotel'=>$vo['id']));?>" target="_blank"><?php echo ($vo["names"]); ?></a> </div>
                <span class="special ellipsis" style="line-height:22px;"><small>HOT</small><?php echo (msubstr(f_html($vo["detail"]),0,76)); ?></span>
                <p class="content_info"> 满意度：<span class="warning"><?php echo($vo['total_points']/$vo['sum_people'])?($vo['total_points']/$vo['sum_people']):0;?>%</span> <span class="default"><?php echo ($vo["sum_people"]); ?></span>人点评</p>
              </span> </div>
              <div class="content"> <span class="content_rmb">￥</span><span class="content_price"><?php echo ($vo["price"]); ?> </span>起
                <div class="booked">访问量：<?php echo ($vo["hits"]); ?>次</div>
                <div class="moreinfo">
                  <div class="btn-more"><a href="<?php echo U('hotel_detailed',array('hotel'=>$vo['id']));?>" target="_blank">查看详情</a></div>
                </div>
              </div>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
      </div>

      <div class="col-18 hotel_main">
        <div class="title col-18">
          <h4><strong>特价酒店</strong></h4>
          <span class="hot_more"><a class="btn default xs more" href="javascript:;" onClick="hotelForm.submit();">更多</a></span>
          <div class="clear"></div>
          <div class="title-bottom"></div>
        </div>
        <ul class="preferential">
          <?php if(is_array($price_hotels)): $i = 0; $__LIST__ = $price_hotels;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('hotel_detailed',array('hotel'=>$vo['id']));?>" target="_blank"><img src="<?php echo (get_hotel_img($vo["id"],'p')); ?>" style="width:214px;height:140px;"/>
              <div class="caption"><?php echo ($vo["names"]); ?></div></a>
              <div class="info">￥</span><span class="highlight"><?php echo ($vo["price"]); ?></span>起</div>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
      </div>
    </div>
  </div>

</div>
<div class="clear"></div>
<div id="footer">途乐欢迎您
    </div>
</body>
</html>