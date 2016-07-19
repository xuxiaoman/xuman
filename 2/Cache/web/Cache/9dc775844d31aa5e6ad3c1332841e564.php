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
<link rel="stylesheet" href="../Public/css/view.css"/>
<link rel="stylesheet" href="../Public/css/view_detail_1.css"/>
<script type="text/javascript" src="../Public/js/view.js"></script>

<script src="__ROOT__/Public/js/jquery.cityLink.js"></script>
<script src="http://api.map.baidu.com/api?key=02b627b5ad5892889e9384d61d91bd08&v=1.1&services=true" type="text/javascript"></script>
<script src="http://api.map.baidu.com/getscript?v=1.1&ak=&services=true&t=20130716024057" type="text/javascript"></script>
<script src="__ROOT__/Public/js/baidu.js"></script>
<style type="text/css">
#l-map{	height:300px; width:600px;  display:block; border-radius:6px; border:1px solid #E9ECF1;text-align:center;}/*商家设置,LBS回复*/
#l-map span.map-text{ display:block; margin-top:120px;}
/*website*/
.website-img{ height:100px;}
.website-logo{ height:60px;}
.website-slide{margin-bottom:0; height:100px;}
.btn_medium{ padding: 0px 10px;font-size: 13px;line-height: 26px; height:28px;}
.col03{ width:200px;}
.help-block { margin-left:5px;color: rgb(141, 141, 141); text-align:left; }
</style>

<div class="wrapper w1200">
 <div class="col-24 nav_nav">
  <div class=" travel_main3"style="margin-top:10px;">
    <div class=" travel_title alpha">
      <h1 class="col-5 hot_route"><?php echo ($viewpoint["names"]); ?></h1>
    </div>
    <div class="clear"></div>
    <div class="att_font"><span class="font_brown">景区地址：<?php echo ($viewpoint["view_address"]); ?></span><a href="javascript:void(0);" onclick="$('#transfer').trigger('click')"><img src="../Public/images/map_icon.jpg" width="13" height="13"/></a>
        <div class="btn grey" ><span class="btn_css"><a href="javascript:;" id='add_coll' ><img src="../Public/images/btn_icon.png" width="17" height="16" align="absmiddle"/><?php if($colls["status"] == 0): ?>收藏该景点<?php else: ?>该景点已收藏<?php endif; ?></a></span>
                        <input name="coll" id="coll" type="hidden" value="<?php echo U('add_coll',array('id'=>$viewpoint['id']));?>">
