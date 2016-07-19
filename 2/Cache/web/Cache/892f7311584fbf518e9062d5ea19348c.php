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
<link rel="stylesheet" href="../Public/css/hotels.css"/>
<link rel="stylesheet" href="../Public/css/hotel_detailed.css"/>
<script type="text/javascript" src="../Public/js/ui/jquery.ui.tabs.min.js"></script>
<script type="text/javascript" src="../Public/js/jquery.city_list_plugs.js"></script>
<script type="text/javascript" src="../Public/js/hotel.js"></script>
<div class="wrapper w1200">
   <div class="col-24 suffix_20 web_map"></div>
<div class="clear"></div>
    <div class="col-6 col-first nav nav_adv">
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

<div class="col-18 filter" >
<div class=" filter_on alpha">
    <div class=" filter_on_main">
        <dl class="table-tr ui-tabs-pos" active="<?php echo ($pos_index[1]); ?>">
            <dt class="table-th" >酒店位置：</dt>
            <?php if(is_array($search_lists["position"])): $i = 0; $__LIST__ = $search_lists["position"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pos): $mod = ($i % 2 );++$i;?><dd class="table-td ui-tabs-item"><a href="#pos-<?php echo ($key); ?>"><?php echo ($pos_name_cn[$key]); ?></a></dd><?php endforeach; endif; else: echo "" ;endif; ?>
        </dl>
        <dl class="table-tr">
            <dt class="table-th" style="width: 60px"></dt>
            <dd class="table-td">
                <?php if(is_array($search_lists["position"])): $i = 0; $__LIST__ = $search_lists["position"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pos): $mod = ($i % 2 );++$i; $poskey=$key;?>
                    <div id="pos-<?php echo ($key); ?>" class="ui-tabs-div">
                        <?php if(is_array($pos)): $i = 0; $__LIST__ = $pos;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$posvo): $mod = ($i % 2 );++$i; if($pos_index[2] == $key): ?><a href="<?php echo ($url_lists["posittion"]); ?>&pos=<?php echo ($poskey); ?>-<?php echo ($key); ?>" class="selected"><?php echo ($posvo); ?></a>
                                <?php else: ?>
                                <a href="<?php echo ($url_lists["posittion"]); ?>&pos=<?php echo ($poskey); ?>-<?php echo ($key); ?>"><?php echo ($posvo); ?></a><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </dd>
        </dl>
        <form action="">
            <dl class="table-tr">
                <dt class="table-th"><?php echo ($label_names['price']); ?>：
                </dt>
                <dd class="table-td">
                    <?php if($search['price']): ?><a href="<?php echo ($url_lists["price"]); ?>">不限</a>
                        <?php else: ?>
                        <a href="<?php echo ($url_lists["price"]); ?>" class="selected">不限</a><?php endif; ?>
                </dd>
                <?php if(is_array($search_lists["price"])): $i = 0; $__LIST__ = $search_lists["price"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pri): $mod = ($i % 2 );++$i;?><dd class="table-td">
                        <?php if($search['price'] == $key): ?><a href="<?php echo ($url_lists["price"]); ?>&price=<?php echo ($key); ?>" class="selected"><?php echo ($pri); ?></a>
                            <?php else: ?>
                            <a href="<?php echo ($url_lists["price"]); ?>&price=<?php echo ($key); ?>"><?php echo ($pri); ?></a><?php endif; ?>
                    </dd><?php endforeach; endif; else: echo "" ;endif; ?>
                <dd class="table-td">
                    自定义：<input class="self_input" value="<?php echo ($search_lists["min_price"]); ?>" name="min_price" type="text">
                    -
                    <input type="text" class="self_input" name="max_price" value="<?php echo ($search_lists["max_price"]); ?>">
                    <button class="btn xs primary">确定</button>
                </dd>
            </dl>
        </form>
        <url style="display: none"><?php echo ($url_lists["base_url"]); ?></url>
        <?php if(is_array($search_lists)): $i = 0; $__LIST__ = $search_lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lists): $mod = ($i % 2 );++$i; if($i<3||!$label_names[$key]) continue;$listskey=$key;;?>
            <dl class="table-tr">
                <dt class="table-th"><?php echo ($label_names[$key]); ?>：</dt>
                <dd class="table-td search_title">
                    <?php if($search[$listskey]): ?><a href="#" data-name="<?php echo ($listskey); ?>">不限</a>
                        <?php else: ?>
                        <a href="#" class="selected" data-name="<?php echo ($listskey); ?>">不限</a><?php endif; ?>
                </dd>
                <?php if(is_array($lists)): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><dd class="font_css  search_box" data-name="<?php echo ($listskey); ?>" style="margin-top:10px;">
                        <?php $_value=join(",",$search[$listskey]);?>
                        <label for="<?php echo ($listskey); ?>_<?php echo ($key); ?>">
                            <input id="<?php echo ($listskey); ?>_<?php echo ($key); ?>" name="<?php echo ($listskey); ?>[<?php echo ($key); ?>]" type="checkbox" value="<?php echo ($key); ?>" <?php if(in_array($key,explode(",", "$_value"))) echo 'checked';?>>
                            <?php echo ($item); ?>
                        </label>

                    </dd><?php endforeach; endif; else: echo "" ;endif; ?>
            </dl><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>
    <div class="clear"></div>
    <div class=" filter_list">
        <div class="title">
     <span class="col-8 col-first filter_list_th">
                    <a href="<?php echo ($url_lists["sort"]); ?>&sort=price_desc">价格</a>&nbsp;
                    <?php if(($lists_sort) == "price_desc"): ?><img src="../Public/images/arrow_blue.png"/>
                        <?php else: ?>
                        <img src="../Public/images/arrow.png"/><?php endif; ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="<?php echo ($url_lists["sort"]); ?>&sort=price_asc">价格</a>&nbsp;
                    <?php if(($lists_sort) == "price_asc"): ?><img src="../Public/images/arrow_blue2.png"/>
                        <?php else: ?>
                        <img src="../Public/images/arrow2.png"/><?php endif; ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="<?php echo ($url_lists["sort"]); ?>&sort=hits_desc">人气</a>&nbsp;
                    <?php if(($lists_sort) == "hits_desc"): ?><img src="../Public/images/arrow_blue.png"/>
                        <?php else: ?>
                        <img src="../Public/images/arrow.png"/><?php endif; ?>
                </span>
            </div>
    </div>
