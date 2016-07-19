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
<link rel="stylesheet" href="../Public/css/travel.css"/>
<script src="../Public/js/jquery.xl_calendar.js" type="text/javascript"></script>
<script type="text/javascript">
    (function($) {
        $.fn.extend({
            claymore_class: function(options) {
                var defaults = {
                    claymore_all: ".h_Append", //class all
                    claymore_main: ".h_main", //class main
                    claymore_Plus: ".h_Plus", //class Plus
                    claymore_Minus: ".h_Minus", //class Minus
                    claymore_roll: ".claymore_roll",
                    table_name: ".Adult_table",
                    td_name: "adult",
                    price_peo: "price_adult"
                };

                var options = $.extend(defaults, options);

                var claymore = {
                    h_id: $(options.claymore_all + " " + options.claymore_main).html(),
                    $claymore: $(options.claymore_roll).eq(0).clone(true),
                    h_create_table: function() {
                        $claymore = claymore.$claymore.clone(true);
                        //var $claymore = $('.claymore_roll').eq(0).clone(true);
                        var string = $claymore.html();
                        var regex = new RegExp(options.td_name + "\\[\\d\\]", "g");
                        var regex2 = new RegExp(options.td_name + "_name_\\d", "g");
                        var regex3 = new RegExp(options.td_name + "_type_\\d", "g");
                        var regex4 = new RegExp(options.td_name + "_card_\\d", "g");
                        string = string.replace(regex, options.td_name + "[" + claymore.h_id + "]");
                        string = string.replace(regex2, options.td_name + "_name_" + claymore.h_id);
                        string = string.replace(regex3, options.td_name + "_type_" + claymore.h_id);
                        string = string.replace(regex4, options.td_name + "_card_" + claymore.h_id);
                        $claymore.html(string);
                        $(options.table_name).append($claymore);
                        return true;
                    },
                    h_remove_table: function() {
                        $(options.table_name).find("tr:last").remove();
                        return true;
                    },
                    default_click: function() {
                        $(options.table_name).find("tr").remove();
                        if ($(options.claymore_all + " " + options.claymore_main).html() == '') {
                            $(options.claymore_all + " " + options.claymore_main).html(0)
                        }
                        for (var i = 1; i <= claymore.h_id; i++) {
                            var $claymore = claymore.$claymore.clone(true);
                            var string = $claymore.html();
                            var regex = new RegExp(options.td_name + "\\[\\d\\]", "g");
                            string = string.replace(regex, options.td_name + "[" + i + "]");
                            $claymore.html(string);
                            $(options.table_name).find("tbody").append($claymore);
                        }

                        claymore.check_total();
                        money_to_total();
                    },
                    check_total: function() {
                        var str = $("td." + options.price_peo).html();
                        var total = claymore.h_id * str;
                        $("div#" + options.td_name).find(".line_money").html(str);
                        $("div#" + options.td_name).find(".line_num").html(claymore.h_id);
                        $("div#" + options.td_name).find(".font_orange").html("￥" + total);
                    }
                };
                claymore.default_click();
                var num = $(options.claymore_all + " " + options.claymore_main).html();
                $(options.claymore_all + " " + options.claymore_Plus + "," + options.claymore_all + " " + options.claymore_Minus).click(function() {
                    if ($(this).hasClass(options.claymore_Plus.replace(/[.]/, ""))) {
                        claymore.h_id++;
                        $(options.claymore_all + " " + options.claymore_main).html(claymore.h_id);
                        claymore.h_create_table();
                        claymore.check_total();
                        money_to_total();
                    } else if ($(this).hasClass(options.claymore_Minus.replace(/[.]/, ""))) {
                        if (claymore.h_id <= 0) {
                            return false;
                        }
                        claymore.h_id--;
                        $(options.claymore_all + " " + options.claymore_main).html(claymore.h_id);
                        claymore.h_remove_table();
                        claymore.check_total();
                        money_to_total();
                    }
                });

                function money_to_total() {
                    var total = 0;
                    $(".step_main_right span.down").find("span.line_num").next().each(function() {
                        var string = $(this).html();
                        string = string.replace(/￥/, "");
                        total += parseFloat(string);
                    });
                    $(".amount .down .font_orange").html("￥" + total);
                }
            }
        });
    })(jQuery);
    $(function() {
        $("#used_card_total").find(".font_orange").html("￥0");
        $("#used_award_total").find(".font_orange").html("￥0");


        $.fn.claymore_class({
            claymore_all: ".h_Append", //class all
            claymore_main: ".h_main", //class main
            claymore_Plus: ".h_Plus", //class Plus
            claymore_Minus: ".h_Minus", //class Minus
            claymore_roll: ".claymore_roll",
            table_name: ".Adult_table",
            td_name: "adult",
            price_peo: "price_adult"
        });

        $.fn.claymore_class({
            claymore_all: ".b_Append", //class all
            claymore_main: ".b_main", //class main
            claymore_Plus: ".b_Plus", //class Plus
            claymore_Minus: ".b_Minus", //class Minus
            claymore_roll: ".claymore_roll_1",
            table_name: ".Adult_table_1",
            td_name: "child",
            price_peo: "price_children"
        });
        var data = $.parseJSON($("#travel_price_list").val());
        $("#order_go_time").xl_calendar({
            data: data,
            price_rackrate: ".price_rackrate",
            price_adult: ".price_adult",
            price_children: ".price_children",
            select_callback: function() {
                (function() {
                    var adult_id = $(".h_Append .h_main").html();
                    var str = $(".price_adult").html();
                    var total = adult_id * str;
                    $("div#adult").find(".line_money").html(str);
                    $("div#adult").find(".line_num").html(adult_id);
                    $("div#adult").find(".font_orange").html("￥" + total);
                })();
                (function() {
                    var child_id = $(".b_Append .b_main").html();
                    var str = $(".price_children").html();
                    var total = child_id * str;
                    $("div#child").find(".line_money").html(str);
                    $("div#child").find(".line_num").html(child_id);
                    $("div#child").find(".font_orange").html("￥" + total);
                })();
                money_to_total();
            }

        })
        function money_to_total() {
            var total = 0;
            $(".step_main_right span.down").find("span.line_num").next().each(function() {
                var string = $(this).html();
                string = string.replace(/￥/, "");
                total += parseFloat(string);
            });
            $(".amount .down .font_orange").html("￥" + total);
        }

        $("input#revise_btn").click(function() {
            var url = "<?php echo U('ajax_award');?>";
            var code = $("input[name=used_card]").val();
            $.ajax({
                url: url,
                data: {code: code},
                type: "post",
                dataType: "json",
                async: false,
                success: function(data) {
                    if (data.status == 1) {
                        $("input#revise_btn").next("span").html("金额=" + data.data);
                        $("#used_card_total").find(".font_orange").html("￥-" + data.data);
                        money_to_total();
                    } else {
                        alert(data.info);
                    }
                }
            });
        });

        $("input[name=used_award]").blur(function() {
            var val = $(this).val();
            if (val == '') {
                val = 0
            } else {
                $("#used_award_total").find(".font_orange").html("￥-" + val);
            }

            money_to_total();
        });

    });