</div>
    </div>
    <div class="clear"></div>
    <div class="att_img">
      <div class="att_img_left"><img src="__ROOT__<?php echo ($show["picpath"]); ?>" width="500" height="295" /></div>
      <div class="att_img_right">
        <div class="att_img_on">
            <?php if($grade == null): ?><span class="xj1" style="font-size:30px;">尚未获得评分</span><?php else: ?>
          <div class="att_img_on_left"><span class="att_score"><?php echo ($grade); ?></span><span>分</span></div>
          <div class="att_img_on_right"><?php echo ($stars); ?></div><?php endif; ?>
        </div>
        <div class="clear"></div>
        <div class="att_img_down"><div class="float_left"> <span class="open_time"><strong>开放时间：</strong><samp>08:30-18:30</samp><br />
                <strong>适宜人群：</strong><?php if(is_array($person_list)): foreach($person_list as $key=>$vo): echo ($vo); ?>&nbsp;<?php endforeach; endif; ?><br />
          <strong>景区主题：</strong><?php if(is_array($type)): foreach($type as $key=>$vo): echo ($vo); ?>&nbsp;<?php endforeach; endif; ?><br />
          <strong>取票方式：</strong><?php echo ($viewpoint["tickets_address"]); ?></span></div>
            <div class="introduction_special"><span class="open_times"><strong>特色购物：</strong><?php echo ($viewpoint["special_shopping"]); ?></span></div> 
            <div class="introduction_special"><span class="open_times"><strong>特色美食：</strong><?php echo ($viewpoint["special_food"]); ?></span></div>
        </div>
      </div>
    </div>
  </div>
  <div class="clear"></div>
  <div class=" price_list travel_main4" >
    <table width="960px" class="table_list" style=" ">
     <thead>
      <tr>
        <th>门票类型</th>
        <th>景区原价</th>
        <th>2人以上价</th>
        <th>10人以上价</th>
        <th>支付方式</th>
        <th>操作</th>
      </tr>
     </thead>
      <?php if(is_array($ticket)): $i = 0; $__LIST__ = $ticket;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tbody >
      <tr>
        <td><strong><?php echo ($vo["names"]); ?></strong></td>
        <td class="yj">￥<?php echo ($vo["price"]); ?></td>
        <td class="xj1">￥<?php echo ($vo["inner_price"]); ?></td>
        <td class="xj2">￥<?php echo ($vo["upon_price"]); ?></td>
        <td>在线支付</td>
        <td>
            <div class="filter_check">
            <input name="" type="submit" value="   立即预定   " class="btn warning active" onclick="location.href='<?php echo U('viewpoint/order');?>/id/<?php echo ($vo["id"]); ?>'">
          </div>
        </td>
      </tr>
      </tbody><?php endforeach; endif; else: echo "" ;endif; ?>
    </table>
  </div>
  <div class="clear"></div>
  <span class="scroll_REF" style="height:0;">&nbsp;</span>
  <div class=" col-24  page_content2 scroll_nav"style="width:1180px; z-index:99999;">
    <ul>
      <li class="title">景点介绍</li>
      <li class="title">预订须知</li>
      <li class="title">景点图片</li>
      <!--<li class="title">景点视频</li>-->
      <li class="title" id="transfer">交通地图</li>
      <li class="title">景点点评</li>
      <li class="title">景点咨询</li>
    </ul>
  </div>
  <div class="clear"></div>

  <div class="travel_main1">

      <div class="travel_title alpha">
          
 <div style="width:100px;height:200px;float:left;padding-left:10px"><div class="ticket_info_style">
        <h1 class="hot_route">景点介绍</h1><i class="detail_icon3"></i></div>
</div>
    </div>
      <div class="features" style="float:left">
      <div class="introduction_img">

        <div class="introduction_img_on">  <span><?php echo ($viewpoint["view_info"]); ?></span>
        </div>
        <div class="clear"></div>
        <!--<div class="introduction_img_down">
            <?php if(is_array($pic)): $i = 0; $__LIST__ = $pic;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="int_img_left"><img src="__ROOT__<?php echo ($vo["picpath"]); ?>" width="402" height="300" /> <span class="font_brown"><?php echo ($vo["title"]); ?></span></div><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>-->
      </div>
    </div>
  </div>
  <div class="clear"></div>
  <div class=" travel_main1">
    <div class="travel_title alpha" style="float:left">
         <div style="width:100px;height:200px;float:left;padding-left:10px"><div class="ticket_info_style">
        <h1 class="hot_route">预订须知</h1><i class="detail_icon1"></i></div></div>
    </div>
    <div class="features" style="float:left">
      <div class="introduction_img">

        <div class="introduction_img_on">  <span><?php echo ($viewpoint["view_info"]); ?></span>
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div>
  <div class=" travel_main1">
    <div class="travel_title alpha">
        <div style="width:100px;height:200px;float:left;padding-left:10px"><div class="ticket_info_style">
        <h1 class="hot_route">景点图片</h1><i class="detail_icon2"></i></div></div>
    </div>
    <div class="features" style="float:left">
      <div class="att_img_int">
        <ul>
          <?php if(is_array($pic)): $i = 0; $__LIST__ = $pic;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a rel="Viewpoint" href="__ROOT__<?php echo ($vo["picpath"]); ?>"><img src="__ROOT__<?php echo ($vo["picpath"]); ?>" width="164" height="123" /></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
      </div>
    </div>
  </div>
 
  <div class=" travel_main1">
    <div class="travel_title alpha">
        <div style="width:100px;height:200px;float:left;padding-left:10px"><div class="ticket_info_style">
        <h1 class="hot_route">视频分享</h1><i class="detail_icon6"></i></div></div>
    </div>
    <div class="features" style="float:left">
      <div class="att_img_int">
        <ul>
            <li><a href="http://v.ku6.com/show/09AMWYHDi4g6Irgpoh4cpA...html" target="_blank"><span class="crop" title="lol 安妮小鱼人火男视频 小智lol解说" target="_blank">
