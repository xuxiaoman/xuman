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
<!--#names 线路详细页 #-->
<link rel="stylesheet" href="../Public/css/travel.css"/>
<script type="text/javascript" src="../Public/js/jquery.xl_calendar.js"></script>
<script type="text/javascript">
    $(function() {
        var url = "<?php echo U('consult',array('id'=>$line_base['id']));?>";
        $("input[name=consult_submit]").bind("click", function() {
            $.ajax({url: url,
                data: {},
                async: false
            });
        });
        url = "<?php echo U('travel/add_coll',array('id'=>$_GET['id']));?>";
        $("#add_coll").bind("click", function() {
            $.ajax({url: url,
                async: false,
                success:function(data){
                        if(data.status == '1'){
                            $("#add_coll").html("<img src=\"../Public/images/btn_icon.png\" width=\"17\" height=\"16\" align=\"absmiddle\"/>取消收藏");
                        }else if(data.status == '2'){
                            $("#add_coll").html("<img src=\"../Public/images/btn_icon.png\" width=\"17\" height=\"16\" align=\"absmiddle\"/>收藏该线路");
                        }
                }
            });
        });
    });
</script>
<script type="text/javascript" src="../Public/js/travel.js"></script>
<div class="wrapper w1200">
<div class="col-24  travel_main_nav">
    
    <div class="clear"></div>
    <div class="travel_main_left">
        <div class="det_img eislideshow" id="ei-slider" style="height: 430px">
            <ul class="ei-slider-large">
                <?php if(is_array($linepic)): $i = 0; $__LIST__ = $linepic;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pic): $mod = ($i % 2 );++$i;?><li><img src="__ROOT__<?php echo ($pic["pic_path"]); ?>" alt="<?php echo ($pic["names"]); ?>"/> </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <ul class="ei-slider-thumbs">
                <?php if(is_array($linepic)): $i = 0; $__LIST__ = $linepic;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pic): $mod = ($i % 2 );++$i; if(($key) == "0"): $style=' class="active_thumbs"'; else: $style=''; endif; ?>
                    <li <?php echo ($style); ?>></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
        
    </div>
    <div class="det_img_main"><div class="travel_title alpha">
        <h1 class="hot_route1"><?php echo ($line_base["names"]); ?></h1><h1 class="hot_route" style="display: none"></h1>
    </div> 
        <ul class="detail_manyinfo">
            <li><b class="web_map_f_css">线路编号：</b><?php echo ($line_base["code"]); ?></li>
      <li><b class="web_map_f_css">出发城市：</b><?php echo ($start_city_belong["names"]); ?></li>
      <li><b class="web_map_f_css">目的地：</b><?php echo (join($target_city_belong," ")); ?></li>
      <li><b class="web_map_f_css">时间安排：</b><?php echo ($line_base["trip_days"]); ?></li>
      <li><b class="web_map_f_css">发团日期：</b><?php echo (get_tuan($line_base["id"])); ?></li>
      <li><b class="web_map_f_css">交通信息：</b><?php echo ($line_base["traffic"]); ?></li></ul>
        <form action="<?php echo U('order',array('id'=>$line_base['id']));?>" method="post">
            
          <div class="clear"></div>
        <div class="schedule_box" style="position: relative">
            <div class="web_map2">
                出发日期：<input tabindex="1" type="text" name="go_time" id="go_time" class="txt" value="">
                <textarea id="travel_price_list" style="display: none;"><?php echo ($travel_price_list); ?></textarea>
                <div tabindex="2" class="the_calendar" style="position: absolute; background: white;">
                </div>
            </div>

            <span class="web_map2">价格类型： <span id="price_type">周末价</span></span>

            <div class="price_list">
                <table width="332px" class="table_list">
                    <tr class="tr_bg_css">
                        <th></th>
                        <th>市场价</th>
                        <th>优惠价</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>成人</td>
                        <td class="price_rackrate">--</td>
                        <td class="price_adult">--</td>
                        <td>
                            <div class="price_list_reg">
                                 <img  class="plus" src="../Public/images/price_2.jpg" width="13" height="13"/>
                                <input type="text" name="adult_num" style="margin-bottom:5px;" class="select_four" value="1">
                               <img class="minus" src="../Public/images/price.jpg" width="13" height="13" align="absmiddle"/></div>
                        </td>
                    </tr>
                    <tr class="tr_css_b">
                        <td>儿童</td>
                        <td class="price_rackrate">--</td>
                        <td class="price_children">--</td>
                        <td>
                            <div class="price_list_reg">
                                <img class="plus" src="../Public/images/price_2.jpg" width="13" height="13"/>
                                <input type="text" name="children_num" style="margin-top:5px;" class="select_four" value="0"><img class="minus" src="../Public/images/price.jpg" width="13" height="13" align="absmiddle"/>
                                </div>
                        </td>
                    </tr>
                </table>
            </div>
            <span class="red">注：价格会根据您选择的出发日期而改变</span>