</script>
<div class="wrapper w1200">
    <div class="row">
        <form action="" method="post" name="myform" id="myform" class="check-form">
            <div class="col-19 step_main_left alpha">
                <div class="step_top">
                    <div class="col-5 step"><img class="step_img" width="100%" src="/web/Tpl/green2/Public/images/step_bg.png"></img><span class="offset-2 step_font">1.选择路线</span></div>
                    <div class="col-5 step_two"><img width="100%" src="/web/Tpl/green2/Public/images/step_bg2.png"><span class="offset-2 step_font">2.提交订单</span></div>
                    <div class="col-5 step_three"><img width="100%" src="/web/Tpl/green2/Public/images/step_bg3.png"><span class="offset-2 step_font">3.等待付款</span></div>
                    <div class="col-5 step_four"><img width="100%" src="/web/Tpl/green2/Public/images/step_bg4.png"></img><span class="offset-2 step_font">4.支付成功</span></div>
                </div>
                <div class="step_whole">
                    <div class="step_title">
                        <h1 class="step_title_css">线路名称</h1>
                    </div>
                    <h2 class="font_brown"><?php echo ($ke_award["names"]); ?></h2>

                    <div class="step_title">
                        <h1 class="step_title_css">预订信息</h1>
                    </div>
                    <div class="ide_main2">
                        <div class="ide_left">
                            <div class="font_brown"> 出发日期：</div>
                        </div>
                        <div class="ide_right" style="position: relative;">
                            <input type="text" class="text-input" id="order_go_time" name="go_time" value="<?php echo ($pos["go_time"]); ?>" datatype="*">
                            <textarea id="travel_price_list" style="display: none;"><?php echo (($travel_price_list)?($travel_price_list):'[]'); ?></textarea>

                            <div tabindex="2" class="the_calendar" style="position: absolute; background: white;">
                            </div>
                        </div>
                    </div>

                    <span class="web_map">价格类型：<span id="price_type">周末价</span></span>
                    <div class="clear"></div>
                    <div class="price_list">
                        <table width="708px" class="table_list">
                            <tr>
                                <th>类型</th>
                                <th>市场</th>
                                <th>价格</th>
                                <th>数量</th>
                            </tr>
                            <tr>
                                <td>成人</td>
                                <td class="price_rackrate">--</td>
                                <td class="real_money price_adult">--</td>
                                <td>
                                    <div class="price_list_reg h_Append">
                                        <img src="../Public/images/price.jpg" width="13" height="13" align="absmiddle" class="h_Minus"/>

                                        <div class="select_four h_main"><?php if($pos["adult_num"] == null): ?>1<?php else: echo ($pos["adult_num"]); endif; ?></div>
                                        <img src="../Public/images/price_2.jpg" width="13" height="13" class="h_Plus"/>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>小孩</td>
                                <td class="price_rackrate">--</td>
                                <td class="real_money price_children">--</td>
                                <td>
                                    <div class="price_list_reg b_Append">
                                        <img src="../Public/images/price.jpg" width="13" height="13" align="absmiddle" class="b_Minus"/>
                                        <div class="select_four b_main"><?php echo ($pos["children_num"]); ?></div>
                                        <img src="../Public/images/price_2.jpg" width="13" height="13" class="b_Plus"/></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="step_title">
                        <h1 class="step_title_css">优惠信息</h1>
                    </div>
                    <div class="message">
                        <div class="use"><span class="font_brown">1.使用奖金：</span></div>
                        <div class="use"><span class="font_brown">您的奖金余额为<?php echo ($amount); ?>元，最多可抵用<?php echo ($ke_award["award"]); ?>元</span></div>
                        <table class="mag_table">
                            <tr>
                                <th>使用金额：</th>
                                <td>
                                    <input name="used_award" id="" type="text" class="" ajaxurl="<?php echo U('order_ajax_award');?>" ignore="ignore" size="20" datatype="award_check" lt="<?php echo ($ke_award["award"]); ?>">
                                </td>
                            </tr>
                        </table>
                        <div class="use"><span class="font_brown">2.使用代金券：</span></div>
                        <table class="mag_table" style="float:left;">
                            <tr>
                                <th>代金券编号：</th>
                                <td>
                                    <input name="used_card" id="used_card" type="text" class="" size="20"><input name="" type="button" id="revise_btn" value="   查询   " class="btn warning active">
                                </td>
                            </tr>
                        </table>
                        <div class="mag_table">

                            <span></span>
                        </div>
                    </div>
                    <div class="step_title">
                        <h1 class="step_title_css">旅客信息</h1>
                    </div>
                    <table class="mag_table Adult_table">
                        <tr class="claymore_roll">
                            <th style="width:70px;">普通票<span class="font_red">*</span>姓名</th>
                            <td style="width:80px;">
                                <input name="adult[1][]" id="adult_name_1" type="text" class="txt" datatype="*" size="10" sucmsg=" " nullmsg=" ">
                            </td>
                            <th style="width:70px;">&nbsp;<span class="font_red">*</span>证件号码&nbsp;<span></span></th>
                            <td style="width:100px;"><select name="adult[1][]" id="adult_type_1" class="select">
                                    <option value="0">身份证</option>
                                    <option value="1">护照</option>
                                    <option value="2">军官证</option>
                                    <option value="3">回乡证</option>
                                    <option value="4">台胞证</option>
                                    <option value="5">国际海员证</option>
                                </select></td>
                            <td style="width:200px;">
                                <input name="adult[1][]" id="adult_card_1" type="text" class="txt" datatype="*" size="30" sucmsg=" " nullmsg=" ">
                            </td>
                            <td style="width:200px;">
                                <div class="Validform_tipbox" for="adult_name_1"></div>
                                <div class="Validform_tipbox" for="adult_card_1"></div>
                            </td>
                        </tr>
                    </table>
                    <table class="mag_table Adult_table_1">
                        <tr class="claymore_roll_1">
                            <th style="width:70px;">儿童票&nbsp;<span class="font_red">*</span>姓名</th>
                            <td style="width:80px;">
                                <input name="child[1][]" id="child_name_1" type="text" class="txt" datatype="*" size="10" sucmsg=" " nullmsg=" ">
                            </td>
                            <th style="width:70px;">&nbsp;<span class="font_red">*</span>证件号码&nbsp;</th>
                            <td style="width:100px;"><select name="child[1][]" id="child_type_1" class="select">
                                    <option value="0">身份证</option>
                                    <option value="1">护照</option>
                                    <option value="2">军官证</option>
                                    <option value="3">回乡证</option>
                                    <option value="4">台胞证</option>
                                    <option value="5">国际海员证</option>
                                </select></td>
                            <td style="width:200px;">
                                <input name="child[1][]" id="child_card_1" type="text" class="txt" datatype="*" size="30" nullmsg=" " sucmsg=" ">
                            </td>
                            <td style="width:200px;">
                                <div class="Validform_tipbox" for="child_name_1"></div>
                                <div class="Validform_tipbox" for="child_card_1"></div>
                            </td>
                        </tr>
                    </table>
                    <div class="step_title">
                        <h1 class="step_title_css">旅客信息</h1>
                    </div>
                    <table class="mag_table">
                        <tr>
                            <th><span class="font_red">*</span>联系人姓名&nbsp;:</th>
                            <td>
                                <input name="contact_name" id="" type="text" class="" datatype="s2-12" size="20">
                            </td>
                        </tr>
                    </table>
                    <table class="mag_table">
                        <tr>
                            <th><span class="font_red">*</span>手机号码&nbsp;:</th>
                            <td>
                                <input name="contact_num" id="" type="text" class="txt" datatype="m" size="20" tip="" nullmsg="">
                            </td>
                        </tr>
                    </table>
                    <table class="mag_table">
                        <tr>
                            <th>&nbsp电子邮箱&nbsp;:</th>
                            <td>
                                <input name="contact_email" id="" type="text" class="" datatype="e" size="20" tip="" nullmsg="">
                            </td>
                        </tr>
                    </table>
                    <table class="mag_table">
                        <tr>
                            <th>&nbsp订单留言&nbsp;:</th>
                            <td>
                                <textarea name="contact_msg" cols="55" rows="5"  class="textarea" tip="" nullmsg=""></textarea>
                            </td>
                        </tr>
                    </table>
                    <div class="order_btn">
                        <div class="filter_check">
                            <input name="order_submit" type="submit" value="   确认订单   " class="btn warning active">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-5">

                <div class="col-5 step_main_right">
                    <h1 class="col-5 sidebar_title_f">结算信息</h1>
                    <div><span class="down">旅游团费：</span></div>
                    <div id="adult" class="t_total">
                        <span class="down">成人：￥<span class="line_money">--</span>*<span class="line_num">--</span><span class="font_orange">￥--</span></span>
                    </div>
                    <div id="child" class="t_total">
                        <span class="down">儿童：￥<span class="line_money">--</span>*<span class="line_num">--</span><span class="font_orange">￥--</span></span>
                    </div>
                    <div id='used_award_total' class="t_total">
                        <span class="down">使用奖金：<span class="line_money"></span><span class="line_num"></span><span class="font_orange">￥--</span></span>
                    </div>
                    <div id="used_card_total" class="t_total">
                        <span class="down">使用代金券：<span class="line_money"></span><span class="line_num"></span><span class="font_orange">￥--</span></span>
                    </div>
                    <div class="amount t_total">
                        <div><span class="down">总计：<span class="font_orange">￥--</span></span></div>
                    </div>
                    <div class="amount_btn">
                        <div class="filter_check4">
                            <input name="order_submit" type="submit" value="   确认订单   " class="btn warning active">
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="clear"></div>
<div id="footer">途乐欢迎您
    </div>
</body>
</html>