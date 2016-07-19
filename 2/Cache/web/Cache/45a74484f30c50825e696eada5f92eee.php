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
<link rel="stylesheet" href="../Public/css/view.css"/>
<style type="text/css">
.ei-slider-loading{margin-top:0; height:250px;}

</style>
<div class="wrapper w1200">
<div class="wrapper w1200">
    <div class="clear"></div>
    <div class="col-6">
        <div class="col-6 sidebar nav_adv">
            <div class="col-6 sidebar_title">
                <h5><strong>景点查询</strong></h5>
            </div>
           <div class="col-24 suffix_20 web_map"></div>
           <div class="  alpha step_main_right_top" style="float:left;" >
      <div class="clear"></div>
      <div class=" side_form1">
          <form action="<?php echo U('filter');?>">
            <div class="side_form2">
                <div class="form_nav">景点城市：<input type="text" size="28px" id="cityName" name="cityName"></div>
                <div class="form_nav" >景点名称：<input name="viewName" type="text" size="28px"></div>
                <div colspan="2" class="filter_check  btn_z" ><button class="btn info">景点查询</button></div>
            </div>
          </form>
        </div>
      <div class="col-6 ticket_query_font">
       <span class="font_brown"><strong>热门搜索关键词：</strong><br />
           
           <?php if(is_array($type)): $i = 0; $__LIST__ = $type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div style="margin:5px;float:left">
        <a href="<?php echo U('filter',array('zppo'=>$vo['id']));?>" style="margin:5px 0px 5px 0px;"><?php echo ($vo["names"]); ?></a></div><?php endforeach; endif; else: echo "" ;endif; ?> 
            
       </span> 
      </div>
    </div>
    
      </div>
      
        <div class="clear"></div>
        <div class="col-6 sidebar nav_adv" style="padding-bottom:40px;">
            <div class="col-6 sidebar_title " style="height:25px;">
                <h5><strong>热销订单推荐</strong></h5>
            </div>
            <?php if(is_array($hotest_targets)): $k = 0; $__LIST__ = $hotest_targets;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><ul class="col-6 sidebar_hot">
                    <li class="hotlist-widget">
                        
                        <div class="hotlist-widget-context" style="text-align: left;width:200px;">
                            <a class="hotlist-widget-title" href="<?php echo U('order',array('id'=>$vo['ticket_id']));?>" ><?php echo (msubstr(f_html($vo["names"]),0,6)); ?></a>

                            <div class="hotlist-widget-info" style="float:right">￥<?php echo ($vo["price"]); ?>元起</div>
                        </div>
                        <div class="clear"></div>
                    </li>
                </ul><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        
        <div class="clear"></div>
         <div class="col-6 sidebar nav_adv" >
            <div class="col-6 sidebar_title ">
                <h5><strong>景点点评</strong></h5>
            </div>
            <ul>
              <?php if(is_array($impr)): $i = 0; $__LIST__ = $impr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="hotel_search">
                  <div class="hotel_comment" style=" padding-left:8px;"><span class=" font_brown1"><b><a href="<?php echo U('detail',array('id'=>$vo['viewpointid']));?>"><?php echo ($vo["names"]); ?></a></b></span><br/>
                    <div class=" font_brown1"><?php echo ($vo["content"]); ?></div>
                    <div>用户<span style="color: green">
                            (<?php echo ($vo["username"]); ?>)</span>
                      获得<span class="c_red">￥<?php echo ($vo["award"]); ?></span> 奖金 </div>
                  </div>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
    </div>
    <div class="col-18" style="position:relative  ">
        <div class="eislideshow" id="ei-slider" style="height: 250px;position: relative;margin-top:10px">
            <ul class="ei-slider-large">
                <?php $_result=M("advert")->table("jee_advert ad") ->join("jee_advert_area area on ad.area_id=area.id") ->where("area.status=1 and area.names_en='viewpoint_banner' and ad.start_time<=1465811830 and (ad.end_time=0 or ad.end_time>=1465811830)") ->order("ad.sort") ->limit("3") ->field("ad.*,area.names") ->select(); if ($_result):$adcount=count($_result);$i=0; foreach($_result as $ad):++$i;?><li><a target="_blank" href="<?php echo ($ad["url"]); ?>"><img src="<?php echo (get_file($ad["pic"])); ?>" alt="<?php echo (get_file($ad["pic"],'names')); ?>"/></a></li><?php endforeach; endif;?>
            </ul>
            <ul class="ei-slider-thumbs">
                <?php for($i=0;$i<$adcount;$i++): if(($i) == "0"): ?><li class="active_thumbs"><a href="#"></a></li>
                    <?php else: ?>
                    <li><a href="#"></a></li><?php endif; endfor;?>
            </ul>
        </div>
    </div>
    <div class="col-18 travel_main">
        <div class="title col-18">

            <h4><strong>热门景点</strong></h4>
            <span class="hot_more"><a class="btn default xs more" href="<?php echo U('filter');?>">更多</a></span>


            <div class="clear"></div>
            <div class="title-bottom"></div>
        </div>
        <ul>
            <?php if(is_array($hot_view)): $i = 0; $__LIST__ = array_slice($hot_view,0,3,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="clear"></div>
                <div class="filter_list_main">
                    <img src="<?php echo (get_view_img($vo["id"])); ?>" width="120" height="76"/>

                    <div class="list_content">
                        <div class="on"><a class="title" href="<?php echo U('detail',array('id'=>$vo['id']));?>"><?php echo ($vo["names"]); ?></a>
                        </div>
                            <span class="special ellipsis"><small>介绍</small>
                                <?php echo (msubstr(f_html(get_special_info($vo["id"])),0,50)); ?>
                                <p class="content_info">
                                    满意度：<span class="warning"><?php echo ($vo["satisfaction"]); ?>%</span> <span class="default"><?php echo ($vo["count"]); ?></span>人点评
                                </p>
                            </span>
                    </div>
                    <div class="content">
                        <span class="content_rmb">￥</span><span class="content_price"><?php echo ($vo["price"]); ?></span>起
                        <div class="booked">访问量：<?php echo ($vo["hits"]); ?>次</div>
                        <div class="moreinfo">
                            <div class="btn-more"><a href="<?php echo U('detail',array('id'=>$vo['id']));?>">查看详情</a></div>
                        </div>
                    </div>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
    <div class="col-18 travel_main">
        <div class="title col-18">

            <h4><strong>特价景点</strong></h4>
            <span class="hot_more"><a class="btn default xs more" href="<?php echo U('filter');?>">更多</a></span>

            <div class="clear"></div>
            <div class="title-bottom"></div>
        </div>
        <ul class="preferential">
            <?php if(is_array($special_view)): $i = 0; $__LIST__ = $special_view;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><img src="<?php echo (get_view_img($vo["id"])); ?>"/>

                    <div class="caption"><a href="<?php echo U('detail',array('id'=>$vo['id']));?>"><?php echo ($vo["names"]); ?></a></div>
                    <div class="info">￥</span><span class="highlight"><?php echo ($vo["price"]); ?></span>起</div>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
    <div class="col-18 travel_main">
        <div class="title col-18">

            <h4><strong>新增景点</strong></h4>
            <span class="hot_more"><a class="btn default xs more" href="<?php echo U('filter');?>">更多</a></span>


            <div class="clear"></div>
            <div class="title-bottom"></div>
        </div>
        <ul>
            <?php if(is_array($new_view)): $i = 0; $__LIST__ = $new_view;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="clear"></div>
                <div class="filter_list_main">
                    <img src="<?php echo (get_view_img($vo["id"])); ?>" width="120" height="76"/>

                    <div class="list_content">
                        <div class="on"><a class="title" href="<?php echo U('detail',array('id'=>$vo['id']));?>"><?php echo ($vo["names"]); ?></a>
                        </div>
                            <span class="special ellipsis"><small>介绍</small><?php echo (msubstr(f_html(get_special_info($vo["id"])),0,50)); ?>
                                <p class="content_info">
                                    满意度：<span class="warning"><?php echo ($vo["satisfaction"]); ?>%</span> <span class="default"><?php echo ($vo["count"]); ?></span>人点评
                                </p>
                            </span>
                    </div>
                    <div class="content">
                        <span class="content_rmb">￥</span><span class="content_price"><?php echo ($vo["price"]); ?></span>起
                        <div class="booked">访问量：<?php echo ($vo["hits"]); ?>次</div>
                        <div class="moreinfo">
                            <div class="btn-more"><a href="<?php echo U('detail',array('id'=>$vo['id']));?>">查看详情</a></div>
                        </div>
                    </div>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>


</div>
</div>

<div class="clear"></div>
<div id="footer">途乐欢迎您
    </div>
</body>
</html>