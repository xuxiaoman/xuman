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
<script src="../Public/js/index.js" type="text/javascript"></script>
<script type="text/javascript" src="../Public/js/viewpoint_order.js"></script>
<script src="../Public/js/jquery.xl_calendar.js" type="text/javascript"></script>
<div class="wrapper w1200">
  <div class="clear"></div>
  <div class="" style="margin-top:10px;">
    <div  class="col-18 step_main_left" style="padding:10px;">
      <div class=" step_top">
        <div class="col-4 step_one"><img  class="step_img" src="../Public/images/step_bg.png" width="100%"/> <span class="offset-2 col-4 step_font">1.选择景点</span></div>
        <div class="col-4 step_two"><img src="../Public/images/step_bg2.png" width="100%"/> <span class="offset-1 col-4 step_font">2.填写核对订单</span></div>
        <div class="col-4 step_three"><img src="../Public/images/step_bg3.png" width="100%"/> <span class="offset-1 col-4 step_font">3.成功提交订单/支付</span></div>
        <div class="col-4 step_four"><img src="../Public/images/step_bg4.png"  width="100%" /> <span class="offset-1 col-4 step_font">4.点评拿奖金</span></div>
      </div>
      <div class="  alpha" >
       <form action="<?php echo U('order');?>/id/<?php echo ($ticket_list["id"]); ?>" method="post" name="myform" id="myform" class="check-form">
           <div class="col-17 step_whole">
              <div class="step_title">
                 <h1 class="step_title_css">门票信息</h1>
              </div>
              <span class="font_brown" style="font-size:14px;">景点名称：<span class="font_red" style="color: red"><?php echo ($ticket_list["viewpoint_names"]); ?></span> [<?php echo (_get_city($ticket_list["viewpoint_cityid"])); ?>]</span>


              <?php if(is_array($tickets)): $i = 0; $__LIST__ = $tickets;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="price_list" value="<?php echo ($vo["id"]); ?>" style="<?php if(($vo["id"]) != $ticket_list['id']): ?>display:none;<?php endif; ?>">
                   <table width="708px" class="table_list" id="kit_list" style="margin:0;">
                      <tr>
                        <th class="room_mag_one">门票类型</th>
                        <th class="room_mag_one">票面价</th>
                        <th class="room_mag_one">2人以上优惠价</th>
                        <th class="room_mag_one">10人以上优惠价</th>
                        <th class="room_mag_one">点评奖金</th>
                        <th class="room_mag_one">定金支付</th>
                        <th class="room_mag_one">可用奖金支付</th>
                    </tr>
                    <tr>
                        <td><?php echo ($vo["names"]); ?></td>
                        <td class="price1">￥<span id="inner_price"><?php echo ($vo["price"]); ?></span></td>
                        <td class="price2">￥<span id="inner_price"><?php echo ($vo["inner_price"]); ?></span></td>
                        <td class="price3">￥<span id="upon_price"><?php echo ($vo["upon_price"]); ?></span></td>
                        <td>￥<?php echo ($vo["review_bonus"]); ?></td>
                        <td><?php echo ($vo["earnest"]); ?>%</td>
                        <td class="price4"><span id="inner_price"><?php echo ($vo["award_price"]); ?></span>元/张</td>
                    </tr>
                </table>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>    
                        
              
                <div class="step_title" >
                  <h1 class="step_title_css">预订信息</h1>
                </div>


              <?php if(is_array($tickets)): $i = 0; $__LIST__ = $tickets;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="ide_main2 ticket" value="<?php echo ($vo["id"]); ?>" <?php if(($vo["id"]) != $ticket_list['id']): ?>style="display:none;"<?php endif; ?>>
                  <div class="ide_left">
                    <div class="font_brown font_brown2" style="min-width:120px;width:auto;"> 门票数(<?php echo ($vo["names"]); ?>)：</div>
                  </div>
                  <div style="float:left;">
                    <input type="text" name="ticket[<?php echo ($key); ?>][ticket_count]" id="view_num_<?php echo ($vo["id"]); ?>" ind="<?php echo ($vo["id"]); ?>" class="text-input ticket_num">
                    <input name="ticket[<?php echo ($key); ?>][ticket_id]" value="<?php echo ($vo["id"]); ?>" type="hidden">
                  </div>
                  <div class="font_brown font_brown-format">
                    <span class="font_red ticket_price_<?php echo ($vo["id"]); ?>" style="color: red"><?php echo ($vo["price"]); ?></span>元/张
                  </div>
                  <?php if(($vo["id"]) == $ticket_list['id']): if($ticket_num == 1): else: ?>
                  <div class="add-ticket"> 
                    <span class="font_brown-format">增加门票类型：</span>
                    <select name="add_ticket" id="add_ticket">
                      <option value="0">可选择...</option>
                      <?php if(is_array($tickets)): $i = 0; $__LIST__ = $tickets;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i; if(($list["id"]) != $ticket_list['id']): ?><option value="<?php echo ($list["id"]); ?>"><?php echo ($list["names"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                  </div><?php endif; ?>
                  <?php else: ?>
                    <div class="add-ticket">
                      <a href="javascript:;" title="取消该类型门票" names="<?php echo ($vo["names"]); ?>" class="minus" value="<?php echo ($vo["id"]); ?>">
                        <img src="../Public/images/minus.png" border="0" width="25" height="25" />
                      </a>
                    </div><?php endif; ?>
                  <div class="font_brown font_brown-format" style="float:right;color:#03C">门票有效期：<?php echo ($vo["begin_time"]); ?>～<?php echo ($vo["end_time"]); ?></div>
                </div>
                <div class="clear"></div><?php endforeach; endif; else: echo "" ;endif; ?>
              
              
              <div class="step_title">
                  <h1 class="step_title_css">优惠信息</h1>
              </div>
              <div class="price_list" style="margin-top:10px;">
                <table class="message">
                <tr>
                  <td height="170">
                    <div class="use"><span class="font_brown">1.使用奖金：</span></div>
                    <div class="use"> <span class="font_brown">您的奖金余额为<?php echo ($user["award"]); ?>元，本订单最多可使用<span id="all_use_award">0</span>元</span></div>
                    <table class="ide_main2 table_form">
                      <tr>
                        <td width="85" class="form_td">使用金额：</td>
                        <td><input name="use_award" id="view_award" type="text" class="txt" datatype="n,numrange" max="0" size="20" sucmsg=" " tip="请输入金额" nullmsg=" " ignore="ignore"><input name="serial_num" id="serial_n" type="hidden"></td>
                        <td></td>
                      </tr>
                    </table>
                    <div class="use"> <span class="font_brown">2.使用代金券：</span> </div>
                    <table class="ide_main2 table_form">
                      <tr>
                        <td width="100" class="form_td">代金券编号：</td>
                        <td><input id="serial_num" type="text" class="txt_id" size="46">
                          <input name="use_serial" id="use_serial" type="button" url="<?php echo U('ajax_use_serial');?>" value="   使用代金券   " class="btn orange">
                        &nbsp;&nbsp;<a id="lookcash" href="javascript:;" url="<?php echo U('look_serial');?>" style="font-size:15px">查看该代金券</a></td>
                        <td><span class="font_red" id="result"></span></td>
                      </tr>
                   </table>
                   </td>
                </tr>
              </table>
              </div>
              <div class="step_title">
                  <h1 class="step_title_css">联系信息</h1>
              </div>
              <div class="price_list" style="margin-top:10px;">
               <table class=" user_table">
                <tr class="ide_main2">
                  <th width="120" style=" text-align:right;"><span class="font_red">*</span>联系人姓名：</th>
                  <td width="134"><input name="contact_name" id="contact_name" type="text" class="" datatype="s2-12" size="20"></td>
                  <td><div class="Validform_tipbox" for="contact_name"></td>
                </tr>
                <tr class="ide_main2">
                  <th style=" text-align:right;"><span class="font_red">*</span>手机号码：</th>
                  <td><input name="contact_phone" id="contact_phone" type="text" class="txt" datatype="m" size="20" tip="" nullmsg=""></td>
                  <td><div class="Validform_tipbox" for="contact_phone"></td>
                </tr>
                <tr class="ide_main2">
                  <th style=" text-align:right;">电子邮箱：</th>
                  <td><input name="contact_email" id="contact_email" type="text" class="" datatype="e" size="20" tip="" nullmsg=""></td>
                  <td><div class="Validform_tipbox" for="contact_email"></td>
                </tr>
                <tr class="ide_main2">
                  <th style=" text-align:right;">特殊要求：</th>
                  <td colspan="2"><textarea name="special_want" id="textarea" cols="55" rows="5" class="textarea" tip="请输入特殊要求" nullmsg=" " sucmsg=" " ignore="ignore"></textarea></td>
                </tr>
              </table>
              </div>
            <div class="order_btn">
              <div class="filter_check">
                <input name="order_submit" type="submit" value="确认订单" class="btn orange" style="width:140px;">
              </div>
            </div>
          </div>
        </form>
      </div>  
    </div>

    <div class="col-6 step_main_right" style="padding:10px;">
      <div class="col-5 sidebar_title alpha">
        <h4 class="col-5 sidebar_title_f"><strong>结算信息</strong></h4>
      </div>
      <div class="down"><span class="down_title">景点费用</span></div>
        <table width="100%" class="jsu">
          <tr>
            <td class="down" id="num">门票数：<font id="rcount">0</font> 张</td>
            <td class="font_orange" id="nums">￥<font id="countmoney">0</font></td>
          </tr>
          <tr>
            <td class="down" id="award_left">奖金：￥<font id="id_view_award"><?php echo ($user["award"]); ?></font></td>
            <td class="font_orange" id="award_right">￥<font id="serial_count">0</font></td>
          </tr>
          <tr>
            <td class="down">代金券：￥<font class="cash_award">0</font></td>
            <td class="font_orange">￥<font class="cash_award">0</font></td>
          </tr>
          <tr class="amount">
            <td class="down">总计：</td>
            <td class="font_orange">￥<font id="total">0</font></td>
          </tr>
        </table>
        <div class="amount_btn">
        <div class="filter_check">
          <input name="sendout" id="sendout" type="botton" value="确认订单" class="btn orange" style="width:130px;">
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