<img class="img" style="" src="http://t2.baidu.com/it/u=631648175,3684227688&fm=20" alt="lol 安妮小鱼人火男视频 小智lol解说" onerror="V.dispatch("data.cacheImg.imgOnload",this,0,20,2,1)" onload="V.dispatch("data.cacheImg.imgOnload",this,0,20,2,0);">
</span></a></li>
          <li><a href="http://www.56.com/u88/v_MTAyMTc2MTcz.html" target="_blank"><span class="crop" title="lol 安妮小鱼人火男视频 小智lol解说" target="_blank">
<img class="img" style="" src="http://t1.baidu.com/it/u=1670685292,3988762338&fm=20" alt="lol 安妮小鱼人火男视频 小智lol解说" onerror="V.dispatch("data.cacheImg.imgOnload",this,0,20,2,1)" onload="V.dispatch("data.cacheImg.imgOnload",this,0,20,2,0);">
</span></a></li>
          <li><a href="http://v.baidu.com/kan/AakV/AakD?fr=v.hao123.com/search" target="_blank"><span class="crop" title="lol 安妮小鱼人火男视频 小智lol解说" target="_blank">
<img class="img" style="" src="http://t2.baidu.com/it/u=2264420695,1775295099&fm=20" alt="lol 安妮小鱼人火男视频 小智lol解说" onerror="V.dispatch("data.cacheImg.imgOnload",this,0,20,2,1)" onload="V.dispatch("data.cacheImg.imgOnload",this,0,20,2,0);">
</span></a></li>
          <li><a href="http://v.ku6.com/show/09AMWYHDi4g6Irgpoh4cpA...html" target="_blank"><span class="crop" title="lol 安妮小鱼人火男视频 小智lol解说" target="_blank">
<img class="img" style="" src="http://t2.baidu.com/it/u=631648175,3684227688&fm=20" alt="lol 安妮小鱼人火男视频 小智lol解说" onerror="V.dispatch("data.cacheImg.imgOnload",this,0,20,2,1)" onload="V.dispatch("data.cacheImg.imgOnload",this,0,20,2,0);">
</span></a></li>
          <li><a href="http://v.ku6.com/show/09AMWYHDi4g6Irgpoh4cpA...html" target="_blank"><span class="crop" title="lol 安妮小鱼人火男视频 小智lol解说" target="_blank">