<div class="clear"></div>
    <?php if(is_array($hotel_lists)): $i = 0; $__LIST__ = $hotel_lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(empty($hotel_id)): $hotel_id=$vo['hotel_id'];?>
            
            <?php elseif($hotel_id == $vo['hotel_id']): ?>
            <tr>
    <td><?php echo ($vo["names"]); ?></td>
    <td>￥<?php echo ($vo["price_retail"]); ?></td>
    <td>￥<?php echo ($vo["price_prefer"]); ?></td>
    <td>￥<?php echo ($vo["bonus_comm"]); ?></td>
    <td>
        <?php switch($vo["broadband"]): case "0": ?>无<?php break;?>
            <?php case "1": ?>有(免费)<?php break;?>
            <?php case "2": ?>有(收费)<?php break; endswitch;?>
    </td>
    <td>
        <?php switch($vo["breakfast"]): case "0": ?>无<?php break;?>
            <?php case "1": ?>有(含早)<?php break;?>
            <?php case "2": ?>有(收早)<?php break;?>
            <?php case "3": ?>有(收三早)<?php break;?>
            <?php case "4": ?>有(收费)<?php break; endswitch;?>
    </td>
    <td>
        <div class="filter_check">
           <a href="<?php echo U('hotel_order',array('hotel'=>$vo['hotel_id'],'room'=>$vo['room_id']));?>" class="btn info">立即预定</a>
        </div>
    </td>
</tr>
            <?php elseif($hotel_id != $vo['hotel_id']): ?>
            <?php $hotel_id=$vo['hotel_id'];?>
            </table>
</div>
</div>

<div class=" travel_main1">
<div class="leftside">
  <div class="col-14 hotel_price_on_left"><b><a href="<?php echo U('hotel_detailed',array('hotel'=>$vo['hotel_id']));?>">&nbsp;<?php echo ($vo["hotel_names"]); ?></a></b></div>
  <div class="col-14 font_brown">地址：<?php echo ($vo["address"]); echo (get_area_belong($vo["canton"],$url_lists['posittion'])); ?></div>
</div>
<div class="rightside">
  <div class="col-3 hotel_price_on_right"><b>￥ <?php echo ($vo["price_prefer"]); ?>元起</b></div>
  <div class="col-3 font_brown"><?php echo ((get_impr_point($vo["hotel_id"]))?(get_impr_point($vo["hotel_id"])):"100"); ?>%满意度<br>
    <?php echo (get_impr_count($vo["hotel_id"])); ?>条酒店点评 </div>
</div>
<div class="clear"></div>
<div class="price_list" style="margin-top:10px; margin-left:15px;">
<table width="720px" class="table_list" style="margin-top:5px; margin-bottom:10px;">
<tr>
  <th>房型</th>
  <th>门市价</th>
  <th>优惠价</th>
  <th>点评奖金</th>
  <th>宽带</th>
  <th>早餐</th>
  <th>早餐</th>
</tr>
<tr>
  <td><?php echo ($vo["names"]); ?></td>
  <td>￥<?php echo ($vo["price_retail"]); ?></td>
  <td>￥<?php echo ($vo["price_prefer"]); ?></td>
  <td>￥<?php echo ($vo["bonus_comm"]); ?></td>
  <td><?php switch($vo["broadband"]): case "0": ?>无<?php break;?>
      <?php case "1": ?>有(免费)<?php break;?>
      <?php case "2": ?>有(收费)<?php break; endswitch;?></td>
  <td><?php switch($vo["breakfast"]): case "0": ?>无<?php break;?>
      <?php case "1": ?>有(含早)<?php break;?>
      <?php case "2": ?>有(收早)<?php break;?>
      <?php case "3": ?>有(收三早)<?php break;?>
      <?php case "4": ?>有(收费)<?php break; endswitch;?></td>
  <td><div class="filter_check"> <a href="<?php echo U('hotel_order',array('hotel'=>$vo['hotel_id'],'room'=>$vo['room_id']));?>" class="btn info">立即预定</a> </div></td>
</tr><?php endif; endforeach; endif; else: echo "" ;endif; ?>
    
    <div class="clear"></div>
    <div class="page"><?php echo ($page); ?></div>

</div>
</div>
</div>

<div class="clear"></div>
<div id="footer">途乐欢迎您
    </div>
</body>
</html>