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
<div id="booking">
    <div class="wrapper w1200">
        <div class="row">
            <div class="col-18" style="position: relative">
                <div class="eislideshow top_ad" id="ei-slider">
                    <ul class="ei-slider-large">
                        <?php $_result=M("advert")->table("jee_advert ad") ->join("jee_advert_area area on ad.area_id=area.id") ->where("area.status=1 and area.names_en='index_banner' and ad.start_time<=1465877108 and (ad.end_time=0 or ad.end_time>=1465877108)") ->order("ad.sort") ->limit("3") ->field("ad.*,area.names") ->select(); if ($_result):$adcount=count($_result);$i=0; foreach($_result as $ad):++$i;?><li><a target="_blank" href="<?php echo ($ad["url"]); ?>"><img src="<?php echo (get_file($ad["pic"])); ?>" alt="<?php echo (get_file($ad["pic"],'names')); ?>"/></a></li><?php endforeach; endif;?>
                    </ul>
                    <ul class="ei-slider-thumbs">
                        <?php for($i=0;$i<$adcount;$i++): if(($i) == "0"): ?><li class="active_thumbs"><a href="#"></a></li>
                            <?php else: ?>
                            <li><a href="#"></a></li><?php endif; endfor;?>
                    </ul>
                </div>
            </div><!-- 搜索 -->
            <div class="col-6 booking-form">
                <div id="booking-tabs" class="ui-tabs">
                    <ul>
                        <li class="span-full-4"><a href="#tabs-hotels" class="span-12">酒店预订</a></li>
                        <li class="span-full-4"><a href="#tabs-tickets" class="span-12">门票预订</a></li>
                        <li class="span-full-4"><a href="#tabs-roads" class="span-12">路线预订</a></li>
                    </ul>
                    <div id="tabs-hotels" class="ui-tabs-panel">
                        <form action="<?php echo U('hotel/lists');?>">
                            <div class="span-13">

                                <div class="search-field">
                                    <label class="search-tit">目的地</label>
                                    <div class="search-input">
                                        <input name="cid" type="hidden" id="cid" value="<?php echo ($search["city_info"]["cid"]); ?>" size="15">
                                        <input type="text" class="form-input city-list-plugs" for-val="cid" id="cityName" url="<?php echo U('hotel/get_hotel_city');?>" value="<?php echo ($search["city_info"]["names"]); ?>">
                                    </div>
                                </div>
                                <div class="search-field">
                                    <label class="search-tit">酒店名称</label>
                                    <div class="search-input"><input type="text"></div>
                                </div>
                            </div>
                            <div class="search-field">
                                <label class="search-tit">入住日期</label>
                                <div class="search-input">
                                    <input type="text" id="start_date" name="start_date" class="form-input ui-date">
                                </div>
                            </div>
                            <div class="search-field">
                                <label class="search-tit">离店日期</label>
                                <div class="search-input">
                                    <input type="text" id="end_date" name="end_date" class="form-input ui-date">
                                </div>
                            </div>
                            <div class="search-field">
                                <label class="search-tit">&nbsp;</label>
                                <div class="search-input"><button type="submit" class="btn primary" >搜索</button></div>
                            </div>
                        </form>
                    </div>
                    <div id="tabs-tickets" class="ui-tabs-panel">
                        <form action="<?php echo U('viewpoint/filter');?>">
                            <div class="search-field">
                                <label class="search-tit"> 景点名称</label>
                                <div class="search-input"><input type="text" name="viewName"></div>
                            </div>
                            <div class="search-field">
                                <label class="search-tit">&nbsp;</label>
                                <div class="search-input"><button type="submit" class="btn primary" >搜索</button></div>
                            </div>
                        </form>
                    </div>
                    <div id="tabs-roads" class="ui-tabs-panel">

                        <form action="<?php echo U('travel/search_key_word');?>">
                            <div class="search-field">
                                <label class="search-tit">关键字</label>
                                <div class="search-input">
                                    <input type="text" name="key_word">
                                </div>
                            </div>
                            <div class="search-field">
                                <label class="search-tit"> 出发城市</label>
                                <div class="search-input">
                                    <input name="cid" type="hidden" id="ctid" value="<?php echo ($search["city_info"]["cid"]); ?>">
                                    <input type="text" size="28px" for-val="ctid" id="cityName2" class="inputText city-list-plugs" url="<?php echo U('travel/get_line_city');?>" value="<?php echo ($search["city_info"]["names"]); ?>">
                                </div>
                            </div>
                            <div class="search-field">
                                <label class="search-tit">旅游类型</label>

                                <div class="search-input">
                                    <select name="type" id="tid">
                                        <?php if(is_array($tour_type)): $i = 0; $__LIST__ = $tour_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tid): $mod = ($i % 2 );++$i;?><option value="<?php echo ($tid["id"]); ?>"><?php echo ($tid["names"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>

                            </div>

                            <div class="search-field">
                                <label class="search-tit"> &nbsp;</label>
                                <div class="search-input">
                                    <button type="submit" class="btn primary">搜索</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--wrapper-->
<div class="wrapper w1200">
    <div class="row">
        <!--国内旅游             国外旅游               周边游-->
        <div id="tourism" class="col-18">
            <div id="tourism-tabs" class="ui-tabs">
                <ul>
                    <?php if(is_array($types)): $i = 0; $__LIST__ = $types;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$type): $mod = ($i % 2 );++$i;?><li><a class="col-2" href="#tourism-<?php echo ($type["id"]); ?>"><?php echo ($type["names"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                    <a href="" class="btn default xs more">更多</a>
                </ul>
                <?php if(is_array($types_result)): $i = 0; $__LIST__ = $types_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$type_re): $mod = ($i % 2 );++$i;?><div id="tourism-<?php echo ($key); ?>">
                        <ul class="imageslist">
                            <?php if(is_array($type_re["0"])): $i = 0; $__LIST__ = $type_re["0"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list_0): $mod = ($i % 2 );++$i;?><li>
                                    <div class="im">
                                        <a href="<?php echo U('travel/detail',array('id'=>$list_0['id']));?>">
                                            <img src="<?php echo (get_line_img($list_0["id"],'m')); ?>"></a>
                                    </div>
                                    <div class="img-arrow">
                                        <div class="caption"><a href="<?php echo U('travel/detail',array('id'=>$list_0['id']));?>"><?php echo ($list_0["names"]); ?></a>
                                        </div>
                                        <div class="info">
                                            <h6><?php echo ((get_line_min_price($list_0["id"]))?(get_line_min_price($list_0["id"])):1); ?>元起</h6>
                                            <a class="booking-btn" href="<?php echo U('travel/order',array('id'=>$list_0['id']));?>">预定</a>
                                        </div>
                                    </div>
                                </li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                        <div class="row">
                            <ul class="text_list ">
                                <?php if(is_array($type_re["1"])): $i = 0; $__LIST__ = $type_re["1"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list_1): $mod = ($i % 2 );++$i;?><li>
                                        <a href="<?php echo U('travel/detail',array('id'=>$list_1['id']));?>"><?php echo ($list_1["names"]); ?> </a>
                                        <span class="price">¥<?php echo ((get_line_min_price($list_1["id"]))?(get_line_min_price($list_1["id"])):1); ?></span>
                                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                            <ul class="text_list">
                                <?php if(is_array($type_re["2"])): $i = 0; $__LIST__ = $type_re["2"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list_2): $mod = ($i % 2 );++$i;?><li>
                                        <a href="<?php echo U('travel/detail',array('id'=>$list_2['id']));?>"><?php echo ($list_2["names"]); ?> </a>
                                        <span class="price">¥<?php echo ((get_line_min_price($list_2["id"]))?(get_line_min_price($list_2["id"])):1); ?></span>
                                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                        </div>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
        <div class="sidebar side-lables col-6" style="margin-top:0px;height:370px;">
            <h5></h5>
            <?php if(is_array($target_type)): $i = 0; $__LIST__ = $target_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="hh5"><a href="<?php echo U('travel/travel_lists',array('type'=>$key,'current_city'=>$currentCity['en']));?>"><?php echo ($types_names[$key]); ?></a></div>
                <ul class="target_topic_ul">
                    <?php if(is_array($vo)): $i = 0; $__LIST__ = $vo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('travel/travel_lists',array('type'=>$vo2['type_id'],'current_city'=>$currentCity['en']));?>?goal=<?php echo ($vo2['area_id']); ?>"><?php echo ($vo2["areanames"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
    <div class="row">
        <!--酒店宾馆-->
        <div id="hotels" class="col-18 ">
            <div id="tabs-default" class="ui-tabs">
                <div class="title">
                    <h4>酒店宾馆</h4>
                </div>
                <ul class="ui-helper-reset ui-helper-clearfix ">
                    <?php if(is_array($hotel_target)): $i = 0; $__LIST__ = $hotel_target;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$h_t): $mod = ($i % 2 );++$i;?><li><a href="#hotels-<?php echo ($h_t["cid"]); ?>"><?php echo ($h_t["names"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                    <a href="<?php echo U('hotel/lists');?>" class="btn default xs more">更多</a>
                </ul>
                <div class="clear"></div>
                <div class="title-bottom"></div>
                <?php if(is_array($hotel_result)): $i = 0; $__LIST__ = $hotel_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ho): $mod = ($i % 2 );++$i;?><div id="hotels-<?php echo ($key); ?>">
                        <ul class="imageslist">
                            <?php if(is_array($ho["0"])): $i = 0; $__LIST__ = $ho["0"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ho_0): $mod = ($i % 2 );++$i;?><li>
                                    <div class="im2">
                                        <a href="<?php echo U('hotel/hotel_detailed',array('id'=>$ho_0['id']));?>">
                                            <img src="<?php echo (get_hotel_img($ho_0["id"],'p')); ?>"></a>
                                    </div>
                                    <div class="img-arrow">
                                        <div class="caption"><a href="<?php echo U('hotel/hotel_detailed',array('id'=>$ho_0['id']));?>"><?php echo ($ho_0["names"]); ?></a></div>
                                    </div>
                                </li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                        <div class="row">
                            <ul class="text_list">
                                <?php if(is_array($ho["1"])): $i = 0; $__LIST__ = $ho["1"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ho_1): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('hotel/hotel_detailed',array('id'=>$ho_1['id']));?>"><?php echo ($ho_1["names"]); ?> </a>
                                        <span class="price">¥<?php echo ((get_min_price($ho_1["id"]))?(get_min_price($ho_1["id"])):1); ?></span></li><?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                            <ul class="text_list">
                                <?php if(is_array($ho["2"])): $i = 0; $__LIST__ = $ho["2"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ho_2): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('hotel/hotel_detailed',array('id'=>$ho_2['id']));?>"><?php echo ($ho_2["names"]); ?></a>
                                        <span class="price">¥<?php echo ((get_min_price($ho_2["id"]))?(get_min_price($ho_2["id"])):1); ?></span></li><?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                        </div>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
    </div>
    <div class="sidebar col-6 side-lables">
        <h5>品牌连锁酒店</h5>
        <ul>
            <?php if(is_array($hotel_chain_lists)): $i = 0; $__LIST__ = $hotel_chain_lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$chain): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('hotel/lists');?>?cid=<?php echo ($search["city_info"]["cid"]); ?>&chain=<?php echo ($chain["id"]); ?>"><?php echo ($chain["names"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <div class="clear"></div>
        <?php $_result=M("advert")->table("jee_advert ad") ->join("jee_advert_area area on ad.area_id=area.id") ->where("area.status=1 and area.names_en='index_advertise1' and ad.start_time<=1465877108 and (ad.end_time=0 or ad.end_time>=1465877108)") ->order("ad.sort") ->limit("2") ->field("ad.*,area.names") ->select(); if ($_result):$adcount=count($_result);$i=0; foreach($_result as $ad):++$i;?><div class="ht_img"><a target="_blank" href="<?php echo ($ad["url"]); ?>"></a></div><?php endforeach; endif;?>

        <div class="clear"></div>
    </div>



    <div class="row">
        <!--景点门票-->
        <div id="tickets" class="col-18 ">
            <div id="tabs-viewpoint" class="ui-tabs">
                <div class="title">
                    <h4>景点门票</h4>
                </div>
                <ul class="ui-helper-reset ui-helper-clearfix ">
                    <?php if(is_array($Viewpoint_target)): $i = 0; $__LIST__ = $Viewpoint_target;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v_t): $mod = ($i % 2 );++$i;?><li><a href="#tickets-<?php echo ($v_t["cid"]); ?>"><?php echo ($v_t["names"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                    <a href="<?php echo U('viewpoint/lists');?>" class="btn default xs more">更多</a>
                </ul>

                <div class="clear"></div>
                <div class="title-bottom"></div>
                <?php if(is_array($Viewpoint_result)): $i = 0; $__LIST__ = $Viewpoint_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vi): $mod = ($i % 2 );++$i;?><div id="tickets-<?php echo ($key); ?>">
                        <ul class="imageslist">
                            <?php if(is_array($vi["0"])): $i = 0; $__LIST__ = $vi["0"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vi_0): $mod = ($i % 2 );++$i;?><li>
                                    <div class="im">
                                        <a href="<?php echo U('viewpoint/detail',array('id'=>$vi_0['id']));?>">
                                            <img src="<?php echo (get_viewpoint_img($vi_0["id"],'p')); ?>"></a>
                                    </div>
                                    <div class="img-arrow">
                                        <div class="caption"><a href="<?php echo U('viewpoint/detail',array('id'=>$vi_0['id']));?>"><?php echo ($vi_0["names"]); ?></a></div>
                                    </div>
                                </li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                        <div class="row">
                            <ul class="text_list">
                                <?php if(is_array($vi["1"])): $i = 0; $__LIST__ = $vi["1"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vi_1): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('viewpoint/detail',array('id'=>$vi_1['id']));?>"><?php echo ($vi_1["names"]); ?> </a>
                                        <span class="price">¥<?php echo ((get_vp_min_price($vi_1["id"]))?(get_vp_min_price($vi_1["id"])):1); ?></span></li><?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                            <ul class="text_list">
                                <?php if(is_array($vi["2"])): $i = 0; $__LIST__ = $vi["2"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vi_2): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('viewpoint/detail',array('id'=>$vi_2['id']));?>"><?php echo ($vi_2["names"]); ?></a>
                                        <span class="price">¥<?php echo ((get_vp_min_price($vi_2["id"]))?(get_vp_min_price($vi_2["id"])):1); ?></span></li><?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                        </div>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
    </div>
    <div class="sidebar col-6 side-lables">
        <h5>游玩主题</h5>
        <ul>
            <?php if(is_array($viewpoint_type_lists)): $i = 0; $__LIST__ = $viewpoint_type_lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vl): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('viewpoint/filter');?>/zppo/<?php echo ($vl["id"]); ?>"><?php echo ($vl["names"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <div class="clear"></div>
        <?php $_result=M("advert")->table("jee_advert ad") ->join("jee_advert_area area on ad.area_id=area.id") ->where("area.status=1 and area.names_en='index_advertise2' and ad.start_time<=1465877108 and (ad.end_time=0 or ad.end_time>=1465877108)") ->order("ad.sort") ->limit("2") ->field("ad.*,area.names") ->select(); if ($_result):$adcount=count($_result);$i=0; foreach($_result as $ad):++$i;?><div class="ht_img"><a target="_blank" href="<?php echo ($ad["url"]); ?>"></a></div><?php endforeach; endif;?>
        <div class="clear"></div>
    </div>


    <div class="col-24">
        <?php $_result=M("advert")->table("jee_advert ad") ->join("jee_advert_area area on ad.area_id=area.id") ->where("area.status=1 and area.names_en='index_center' and ad.start_time<=1465877108 and (ad.end_time=0 or ad.end_time>=1465877108)") ->order("ad.sort") ->limit("1") ->field("ad.*,area.names") ->select(); if ($_result):$ad_c_count=count($_result);$i=0; foreach($_result as $ad_c):++$i;?><a target="_blank" href="<?php echo ($ad_c["url"]); ?>">
                <img class="ad_banner" src="<?php echo (get_file($ad_c["pic"])); ?>" alt="<?php echo (get_file($ad_c["pic"],'names')); ?>"/>
            </a><?php endforeach; endif;?>
    </div>
    <div class="row">

        <div id="guiding" class="col-18">
            <div class="title col-18">
                <h4>最新资讯</h4>
                <a href="<?php echo U('article/detail');?>" class="btn default xs more">更多</a>
                <div class="clear"></div>
                <div class="title-bottom"></div>
            </div>
            <div class="row">
                <?php if(!empty($articles["0"])): ?><div class="preview">
                        <div class="preview-image"><a href="<?php echo U('article/detail',array('id'=>$articles[0][0]['id']));?>">
                                <img class="" src="__ROOT__<?php echo ($articles[0][0]['pic']); ?>"></a>
                        </div>
                        <div class="img-arrow">
                            <div class="caption">
                                <a href="<?php echo U('article/detail',array('id'=>$articles[0][0]['id']));?>"><?php echo ($articles[0][0]['title']); ?></a>
                            </div>
                        </div>
                        <span class="content"><?php echo (msubstr(f_html($articles[0][0]['content']),0,70)); ?></span>
                    </div><?php endif; ?>
                <ul class="guiding_list">
                    <?php if(is_array($articles["1"])): $i = 0; $__LIST__ = $articles["1"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$art): $mod = ($i % 2 );++$i;?><dl>
                            <div class="guiding_list-thumb">
                                <a href="<?php echo U('article/detail',array('id'=>$art['id']));?>">
                                    <img src="__ROOT__<?php echo ($art["pic"]); ?>" />
                                </a>
                            </div>
                            <dt>
                            <a href="<?php echo U('article/detail',array('id'=>$art['id']));?>"><?php echo ($art["title"]); ?></a>
                            <small><?php echo ($art["source"]); ?></small>
                            </dt>
                            <dd>
                                <?php echo (msubstr(f_html($art["content"]),0,50)); ?>
                            </dd>
                        </dl><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
        </div>

        <div class="col-6">
            <div id="site_info">
                <h5>网站公告</h5>

                <div class="title-bottom"></div>
                <ul>
                    <?php if(is_array($notice_list)): $i = 0; $__LIST__ = $notice_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$no): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('article/notice',array('id'=>$no['id']));?>"><?php echo ($no["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
            <div id="services">
                <h5>服务承诺</h5>

                <div class="title-bottom"></div>
                <ul>
                    <li class="tag1">全程价格透明</li>
                    <li class="tag2">精心筛选形成</li>
                    <li class="tag3">绝无强制消费</li>
                    <li class="tag4">品牌质量保证</li>
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