<img class="img" style="" src="http://t2.baidu.com/it/u=631648175,3684227688&fm=20" alt="lol 安妮小鱼人火男视频 小智lol解说" onerror="V.dispatch("data.cacheImg.imgOnload",this,0,20,2,1)" onload="V.dispatch("data.cacheImg.imgOnload",this,0,20,2,0);">
</span></a></li>
        </ul>
      </div>
    </div>
  </div>-->
  <div class=" travel_main1">
    <div class="travel_title alpha">
        <div style="width:100px;height:200px;float:left;padding-left:10px"><div class="ticket_info_style">
        <h1 class="hot_route">地图交通</h1><i class="detail_icon2"></i></div></div>
    </div>
      <div class="features" style="float:left;width:380px">
       <div class="introduction_img">

        <div class="introduction_img_on"style="width:380px">  <span><?php echo ($viewpoint["view_info"]); ?></span>
        </div>
        <div class="clear"></div>
       </div>
       
     </div>
      
            <div class="controls" style="float:right;">
                <div id="l-map" class="col06" style=" position: relative; z-index:-1">
                    <i class="icon-spinner icon-spin icon-large"></i><span class="map-text">地图加载中...</span>
                </div>
                <div class="r-result">
                    <input type="hidden" id="lng" name="location_x" value="<?php echo ($viewpoint["location_x"]); ?>" class="col01"><input type="hidden" id="lat" name="location_y" value="<?php echo ($viewpoint["location_y"]); ?>" class="col01">
                </div>

            </div>
  </div>
  <div class="clear"></div>
  <div class=" travel_main1">
    <div class="travel_title alpha">
      <div style="width:100px;height:200px;float:left;padding-left:10px"><div class="ticket_info_style">
        <h1 class="hot_route">景点点评</h1><i class="detail_icon4"></i></div></div>
    </div>
    <div class="grade">
      <div class="grade_left">
        <div class="main"> <span>综合满意度</span> <span class="cent"><?php echo (floatval($impr_counts["avg_satisfaction"])); ?>%</span> <span><?php echo ($impr_counts["tmp_count"]); ?>人评价</span> </div>
        <div class="cent_color">
          <div class="twig">
            <div class="twig_title">装饰：</div>
            <div class="twig_right">
                <div class="twig_right1" style="width:<?php echo ($impr_counts['avg_decoration']); ?>%;"></div>
            </div>
            <div class="twig_title_red">&nbsp;<?php echo (floatval($impr_counts["avg_decoration"])); ?>分</div>
          </div>
          <div class="twig"> <span class="twig_title">特产：</span>
            <div class="twig_right">
                <div class="twig_right2" style="width:<?php echo ($impr_counts['avg_goods']); ?>%;"></div>
            </div>
            <div class="twig_title_red">&nbsp;<?php echo (floatval($impr_counts["avg_goods"])); ?>分</div>
          </div>
          <div class="twig"> <span class="twig_title">交通：</span>
            <div class="twig_right">
                <div class="twig_right3" style="width:<?php echo ($impr_counts['avg_traffic']); ?>%;"></div>
            </div>
            <div class="twig_title_red">&nbsp;<?php echo (floatval($impr_counts["avg_traffic"])); ?>分</div>
          </div>
          <div class="twig"> <span class="twig_title">住宿：</span>
            <div class="twig_right">
                <div class="twig_right4" style="width:<?php echo ($impr_counts['avg_hygiene']); ?>%;"></div>
            </div>
              <div class="twig_title_red">&nbsp;<?php echo (floatval($impr_counts["avg_hygiene"])); ?>分</div>
          </div>
        </div>
      </div>
      <div class="grade_right">
            <div class="filter_check">
                <input name="publish" id="publish" url="<?php echo U('./userviewpoint');?>" type="button" value="   发表评论   " class="btn warning active">
                <input name="consult" id="consult" type="hidden" value="<?php echo U('consult',array('id'=>$viewpoint['id']));?>">
            </div>
            <div class="btn_css_font"><span class="font_brown red"><strong>只有预定过此产品的用户才能参加点评!</strong></span></div>
        </div>
    </div>
    <?php if(is_array($impr_lists)): $i = 0; $__LIST__ = $impr_lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="grade">
      <div class="appraise">
        <div class="appraise_left"> <span class="font_brown">装饰：<span class="font_red"><?php echo (impr_detail($vo["decoration"])); ?></span> 特产：<span class="font_red"><?php echo (impr_detail($vo["goods"])); ?></span> 交通：<span class="font_red"><?php echo (impr_detail($vo["traffic"])); ?></span> 卫生：<span class="font_red"><?php echo (impr_detail($vo["hygiene"])); ?></span> 满意度：<span class="font_red"><?php echo (floatval($vo["satisfaction"])); ?>%</span> 点评奖金：<span class="font_red"><?php echo (f_money($vo["award_price"])); ?>元</span></span><span class="appraise_right"></span></div>
        <div class="appraise_right"> <span class="font_red"><?php echo (get_user($vo["user_id"])); ?><span class="font_brown"><?php echo (f_time($vo["publish_time"])); ?></span></span></div>
      </div>
      
      <div class="appraise"> <span class="font_brown"><?php echo ($vo["content"]); ?></span> </div>
      <div class="impression">
        <table width="100%" class="table_form">
          <tr>
            <th>客户印象：</th>
            <td><?php echo (impr_list($vo["view_id"])); ?></td>
          </tr>
        </table>
      </div>
    </div><?php endforeach; endif; else: echo "" ;endif; ?>
  </div>
  <div class=" travel_main1">
    <div class="travel_title alpha">
      <div style="width:100px;height:200px;float:left;padding-left:10px"><div class="ticket_info_style">
        <h1 class="hot_route">客户咨询</h1><i class="detail_icon5"></i></div></div>
    </div>
    <div class="features" style="float:left; margin-left:18px;">
      <?php if(empty($viewpoint_que)): ?><span class="down">该产品暂无咨询信息！ </span>
            <?php else: ?>
            <span class="down">&nbsp;</span>
     <?php if(is_array($viewpoint_que)): $i = 0; $__LIST__ = $viewpoint_que;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$que): $mod = ($i % 2 );++$i;?><div class="customer">
        <div class="customer_on">
          <div class="customer_on_1">
            <div class="customer_on_left"><span class="font_blue"><img src="../Public/images/att_img_iocn.jpg" width="17" height="17" />&nbsp;咨询内容：<?php echo ($que["question1"]); ?></span></div>
            <div class="customer_on_right"><span class="font_red"><?php echo (get_user($que["user_id"])); ?>&nbsp;<span class="font_brown"><?php echo (f_time($que["publish_time"])); ?></span></span></div>
            <div class="customer_on_left"><span class="font_brown"><img src="../Public/images/att_img_iocn2.jpg" width="17" height="17" />&nbsp;客服回复：<?php echo (($que["answer"])?($que["answer"]):"请等待回复"); ?></span></div>
          </div>
        </div>
      </div><?php endforeach; endif; else: echo "" ;endif; endif; ?>
      <div class="clear"></div>
      <div class="doubt"><span class="down">您的问题？告诉我们您的疑惑，我们会在第一时间为您解答。</span></div>
            <div class="doubt_main">
                <form action="<?php echo U('consult',array('id'=>$viewpoint['id']));?>" ajax="true" class="check-form" method="post" id="consult">
                    <table width="100%" class="table_form">
                        <tr>
                            <th>咨询内容:</th>
                            <td>
                                <textarea name="question1" id="textarea" datatype="*5-255" cols="55" rows="5" class="textarea" tip="请输入文本" nullmsg="请输入文本"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>验证码:</th>
                            <td  style="height:40px;">
                                <input type="text" class="txt verify" imgpath="<?php echo U('Common/verify');?>" ajaxurl="<?php echo U('validform');?>" name="verify" datatype="n4-4" errmsg="请填写验证码">
                            </td>
                        </tr>
                        <tr>
                            <th>&nbsp;</th>
                            <td ><input name="consult_submit" type="submit" value="提交咨询" class="btn warning active"></td>
                        </tr>
                    </table>
                </form>
            </div>
    </div>
  </div>
 </div>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
</script> 
<script type="text/javascript">
    
 $(function () {
         $.fn.cityLink({
            linkId: {
                province: "province",
                city: "city",
                county: "area"
            },
            defaults: {
                province: "<?php echo ($province); ?>",
                city: "<?php echo ($city); ?>",
                county: "<?php echo ($area); ?>"
            }});
            <?php if($viewpoint["location_x"] != ''): ?>baidu_map({ lat: <?php echo ($viewpoint["location_y"]); ?>,lng: <?php echo ($viewpoint["location_x"]); ?>,adr: ""});<?php else: ?>baidu_map();<?php endif; ?>

});

</script>
<div class="clear"></div>
<div id="footer">途乐欢迎您
    </div>
</body>
</html>