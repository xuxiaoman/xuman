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
<script type="text/javascript" src="../Public/js/hotel_detailed.js"></script>
<script src="__ROOT__/Public/js/jquery.cityLink.js"></script>
<script src="http://api.map.baidu.com/api?key=02b627b5ad5892889e9384d61d91bd08&v=1.1&services=true" type="text/javascript"></script>
<script src="http://api.map.baidu.com/getscript?v=1.1&ak=&services=true&t=20130716024057" type="text/javascript"></script>
<script src="__ROOT__/Public/js/baidu.js"></script>
<style type="text/css">
#l-map {
	height: 300px;
	width: 1168px;
	margin: 10px 5px;
	display: block;
	border-radius: 6px;
	border: 1px solid #E9ECF1;
	text-align: center;
}/*商家设置,LBS回复*/
#l-map span.map-text {
	display: block;
	margin-top: 120px;
}
/*website*/
.website-img {
	height: 100px;
}
.website-logo {
	height: 60px;
}
.website-slide {
	margin-bottom: 0;
	height: 100px;
}
.btn_medium {
	padding: 0px 10px;
	font-size: 13px;
	line-height: 26px;
	height: 28px;
}
.col03 {
	width: 200px;
}
.help-block {
	margin-left: 5px;
	color: rgb(141, 141, 141);
	text-align: left;
}
</style>
<div class="wrapper w1200">
  <div class="col-24 travel_main hotel-detailed">
    <div class="col-24 travel_title alpha">
      <div>
        <h1 class="hot_route"><strong> <span style="padding-left:5px;"><?php echo ($hotelinfo["names"]); ?></span> <span id="collect-cmd" hotel="<?php echo ($hotelinfo["id"]); ?>" url="<?php echo U('ajax_collect');?>">
          <?php if(($is_logined) == "true"): if(($is_collected) != "1"): ?><a title='添加至酒店收藏' class='_collect' value='1'><span class='collect_off'>&nbsp;</a>
              <?php else: ?>
              <a title='取消酒店收藏' class='_collect' value='0'> <span class='collect_on' style="width:80px;"  >[已收藏]</span></a><?php endif; endif; ?>
          </span> </strong></h1>
      </div>
    </div>
    <div class="clear"></div>
    <div class="col-9 hotel_det_img">
      <div class="col-9 advertisement" style="position: relative">
        <div class="eislideshow" id="ei-slider" style="height: 290px">
          <ul class="ei-slider-large">
            <?php if(is_array($hotel_img)): $i = 0; $__LIST__ = $hotel_img;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><img src="__ROOT__<?php echo ($vo["picpath"]); ?>" height="290" /></li><?php endforeach; endif; else: echo "" ;endif; ?>
          </ul>
          <ul class="ei-slider-thumbs">
            <?php for($i=0;$i<count($hotel_img);$i++):;?>
            
            
            
            <?php if(($i) == "0"): ?><li class="active_thumbs"><a href="javascript:;"></a></li>
              <?php else: ?>
              <li><a href="javascript:;"></a></li><?php endif; ?>
            <?php endfor;?>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-14" style="padding-left:7px;width:716px;">
      <table width="100%" >
        <tr class="web_map" style=" border-bottom:1px solid #dddddd;">
          <th><b>开业时间：</b></th>
          <td><?php echo (date("Y年m月d日",$hotelinfo["open_time"])); ?></td>
        </tr>
        <tr class="web_map" style=" border-bottom:1px solid #dddddd;">
          <th style="width:105px"><b>最后装修时间：</b></th>
          <td><?php echo (date("Y年m月d日",$hotelinfo["fix_time"])); ?></td>
        </tr>
        <tr class="web_map" style=" border-bottom:1px solid #dddddd;">
          <th><b>联系方式：</b></th>
          <td><?php echo ($hotelinfo["contact"]); ?></td>
        </tr>
        <tr class="web_map" style=" border-bottom:1px solid #dddddd;">
          <th><b>特色服务：</b></th>
          <td><?php echo ($services); ?></td>
        </tr>
        <tr class="web_map" style=" border-bottom:1px solid #dddddd;">
          <th><b>餐饮服务：</b></th>
          <td><?php echo ($dinners); ?></td>
        </tr>
        <tr class="web_map" style=" border-bottom:1px solid #dddddd;">
          <th><b>休闲娱乐：</b></th>
          <td><?php echo ($Recreations); ?></td>
        </tr>
        <tr class="web_map" style=" border-bottom:1px solid #dddddd;">
          <th><b>服务项目：</b></th>
          <td><?php echo ($Services); ?></td>
        </tr>
      </table>
    </div>
  </div>
  <div class="col-24 page_content2 hotel-detailed-bg"><span class="scroll_nav"></span>
    <div class="col-24 page_content2 scroll_nav" style="width:1180px;">
      <ul class="alpha">
        <li class="col-2 title">酒店预订</li>
        <li class="col-2 title">酒店信息</li>
        <li class="col-2 title">客户点评</li>
        <li class="col-2 title">酒店咨询</li>
        <li class="col-2 title">房间信息</li>
        <li class="col-2 title">酒店视频</li>
        <li class="col-2 title">交通指南</li>
      </ul>
    </div>
  </div>
  <div class="col-24 hotel-detailed-bg hotel-detailed-content">
    <div class="col-24 room_box" style="margin:0;"> <a name="bottom"></a>
      <h1 class="hot_route">&nbsp;酒店预订</h1>
      <div class="room_date">
        <div class="room_date_one">
          <form action="<?php echo U('hotel_detailed');?>/hotel/<?php echo ($hotel_id); ?>/#bottom" method="post">
            <table class="select_date">
              <tr>
                <th style="text-align:right;"><a name="bottom"></a>入住日期：</th>
                <td><input type="text" name="begin_time" id="begin_time" class="txt ui-date" value="<?php echo (date("Y-m-d",$during_time["begin"])); ?>" datatype="*" size="20" tip="请输入文本" nullmsg="请输入文本"></td>
                <th style="text-align:right">离店日期：</th>
                <td><input type="text" name="begin_end" id="begin_end" class="txt ui-date" value="<?php echo (date("Y-m-d",$during_time["leave"])); ?>" datatype="*" size="20" tip="请输入文本" nullmsg="请输入文本"></td>
                <td class="filter_check"><button class="btn info">修改日期</button></td>
              </tr>
            </table>
          </form>
        </div>
        <div class="clear"></div>
        <div class="col-24 price_list" style="width:1180px;">
          <table class="table_list">
            <tr>
              <th>房型</th>
              <th>门市价</th>
              <th>优惠价</th>
              <th>点评奖金</th>
              <th>宽带</th>
              <th>早餐</th>
              <th>操作</th>
            </tr>
            <?php if(is_array($rooms)): $i = 0; $__LIST__ = $rooms;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td><?php echo ($vo["names"]); ?></td>
                <td><s><?php echo ($vo["price_retail"]); ?></s></td>
                <td><?php echo ($vo["price_prefer"]); ?></td>
                <td><?php echo ($vo["price_bonus_part"]); ?></td>
                <td><?php switch($vo["broadband"]): case "0": ?>无<?php break;?>
                    <?php case "1": ?>有（免费）<?php break;?>
                    <?php case "2": ?>有（收费）<?php break; endswitch;?></td>
                <td><?php switch($vo["breakfast"]): case "0": ?>无<?php break;?>
                    <?php case "1": ?>含早<?php break;?>
                    <?php case "2": ?>含双早<?php break;?>
                    <?php case "3": ?>含三早<?php break;?>
                    <?php case "4": ?>有（收费）<?php break; endswitch;?></td>
                <?php if($vo["order_roomcount"] < $vo['room_count']): ?><td><div class="ide_btn">
                      <div class="btn3 orange">
                        <button class="btn info" onClick="location.href='<?php echo U('hotel_order');?>/hotel/<?php echo ($vo["hotel_id"]); ?>/room/<?php echo ($vo["room_id"]); ?>'"> 预定 </button>
                      </div>
                    </div></td>
                  <?php else: ?>
                  <td class="hotel_full"> 已满 </td><?php endif; ?>
              </tr><?php endforeach; endif; else: echo "" ;endif; ?>
          </table>
        </div>
      </div>
    </div>
    <div class="clear"></div>
    <div class="col-24 room_box">
      <h1 class="hot_route">&nbsp;酒店信息</h1>
      <div>
        <table width="100%" border="0" class="hotelinfo">
          <tr>
            <th>酒店介绍：</th>
            <td><?php if(($hotelinfo["detail"]) != ""): echo ($hotelinfo["detail"]); ?>
                <?php else: ?>
                无<?php endif; ?></td>
          </tr>
          <tr>
            <th>酒店特别提示：</th>
            <td><?php if(($hotelinfo["tip"]) != ""): echo ($hotelinfo["tip"]); ?>
                <?php else: ?>
                无<?php endif; ?></td>
          </tr>
          <tr>
            <th>附加服务：</th>
            <td><?php if(($hotelinfo["adds_service"]) != ""): echo ($hotelinfo["adds_service"]); ?>
                <?php else: ?>
                无<?php endif; ?></td>
          </tr>
        </table>
      </div>
    </div>
    <div class="clear"></div>
    <div class="col-24 room_box">
      <h1 class="hot_route">&nbsp;客户点评</h1>
      <div class="grade">
        <div class="grade_left">
          <div class="main"><span>综合满意度</span><span class="cent"><?php echo ($point["point"]); ?>%</span><span>基于 <?php echo ($point["users"]); ?> 人评价</span></div>
          <div class="cent_color">
            <div class="twig">
              <div class="twig_title">设施装潢：</div>
              <div class="twig_right">
                <div class="twig_right1" style="width:<?php echo ($impr_counts['decoration']); ?>%;"></div>
              </div>
              <div class="twig_title_red">&nbsp;<?php echo ($impr_counts['decoration']); ?>分</div>
            </div>
            <div class="twig"><span class="twig_title">交通位置：</span>
              <div class="twig_right">
                <div class="twig_right2" style="width:<?php echo ($impr_counts['traffic']); ?>%;"></div>
              </div>
              <div class="twig_title_red">&nbsp;<?php echo ($impr_counts['traffic']); ?>分</div>
            </div>
            <div class="twig"><span class="twig_title">卫生情况：</span>
              <div class="twig_right">
                <div class="twig_right3" style="width:<?php echo ($impr_counts['hygiene']); ?>%;"></div>
              </div>
              <div class="twig_title_red">&nbsp;<?php echo ($impr_counts['hygiene']); ?>分</div>
            </div>
            <div class="twig"><span class="twig_title">&nbsp;性 价 比：</span>
              <div class="twig_right">
                <div class="twig_right4" style="width:<?php echo ($impr_counts['prices']); ?>%;"></div>
              </div>
              <div class="twig_title_red">&nbsp;<?php echo ($impr_counts['prices']); ?>分</div>
            </div>
          </div>
        </div>
        <div class="grade_right" style="margin:40px 18% auto auto">
          <?php if(($is_logined) == "true"): ?><div class="filter_check">
              <input name="publish" type="button" id="publish" url="<?php echo U('userhotel/hotel');?>/status/3" value="   发表评论   " class="btn red  info">
            </div>
            <?php else: ?>
            <div class="btn_css_font"><span class="font_brown">只有登录后的用户才可进行点评</span></div><?php endif; ?>
        </div>
      </div>
      <?php if(is_array($imprs)): $i = 0; $__LIST__ = $imprs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="grade1">
          <div class="person-impr">
            <div class="appraise">&nbsp;&nbsp;点评人：
              <?php if(($vo["show_name"]) == "1"): echo ($vo["username"]); ?>
                <?php else: ?>
                （匿名）<?php endif; ?>
            </div>
            <div class="clear"></div>
            <div class="appraise">
              <div class="appraise_left"><span class="font_brown"><span class="twig_title">&nbsp;&nbsp;设施装潢</span>：<span class="font_red"><?php echo ($vo['decoration']); ?></span>分&nbsp;&nbsp;交通位置：<span class="font_red"><?php echo ($vo['traffic']); ?></span>分&nbsp;&nbsp;卫生情况：<span class="font_red"><?php echo ($vo['hygiene']); ?></span>分&nbsp;&nbsp;性价比：<span class="font_red"><?php echo ($vo['prices']); ?></span>分&nbsp;&nbsp;满意度：<span class="font_red"><?php echo ($vo['points']); ?></span>%&nbsp;&nbsp;点评奖金：<span class="font_red"><?php echo ($vo['bonus_comm']); ?></span></span>元</div>
            </div>
            <div class="clear"></div>
            <div class="appraise">
              <table>
                <tr>
                  <td width="75">&nbsp;&nbsp;点评内容：</td>
                  <td><?php echo ($vo["content"]); ?></td>
                </tr>
              </table>
            </div>
            <div class="impression">
              <table width="100%" class="table_form">
                <tr>
                  <th width="88" style="text-align:right">客户印象：</th>
                  <td><?php if(is_array($vo["cimprs"])): $i = 0; $__LIST__ = $vo["cimprs"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cs): $mod = ($i % 2 );++$i;?><li class="li impr_li <?php echo ($cs["color"]); ?>"><?php echo ($cs["names"]); ?></li><?php endforeach; endif; else: echo "" ;endif; ?></td>
                </tr>
              </table>
            </div>
          </div>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    <div class="clear"></div>
    <div class="col-24 room_box">
      <h1 class="hot_route">&nbsp;在线咨询</h1>
      <div class="feature" id="lists_que">
        <?php if(($ques) == ""): ?><span class="down" style="line-height:58px;">&nbsp;该产品暂无咨询信息！</span>
          <?php else: ?>
          <?php if(is_array($ques)): $i = 0; $__LIST__ = $ques;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="customer_on">
              <div class="clear"></div>
              <div class="customer_on_1">
                <table width="100%">
                  <tr>
                    <td width="108"><span class="font_blue"><img src="../Public/images/att_img_iocn.jpg" width="17" height="17"/>&nbsp;咨询内容：</span></td>
                    <td width="1016"><span><?php echo ($vo["question1"]); ?></span></td>
                  </tr>
                  <tr>
                    <td class="customer_on_left" style="width:110px;border-top:none;"><span class="font_brown"><img src="../Public/images/att_img_iocn2.jpg" width="17" height="17"/>&nbsp;客服回复：</span></td>
                    <td style="border-top:none;"><span>
                      <?php if(($vo["answer"]) != ""): echo ($vo["answer"]); ?>
                        <?php else: ?>
                        <span style="color:#999">&lt;- 客服暂未作答 -&gt;</span><?php endif; ?>
                      </span></td>
                  </tr>
                  <tr>
                    <td colspan="2" style="border-top:none; font-size:12px"> 提问用户：<?php echo ($vo["username"]); ?>&nbsp;&nbsp;|&nbsp;&nbsp;提问时间：<?php echo (date("Y年m月d日 H:i:s", $vo["publish_time"])); ?>
                      <?php if(($vo["que_status"]) == "1"): ?>&nbsp;&nbsp;|&nbsp;&nbsp;回答时间：<?php echo (date("Y年m月d日 H:i:s", $vo["answer_time"])); endif; ?></td>
                  </tr>
                </table>
              </div>
            </div><?php endforeach; endif; else: echo "" ;endif; endif; ?>
        <div class="clear"></div>
        <div class="doubt"><span class="down">&nbsp;您有问题？告诉我们您的疑惑，我们会在第一时间为您解答。</span></div>
        <?php if(($is_logined) == "true"): ?><div class="doubt_main">
            <form action="<?php echo U('hotel_quePublish');?>/hotel/<?php echo ($hotel_id); ?>/#bottom" ajax="true" method="post" id="quePublish">
              <table width="95%" class="table_form">
                <tr>
                  <th width="7%">咨询内容:</th>
                  <td width="93%"><textarea name="question1" id="textarea" datatype="*5-255" cols="55" rows="5" class="textarea" tip="请输入文本" nullmsg="请输入文本"></textarea></td>
                </tr>
                <tr>
                  <th>验证码:</th>
                  <td><input type="text" class="txt" id="verify" name="verify" datatype="n4-4" nullmsg="请填写验证码" errmsg="请填写验证码">
                    &nbsp;&nbsp;<span><img src="<?php echo U('Common/verify');?>" url="<?php echo U('Common/verify');?>" /></span></td>
                </tr>
                <tr>
                  <th>&nbsp;</th>
                  <td><input name="" type="submit" value="提交咨询" class="btn red info"></td>
                </tr>
              </table>
            </form>
          </div>
          <?php else: ?>
          <div class="cannotSpeak">未登录的用户不能提问，请您先&nbsp;<a href="<?php echo U('login/index');?>">登录</a>！</div><?php endif; ?>
      </div>
    </div>
    <div class="clear"></div>
    <div class="col-24 room_box">
      <h1 class="hot_route">&nbsp;房间信息</h1>
      <ul>
        <?php if(is_array($piclist)): $i = 0; $__LIST__ = $piclist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="adv_select_img">
            <div class="adv_select_img_z"><img src="__ROOT__<?php echo ($vo["logopath"]); ?>" width="182" height="130"/>
              <div class="adv_select_img_means"><span></span>
                <h4><b style="color: #F90;">￥<?php echo ($vo["nicePrice"]); ?>元</b> 起</h4>
              </div>
            </div>
          </li><?php endforeach; endif; else: echo "" ;endif; ?>
      </ul>
    </div>
    <div class="clear"></div>
    <div class="col-24 room_box">
      <h1 class="hot_route">&nbsp;酒店视频</h1>
      <div class="feature"><span class="url">
        <?php if(($hotelinfo["video"]) != ""): ?><a href="<?php echo ($hotelinfo["video"]); ?>" target="_blank"><u><?php echo ($hotelinfo["video"]); ?></u></a>
          <?php else: ?>
          <span style="color:#999">&lt;- 酒店未提供视频 -&gt;</span><?php endif; ?>
        </span></div>
    </div>
    <div class="clear"></div>
    <div class="col-24 room_box">
      <h1 class="hot_route">&nbsp;交通指南</h1>
      <div class="feature"><span class="down"><?php echo ($hotelinfo["traffic_info"]); ?>
        <p></p>
        <div class="otherinfo"><strong><font size="3"> 位置距离：</font></strong><br/>
          <?php echo ($hotelinfo["location"]); ?>
          <p></p>
          <strong><font size="3"> 周围环境：</font></strong><br/>
          <?php echo ($hotelinfo["around_info"]); ?></div>
        </span></div>
      <div class="controls" style="float:left">
        <div id="l-map" class="col06"> <i class="icon-spinner icon-spin icon-large"></i><span class="map-text">地图加载中...</span> </div>
        <div id="r-result">
          <input type="hidden" id="lng" name="position_x" value="<?php echo ($hotelinfo["location_x"]); ?>" class="col01">
          <input type="hidden" id="lat" name="position_y" value="<?php echo ($hotelinfo["location_y"]); ?>" class="col01">
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