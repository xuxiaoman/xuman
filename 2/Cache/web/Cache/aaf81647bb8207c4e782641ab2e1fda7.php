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
<!--#names 路线筛选列表页 #-->
<link rel="stylesheet" href="../Public/css/travel.css"/>

<div class="wrapper w1200">
    <div class="clear"></div>
    <div class="col-6">
        <div class="col-6 sidebar">
            <div class="col-5 sidebar_title"><h5><strong>旅游主题</strong></h5></div>
            <div class=" sidebar_main">
                <div class="clear"></div>
                <ul class="sidebar_nav">
                    <?php if(is_array($line_topics)): $i = 0; $__LIST__ = $line_topics;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($base_url); ?>?theme=<?php echo ($key); ?>"><?php echo ($vo); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
        </div>
        <div class="clear"></div>
        <div class="col-6 sidebar">
            <div class="col-6 sidebar_title ">
                <h5><strong>热门线路点评</strong></h5>
            </div>
            <ul>
                <?php if(is_array($line_comments)): $i = 0; $__LIST__ = $line_comments;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="hotel_search">
                        <div class="hotel_comment" style=" padding-left:8px;">
                    <span class=" font_brown1">
                        <b><a href="<?php echo U('travel/detail', array('id'=>$vo['line_id']));?>"><?php echo ($vo["names"]); ?></a></b>
                    </span><br/>

                            <div class=" font_brown1"><?php echo ($vo["content"]); ?></div>
                            <br/>
                        </div>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
    </div>


    <div class="col-18 filter filter_nav">
        <div class="filter_on">
            <div class="title"><h3>线路筛选</h3>

                <div class="arrow_box"></div>
            </div>
            <div class="filter_on_main">
                <div class="fontstyle">
                    <span class="lable-keyword">出发城市</span>

                    <div class="progress_slider">
                        <form action="<?php echo U('Common/handoff');?>">
                            <input name="cid" type="hidden" id="cid" value="<?php echo ($currentCity["id"]); ?>">
                            <input type="text" size="28px" for-val="cid" id="cityName" class="inputText city-list-plugs" url="<?php echo U('get_line_city');?>" value="">
                            <button type="submit" class="btn info sm">搜索</button>
                        </form>
                    </div>

                </div>
                <?php if(is_array($no_search)): $i = 0; $__LIST__ = $no_search;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$no_se): $mod = ($i % 2 );++$i; if(!empty($no_se)): ?><div class="fontstyle">
                 <span class="lable-keyword">
                 <?php echo ($titles[$key]); ?>
                 <?php $no_search_key=$key;?>
                 </span>
                            <?php if(is_array($no_se)): $i = 0; $__LIST__ = $no_se;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$no_se_a): $mod = ($i % 2 );++$i; $params=array_merge($base_param,array($no_search_key=>$key));?>
                                <a href="<?php echo ($base_url); ?>?<?php echo (url_params($params)); ?>"><?php echo ($no_se_a); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
                        </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                <div class="filter_prcie">
                    <span class="lable-keyword">线路价格</span>

                    <div class="progress_slider">
                        <form action="">
                            <?php if(is_array($search_lists["price"])): $i = 0; $__LIST__ = $search_lists["price"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pri): $mod = ($i % 2 );++$i; $params=array_merge($base_param,array("price"=>$key));?>
                                <?php if($search['price'] == $key): ?><a href="<?php echo ($base_url); ?>?<?php echo (url_params($params)); ?>" class="selected"><?php echo ($pri); ?></a>
                                    <?php else: ?>
                                    <a href="<?php echo ($base_url); ?>?<?php echo (url_params($params)); ?>"><?php echo ($pri); ?></a><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                            自定义：<input class="self_input" value="<?php echo ($search_lists["min_price"]); ?>" name="min_price" type="text">
                            -
                            <input type="text" class="self_input" name="max_price" value="<?php echo ($search_lists["max_price"]); ?>">
                            <?php if(is_array($search)): $i = 0; $__LIST__ = $search;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$search_row): $mod = ($i % 2 );++$i;?><input type="hidden" name="<?php echo ($key); ?>" value="<?php echo ($search_row["0"]); ?>"><?php endforeach; endif; else: echo "" ;endif; ?>
                            <button class="btn xs primary">确定</button>
                        </form>
                    </div>
                </div>
                <?php if($search): ?><div class="clear"></div>
                    <div class="box-select">
                        <span class="lable-keyword" style="display: inline-block">已选条件</span>

                        <div class="font_css_select">
                            <?php if(is_array($search)): $i = 0; $__LIST__ = $search;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$search_row): $mod = ($i % 2 );++$i;?><dl class="select_one">
                                    <dt class="select_two"><?php echo ($titles[$key]); ?>:<?php echo ($search_row[1]); ?></dt>
                                    <dd class="select_three">
                                        <?php $params=$base_param; if(isset($key)){ unset($params[$key]); } ?>
                                        <a href="<?php echo ($base_url); ?>?<?php echo (url_params($params)); ?>"><img src="../Public/images/sel.jpg" width="13" height="13"/></a>
                                    </dd>
                                </dl><?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                    </div><?php endif; ?>
            </div>
            <div class="clear"></div>


            <div class="filter_list">
                <div class="title">
                <span class="col-8 col-first filter_list_th">
                    <a href="<?php echo ($base_url); ?>?<?php echo (url_params($base_param)); ?>&sort=price_desc">价格</a>&nbsp;
                    <?php if(($lists_sort) == "price_desc"): ?><img src="../Public/images/arrow_blue.png"/>
                        <?php else: ?>
                        <img src="../Public/images/arrow.png"/><?php endif; ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="<?php echo ($base_url); ?>?<?php echo (url_params($base_param)); ?>&sort=price_asc">价格</a>&nbsp;
                    <?php if(($lists_sort) == "price_asc"): ?><img src="../Public/images/arrow_blue2.png"/>
                        <?php else: ?>
                        <img src="../Public/images/arrow2.png"/><?php endif; ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="<?php echo ($base_url); ?>?<?php echo (url_params($base_param)); ?>&sort=hits_desc">人气</a>&nbsp;
                    <?php if(($lists_sort) == "hits_desc"): ?><img src="../Public/images/arrow_blue.png"/>
                        <?php else: ?>
                        <img src="../Public/images/arrow.png"/><?php endif; ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="<?php echo ($base_url); ?>?<?php echo (url_params($base_param)); ?>&sort=day_desc">天数</a>&nbsp;
                    <?php if(($lists_sort) == "day_desc"): ?><img src="../Public/images/arrow_blue.png"/>
                        <?php else: ?>
                        <img src="../Public/images/arrow.png"/><?php endif; ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="<?php echo ($base_url); ?>?<?php echo (url_params($base_param)); ?>&sort=day_asc">天数</a>&nbsp;
                    <?php if(($lists_sort) == "day_asc"): ?><img src="../Public/images/arrow_blue2.png"/>
                        <?php else: ?>
                        <img src="../Public/images/arrow2.png"/><?php endif; ?>
                </span>

                    <div class=" push-7 col-3 col-last filter_list_icon">
                        <img src="../Public/images/icon.png" width="18" height="14"/>&nbsp;<a href="##" id="t_list">列表</a>&nbsp;<img src="../Public/images/icon2.png" width="14" height="14"/>&nbsp;<a href="##" id="t_big">大图</a>
                    </div>
                </div>
                <div class="show_list">
                    <?php if(is_array($lists)): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="filter_list_main">
                            <div class="photo">
                                <img src="__ROOT__<?php echo (get_titlepage($vo["line_id"],'pic_path')); ?>" width="120" height="76"/>
                            </div>
                            <div class="photo_font">
                                <div class="on"><a href="<?php echo U('detail',array('id'=>$vo['id']));?>"><?php echo ($vo["names"]); ?> </a></div>
                                <div class="down">出发日期：<span class="down_font"><?php echo (get_tuan($vo["line_id"])); ?></span><br/>
                                    <?php echo (msubstr(f_html(get_special_info($vo["line_id"])),0,50)); ?>
                                </div>

                            </div>
                            <div class="detail"><span class="detail_money">￥</span><span class="detail_on"><?php echo ($vo["price_adult"]); ?></span>起
                                <div class="fatalism"><span class="fatalism">行程天数：<?php echo ($vo["trip_days"]); ?>天</span></div>
                                <div class="filter_check">
                                    <a class="btn warning active" href="<?php echo U('detail',array('id'=>$vo['id']));?>">查看详情</a>
                                </div>
                            </div>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
                <div class="show_big" style="display: none">
                    <?php if(is_array($lists)): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="filter_img">
                            <div class="filter_photo">
                                <img src="__ROOT__<?php echo (get_titlepage($vo["line_id"],'pic_path')); ?>" width="365" height="248"/>
                            </div>
                            <div>
                                <span class="filter_main"><a href="<?php echo U('detail',array('id'=>$vo['id']));?>"><?php echo ($vo["names"]); ?></a></span>
                            </div>
                            <div class="filter_main_two">
                                <div class="left"><span class="down">出发日期：<?php echo (get_tuan($vo["line_id"])); ?></span></div>
                                <div class="right"><span class="detail_money">￥</span><span class="detail_on"><?php echo ($vo["price_adult"]); ?></span>起
                                </div>
                            </div>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
                <div class="clear"></div>
                <div class="filter_page"><?php echo ($page); ?></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $("#t_big,#t_list").bind("click", function () {
            var t_id = $(this).attr("id");
            if (t_id == "t_big") {
                $(".show_big").show();
                $(".show_list").hide();
            } else {
                $(".show_list").show();
                $(".show_big").hide()
            }
        });
        $("body").height(parseInt($("body").height()) + 10);
    })
</script>

<div class="clear"></div>
<div id="footer">途乐欢迎您
    </div>
</body>
</html>