<div class="price_list_btn">
      <button class="btn danger active col-3" type="button" id="reserve">立即预定</button>
                <div class="btn grey">
                    <span class="btn_css"><a href="javascript:;" id='add_coll' ><?php if($keep_status == '0'): ?><img src="../Public/images/btn_icon.png" width="17" height="16" align="absmiddle"/>收藏该线路<?php else: ?><img src="../Public/images/btn_icon.png" width="17" height="16" align="absmiddle"/>取消收藏<?php endif; ?></a></span>
                </div>
            </div>
            
        </div>
        </form>
    </div>
    <div class="clear"></div>
    <span class="scroll_REF" style="height:0;">&nbsp;</span>

<div class="col-24  page_content2 scroll_nav" style="z-index:99999; width:1180px;">
    <ul>
        <li class="title">特色介绍</li>
        <li class="title">参考行程</li>
        <li class="title">费用说明</li>
        <li class="title">预订须知</li>
        <li class="title">温馨提示</li>
        <li class="title">预订流程</li>
        <li class="title">线路点评</li>
        <li class="title">在线咨询</li>
        <!--<li class="title">旅游攻略</li>-->
    </ul>
</div>
<div class="clear"></div>
  
  <div class="col-24">
    <div class="col-24 travel_main">
        <div class="col-24 travel_title alpha">

            <h1 class="hot_route">特色介绍</h1>
        </div>
        <div class="feature"> <?php echo ($lineinfo["special_info"]); ?></div>
    </div>
    <div class="clear"></div>
    <div class="col-24 travel_main">
        <div class="col-24 travel_title alpha">
            <h1 class="hot_route">参考行程</h1>
        </div>
        <div class="clear"></div>
        <?php if($view_method == 2): ?><div class="general"><span class="font_brown"><?php echo ($lineinfo["General"]); ?></span></div>
            <?php else: ?>
            <!--#names 按天显示方式视图 #-->
<?php $dining=array(1=>1,2=>2,3=>3);?>
<?php if(is_array($view_result)): $i = 0; $__LIST__ = $view_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vid): $mod = ($i % 2 );++$i;?><div class="reference">
        <div class="ref_left">
            <div class="first"><b><span>第<span class="font_red"><?php echo ($vid["day_info"]["day"]); ?></span>天</span></b></div>
        </div>
        <div class="ref_right">
            <div class="ref_right_line">
                <div class="ref_right_top">
                    <div class="red_main">
                        <div class="red_left"><span class="red_left_title">行程</span></div>
                        <div class="red_right">
                            <span class="font_red"><?php echo ($vid["day_info"]["title"]); ?></span>
                        </div>
                    </div>
                    <div class="red_main">
                        <div class="blue_left"><span class="red_left_title">用餐</span></div>
                        <div class="red_right"><span class="font_brown">早餐：<?php if(in_array(($dining["1"]), is_array($vid["day_info"]["dining"])?$vid["day_info"]["dining"]:explode(',',$vid["day_info"]["dining"]))): ?>√
                            <?php else: ?>
                            ×<?php endif; ?>
                            午餐：<?php if(in_array(($dining["2"]), is_array($vid["day_info"]["dining"])?$vid["day_info"]["dining"]:explode(',',$vid["day_info"]["dining"]))): ?>√
                                <?php else: ?>
                                ×<?php endif; ?>
                            晚餐：<?php if(in_array(($dining["3"]), is_array($vid["day_info"]["dining"])?$vid["day_info"]["dining"]:explode(',',$vid["day_info"]["dining"]))): ?>√
                                <?php else: ?>
                                ×<?php endif; ?>
                        </span></div>
                    </div>
                    <div class="red_main">
                        <div class="brown_left"><span class="red_left_title">住宿</span></div>
                        <div class="red_right"><span class="font_brown">住宿：<?php echo (($vid["day_info"]["stay"])?($vid["day_info"]["stay"]):"暂无信息"); ?></span>
                        </div>
                    </div>
                </div>
                <div class="ref_right_below">
                    <?php if(is_array($vid["Section_info"])): $i = 0; $__LIST__ = $vid["Section_info"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sid): $mod = ($i % 2 );++$i;?><div class="ref_right_time">
                            <div class="khaki_left"><span class="font_brown_bg">第<?php echo ($key+1); ?>段：<?php echo ($sid["title"]); ?></span></div>
                            <span class="font_brown"><?php echo ($sid["content"]); ?></span>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div><?php endforeach; endif; else: echo "" ;endif; endif; ?>
    </div>
    <div class="clear"></div>
    <div class="col-24 travel_main">
        <div class="col-24 travel_title alpha">
            <h1 class="hot_route">费用说明</h1>
        </div>
        <div class="clear"></div>
        <div class="ref_right_time">
            <div class="khaki_left"><span class="font_brown_bg">费用包含</span></div>
            <span class="font_brown"><?php echo ($lineinfo["contain"]); ?></span></div>
        <div class="ref_right_time">
            <div class="khaki_left"><span class="font_brown_bg">费用不包含</span></div>
            <span class="font_brown"><?php echo ($lineinfo["notcontain"]); ?></span></div>
        <div class="ref_right_time">
            <div class="khaki_left"><span class="font_brown_bg">自费项目</span></div>
            <span class="font_brown"><?php echo ($lineinfo["selfpay"]); ?></span></div>
    </div>
    <div class="col-24 travel_main">
        <div class="col-24 travel_title alpha">
            <h1 class="hot_route">预订须知</h1>
        </div>
        <div class="feature"> <?php echo ($lineinfo["order_info"]); ?> </div>
    </div>
    <div class="col-24 travel_main">
        <div class="col-24 travel_title alpha">
            <h1 class="hot_route">温馨提示</h1>
        </div>
        <div class="feature"> <?php echo ($lineinfo["tip"]); ?></div>
    </div>
    <div class="col-24 travel_main">
    <div class="col-24 travel_title alpha">
        <h1 class="hot_route">预定流程</h1>
    </div>
    <div class="clear"></div>
    <div class="flow_path"><img src="../Public/images/flow_path.jpg" width="925" height="77" /></div>
    <div class="ref_right_time"> <span class="font_brown"><span class="font_red">网上预订：</span>北京市内免费上门接游客，豪华空调旅游巴士。 <br />
      <span class="font_red">电话预定：</span>专业持证导游全程讲解 <br />
      </span> </div>
    <div class="ref_right_time">
        <div class="khaki_left"><span class="font_brown_bg">签约方式</span></div>
      <span class="font_brown"><span class="font_red">网上预订：</span>北京市内免费上门接游客，豪华空调旅游巴士。 <br />
      <span class="font_red">电话预定：</span>专业持证导游全程讲解 <br />
      <span class="font_red">网上预订：</span>北京市内免费上门接游客，豪华空调旅游巴士。 <br />
      <span class="font_red">电话预定：</span>专业持证导游全程讲解 <br />
      <span class="font_red">网上预订：</span>北京市内免费上门接游客，豪华空调旅游巴士。 <br />
      </span> </div>
    <div class="ref_right_time">
        <div class="khaki_left"><span class="font_brown_bg">付款方式</span></div>
      <span class="font_brown"><span class="font_brown"><span class="font_red">网上预订：</span>北京市内免费上门接游客，豪华空调旅游巴士。 <br />
      <span class="font_red">电话预定：</span>专业持证导游全程讲解 <br />
      <span class="font_red">网上预订：</span>北京市内免费上门接游客，豪华空调旅游巴士。 <br />
      <span class="font_red">电话预定：</span>专业持证导游全程讲解 <br />
      <span class="font_red">网上预订：</span>北京市内免费上门接游客，豪华空调旅游巴士。 <br />
      </span></span> </div>
</div>
<div class="clear"></div>
    <div class="col-24 travel_main">
        <div class="col-24 travel_title alpha">
            <h1 class="hot_route">线路点评</h1>
        </div>
        <div class="clear"></div>
        <div class="grade">
            <div class="grade_left">
                <div class="main"><span>综合满意度</span> <span class="cent"><?php echo (round(floatval($impr_counts["avg_point"]),"1")); ?>%</span> <span>基于<?php echo ($impr_counts["tmp_count"]); ?>人评价</span>
                </div>
                <div class="cent_color">
                    <div class="twig">
                        <div class="twig_title">行程：</div>
                        <div class="twig_right">
                            <div class="twig_right1" style="width:<?php echo ($impr_counts['avg_travel']*20); ?>%;"></div>
                        </div>
                        <div class="twig_title_red"><?php echo (round(floatval($impr_counts["avg_travel"]),"1")); ?>分</div>
                    </div>
                    <div class="twig"><span class="twig_title">导游：</span>
    
                        <div class="twig_right">
                            <div class="twig_right2" style="width:<?php echo ($impr_counts['avg_guide']*20); ?>%;"></div>
                        </div>
                        <div class="twig_title_red"><?php echo (round(floatval($impr_counts["avg_guide"]),"1")); ?>分</div>
                    </div>
                    <div class="twig"><span class="twig_title">交通：</span>
    
                        <div class="twig_right">
                            <div class="twig_right3" style="width:<?php echo ($impr_counts['avg_traffic']*20); ?>%;"></div>
                        </div>
                        <div class="twig_title_red"><?php echo (round(floatval($impr_counts["avg_traffic"]),"1")); ?>分</div>
                    </div>
                    <div class="twig"><span class="twig_title">住宿：</span>
    
                        <div class="twig_right">
                            <div class="twig_right4" style="width:<?php echo ($impr_counts['avg_room']*20); ?>%;"></div>
                        </div>
                        <div class="twig_title_red"><?php echo (round(floatval($impr_counts["avg_room"]),"1")); ?>分</div>
                    </div>
                </div>
            </div>
        </div>
        <?php if(is_array($impr_lists)): $i = 0; $__LIST__ = $impr_lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="grade">
                <div class="appraise">
                    <div class="appraise_left">
                        <span class="font_brown">行程：<span class="font_red"><?php echo (impr_detail($vo["travel"])); ?></span> 导游：<span class="font_red"><?php echo (impr_detail($vo["guide"])); ?></span> 交通：<span class="font_red"><?php echo (impr_detail($vo["traffic"])); ?></span> 住宿：<span class="font_red"><?php echo (impr_detail($vo["room"])); ?></span> 满意度：<span class="font_red"><?php echo (floatval($vo["point"])); ?>%</span> 点评奖金：<span class="font_red"><?php echo (f_money($vo["review_award"])); ?>元</span></span><span class="appraise_right"></span>
                    </div>
                    <div class="appraise_right"><span class="font_red"><?php echo (get_user($vo["user_id"])); ?> <span class="font_brown"><?php echo (f_time($vo["impr_time"])); ?></span></span>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="appraise"><span class="font_brown"><?php echo ($vo["content"]); ?></span></div>
                <div class="impression">
                    <table width="100%" class="table_form">
                        <tr>
                            <th>客户印象：</th>
                            <td class="impr_class">
                                <?php echo (impr_list($vo["impr_id"])); ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    <div class="col-24 travel_main">
        <div class="col-24 travel_title alpha">
            <h1 class="hot_route">在线咨询</h1>
        </div>
        <div class="feature" id="lists_que">
            <?php if(empty($lists_que)): ?><span class="down">该产品暂无咨询信息！ </span>
                <?php else: ?>
                <span class="down">&nbsp;</span>
                <?php if(is_array($lists_que)): $i = 0; $__LIST__ = $lists_que;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$que): $mod = ($i % 2 );++$i;?><div class="customer">
                        <div class="customer_on">
                            <div class="customer_on_1">
                                <div class="customer_on_left">
                                    <span class="font_blue"><img src="../Public/images/att_img_iocn.jpg" width="17" height="17"/>
                                        &nbsp;<b>咨询内容：</b><?php echo ($que["question1"]); ?></span>
                                </div>
                                <div class="customer_on_right"><span class="font_brown blue"><?php echo (get_user($que["user_id"])); ?>&nbsp;<?php echo (f_time($que["publish_time"])); ?></span></div>
                                <div class="customer_on_left">
                                    <span class="font_brown2"><img src="../Public/images/att_img_iocn2.jpg" width="17" height="17"/>
                                        &nbsp;<b>客服回复：</b><?php echo (($que["answer"])?($que["answer"]):"请等待回复"); ?></span>
                                </div>
                            </div>
                        </div>
                    </div><?php endforeach; endif; else: echo "" ;endif; endif; ?>
            <div class="clear"></div>
            <div class="doubt"><span class="down"><img width="17" height="17"  src="../Public/images/b.jpg"></img>&nbsp;&nbsp;您的问题告诉我们您的疑惑，我们会在第一时间为您解答。</span></div>
            <div class="doubt_main">
                <form action="<?php echo U('consult',array('id'=>$line_base['id']));?>" ajax="true" class="check-form" method="post" id="consult">
                    <table width="100%" class="table_form">
                        <tr>
                            <th class="test">咨询内容:</th>
                            <td>
                                <textarea name="question1" id="textarea" datatype="*5-255" cols="55" rows="5" class="textarea" tip="请输入文本" nullmsg="请输入文本"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th class="test">验证码:</th>
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
</div>



<div class="clear"></div>
<div id="footer">途乐欢迎您
    </div>
</body>
</html>